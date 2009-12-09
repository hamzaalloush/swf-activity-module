package com.matbury.sam.gui {
	
	import flash.display.Sprite;
	import flash.text.*;
	
	public class MessagePanel extends Sprite {
		
		private var _message:String;
		private var _bg:Sprite;
		private var _tf:TextField;
		
		public function MessagePanel(message:String = "message parameter required"):void {
			_message = message;
			this.buttonMode = true;
			init();
		}
		
		private function init():void {
			initTextField();
			initBg();
		}
		
		private function initTextField():void {
			var f:TextFormat = new TextFormat("Trebuchet MS",16,0,true,false,false,null,null,TextFormatAlign.CENTER);
			_tf = new TextField();
			_tf.defaultTextFormat = f;
			_tf.autoSize = TextFieldAutoSize.CENTER;
			_tf.wordWrap = true;
			_tf.width = 400;
			_tf.text = _message;
			_tf.x = -_tf.width * 0.5;
			_tf.y = -_tf.height * 0.5;
			_tf.mouseEnabled = false;
			addChild(_tf);
		}
		
		private function initBg():void {
			_bg = new Sprite();
			_bg.graphics.beginFill(0xffffff,1);
			var w:int = _tf.width * 1.1;
			var h:int = _tf.height * 1.2;
			_bg.graphics.drawRect(0,0,w,h);
			_bg.graphics.endFill();
			_bg.x = -_bg.width * 0.5;
			_bg.y = -_bg.height * 0.5;
			addChild(_bg);
			addChild(_tf);
		}
	}
}