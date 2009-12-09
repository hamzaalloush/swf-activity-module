package com.matbury.sam.gui {
	
	import flash.display.Sprite;
	import flash.text.*;
	
	public class NumberIcon extends Sprite {
		
		private var _num:uint;
		private var _bgColor:int;
		private var _txtColor:int;
		private var _font:String;
		private var _bg:Sprite;
		private var _tf:TextField;
		
		public function NumberIcon(num:uint, bg:int = 0xbbbbbb,txt:int = 0xffffff,font:String = "Trebuchet MS") {
			_num = num;
			_bgColor = bg;
			_txtColor = txt;
			_font = font;
			init();
		}
		
		private function init():void {
			initTextField();
			initBg();
		}
		
		private function initTextField():void {
			var f:TextFormat = new TextFormat(_font,12,_txtColor,true,false,false,null,null,TextFormatAlign.CENTER);
			_tf = new TextField();
			_tf.defaultTextFormat = f;
			_tf.width = 18;
			_tf.height = 18;
			_tf.y = -2; // align it better with bg
			addChild(_tf);
			_tf.text = String(_num + 1); // convert to human readable number
			_tf.mouseEnabled = false;
		}
		
		private function initBg():void {
			_bg = new Sprite();
			_bg.graphics.beginFill(_bgColor,1);
			_bg.graphics.drawRoundRect(0,0,18,18,10,10);
			_bg.graphics.endFill();
			addChild(_bg);
			addChild(_tf);
		}
		
		public function get index():uint {
			return _num;
		}
	}
}