# Delete the Service Browser! #

The AMFPHP Flash Remoting package contains a service browser application which is useful for debugging and developing new service classes. However, it should not be uploaded to a publicly accessible server. The following directories and all their contents should be deleted:

  * MOODLE/lib/amfphp/browser/
  * MOODLE/lib/amfphp/services/amfphp/


# Flash Security #

The Flash platform is a fully functioning development platform. As such it is very important to ensure that any Flash applications deployed on your server are secure and contain no malware or spyware. Do not deploy any Flash application if you do not know who developed it and exactly what it does.

Remember: Treat Flash applications as cautiously as you would any other software.

## Allow Script Access and Allow Networking ##

The default settings for these advanced parameters can be set by Admins in:

  * Moodle > Site Administration > Modules > Activities > SWF

This allows you to set site wide guidelines for teachers to follow. If you want to restrict the options available the parameter settings can be found in:

  * moodle/mod/swf/lib.php in the functions
  * swf\_list\_allownetworking() and
  * swf\_list\_allowscriptaccess()

This will only effect new instances of the SWF Activity Module.

## Further reading ##

  * [Controlling access to scripts in a host web page](http://kb2.adobe.com/cps/164/tn_16494.html)
  * [Restricting networking APIs](http://www.adobe.com/livedocs/flash/9.0/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00001079.html)
  * [Cross-domain policy for Flash movies](http://kb2.adobe.com/cps/142/tn_14213.html)