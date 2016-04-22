

## Please note ##

**AMFPHP and Moodle are not fully compatible with PHP 5.3** AMFPHP has 2 instances of eregi\_replace() which is easy enough to fix [see here](http://matthiasvanneste.wordpress.com/2009/09/18/php-5-3-0-and-amfphp/). However, all versions of Moodle have hundreds of instances of eregi\_replace(), ereg\_replace(), etc. and is a major issue. If your server is running PHP 5.3+, eregi\_replace() will return deprecated function warnings which prevent AMFPHP from functioning correctly. It is strongly recommended not to upgrade PHP versions to 5.3+ if running Moodle until they have resolved this issue (This was posted 4th December 2010).

**The [installer package](http://code.google.com/p/swf-activity-module/downloads/list) contains a pre-configured version of AMFPHP. It requires no configuration and installing it is a very simple process. Just put it in the MOODLE/lib/ directory and that's it!**

## Introduction ##

This tutorial describes how to install the AMFPHP Flash Remoting web service in Moodle on a server. If you do not want to record users' results or grades then this installation is unnecessary.

It was originally written some time ago by Jamie Pratt. This version is updated so that it works with the most recent version of AMFPHP (1.9.beta), ActionScript? 3.0 and AMF3 (Action Message Format).

**Please note:** Moodle 2.0 will have Zend\_Amf installed in the standard download package. It is a better version than AMFPHP (by the same developer) and it is strongly recommended that you use it when Moodle 2.0 is released. If you are new to Flash Remoting, I recommend using AMFPHP to get started as it includes a Flex service browser application that makes testing and debugging particularly easy.


---


## What is AMFPHP? ##

AMFPHP (Action Message Format PHP) is a widely used open source remoting server that allows Flash and Flex client-side applications to call PHP methods directly, as if they were native Flash/Flex ActionScript methods. It is fast and lightweight and presents an efficient, simple and easy to implement method of communicating with PHP and databases.

## Preserved Data Types ##

AMFPHP preserves the following data types between ActionScript and PHP:

  * Array
  * Bitmap
  * ByteArray?
  * int
  * Number
  * Object
  * Recordset (mysql\_result)
  * String
  * XML (ActionScript? 3.0 also supports E4X notation)

Note: Please add to this list if you have successfully tested data types using AMFPHP 1.9.beta+ and ActionScript 3.0.

AMFPHP automatically converts data types between ActionScript and PHP to their native equivalents. For example, it can convert a PHP array into and ActionScript array or a PHP resource, such as a mysql\_result() into an Actionscript Recordset (Flex class).

## AMF0 and AMF3 ##

Previous versions of Flash, using ActionScript 2.0 (versions 6, 7 and 8), use AMF0. ActionScript 3.0, Flash CS3+ and Flex, use AMF3 by default but can also use AMF0. What's the difference? AMF3 is compressed and therefore lighter and faster. For more details look here. AMFPHP version (1.9) supports AMF3


---


## Installing AMFPHP in Moodle ##

### Download from this project site ###

  * Goto the downloads section on this site and download the amfphp-1.9-beat.zip file.
  * Unzip the file and upload to MOODLE/lib/
  * That's it!

### Download from AMFPHP.org ###

  * Download the latest version of AMFPHP, currently version 1.9.beta (SourceForge? repository).
  * Unzip the file and find the directory amfphp.
  * Upload the amfphp directory to MOODLEROOT/lib/
  * Find the file MOODLEROOT/lib/amfphp/gateway.php and open it in your favourite text/PHP editor
  * At the beginning of the code, add the line: include\_once "../../config.php";
  * Find and edit the line: $gateway->setClassPath($servicesPath); and change it to: $gateway->setClassPath($CFG->dirroot."/lib/amfphp/services/");
  * Upload the edited MOODLEROOT/lib/amfphp/gateway.php file.
  * That's it!

## Setting PHP 5 as Default ##
**Please note:** AMFPHP requires PHP 5 to work seamlessly, although some report success with version 4. Many servers run both PHP 4 and 5, but have them set to run PHP 4 by default. If you experience problems, you may have to change the default PHP version in the MOODLEROOT/lib/amfphp/ directory with an .htaccess file. The following is an example only. Please check that it is correct for your server configuration. If you have a hosted server, they'll probably have an example in their help files. For example:

Find the AMFPHP .htaccess file at MOODLEROOT/lib/amfphp/.htaccess
Open it with a text editor. You'll see:
```
#If you're working with a server which doesn't seem to display errors and you don't 
#have access to httpd.conf and you have a good reason to develop remotely instead of
#locally, you may have luck with uploading this configuration file to the server

php_flag display_errors on
php_flag display_startup_errors on
php_value error_reporting 2047
```

Add another line of code to change the default PHP version setting for the amfphp directory:
```
#If you're working with a server which doesn't seem to display errors and you don't 
#have access to httpd.conf and you have a good reason to develop remotely instead of
#locally, you may have luck with uploading this configuration file to the server

php_flag display_errors on
php_flag display_startup_errors on
php_value error_reporting 2047

SetEnv DEFAULT_PHP_VERSION 5
```


---


## Testing AMFPHP in Moodle ##

Here is an example of a simple AMF Moodle service that checks the login status of your browser.

  * Create a new document in your preferred text/PHP editor
  * Copy and paste the code and save it as UserName?.php
  * Upload it to MOODLEROOT/lib/amfphp/services/

Now is a good time to find out if your server is running the correct PHP version. If your server is running PHP 4 by default, you'll get error messages caused by the "public" namespace.

  * Navigate to the PHP page you just uploaded in your browser: MOODLEROOT/lib/amfphp/services/UserName?.php
  * If there are no error messages then you can continue to the "Testing AMFPHP in Flash" section

```
<?php
class UserName
{
        public function __construct()
        {
                
        }
        /**
        *checks whether you're logged in to Moodle
        *@returns user login status
        */
    public function loggedInAs() {
        global $USER;
        if (isguestuser()){
            return get_string('loggedinasguest', 'moodle');
        }else if (isloggedin()){
            return get_string('loggedinas', 'moodle', fullname($USER));
        } else {
            return get_string('loggedinnot');
        }
    }
}
?>
```


---


## Testing AMFPHP in Flash ##

**Please note:** Flash CS3 and Flex come with the necessary classes for using AMFPHP already installed. It is not necessary to install any new classes!

  * Open the Flash CS3 IDE
  * Create a new ActionScript? 3.0 class document
  * Copy and past the code below and save it as UserName.as
  * Create a new ActionScript? 3.0 FLA document
  * In Properties > Document class: type UserName.as
  * Save the FLA file as UserName.fla in the same directory as UserName.as
  * Publish the UserName.swf, AC\_RunActiveContent.js and UserName.html files
  * Upload the published files to your server
  * In your browser, navigate to the location of the UserName.html page
  * Try logging in and out of Moodle to see the change in your login status

## ActionScript 3.0 Document Class ##

```
package {
        
        import flash.display.Sprite;
        import flash.text.*;
        import flash.events.MouseEvent;
        import flash.events.NetStatusEvent;
        import flash.net.NetConnection;
        import flash.net.Responder;
        
        public class UserName extends Sprite {
                
                private var _format:TextFormat;
                private var _display:TextField;
                private var _call:Sprite;
                private var _gateway:NetConnection;
                private var _responder:Responder;
                
                public function UserName() {
                        initFormat();
                        initDisplay();
                        initCall();
                        initGateway();
                }
                
                // create text format object
                private function initFormat():void {
                        _format = new TextFormat();
                        _format.font = "Trebuchet MS";
                        _format.size = 15;
                        _format.bold = true;
                }
                
                // create text field to display results
                private function initDisplay():void {
                        _display = new TextField();
                        _display.autoSize = TextFieldAutoSize.LEFT;
                        _display.multiline = true;
                        _display.x = 10;
                        _display.y = 10;
                        _display.defaultTextFormat = _format;
                        _display.text = "Click on \"Call UserName.php\" to test.";
                        addChild(_display);
                }
                
                // create text button to call UserName.php
                private function initCall():void {
                        _call = new Sprite();
                        _call.mouseChildren = false;
                        _call.buttonMode = true;
                        _call.addEventListener(MouseEvent.MOUSE_DOWN, callDownHandler);
                        var btn:TextField = new TextField();
                        btn.autoSize = TextFieldAutoSize.LEFT;
                        btn.border = true;
                        btn.background = true;
                        btn.backgroundColor = 0xdddddd;
                        btn.defaultTextFormat = _format;
                        btn.text = " Call UserName.php ";
                        btn.x = (stage.stageWidth - btn.width) - 10;
                        btn.y = stage.stageHeight - 30;
                        _call.addChild(btn);
                        addChild(_call);
                }
                
                // depress call button and call UserName.loggedInAs method
                private function callDownHandler(event:MouseEvent):void {
                        _call.removeEventListener(MouseEvent.MOUSE_DOWN, callDownHandler);
                        stage.addEventListener(MouseEvent.MOUSE_UP, callUpHandler);
                        _call.x += 2;
                        _call.y += 2;
                        _gateway.call("UserName.loggedInAs",_responder);
                        _display.appendText("\n  Calling UserName.loggedInAs ... ");
                }
                
                // reset call button
                private function callUpHandler(event:MouseEvent):void {
                        _call.addEventListener(MouseEvent.MOUSE_DOWN, callDownHandler);
                        stage.removeEventListener(MouseEvent.MOUSE_UP, callUpHandler);
                        _call.x -= 2;
                        _call.y -= 2;
                }
                
                // connect to AMFPHP gateway
                private function initGateway():void {
                        _gateway = new NetConnection();
                        // Edit the following line to reflect your server configuration
                        _gateway.connect("http://MOODLEROOT/lib/amfphp/gateway.php");
                        _gateway.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
                        _responder = new Responder(onResult,onFault);
                }
                
                // show returned results
                private function onResult(res:Object):void {
                        _display.appendText("\n  " + String(res));
                }
                
                // show details if call is unsuccessful
                private function onFault(res:Object):void {
                        for(var i:String in res) {
                                _display.appendText("\n  " + String(res[i]));
                        }
                }
                
                // show all net status events (can be status or error events)
                function netStatusHandler(event:NetStatusEvent):void {
                        for(var i:String in event.info) {
                                _display.appendText("\n" + String(event.info[i]));
                        }
                }
        }
}
```


---


## AMFPHP Moodle Service Library ##

### AMFPHP Service Browser ###

AMFPHP 1.9.beta comes with a Flex service browser ready installed. It allows you to see your library of services in the MOODLEROOT/lib/amfphp/services/ directory and call them. It also displays error messages very well. It's an ideal tool for checking out your services before your write Flash and Flex applications that call them.

### VERY IMPORTANT! ###

DO NOT leave the service browser installed on a production server (i.e. public). It will leave your service library and therefore your Moodle API and databases exposed to the public!

### User.php Script ###

```
<?php
class User
{
        public function __construct()
        {
                
        }
    /**
     * Returns string indicating whether a user is logged in.
     * @access remote
     */
    public function loggedInAs() {
        global $USER;
        if (isguestuser()){
            return get_string('loggedinasguest', 'moodle');
        }else if (isloggedin()){
            return get_string('loggedinas', 'moodle', fullname($USER));
        } else {
            return get_string('loggedinnot');
        }
    }
    /**
     * Returns an array of config settings you can set.
     * @access remote
     */
    public function configSettings() {
        return array_keys(get_user_preferences());

    }
    /**
     * Returns a value of a config setting.
     * @param string name
     * @access remote
     */
    public function getConfigSetting($name) {
        $name = clean_param($name, PARAM_ALPHAEXT);
        return get_user_preferences($name);
    }
    /**
     * sets a value of a config setting.
     * @param string name
     * @param string value
     * @access remote
     */
    public function setConfigSetting($name, $value) {
        $name = clean_param($name, PARAM_ALPHAEXT);
        $value = clean_param($value, PARAM_NOTAGS);
        return set_user_preference($name, $value);
    }
}
?>
```

### MDL.php ###

The following is a simple AMFPHP service that retrieves current user session data from Moodle. Save this file in the services directory of AMFPHP, i.e. MOODLEROOT/lib/amfphp/services/MDL.php.

This class is PHP5 Object Oriented code and acts as a handy repository for accessing user session data. I haven't forgotten the closing ?> PHP tag. It just isn't recommended for OOP PHP5 classes. (It probably should use getter and setter methods of Moodle's API... but I can't find the relevant documentation!)

**WARNING**: DO NOT upload MDL.php to a public server or anywhere accessible to other users. It exposes information about your Moodle installation that you don't want the world to see!

MDL.php script:

```
<?php
class MDL
{
        public $logged_in = false;
        public $course_id; // course id of current login
        public $course_name; // course name
        public $user_id; // current user's id
        public $data_path; // URL to moodledata directory via moodle/file.php
        
        public function __construct()
        {
                global $CFG;
                require_once('../../../config.php');
                if (isguestuser()){
            $this->logged_in = true;
        }else if (isloggedin()){
            $this->logged_in = true;
                        global $USER;
                        global $SESSION;
                        // set variables
                        $this->course_id = $SESSION->cal_course_referer;
                        $this->course_name = 'Not yet set!'; // TODO - find out how to get this
                        $this->user_id = $USER->id;
                        $this->data_path = $CFG->wwwroot . '/file.php/' . $this->course_id . '/';
        } else {
            $this->logged_in = false;
        }
        }
        /**
        *cleans up objects and variables for garbage collector
        *@returns nothing
        */
        public function __destruct()
        {
                unset($this->logged_in);
                unset($this->course_id);
                unset($this->course_name);
                unset($this->user_id);
                unset($this->data_path);
        }
}
```

You can create an instance of this class like this:

```
<?php
class YourClassName
{
        private $mdl;
        
        public function __construct()
        {
                // create moodle authentication and user variables object
                require('MDL.php');
                $this->mdl = new MDL();
        }
```

You can then access the user session data like this:

```
                $this->mdl->course_id;
                $this->mdl->course_name;
                $this->mdl->user_id;
                $this->mdl->data_path;
```


---


## Useful Links ##

### AMFPHP ###

  * [AMFPHP home page amfphp.org](http://amfphp.org/)
  * [AMFPHP code repository SourceForge.net](http://sourceforge.net/projects/amfphp/)
  * [Sir Lee Brimelow's video tutorial Introduction to AMFPHP part 1](http://gotoandlearn.com/play?id=78)
  * [Sir Lee Brimelow's video tutorial Introduction to AMFPHP part 1](http://gotoandlearn.com/play?id=79)

### Alessandro Crugnola's tutorials ###

  * [Flex RemoteObject and AMFPHP 1.9](http://www.sephiroth.it/tutorials/flashPHP/flex_remoteobject/)
  * [Send and Receive ByteArray to AMFPHP](http://www.sephiroth.it/tutorials/flashPHP/amfphp_bytearray/)
  * [Flex2/Actionscript3.0 and AMFPHP](http://www.sephiroth.it/tutorials/flashPHP/flex2_amfphp/)

### The two most important classes related to AMFPHP are: ###

  * [NetConnection class](http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/net/NetConnection.html)
  * [Responder class](http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/net/Responder.html)

### Other useful resources ###

  * [Interface IExternalizable](http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/utils/IExternalizable.html)

### MISC AMFPHP/Flex/AS3/AIR Tutorials ###

  * [How to set up a simple connection from Actionscript 3 to call your services in AMFPHP](http://soenkerohde.com/2008/06/amfphp-on-air-tutorial-with-mamp/)
  * [Flash Remoting with AS3](http://www.flash-db.com/Tutorials/helloAS3/)
  * [Writing private attributes to a database using AMF in AS3 (IExternalizable)](http://www.actionscriptfreelancer.com/category/php/)
  * [Communication between Flash and AMFPHP made easy](http://www.kylebrekke.com/wordpress/2008/amfphp-as3-class-communication-between-flash-and-amfphp-made-easy/)
  * [AMFPHP in Flash CS3 with AS3 - jQuery.post style](http://www.prodevtips.com/2008/07/28/amfphp-in-flash-cs3-with-as3-jquerypost-style/)
  * [Writing private attributes to a database using AMF in AS3](http://www.actionscriptfreelancer.com/writing-private-attributes-to-a-database-using-amf-in-as3-iexternalizable/)
  * [Turning Flash CS3 assets into Flex components using Flex Component Kit](http://blog.flexcommunity.net/?p=40)

Sample Flex,AS3(&AIR) Sourcecode & Applications

  * [AMF-PHP based poll in Flex](http://polygeek.com/510_flex-tutorial_amf-php-based-poll-in-flex)
  * [AMFPHP on AIR Tutorial with MAMP](http://soenkerohde.com/2008/06/amfphp-on-air-tutorial-with-mamp/)

### Misc Example AMFPHP Framework Examples ###

  * [Combining Drupal, AMFPHP, SWFAddress and Flash](http://modern-carpentry.com/talk/?p=32)
  * [Displaying Paypal IPN payment data in Adobe Flex via AMFPHP](http://blog.flexcommunity.net/?p=43)

### AS3 APIS ###

  * [Youtube Data API AS3](http://code.google.com/apis/youtube/flash_api_reference.html)
  * [Google Maps API AS3](http://code.google.com/apis/maps/documentation/flash/)
  * [Yahoo! Flash Developer Center](http://developer.yahoo.com/flash/)

### AMFPHP Is Fast ###

There's always someone who'll play with a new toy and push it until it breaks. AMFPHP can go pretty fast! [Check out this blog article](http://www.5etdemi.com/blog/archives/2007/01/amfphp-19-beta-2-ridiculously-faster/).