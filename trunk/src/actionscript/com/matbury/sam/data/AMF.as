/**
* class Amf connects learning applications to Moodle DB via lib/amfphp/services
* Uses AMFPHP 1.9
*
* @author Matt Bury - matbury@gmail.com - http://matbury.com/
* 
* Requires com.matbury.sam.data.FlashVars.as
*/

/*
Example code to push a grade into Moodle grade book:

import com.matbury.sam.data.FlashVars
import com.matbury.sam.data.Amf;

var amf:Amf = new Amf();
amf.addEventListener(Amf.GOT_DATA, gotDataHandler);
var obj:Object = new Object();
obj.swfid = FlashVars.swfid; // required
obj.instance = FlashVars.instance; // required
obj.course = FlashVars.course; // optional
obj.feedback = _feedback.text; // optional
obj.feedbackformat = Math.round(getTimer() / 1000); // optional
obj.rawgrade = int(_input.text); // optional
obj.serviceFunction = "Grades.amf_grade_update"; // required
amf.getObject(obj);

function gotDataHandler(event:Event):void {
	amf.removeEventListener(Amf.GOT_DATA, gotDataHandler);
}


*/

package com.matbury.sam.data {
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.NetStatusEvent;
	import flash.events.SecurityErrorEvent;
	import flash.net.NetConnection;
	import flash.net.Responder;
	import com.matbury.sam.data.FlashVars;
	
	public class Amf extends Sprite {
		
		// server
		private var _responder:Responder;
		private var _nc:NetConnection;
		private var _obj:Object;
		private var _array:Array;
		// events
		public static const GOT_DATA:String = "gotData";
		public static const FAULT:String = "fault";
		public static const SECURITY_FAULT:String = "securityFault";
		
		public function Amf() {
			initConnection();
		}
		
		private function initConnection():void {
			_nc = new NetConnection();
			_nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
			_nc.connect(FlashVars.gateway);
		}
		
		// --------------------------------------------------------------------- return object
		public function getObject(obj:Object):void {
			_responder = new Responder(onObject,onFault);
			_nc.call(obj.serviceFunction, _responder, obj);
		}
		
		private function onObject(obj:Object):void {
			_obj = obj;
			dispatchEvent(new Event(GOT_DATA));
		}
		
		// --------------------------------------------------------------------- return Array
		public function getArray(obj:Object):void {
			_responder = new Responder(onArray,onFault);
			_nc.call(obj.serviceFunction, _responder, obj);
		}
		
		private function onArray(obj:Object):void {
			// Moodle's get_records() returns objects containing objects
			// i.e. obj['1'] = [object Object]
			// so we need to convert it into an array of objects
			_array = new Array();
			for(var s:String in obj) {
				_array.push(obj[s]);
			}
			dispatchEvent(new Event(GOT_DATA));
		}
		
		// --------------------------------------------------------------------- errors
		private function onFault(obj:Object):void {
			FlashVars.amfinfo = obj;
			dispatchEvent(new Event(FAULT));
		}
		
		private function netStatusHandler(event:NetStatusEvent):void {
			_obj = event;
			dispatchEvent(event);
		}
		
		private function securityErrorHandler(event:SecurityErrorEvent):void {
			_obj = event;
			dispatchEvent(new Event(SECURITY_FAULT));
		}
		
		// --------------------------------------------------------------------- returned data
		public function get obj():Object {
			return _obj;
		}
		
		public function get array():Array {
			return _array;
		}
	}
}