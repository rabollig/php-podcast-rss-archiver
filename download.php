<?php

define("LOG_FILE_NAME", 'already_downloaded_guids.log');
define("EPISODES_PER_BATCH", 5);

// Gets list of podcasts
require "config.php";

// Get all previously downloaded GUIDs

$guidHistory = explode("\n", file_get_contents(LOG_FILE_NAME));


foreach ($podcasts as $name => $url) {

    // Download feed
    $temp = array();
    $url = escapeshellarg($url);
    exec("curl {$url}", $feed);
    $feed = implode("\n", $feed);
    $xml = simplexml_load_string($feed);
       

    $episodes = $xml->channel->item;

    $ttl = EPISODES_PER_BATCH;
    foreach ($episodes as $episode) {
  
        // If we already downloaded this GUID, skip it
        if (in_array($episode->guid, $guidHistory)) {
            continue;
        }


        $title = $episode->title;
        $title = str_replace(' ', '_', $title);
        $title = preg_replace('/[^0-9a-zA-Z_]/', '_', $title);

        $title = "{$name}_".$title.".mp3";
        $title = str_replace('__', '_', $title);

        echo "Downloading {$title}\n";

        // Download the podcast
        file_put_contents($title, file_get_contents($episode->enclosure['url']));

        // If file has enough contents to be media, write the GUID

        file_put_contents(LOG_FILE_NAME, "{$episode->guid}\n", FILE_APPEND);

        // Limit batch size to not hammer podcast's server constantly
        // and reduce the amount of space used locally.
        $ttl--;
        if ($ttl < 1 ) {
            break;
        }
    }

    // If we had new episodes, sync to S3
    if ($ttl < EPISODES_PER_BATCH) {
        echo "Sync to storage\n";
        passthru(
            "s3cmd put --delete-after --continue-put -rr " . __DIR__ . "/*.mp3 " . S3BUCKET, 
            $return
        );
        // --delete-after doesn't seem to work.
        if ($return == 0) {
            exec("rm " . __DIR__ . "/*.mp3");
        }

    }

}




echo "Done\n";

