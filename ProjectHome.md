# Project hosting has moved to [GitHub.com](https://github.com/matbury)! #

**Google code announced, "Starting today, existing projects that do not have any downloads and all new projects will not have the ability to create downloads. Existing projects with downloads will see no visible changes until January 14, 2014 and will no longer have the ability to create new downloads starting on January 15, 2014." Source: http://google-opensource.blogspot.ca/2013/05/a-change-to-google-code-download-service.html***

# This is Google Code now: #

<img src='http://i.imgur.com/7ZRSgGY.gif' alt='Google Code' border='0' width='640' height='360' />

### The new SWF Activity Module project host is at GitHub.com at: https://github.com/matbury/SWF-Activity-Module1.9 ###

### The new SWF Activity Module for Moodle 2.5+ project host is at GitHub.com at: https://github.com/matbury/SWF-Activity-Module2.5 ###

**This Google Code project site will no longer be updated. (2013-07-14)**

<a href='http://code.google.com/p/moodle-swf/'><img src='http://matbury.com/tutorials/swf_module_header.png' alt='Moodle plus Flash equals SWF' border='0' width='384' height='83' /></a>
# <img src='http://moodle.matbury.com/mod/swf/icon.gif' alt='SWF Icon' width='16' height='16' /> SWF Activity Module plugin for Moodle 1.9 #
## Truly interactive e-learning with Rich Internet Applications ##

<img src='http://matbury.com/tutorials/swf_activity_module.gif' alt='SWF Activity Module' width='600' height='300' />




---


## What does it do? ##

**The SWF Activity Module is a Moodle plugin extension for deploying Flash learning applications, also known as Flash templates. Teachers and course content developers can use the SWF Activity Module to deploy any Flash applications, including but not limited to those that use external data and media in order to function correctly. It also allows Flash applications to communicate with Moodle for purposes such as passing learners' grades into Moodle's grade book.**

It does not include any Flash learning applications. However, a word search application is provided separately for testing and demonstration purposes. You're also free to use the word search in courses if you wish.

Sufficient documentation, [source code and examples](http://code.google.com/p/swf-activity-module/source/browse/#svn/trunk) are provided on this project site so that Flash developers can easily write or adapt applications to work fully with the SWF Activity Module. See the [project wiki pages](http://code.google.com/p/swf-activity-module/w/list) for further details.


---


## Production ##
**Please note:** This module is now ready for production.

For a summary of the latest developments of the SWF Activity Module, please see the [ChangeLog page](http://code.google.com/p/swf-activity-module/wiki/ChangeLog).


---


## Why develop the SWF Activity Module? ##

I started the SWF Activity Module project to resolve two issues in Moodle:

  1. Moodle does not provide an effective, W3C standards compliant way to embed Flash applications in its web pages.
  1. Moodle does not provide any direct means of communication between Flash applications and its functions and data (generally referred to as an API - Application Programming Interface)

These two issues effectively make it very difficult for developers and almost impossible for non-developers, such as teachers, course content developers and instructional designers, to deploy dynamic, Flash based e-learning applications in Moodle.

The SWF Activity Module provides an easy, reliable way for developers and non-developers (i.e. teachers, trainers, course content developers and instructional designers) to deploy Flash e-learning interactions in Moodle.

No Flash e-learning applications are included with the SWF Activity Module, except for a word search activity, available as a separate download, to help you get started.


---


## Features ##

### General ###
  * Uses a "wizard interface" to deploy Flash learning applications: No developer skills necessary
  * Supports all versions of Flash and is forwardly compatible
  * Section 508 (accessibility) compliant
  * Supports web services including dynamic API keys: No need to hard-code API keys into your software!
  * Will push grades from Flash learning applications into Moodle grade book
  * Supports customisable FlashVars so that many existing 3rd party Flash applications can also be deployed

### Supports SWF Object ###
  * Uses the best Flash embed method available: stable, fully functioning and reliable, as recommended by Adobe and leading Flash developers
  * Detects users' Flash Player version to inform users if they need to upgrade
  * W3C standards compliant Flash embed code (XHTML 1.0 Strict)
  * Supports all Flash Player parameters
  * Works in all browsers and browser versions that support Flash Player 6 and later
  * Even works in browsers with Javascript disabled

### Supports Flash Remoting ###
  * Uses AMFPHP as a communication gateway with Moodle
  * Compatible with ActionScript 3.0 (Flash Player 9 and later)
  * Includes Access.php service to authenticate users and determine their capabilities seamlessly and securely (i.e. no need to re-login in Flash applications)
  * Includes service library to give Flash clients access to Moodle functions for things like Users, Groups, Grades, etc.
  * Object Oriented code: Requires PHP5 or later

### Supports XML ###
  * Can deploy dynamic Flash learning applications that load in external lesson data via XML
  * URLs to XML files are dynamic: No need to hard-code them
  * One Flash learning application can present an unlimited number of learning interactions (lessons)
  * Supports any type of XML that the deployed learning application is compatible with: XML, RSS, ATOM, SMIL, etc.
  * Strongly supported by W3C standards for dynamic content
  * Ideal for non-developers: A wide range of 3rd party XML authoring tools available

### Does Not Support Authorware ###

**Please note:** Most authorware packages such as Adobe Captivate, Techsmith Camtasia, Raptivity, etc. publish learning interactions specifically for static web pages and in SCORM packages. They do not function correctly in dynamic web pages such as Moodle activity modules. Use the Moodle SCORM module to deploy content developed and published for SCORM.

However, it is sometimes possible to deploy screen recordings if they are single output .swf files. Due to the limit on the number of animation frames a single .swf file can contain, this is only possible for relatively short recordings.


---


## Documentation ##
  * [SWF Activity Module documentation](http://code.google.com/p/swf-activity-module/w/list)


---


## Demo ##
Visit my [Moodle demo course](http://moodle.matbury.com/course/view.php?id=9) (Login as a guest) which allows users to login without creating an account.


---


## Related Open Source Projects ##
  * [Media Player Module](http://code.google.com/p/moodle-mplayer/) project - Deploy video as an activity in Moodle with captions, alternative audio, take snapshots, use RTMP streaming, provide download links, etc. all with an easy to use wizard.
  * [MP3 Player for Tests](http://code.google.com/p/moodle-mp3-player-for-tests/) project - Limits the number of times an MP3 file can be played as well as a number of other features that make this the ideal Flash audio player for tests. Currently being used in the QuizPort module successor to the HotPot Moodle module.


<img src='http://www.gnu.org/graphics/gplv3-127x51.png' alt='GPL3 Open Source' border='0' width='127' height='51' />