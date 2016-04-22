# Snapshot #

The Snapshot.php service script for the SWF Activity Module saves screenshot images taken by Flash applications to the moodledata course directory and pushes a grade and an HTML link (feedback) to the image into the grade book.

## Example Flash application ##

There is an open source [Concept Map](http://code.google.com/p/swf-activity-module/downloads/detail?name=concept_map_application_src_2011_06_23.zip) application for you in the [downloads](http://code.google.com/p/swf-activity-module/downloads/list) section. You can try it out on the [matbury.com Moodle 1.9 demo course](http://moodle.matbury.com/course/view.php?id=9).

## Work flow ##

  1. Learner takes snapshot in current Flash application
  1. Flash application encodes snapshot and sends it to Snapshot.php service
  1. Snapshot.php service saves snapshot image as JPG or PNG in moodledata course files directory
  1. Snapshot.php pushes grade and a link to saved image into Moodle grade book
  1. Snapshot.php returns URL of saved image to Flash application
  1. Flash application opens saved image in new window (to confirm it was successfully saved)
  1. Teachers can manually edit grades and add feedback in grade book

## Requirements ##

  * Moodle 1.8 or 1.9
  * SWF Activity Module installed
  * AMFPHP 1.9 (preconfigured version with services included in SWF Activity Module downloads) installed
  * Adobe [as3corelib class library](https://github.com/mikechambers/as3corelib) installed in your Actionscrip 3.0 class path
  * com.matbury.sam.data class library installed in your Actionscript 3.0 class path
  * Your Actionscript compiler of choice.

## Import Actionscript classes ##

```
	import com.adobe.images.PNGEncoder; // Static class, doesn't need instantiating
	import com.adobe.images.JPGEncoder; // Normal class, needs instantiating
	import com.matbury.sam.data.Amf; // Normal class, communicates with Moodle (AMF3)
	import com.matbury.sam.data.FlashVars; // Static class, easy way to manage SWF Activity Module data
```

## Take a snapshot ##

var _imageType:String; // png/jpg defines the image MIME type to be encoded
var_byteArray:ByteArray; // stores the encoded bitmap data to send to the Snapshot service
```
	private function takeSnapshot(event:MouseEvent):void {
		// Define the dimensions of the area to record
		var bitmapData:BitmapData = new BitmapData(stage.stageWidth,stage.stageHeight);
		// Record a DisplayObject. In this case the stage cast as a Sprite.
		bitmapData.draw(Sprite(this));
		// Record it in the selected file format
		if(_imageType == "png") {
			_byteArray = PNGEncoder.encode(bitmapData);
			sendData();
		} else if(_imageType == "jpg") {
			var jpgEncoder:JPGEncoder = new JPGEncoder();
			_byteArray = jpgEncoder.encode(bitmapData);
			sendData();
		} else {
			throw new Error("Error: image MIME type undefined. Set it as .jpg or .png");
		}
	}
```

## Send the image ##

```
// Send the snapshot to the server to be saved by Snapshot.php
	private function sendData():void {
		// Send the ByteArray to AMFPHP
		_amf = new Amf(); // create Flash Remoting API object
		_amf.addEventListener(Amf.GOT_DATA, gotDataHandler); // listen for server response
		_amf.addEventListener(Amf.FAULT, faultHandler); // listen for server fault
		var obj:Object = new Object(); // create an object to hold data sent to the server
		obj.feedback = ""; // (String) optional
		obj.feedbackformat = Math.floor(getTimer() / 1000); // (int) elapsed time in seconds
		obj.gateway = FlashVars.gateway; // (String) AMFPHP gateway URL
		obj.instance = FlashVars.instance; // (int) Moodle instance ID
		obj.rawgrade = 0; // (Number) grade, normally 0 - 100 but depends on grade book settings
		obj.pushgrade = true; // To push or not push a grade
		obj.servicefunction = "Snapshot.amf_save_snapshot"; // (String) ClassName.method_name
		obj.swfid = FlashVars.swfid; // (int) activity ID
		obj.bytearray = _byteArray;
		obj.imagetype = _imageType; // PNGExport = png, JPGExport = jpg
		_amf.getObject(obj); // send the data to the server
	}
		
	// Connection to AMFPHP succeeded
	// Manage returned data and inform user
	private function gotDataHandler(event:Event):void {
		// Clean up listeners
		_amf.removeEventListener(Amf.GOT_DATA, gotDataHandler);
		_amf.removeEventListener(Amf.FAULT, faultHandler);
		// Check if grade was sent successfully
		switch(_amf.obj.result) {
			//
			case "SUCCESS":
			throw new Error("Error: Your image was saved successfully.");
			navigateToImage(_amf.obj.imageurl);
			break;
			//
			case "NO_SNAPSHOT_DIRECTORY":
			throw new Error("Error: " + _amf.obj.imageurl + " does not exist");
			break;
			//
			case "FILE_NOT_WRITTEN":
			throw new Error("Error: There was a problem. Your image was not saved.");
			break;
			//
			case "NO_PERMISSION":
			_display.text = _amf.obj.message;
			break;
			//
			default:
			throw new Error("Unknown error.");
		}
	}
	
	// Connection to AMFPHP failed
	private function faultHandler(event:Event):void {
		// Clean up listeners
		_amf.removeEventListener(Amf.GOT_DATA, gotDataHandler);
		_amf.removeEventListener(Amf.FAULT, faultHandler);
		throw new Error("Error: There was a problem. Your image was not saved.");
	}
	
	private function navigateToImage(url:String):void {
		// Open returned URL in a new window,
		var request:URLRequest = new URLRequest(url);
		navigateToURL(request,"_blank");
		// or...
		// redirect to Moodle grade book
		//var gradebook:String = FlashVars.gradebook;
		//navigateToURL(request,"_self");
	}
```

## Possible issues ##

Two users have reported that Snapshot.php fails to write files to Moodle's course files directories (moodledata), triggering either 500 or 403 HTTP errors. In both cases, the Moodle installation was divided between two servers, i.e. Moodle on one server and moodledata on another. If this is the case, contact your IT support about how to resolve the write permissions from moodle/lib/amfphp/services/Snapshot.php to moodledata.

## Notes ##

Obviously, you'll want to use something more elegant and practical that throw new Error() to inform users of what's going on with your application. This is just a quick and dirty version of example code to get you started. Good luck and happy coding! :)

## Acknowledgements ##

<a href='http://www.greenwoodcollege.com'><img src='http://matbury.com/assets/Greenwood-logo-loRes-small.jpg' align='right' height='67' width='213' alt='Greenwood logo' border='0' /></a>The interactive whiteboard SWF idea was conceived at Greenwood College School as a means to further personalize the learning experience for its students. Greenwood is excited to be partnering with Matt Bury on this project because this module enhancement will help educators using Moodle to track student progress using a flexible, online input method.

http://www.greenwoodcollege.com