/**
* class AMF connects learning applications to Moodle DB via services/SWF.php
* Uses AMFPHP 1.9
*
* @author Matt Bury - matbury@gmail.com - http://matbury.com/
* 
* Requires com.matbury.swf.FlashVars.as
*/

/*
Example code:

import com.matbury.sam.AMF;

var amf:AMF = new AMF();
amf.addEventListener(AMF.GOT_INFO, gotInfoHandler);
amf.addEventListener(AMF.GOT_DATA, gotDataHandler);
amf.addEventListener(AMF.GOT_FEEDBACK, gotFeedbackHandler);
amf.addEventListener(AMF.SET_GRADE, setGradeHandler);
amf.addEventListener(AMF.GOT_GRADES, gotGradesHandler);

amf.getInteractionInfo();
amf.getInteractionData();
amf.getFeedback();
amf.setGrade();
amf.getGrades();

function gotInfoHandler(event:Event):void {
	amf.removeEventListener(AMF.GOT_INFO, gotInfoHandler);
}

function gotDataHandler(event:Event):void {
	amf.removeEventListener(AMF.GOT_DATA, gotDataHandler);
}

function gotFeedbackHandler(event:Event):void {
	amf.removeEventListener(AMF.GOT_FEEDBACK, gotFeedbackHandler);
}

function gotDataHandler(event:Event):void {
	amf.removeEventListener(AMF.SET_GRADE, setGradeHandler);
}


function gotDataHandler(event:Event):void {
	amf.removeEventListener(AMF.GOT_GRADES, gotGradesHandler);
}


*/

package {
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.NetStatusEvent;
	import flash.events.SecurityErrorEvent;
	import flash.net.NetConnection;
	import flash.net.Responder;
	
	public class AMF extends Sprite {
		
		// server
		private var _info:Responder;
		private var _data:Responder;
		private var _feedback:Responder;
		private var _grade:Responder;
		private var _grades:Responder;
		private var _nc:NetConnection;
		// events
		public static const GOT_INFO:String = "gotInfo";
		public static const GOT_DATA:String = "gotData";
		public static const GOT_FEEDBACK:String = "gotFeedback";
		public static const SET_GRADE:String = "setGrade";
		public static const GOT_GRADES:String = "gotGrades";
		public static const FAULT:String = "fault";
		public static const METHOD_FAULT:String = "methodFault";
		public static const SECURITY_FAULT:String = "securityFault";
		public static const NET_STATUS_FAULT:String = "netStatusFault";
		
		public function AMF() {
			initConnection();
		}
		
		// this function is deprecated
		public function loadData():void {
			//getInteractionInfo();
			//getInteractionData();
			//getFeedback();
			//setGrade();
			//getGrades();
		}
		
		private function initConnection():void {
			_nc = new NetConnection();
			_nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
			_nc.connect(FlashVars.gateway);
		}
		
		// ---------------------------------------------------------------------
		public function getInteractionInfo():void {
			_info = new Responder(onInfo,onFault);
			_nc.call("SWF.getInteractionInfo", _info, FlashVars.interaction);
		}
		
		private function onInfo(obj:Object):void {
			FlashVars.interactionInfo = obj;
			FlashVars.amfTable = obj.amftable;
			dispatchEvent(new Event(GOT_INFO));
		}
		
		// ---------------------------------------------------------------------
		public function getInteractionData():void {
			_data = new Responder(onIntData,onFault);
			_nc.call("SWF.getInteractionData", _data, FlashVars.interaction, FlashVars.amfTable);
		}
		
		private function onIntData(arr:Array):void {
			FlashVars.interactionData = arr;
			dispatchEvent(new Event(GOT_DATA));
		}
		
		// ---------------------------------------------------------------------
		public function getFeedback():void {
			_feedback = new Responder(onFeedback,onFault);
			_nc.call("SWF.getFeedback", _feedback);
		}
		
		private function onFeedback(arr:Array):void {
			FlashVars.feedback = arr;
			dispatchEvent(new Event(GOT_FEEDBACK));
		}
		
		// ---------------------------------------------------------------------
		public function setGrade():void {
			_grade = new Responder(onSet,onFault);
			_nc.call("SWF.setGrade", _grade, FlashVars.grade);
		}
		
		private function onSet(obj:Object):void {
			dispatchEvent(new Event(SET_GRADE));
		}
		
		// ---------------------------------------------------------------------
		public function getGrades():void {
			_grades = new Responder(onGrades,onFault);
			_nc.call("SWF.getGrades", _grades);
		}
		
		private function onGrades(arr:Array):void {
			FlashVars.grades = arr;
			dispatchEvent(new Event(GOT_GRADES));
		}
		
		// ---------------------------------------------------------------------
		private function onFault(obj:Object):void {
			FlashVars.info = obj.description;
			dispatchEvent(new Event(METHOD_FAULT));
		}
		
		private function netStatusHandler(event:NetStatusEvent):void {
			dispatchEvent(new Event(NET_STATUS_FAULT));
		}
		
		private function securityErrorHandler(event:SecurityErrorEvent):void {
			dispatchEvent(new Event(SECURITY_FAULT));
		}
	}
}