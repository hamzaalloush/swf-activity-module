package {
	
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageScaleMode;
	import flash.text.*;
	import flash.events.*;
	import fl.data.DataProvider;
	import fl.controls.DataGrid;
	import FlashVars;
	import com.matbury.LoadXML;
	
	public class XMLDemo extends Sprite {
		
		private var _display:TextField;
		private var _xml:XML;
		private var _grid:DataGrid;
		
		public function XMLDemo() {
			stage.align = StageAlign.TOP_LEFT;
			stage.scaleMode = StageScaleMode.NO_SCALE;
			FlashVars.vars = this.loaderInfo.parameters;
			initDisplay();
			stage.addEventListener(Event.RESIZE, resizeHandler);
			loadXML();
		}
		
		private function initDisplay():void {
			var format:TextFormat = new TextFormat();
			format.font = "Trebuchet MS";
			format.color = 0x000000;
			format.size = 14;
			//
			_display = new TextField();
			_display.wordWrap = true;
			_display.defaultTextFormat = format;
			_display.width = stage.stageWidth;
			_display.height = stage.stageHeight;
			addChild(_display);
			_display.text = "Loading...";
		}
		
		private function resizeHandler(event:Event):void {
			_display.width = stage.stageWidth;
			_display.height = stage.stageHeight;
		}
		
		private function loadXML():void {
			var lxml:LoadXML = new LoadXML(FlashVars.xmlUrl,true);
			lxml.addEventListener(LoadXML.LOADED, loadedHandler);
			lxml.addEventListener(LoadXML.FAILED, failedHandler);
		}
		
		private function loadedHandler(event:Event):void {
			event.target.removeEventListener(LoadXML.LOADED, loadedHandler);
			event.target.removeEventListener(LoadXML.FAILED, failedHandler);
			_xml = event.target.xml;
			//_display.text = _xml;
			var convert:ConvertXML = new ConvertXML(_xml);
			FlashVars.interactionData = convert.data;
			displayData();
		}
		
		private function failedHandler(event:Event):void {
			event.target.removeEventListener(LoadXML.LOADED, loadedHandler);
			event.target.removeEventListener(LoadXML.FAILED, failedHandler);
			trace(event);
		}
		
		// trace out the contents of the interaction data array
		private function displayData():void {
			for(var str:String in this.root.loaderInfo.parameters) {
				_display.appendText("\n		" + str + " = " + this.root.loaderInfo.parameters[str]);
			}
			var len:uint = FlashVars.interactionData.length;
			for(var i:uint = 0; i < len; i++) {
				for(var s:String in FlashVars.interactionData[i]) {
					_display.appendText("\n\n" + s + " = " + FlashVars.interactionData[i][s]);
				}
			}
			for(var f:String in FlashVars.info) {
				_display.appendText("\n\n" + f + " = " + FlashVars.info[f]);
			}
			initDataGrid();
		}
		
		private function initDataGrid():void {
			_grid = new DataGrid();
			_grid.width = stage.stageWidth;
			_grid.rowCount = 10;
			var columns:Array = new Array();
			for(var s:String in FlashVars.interactionData[0]) {
				columns.push(s);
			}
			_grid.columns = columns;
			_grid.addItem(FlashVars.interactionData[0]);
            //_grid.addEventListener(Event.CHANGE, gridItemSelected);
            _grid.minColumnWidth = 5; //_grid.width / columns.length;
            addChild(_grid);
		}
	}
}