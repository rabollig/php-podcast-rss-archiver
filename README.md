=php-podcast-rss-archiver=

This is a little script to download podcasts and archive them to S3.

Sometimes podcasts go away completely.  Sometimes podcasts remove old episodes (apparently some feed readers
cannot handle a huge number of episodes).  Occasionally, episodes go behind a paywall.

Since storage is pretty cheap these days, I wanted to start saving podcasts I enjoy and want to refer back to
from time to time.

-----

==Installation==

To install, you will need at least PHP5 installed on the command line.  Depending on your version, you may need to 
install feature for XML.  You also need s3cmd installed and configured and need to have already created a storage
bucket.  If you do not want to store your files on S3, you can comment out that block of code.

Copy the config.php.example to config.php and edit the file to your liking... in particular, you need to change the
bucket name.

-----

==Epilogue==

This is a really simple script written on a rainy afternoon, but it may be useful to others as a working example
of getting RSS feeds.  There are many features that are lacking.

Pull requests welcome.


