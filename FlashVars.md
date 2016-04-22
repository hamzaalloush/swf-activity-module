

## Introduction ##

The Flash embed code of the SWF Activity Module passes in parameters via FlashVars dynamically that can be accessed by the e-learning application.

## List of parameters ##

(In alphabetical order)

  * **apikey:String** - Some web services require an API key for authentication
  * **configxml:String** - An option to set all FlashVars parameters via an externally loaded XML file
  * **course:uint** - Current Moodle course ID
  * **coursepage:String** - Quick link to current Moodle course page
  * **fullbrowser:String** - SWF Activity Module is in full browser mode: 100% width, 100% height
  * **gateway:String** - URL to Flash Remoting gateway
  * **gradebook:String** - URL to Moodle's grade book user report page
  * **grademax:Number** - Maximum grade currently set in Moodle grade book item
  * **grademin:Number** - Minimum grade currently set in Moodle grade book item
  * **instance:uint** - Moodle module instance ID
  * **moodledata:String** - URL to current Moodle course files directory
  * **nextinstance:uint** - Next uncompleted Moodle module instance ID according to Moodle grade book
  * **skin:String** - URL to skin SWF file or skin XML file
  * **starttime:uint** - Server time for start of learning interaction
  * **swfid:uint** - SWF Activity Module instance ID
  * **wwwroot:String** - The root directory of the Moodle installation, e.g. http://example.com/moodle/
  * **xmlurl:String** - URL to learning interaction data XML file

Additionally, non-standard name value pairs can be passed in by the SWF Activity Module. Access these in the normal way. For example:

```
var myString:String = this.root.loaderInfo.parameters.mystring;
var myNumber:Number = this.root.loaderInfo.parameters.mynumber;
// etc.
```


---


## Optional user-defined FlashVars ##

The SWF Activity Module now includes 3 optional FlashVars that are user-defined name-value pairs. These were originally set names, i.e. flashvar1, flashvar2 and flashvar3. Now you can pass any FlashVars into your Flash e-learning applications.


---


## Example code ##

The FlashVars object is a static class and therefore can be accessed from anywhere within a Flash application. Create and initialise the FlashVars object like this:
```
import com.matbury.sam.data.FlashVars;

public function initFlashVars():void {
     FlashVars.vars = this.root.loaderInfo.parameters;
}
```

All the listed FlashVars passed in via the SWF Activity Module are now accessible.


---


## FlashVars.info ##

The FlashVars.info object is a record of the status of each of the FlashVars parameters passed in by the SWF Activity Module. Here's some example code to read off those messages:

```
private function readInfo():void {
     var tf:TextField = new TextField();
     tf.autoSize = TextFieldAutoSize.LEFT;
     tf.multiline = true;
     tf.text = "FlashVars.info:";
     addChild(tf);
     for(var s:String in FlashVars.info) {
          tf.appendText("\n" + s + " = " + FlashVars.info[s]);
     }
}
```


---


## Keeping learning interaction data transferable ##

Please note that the following values are not passed in as full URLs:

  * **FlashVars.configxml**
  * **FlashVars.skin**

Therefore the full URLs should be contructed like this:
```
var config:String = FlashVars.moodledata + FlashVars.configxml;
var skin:String = FlashVars.moodledata + FlashVars.skin;
var xmlurl:String = FlashVars.xmlurl; // exception
```

When referencing media in the corresponding moodledata course directory, it's best to use URLs relative to the root of that directory and then build the full URL by concatenating it with FlashVars.moodledata. For example:
```
// Should be:
<image>animals/images/elephant_african.jpg</image>
// and not:
<image>http://mymoodle.com/file.php/99/animals/images/elephant_african.jpg</image>
```

This way, you can upload entire directories of learning interaction data from any course into new courses and they will work without any modification.