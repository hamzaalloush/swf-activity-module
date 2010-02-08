/**
* class FlashVars
* package com.matbury.sam.data
* By Matt Bury - matbury@gmail.com - http://matbury.com/
* Version 0.2 28/11/2009
* Licence - GNU GPL 3.0 http://www.gnu.org/licenses/gpl.html
* SWF Activity Module variables
*
*/

/*
Example FLA/doc class code:

import com.matbury.sam.data.FlashVars;
FlashVars.vars = this.root.loaderInfo.parameters;

*/

package com.matbury.sam.data {
	
	public class FlashVars extends Object {
// ------------------------------- VARS ------------------------------------ //
		private static var _info:Object; // stores error messages about parameters in FlashVars
		private static var _amfinfo:Object; // stores error messages about parameters in Amf
		// passed in by mod/swf/lib.php
		// Moodle FlashVars in alphabetical order
		private static var _apikey:String;
		private static var _configxml:String;
		private static var _course:String;
		private static var _coursepage:String;
		private static var _gateway:String;
		private static var _grademax:String;
		private static var _gradepass:String;
		private static var _instance:uint;
		private static var _interaction:uint;
		private static var _moodledata:String;
		private static var _skin:String;
		private static var _starttime:String;
		private static var _swfid:uint;
		private static var _xmlurl:String;
		
		// retrieved from XML or AMFPHP:
		public static var interactioninfo:Object;
		public static var interactiondata:Array;
		public static var feedback:Array;
		public static var grade:Object;
		public static var grades:Array;
		
// ------------------------ SETTER FUNCTION ------------------------ //
		// this.root.loaderInfo.parameters object passed in from main SWF assign parameters to 
		//static variables so they can be accessed from anywhere in an application
		// Listed in alphabetical order
		public static function set vars(obj:Object):void {
			_info = new Object();
			// apiKey:String = null - Some web services require an API key for authentication, e.g. Google Maps
			validated = checkVar(obj.apikey);
			if(validated) {
				_apikey = obj.apikey;
			} else {
				_info.apikey = "apikey not set";
			}
			// configXml:String = null - FlashVars can also be set using a config XML file
			validated = checkVar(obj.configxml);
			if(validated) {
				_configxml = obj.configxml;
			} else {
				_info.configxml = "configxml not set";
			}
			// course:String - Current course ID
			validated = checkVar(obj.course);
			if(validated) {
				_course = obj.course;
			} else {
				_info.course = "course not set";
			}
			// coursePage:String - URL to current course page, useful for redirects
			validated = checkVar(obj.coursepage);
			if(validated) {
				_coursepage = obj.coursepage;
			} else {
				_info.coursepage = "coursepage not set";
			}
			// gateway:String - Flash Remoting gateway for communicating with Moodle
			var validated:Boolean = checkVar(obj.gateway);
			if(validated) {
				_gateway = obj.gateway;
			} else {
				_info.gateway = "gateway not set";
			}
			// gradeMax:String = null - FlashVars can also be set using a config XML file
			validated = checkVar(obj.grademax);
			if(validated) {
				_grademax = obj.grademax;
			} else {
				_info.grademax = "grademax not set";
			}
			// gradePass:String = null - FlashVars can also be set using a config XML file
			validated = checkVar(obj.gradepass);
			if(validated) {
				_gradepass = obj.gradepass;
			} else {
				_info.gradepass = "gradepass not set";
			}
			// instance:int - instance of activity module ID
			validated = checkVar(obj.instance);
			if(validated) {
				_instance = obj.instance;
			} else {
				_info.instance = "instance not set";
			}
			// interaction:int - learning interaction data set ID
			validated = checkVar(obj.interaction);
			if(validated) {
				_interaction = obj.interaction;
			} else {
				_info.interaction = "interaction not set";
			}
			// moodleData:String - URL to current course moodledata directory
			validated = checkVar(obj.moodledata);
			if(validated) {
				_moodledata = obj.moodledata;
			} else {
				_info.moodledata = "moodledata not set";
			}
			// skin:String = null - Flash applications can load an external SWF containing GUI classes
			validated = checkVar(obj.skin);
			if(validated) {
				_skin = obj.skin;
			} else {
				_info.skin = "skin not set";
			}
			// starttime:String - start time on server of learning interaction
			validated = checkVar(obj.starttime);
			if(validated) {
				_starttime = obj.starttime;
			} else {
				_info.starttime = "starttime not set";
			}
			// swfId:int - instance of swf module ID
			validated = checkVar(obj.swfid);
			if(validated) {
				_swfid = obj.swfid;
			} else {
				_info.swfid = "swfid not set";
			}
			// xmlUrl:String = null - URL to learning interaction data XML file
			validated = checkVar(obj.xmlurl);
			if(validated) {
				_xmlurl = obj.xmlurl;
			} else {
				_info.xmlurl = "xmlurl not set";
			}
		}
		
// ------------------------ GETTER FUNCTIONS ------------------------ //

		// Listed in alphabetical order
		
		public static function get apikey():String {
			return _apikey;
		}
		
		public static function get configxml():String {
			return _configxml;
		}
		
		public static function get coursepage():String {
			return _coursepage;
		}
		
		public static function get course():String {
			return _course;
		}
		
		public static function get gateway():String {
			return _gateway;
		}
		
		public static function get grademax():String {
			return _grademax;
		}
		
		public static function get gradepass():String {
			return _gradepass;
		}
		
		public static function get instance():uint {
			return _instance;
		}
		
		public static function get interaction():uint {
			return _interaction;
		}
		
		public static function get moodledata():String {
			return _moodledata;
		}
		
		public static function get skin():String {
			return _skin;
		}
		
		public static function get starttime():String {
			return _starttime;
		}
		
		public static function get swfid():uint {
			return _swfid;
		}
		
		public static function get xmlurl():String {
			return _xmlurl;
		}
		
// -------------------------------------------------------------------------- //
		
		// Error report
		public static function set amfinfo(obj:Object):void {
			_amfinfo = obj;
		}
		
		public static function get amfinfo():Object {
			return _amfinfo;
		}
		
		public static function get info():Object {
			return _info;
		}
		
// --------------------------- VALIDATE FLASHVARS --------------------------- //
		
		private static function checkVar(s:String):Boolean {
			// If null
			if(!s) {
				return false;
			}
			try{
				var str:String = s;
			} catch (e:Error) {
				return false;
			}
			// If malicious injection attack
			if(s.indexOf("javascript") != -1) {
				_info += "WARNING! Malicious injection attack detected!!! -> ";
				throw new Error("WARNING! Malicious injection attack detected!!! -> " + s);
				s = null;
				return false;
			}
			return true;
		}
	}
}