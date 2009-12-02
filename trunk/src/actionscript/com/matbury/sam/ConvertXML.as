/*

*/
package {
	
	public class ConvertXML {
		
		private var _xml:XML;
		private var _data:Array;
		
		public function ConvertXML(xml:XML) {
			_xml = xml;
			init();
		}
		
		private function init():void {
			var len:uint = _xml.interaction.node.length();
			_data = new Array();
			for(var i:uint = 0; i < len; i++) {
				var obj:Object = new Object();
				// question
				obj.questionAudio = String(_xml.interaction.node[i].question.audio);
				obj.questionStretched = String(_xml.interaction.node[i].question.stretched);
				obj.questionText = String(_xml.interaction.node[i].question.text);
				obj.questionImage = String(_xml.interaction.node[i].question.image);
				// answer
				obj.answerAudio = String(_xml.interaction.node[i].answer.audio);
				obj.answerStretched = String(_xml.interaction.node[i].answer.stretched);
				obj.answerText = String(_xml.interaction.node[i].answer.text);
				obj.answerImage = String(_xml.interaction.node[i].answer.image);
				// gapfill
				obj.begText = String(_xml.interaction.node[i].gapfill.beg);
				obj.midText = String(_xml.interaction.node[i].gapfill.mid);
				obj.endText = String(_xml.interaction.node[i].gapfill.end);
				// correct audio
				var lngth:uint = _xml.interaction.node[i].correct.audio.option.length();
				var correctAudio:Array = new Array();
				for(var j:uint = 0; j < lngth; j++) {
					var audioOption:String = _xml.interaction.node[i].correct.audio.option[j];
					correctAudio.push(audioOption);
				}
				obj.correctAudio = correctAudio;
				// correct stretched
				lngth = _xml.interaction.node[i].correct.stretched.option.length();
				var correctStretched:Array = new Array();
				for(j = 0; j < lngth; j++) {
					var stretchedOption:String = _xml.interaction.node[i].correct.stretched.option[j];
					correctStretched.push(stretchedOption);
				}
				obj.correctStretched = correctStretched;
				// correct text
				lngth = _xml.interaction.node[i].correct.text.option.length();
				var correctText:Array = new Array();
				for(j = 0; j < lngth; j++) {
					var textOption:String = _xml.interaction.node[i].correct.text.option[j];
					correctText.push(textOption);
				}
				obj.correctText = correctText;
				// correct image
				lngth = _xml.interaction.node[i].correct.image.option.length();
				var correctImage:Array = new Array();
				for(j = 0; j < lngth; j++) {
					var imageOption:String = _xml.interaction.node[i].correct.image.option[j];
					correctImage.push(imageOption);
				}
				obj.correctImage = correctImage;
				// ----------------------------------------------------------------------------------------------------
				// wrong audio
				lngth = _xml.interaction.node[i].wrong.audio.option.length();
				var wrongAudio:Array = new Array();
				for(j = 0; j < lngth; j++) {
					var wrongAudioOption:String = _xml.interaction.node[i].wrong.audio.option[j];
					wrongAudio.push(wrongAudioOption);
				}
				obj.wrongAudio = wrongAudio;
				// wrong stretched
				lngth = _xml.interaction.node[i].wrong.stretched.option.length();
				var wrongStretched:Array = new Array();
				for(j = 0; j < lngth; j++) {
					var wrongStretchedOption:String = _xml.interaction.node[i].wrong.stretched.option[j];
					wrongStretched.push(wrongStretchedOption);
				}
				obj.wrongStretched = wrongStretched;
				// wrong text
				lngth = _xml.interaction.node[i].wrong.text.option.length();
				var wrongText:Array = new Array();
				for(j = 0; j < lngth; j++) {
					var wrongTextOption:String = _xml.interaction.node[i].wrong.text.option[j];
					wrongText.push(wrongTextOption);
				}
				obj.wrongText = wrongText;
				// wrong image
				lngth = _xml.interaction.node[i].wrong.image.option.length();
				var wrongImage:Array = new Array();
				for(j = 0; j < lngth; j++) {
					var wrongImageOption:String = _xml.interaction.node[i].wrong.image.option[j];
					wrongImage.push(wrongImageOption);
				}
				obj.wrongImage = wrongImage;
				// ----------------------------------------------------------------------------------------------------
				// keywords
				lngth = _xml.interaction.node[i].keywords.option.length();
				var keywords:Array = new Array();
				for(j = 0; j < lngth; j++) {
					var keyword:String = _xml.interaction.node[i].keywords.option[j];
					keywords.push(keyword);
				}
				obj.keywords = keywords;
				//
				obj.speaker = _xml.interaction.node[i].speaker;
				obj.videoSource = _xml.interaction.node[i].video.source;
				obj.videoText = _xml.interaction.node[i].video.text;
				obj.videoCaptions = _xml.interaction.node[i].video.captions;
				//
				_data.push(obj);
			}
		}
		
		public function get data():Array {
			return _data;
		}
	}
}