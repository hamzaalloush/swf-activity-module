

This page answers some frequently asked questions about the course media file directories in Moodle and how this affects deploying learning interactions with the SWF Activity Module.


---


## Where are my Flash learning applications stored? ##

If you upload a Flash learning application using the SWF Activity Module upload tool as described on this page, it is stored in the current course's media directory in the /moodledata/ directory.

The numbered directories nested inside /moodledata/ are directories that contain files uploaded for a particular course. You can find out which numbered directory the files for a course are stored in by going to the course page and looking in the browser address bar. You should see something like this:

<img src='http://matbury.com/tutorials/swf_moodledata.gif' alt='moodledata course files directories' width='136' height='291' />

  * `http://MOODLE/course/view.php?id=1`

The number after id= is the number of the course directory. So the course files for the course page above are in /moodledata/1/...

Likewise, if you upload files using the Moodle course files manager, they will be stored in this directory.

Please note that a course directory is only created by Moodle when the first file has been uploaded.

Also, please note that only the contents of the /public\_html/ (sometimes called /htdocs/ or /www/) directory are directly accessible on a public server through a web browser. Any files stored outside the public directory must be accessed via a proxy script in the public directory. Luckily, Moodle already has a proxy script at MOODLE/file.php


---


## How can a Flash learning application access /moodledata/ course files? ##

The easiest way for a Flash learning application to access files stored in a /moodledata/ course directory is to link to them using absolute paths, like this:

  * `http://MOODLE/file.php/1/photos/example_photo_1.jpg`

  * `http://MOODLE/file.php/1/photos/example_photo_2.jpg`

  * `http://MOODLE/file.php/1/audio/example_audio_1.mp3`

  * `http://MOODLE/file.php/1/audio/example_audio_2.mp3`

etc.

The MOODLE/file.php script automatically manages access and permissions so that only users logged in on course can access files stored in the corresponding /moodledata/ course directory. Also, search engines cannot index files stored in /moodledata/.


---


## Dynamically accessing /moodledata/ course files ##

If you copy a course to the same Moodle installation or another (backup and restore), the path to the moodledata will most likely be different resulting in broken links in any XML files or hard-coded SWF files. The SWF Activity Module provides a dynamic path to the moodledata course files directory via FlashVars. This means that individual instances or entire courses can be backed up and transfered while maintaining the correct file paths.

```
var moodledata:String = this.root.loaderInfo.parameters.moodledata; // http://example.com/file.php/99/
var mp3URL:String = moodledata + "audio/my_audio.mp3";
```

The static class, `com.matbury.sam.data.FlashVars`, makes accessing all FlashVars data throughout a Flash application easy, e.g. `FlashVars.moodledata`. See the FlashVars wiki page for further details.


---


## Example SMIL XML Playlist ##

Here is an example SMIL XML playlist that accesses files stored in /moodledata/. Note that the course number is 99. If the name if the SMIL XML file is /example\_smil\_playlist.xml/, then the path to it should be something like:

  * `http://MOODLE/file.php/99/playlists/example_smil_playlist.xml`

Here's the SMIL XML playlist with the absolute paths to the course files directory:

```
<?xml version="1.0" encoding="utf-8"?>
<smil>
        <head>
                <meta name="title" content="Example SMIL playlist for the JW Player"/>
        </head>
        <body>
                <seq>
                        <par>
                                <video title="First FLV video" src="http://MOODLE/file.php/99/flv/yourvideo_1.flv" author="The video author" alt="This is an example of one video in an FLV playlist"/>
                                <anchor href="http://www.thevideoauthor.org/"/>
                        </par>
                        <par>
                                <video title="Second FLV video" src="http://MOODLE/file.php/99/flv/yourvideo_2.flv" author="The video author" alt="This is an example of the next video in an FLV playlist"/>
                                <anchor href="http://www.thevideoauthor.org/"/>
                        </par>
                        <par>
                                <video title="Third FLV video" src="http://MOODLE/file.php/99/flv/yourvideo_3.flv" author="The video author" alt="This is an example of the third video in an FLV playlist"/>
                                <anchor href="http://www.thevideoauthor.org/"/>
                        </par>
                </seq>
        </body>
</smil>
```