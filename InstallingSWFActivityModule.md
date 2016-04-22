


---


## Introduction ##

This page describes how to install the SWF Activity Module in Moodle.


---


## Requirements ##

The SWF Activity Module is tested and stable when running on:

  * LAMP server (Linux, Apache, MySQL, PHP)
  * PHP 5 (Now will work with PHP 5.3+)
  * Moodle 1.9+
  * Moodle 2.0 is not yet supported

It may also work on other server configurations and previous versions of Moodle, e.g. 1.8, but this hasn't been tested. Please report known problems and bugs in the issues tracker.

Please note that Moodle 1.9 is not fully compatible with PHP 5.3+


---


## Installing the Activity Module ##

  * Download and unzip the swf-activity-module...zip file from the Downloads section of this project site
  * Put the /swf/directory in MOODLE/mod/
  * Log in to Moodle and go to Administration > Notifications
  * The module should install correctly
  * That's it!

<img src='http://matbury.com/assets/moodle_swf_files_view.gif' alt='Moodle SWF files view' width='230' height='1210' />


---


## Common installation problems ##

Error: "Module swf: /home/example.com/www/moodle/mod/swf/version.php was not readable"

Make sure that the SWF Activity Module directory structure in Moodle is as follows:

  * MOODLE/mod/swf/... - NOT - MOODLE/mod/swf/swf/...


---


## Installing the Flash-Moodle interface (AMFPHP) ##

A version of AMFPHP 1.9 which is preconfigured for Moodle is included is the SWF Activity Module installer package. How to install it:

  * Download and unzip the SWF Activity Module installer package
  * Find the /amfphp/ directory
  * Upload the /amfphp/ directory and all its contents to MOODLE/lib/
  * It isn't necessary to go to Moodle's Administration > Notifications page after installation
  * That's it!


---


## Any other installation problems ##

Please report any problems you have with installing any part of The SWF Activity Module in the issues tracker, even if you managed to resolve them yourself. I'd like to make using this module as easy as possible for everyone and I regularly update this documentation when users report difficulties. :)