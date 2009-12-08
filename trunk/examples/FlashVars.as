/**
* class FlashVars
* package com.matbury.sam
* By Matt Bury - matbury@gmail.com - http://matbury.com/
* Version 0.2 28/11/2009
* Licence - GNU GPL 3.0 http://www.gnu.org/licenses/gpl.html
* SWF Activity Module variables
*
*/

/*
Example FLA/doc class code:

import com.matbury.sam.FlashVars;
FlashVars.vars = this.root.loaderInfo.parameters;

*/

package {
	
	public class FlashVars extends Object {
// ------------------------------- VARS ------------------------------------ //
		private static var _info:Object; // stores error messages about parameters in FlashVars
		// passed in by mod/swf/lib.php
		// Moodle FlashVars
		private static var _gateway:String;
		private static var _amfTable:String;
		private static var _instance:uint;
		private static var _interaction:uint;
		private static var _swfId:uint;
		private static var _moodleData:String;
		private static var _coursePage:String;
		private static var _xmlUrl:String;
		private static var _apiKey:String;
		private static var _flashVar1:String;
		private static var _flashVar2:String;
		private static var _flashVar3:String;
		// retrieved from XML or AMFPHP:
		public static var interactionInfo:Object;
		public static var interactionData:Array;
		public static var feedback:Array;
		public static var grade:Object;
		public static var grades:Array;
		
// ------------------------ SETTER FUNCTION ------------------------ //
		// this.root.loaderInfo.parameters object passed in from main SWF
		// assign parameters to static variables so they can be accessed
		// from anywhere in an application
		public static function set vars(obj:Object):void {
			_info = new Object();
			// gateway:String - Flash Remoting gateway for communicating with Moodle
			var validated:Boolean = checkVar(obj.gateway);
			if(validated) {
				_gateway = obj.gateway;
			} else {
				_info.gateway = "gateway not set";
			}
			// amfTable:String = null - name of table that stores learning interaction data
			validated = checkVar(obj.amftable);
			if(validated) {
				_amfTable = obj.amftable;
			} else {
				_info.amfTable = "amftable not set";
			}
			// instance:int - instance of activity module ID
			validated = checkInt(obj.instance);
			if(validated) {
				_instance = obj.instance;
			} else {
				_info.instance = "instance not set";
			}
			// interaction:int - learning interaction data set ID
			validated = checkInt(obj.interaction);
			if(validated) {
				_interaction = obj.interaction;
			} else {
				_info.interaction = "interaction not set";
			}
			// swfId:int - instance of swf module ID
			validated = checkInt(obj.swfid);
			if(validated) {
				_swfId = obj.swfid;
			} else {
				_info.swfId = "swfId not set";
			}
			// moodleData:String - URL to current course moodledata directory
			validated = checkVar(obj.moodledata);
			if(validated) {
				_moodleData = obj.moodledata;
			} else {
				_info.moodleData = "moodleData not set";
			}
			// coursePage:String - URL to current course page, useful for redirects
			validated = checkVar(obj.coursepage);
			if(validated) {
				_coursePage = obj.coursepage;
			} else {
				_info.coursePage = "coursePage not set";
			}
			// xmlUrl:String = null - URL to learning interaction data XML file
			validated = checkVar(obj.xmlurl);
			if(validated) {
				_xmlUrl = obj.xmlurl;
			} else {
				_info.xmlUrl = "xmlUrl not set";
			}
			// apiKey:String = null - Some web services require an API key for authentication, e.g. Google Maps
			validated = checkVar(obj.apikey);
			if(validated) {
				_apiKey = obj.apikey;
			} else {
				_info.apiKey = "apiKey not set";
			}
			// flashVar1:String = null - pass in anything here that doesn't fit anywhere else
			validated = checkVar(obj.flashvar1);
			if(validated) {
				_flashVar1 = obj.flashvar1;
			} else {
				_info.flashVar1 = "flashVar1 not set";
			}
			// flashVar2:String = null - pass in anything here that doesn't fit anywhere else
			validated = checkVar(obj.flashvar2);
			if(validated) {
				_flashVar2 = obj.flashvar2;
			} else {
				_info.flashVar2 = "flashVar2 not set";
			}
			// flashVar3:String = null - pass in anything here that doesn't fit anywhere else
			validated = checkVar(obj.flashvar3);
			if(validated) {
				_flashVar3 = obj.flashvar3;
			} else {
				_info.flashVar3 = "flashVar3 not set";
			}
		}
		
// ------------------------ GETTER FUNCTIONS ------------------------ //

		public static function get gateway():String {
			return _gateway;
		}
		
		public static function get amfTable():String {
			return _amfTable;
		}
		
		public static function get instance():uint {
			return _instance;
		}
		
		public static function get interaction():uint {
			return _interaction;
		}
		
		public static function get swfId():uint {
			return _swfId;
		}
		
		public static function get moodleData():String {
			return _moodleData;
		}
		
		public static function get coursePage():String {
			return _coursePage;
		}
		
		public static function get xmlUrl():String {
			return _xmlUrl;
		}
		
		public static function get apiKey():String {
			return _apiKey;
		}
		
		public static function get flashVar1():String {
			return _flashVar1;
		}
		
		public static function get flashVar2():String {
			return _flashVar2;
		}
		
		public static function get flashVar3():String {
			return _flashVar3;
		}
// -------------------------------------------------------------------------- //
		
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
				s = null;
				_info += "WARNING! Malicious injection attack detected!!! -> ";
				return false;
			}
			return true;
		}
		
		private static function checkInt(s:int):Boolean {
			// If null
			if(!s) {
				return false;
			}
			try{
				var num:int = s;
			} catch (e:Error) {
				return false;
			}
			return true;
		}
	}
}