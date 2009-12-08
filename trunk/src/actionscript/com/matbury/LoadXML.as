/**
* class LoadXML (c) Matt Bury 2007
* package com.matbury
* By Matt Bury - matbury@gmail.com - http://matbury.com/
* Version 1.0 28/11/2009
* Licence - GNU GPL 3.0 http://www.gnu.org/licenses/gpl.html
* SWF Activity Module LoadXML handles loading XML files 
*
*/

/*
Example code:

import com.matbury.LoadXML

var url:String = "http://yoursite.com/path/to/xml/file.xml";
var test:Boolean = true; // change to false for production server
var lxml:LoadXML = new LoadXML(url,test);
lxml.addEventListener(LoadXML.LOADED, loadedHandler);
lxml.addEventListener(LoadXML.FAILED, failedHandler);

function loadedHandler(event:Event):void {
	lxml.removeEventListener(LoadXML.LOADED, loadedHandler);
	lxml.removeEventListener(LoadXML.FAILED, failedHandler);
	// do something
}

function failedHandler(event:Event):void {
	lxml.removeEventListener(LoadXML.LOADED, loadedHandler);
	lxml.removeEventListener(LoadXML.FAILED, failedHandler);
	// do something
}
*/

package com.matbury {
	
	import flash.display.Sprite;
	import flash.net.URLRequest;
	import flash.net.URLLoader;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	
	public class LoadXML extends Sprite {
		
		public var xml:XML;
		private var _loader:URLLoader;
		
		public static const LOADED:String = "loaded";
		public static const FAILED:String = "failed";
				
		public function LoadXML(url:String,test:Boolean = false):void {
			var _url:String = url;
			if(!test) {
				// prevent loading XML from user's cache - Throws error in Flash IDE
				_url += "?" + new Date().toString();
			}
			var request:URLRequest = new URLRequest(_url);
			_loader = new URLLoader(request);
			_loader.addEventListener(Event.COMPLETE, completeHandler);
			_loader.addEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler);
		}
		
		private function completeHandler(event:Event):void {
			// clean up
			_loader.removeEventListener(Event.COMPLETE, completeHandler);
			_loader.removeEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler);
			if(_loader.data) {
				xml = XML(_loader.data);
				dispatchEvent(new Event(LOADED));
			}else{
				dispatchEvent(new Event(FAILED));
			}
		}
		
		private function ioErrorHandler(event:IOErrorEvent):void {
			dispatchEvent(new Event(FAILED));
		}
	}
}