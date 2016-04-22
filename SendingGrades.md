## Introduction ##

This page describes how Actionscript 3.0 developers can include code in learning applications that sends grades to Moodle's grade book via the SWF Activity Module API.


---


## Getting started ##

Requirements:

  * The SWF Activity Module com.matbury.sam.data package installed in your Actionscript 3.0 class path

The following SWF Activity Module instance parameters, on the "Editing SWF" page, should be left at their default values:
  * Allow Script Access = all
  * Allow Networking = sameDomain

### Please note ###

The AMFPHP service scripts are designed for production servers. They follow Moodle's capabilities and permissions and therefore only work with users that are logged in and Flash apps that are deployed with all the correct parameters and paths suppled either by the SWF Activity Module or some other means. In other words, you can't successfully call the AMFPHP service scripts from the Flash IDE.


---


## Example code ##

Firstly, import the SWF Activity Module API classes, usually in the document class:
```
import com.matbury.sam.data.Amf;
import com.matbury.sam.data.FlashVars;
```

Next, load the SWF Activity Module instance ID numbers into the FlashVars static class object. Please note that FlashVars is a static class so there is no need to create an instance of it, i.e. var flashVars:FlashVars = new FlashVars():
```
FlashVars.vars = this.root.loaderInfo.parameters;
```

Then, write the function that sends the grades
```
/*
############################ SEND DATA #############################
*/

private var _amf:Amf;
private var _text:TextField; // Add the required code to create a text field. 

private function sendGrade():void {
	_amf = new Amf(); // create Flash Remoting API object
	_amf.addEventListener(Amf.GOT_DATA, gotDataHandler); // listen for server response
	_amf.addEventListener(Amf.FAULT, faultHandler); // listen for server fault
	var obj:Object = new Object(); // create an object to hold data sent to the server
	obj.gateway = FlashVars.gateway; // (String) AMFPHP gateway URL
	obj.swfid = FlashVars.swfid; // (int) activity ID
	obj.instance = FlashVars.instance; // (int) Moodle instance ID
	//For some reason, Moodle returns Wiki formatting warning (bug) 
	// so it's necessary to cast obj.feedback as a string!
	obj.feedback = String("Some text feedback for teachers and/or learners to read"); // (String) optional
	obj.feedbackformat = Math.floor(getTimer() / 1000); // (int) elapsed time in seconds
	obj.rawgrade = 100; // (Number) grade, normally 0 - 100 but depends on grade book settings
	obj.servicefunction = "Grades.amf_grade_update"; // (String) ClassName.method_name
	_amf.getObject(obj); // send the data to the server
}

// Connection to AMFPHP succeeded
// Manage returned data and inform user
private function gotDataHandler(event:Event):void {
	// Clean up listeners
	_amf.removeEventListener(Amf.GOT_DATA, gotDataHandler);
	_amf.removeEventListener(Amf.FAULT, faultHandler);
	// Check if grade was sent successfully
	try {
		switch(_amf.obj.result) {
			// Adapt the following code to manage output from AMFPHP as you wish
			//
			case "SUCCESS":
			_text.appendText(_amf.obj.message);
			break;
			//
			case "NO_PERMISSION":
			_text.appendText(_amf.obj.message);
			break;
			//
			default:
			_text.appendText(_amf.obj.message);
		}
	} catch(e:Error) {
		_text.appendText(_amf.obj.message);
	}
}

private function faultHandler(event:Event):void {
	// clean up listeners
	_amf.removeEventListener(Amf.GOT_DATA, gotDataHandler);
	_amf.removeEventListener(Amf.FAULT, faultHandler);
	// Display server errors
	var msg:String = "Error: ";
	for(var s:String in _amf.obj.info) { // trace out returned data
		_text.appendText("\n" + s + "=" + _amf.obj.info[s]);
	}
}
```


---


## Common problems ##

### Grades successfully pushed but not confirmed by Flash client ###

This is more than likely due to your server running PHP 5.3 or later. There are a few hundred lines of deprecated code in Moodle's core that throw deprecation warnings in PHP 5.3. There is currently no solution other than to go into Moodle's core code and correct the deprecated functions.