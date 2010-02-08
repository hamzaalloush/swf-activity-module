package {
	import flash.display.Sprite;
	import flash.display.StageScaleMode;
	import flash.display.StageAlign;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.text.*;
	import flash.utils.getTimer;
	import com.matbury.sam.data.FlashVars;
	import com.matbury.sam.data.Amf;
	
	public class GradePusher extends Sprite {
		
		private var _instructions:TextField;
		private var _input:TextField;
		private var _feedback:TextField;
		private var _results:TextField;
		private var _send:Sprite;
		private var _amf:Amf;
		
		public function GradePusher() {
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.align = StageAlign.TOP_LEFT;
			FlashVars.vars = this.root.loaderInfo.parameters;
			initDisplay();
			initAmf();
		}
		
		// Create the text fields, input boxes and Send Grade button
		private function initDisplay():void {
			var f:TextFormat = new TextFormat("Trebuchet MS",14,0,true);
			// Instructions
			_instructions = new TextField();
			_instructions.defaultTextFormat = f;
			_instructions.autoSize = TextFieldAutoSize.LEFT;
			_instructions.text = "Please input your desired grade (0 - 99) and any feedback you want to give your student:";
			addChild(_instructions);
			// Input text field for grade
			_input = new TextField();
			_input.type = TextFieldType.INPUT;
			_input.defaultTextFormat = f;
			_input.border = true;
			_input.restrict = "0-9";
			_input.maxChars = 2;
			_input.width = 100;
			_input.height = 22;
			_input.x = 2;
			_input.y = _instructions.y + _instructions.height + 4;
			_input.text = "";
			addChild(_input);
			// Input text field for feedback
			_feedback = new TextField();
			_feedback.type = TextFieldType.INPUT;
			_feedback.defaultTextFormat = f;
			_feedback.border = true;
			_feedback.width = stage.stageWidth - 4;
			_feedback.height = 22;
			_feedback.x = 2;
			_feedback.y = _input.y + _input.height + 4;
			_feedback.text = "";
			addChild(_feedback);
			// Send Grade button
			_send = new Sprite();
			_send.addEventListener(MouseEvent.MOUSE_DOWN, mouseDown);
			_send.x = 5;
			_send.y = _feedback.y + _feedback.height + 4;
			_send.buttonMode = true;
			_send.mouseChildren = false;
			var label:TextField = new TextField();
			label.selectable = false;
			label.background = true;
			label.backgroundColor = 0xDDDDDD;
			label.defaultTextFormat = f;
			label.autoSize = TextFieldAutoSize.LEFT;
			label.text = "Send Grade";
			_send.addChild(label);
			addChild(_send);
			// Results text field - displays returned inserted/updated grade
			_results = new TextField();
			_results.wordWrap = true;
			f.bold = false;
			_results.defaultTextFormat = f;
			_results.width = stage.stageWidth;
			_results.y = _send.y + _send.height + 4;
			_results.height = stage.stageHeight - _results.y;
			_results.text = "Results:";
			addChild(_results);
		}
		
		// Mouse event handler calls function to send grade data to Moodle
		private function mouseDown(event:MouseEvent):void {
			if(_input.text == "") {
				_results.appendText("\nPlease input a grade.");
			} else {
				_results.text = "Results:";
				_results.appendText("\nAttempting to connect to Moodle...");
				setGrade();
			}
		}
		
		// Create new Amf object (calls NetConnection.connect())
		// Requires FlashVars.gateway parameter for a successful connection so
		// will only work when client is deployed in Moodle
		private function initAmf():void {
			_amf = new Amf();
		}
		
		// Call lib/amfphp/services/Grades.php::amf_grade_update()
		// to insert or update grade
		private function setGrade():void {
			_amf.addEventListener(Amf.GOT_DATA, gotGrade);
			_amf.addEventListener(Amf.FAULT, fault);
			var obj:Object = new Object();
			obj.swfid = FlashVars.swfid;
			obj.course = FlashVars.course;
			obj.instance = FlashVars.instance;
			obj.feedback = _feedback.text;
			obj.feedbackformat = Math.round(getTimer() / 1000); //_clock.seconds;
			obj.rawgrade = int(_input.text);
			obj.serviceFunction = "Grades.amf_grade_update";
			_amf.getObject(obj);
		}
		
		// lib/amfphp/services/Grades.php::amf_grade_update() returns the inserted/updated grade
		// Display the returned grade
		private function gotGrade(event:Event):void {
			_amf.removeEventListener(Amf.GOT_DATA, gotGrade);
			_amf.removeEventListener(Amf.FAULT, fault);
			_results.appendText("\n\nReturned grade:");
			for(var s:String in _amf.obj) {
				_results.appendText("\n" + s + " = " + _amf.obj[s]);
			}
		}
		
		// Error
		private function fault(event:Event):void {
			_amf.removeEventListener(Amf.GOT_DATA, gotGrade);
			_amf.removeEventListener(Amf.FAULT, fault);
			throw Error("Failed to connect to /lib/amfphp/services/Grades.php");
		}
	}
}