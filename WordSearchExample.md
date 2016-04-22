

The xml\_word\_search.swf Flash learning application is an XML driven word search generator.


---


## Installation ##

No Flash learning applications require installation in Moodle 1.9. All you need is:

  * The SWF Activity Module installed (single upload and install)
  * The AMFPHP Flash Remoting gateway and service library installed (single upload)


---


## How to create a word search in Moodle 1.9 ##

  1. Go to a Moodle course page
  1. Click on "Turn editing on"
  1. Select "Add an activity..." > "SWF"
  1. Complete the SWF Activity Module form
  1. Click on "Save and display" to preview the word search

### SWF Activity Module form parameters ###

  * **Name** - (e.g. "Common Objects Word Search") The name of your learning interaction as it will appear on the Moodle course page.
  * **Description** - (e.g. "Every day objects found in the home") A brief description of the learning interaction.
  * **SWF File** - (e.g. "xml\_word\_search.swf") The Flash learning application, in this case, xml\_word\_search.swf. If the Flash application is not in the Moodle course files directory, you'll have to upload it.
  * **Width** - (e.g. "900" or "100%") Width of the Flash Player window in pixels, also accepts % values.
  * **Height** - (e.g. "570") Height of the Flash Player window in pixels.
  * **Version** - (e.g. "9.0.115.0") Minimum required Flash Player version to run Flash learning application. Also accepts main Flash Player numbers, e.g. "9".
  * **XML File URL** - (e.g. "word\_search\_common\_objects.xml") The learning interaction (lesson) data XML file. If the XML file is not present in the Moodle course files directory, you'll have to upload it.


---


## Optional FlashVars Learning Interaction Data parameters (see mod form) ##

Type the parameter name(s), i.e. "size" or "words" in the "Name" text box and the desired value(s) in the "Value" text box on the SWF Activity Module mod form.

  * **size** (e.g. "20", default = 20) - Number of characters in word search grid across and down
  * **words** (e.g. "apple,banana,carrot,orange,etc.", default = undefined) - List of words to use for the word search activity, separated by commas. Spaces and punctuation count as characters. Special characters must be URL encoded. Words passed in as FlashVars override any XML file URL passed in.

**For example:**

Name: words

Value: apple,banana,carrot,damson,eggplant,etc...


---


## Example Word Search learning interaction data file ##

Note that you can change the language of the xml\_word\_search.swf user interface with the learning interaction data XML file in the tabs.

The words or phrases between the tabs appear in the word search.

```
<?xml version="1.0" encoding="utf-8"?>
<smil xmlns="http://www.w3.org/2005/SMIL21/Language" version="2.1">
	
	<head>
		<!-- Metadata about the SMIL presentation -->
		<meta id="meta-smil1.0-a" name="Creator" content="Matt Bury" />
		<meta id="meta-smil1.0-b" name="Creator URL" content="http://matbury.com/" />
		<meta id="meta-smil1.0-c" name="Creator email" content="matbury@gmail.com" />
		<meta id="meta-smil1.0-d" name="Publisher" content="matbury.com" />
		<meta id="meta-smil1.0-e" name="Publisher URL" content="http://matbury.com/" />
		<meta id="meta-smil1.0-f" name="Date" content="2011-10-13" />
		<meta id="meta-smil1.0-g" name="Rights" content="Copyright © 2011 Matt Bury" />
		<meta id="meta-smil1.0-h" name="License" content="Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)" />
		<meta id="meta-smil1.0-i" name="License URL" content="http://creativecommons.org/licenses/by-sa/3.0/" />
		<!-- Metadata about the SMIL presentation content -->
		<meta id="meta-smil1.0-j" name="Title" content="Common objects" />
		<meta id="meta-smil1.0-k" name="Intro" content="Every day objects that people carry and use." />
		<meta id="meta-smil1.0-l" name="Level" content="CEFRL A1" />
		<meta id="meta-smil1.0-m" name="Language" content="en" />
		<layout>
			<!-- Published for MILAs. No layout data necessary. -->
		</layout>
	</head>
	
	<body>
	
		<seq>
			<par id="keyword">
				<text>an address book</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a book</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>cigarettes</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>coins</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a comb</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a credit card</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a diary</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a dictionary</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a file</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>glasses</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>an ID card</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>keys</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a lighter</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a lipstick</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a magazine</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>matches</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a mobile</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a newspaper</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a pen</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a pencil</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a photo</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>photos</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a purse</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>a stamp</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>stamps</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>sunglasses</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>tissues</text>
			</par>
		</seq>
		
		<seq>
			<par id="keyword">
				<text>an umbrella</text>
			</par>
		</seq>
		
		<seq>
			</par>
			<par id="keyword">
				<text>a wallet</text>
			</par>
		</seq>
		
		<seq>
			</par>
			<par id="keyword">
				<text>a watch</text>
			</par>
		</seq>
		
	</body>
</smil>
```

or

```
<?xml version="1.0" encoding="utf-8"?>
<smil xmlns="http://www.w3.org/2005/SMIL21/Language" version="2.1">
	
	<head>
		<!-- Metadata about the SMIL presentation -->
		<meta id="meta-smil1.0-a" name="Creator" content="Matt Bury" />
		<meta id="meta-smil1.0-b" name="Creator URL" content="http://matbury.com/" />
		<meta id="meta-smil1.0-c" name="Creator email" content="matbury@gmail.com" />
		<meta id="meta-smil1.0-d" name="Publisher" content="matbury.com" />
		<meta id="meta-smil1.0-e" name="Publisher URL" content="http://matbury.com/" />
		<meta id="meta-smil1.0-f" name="Date" content="2011-10-13" />
		<meta id="meta-smil1.0-g" name="Rights" content="Copyright © 2011 Matt Bury" />
		<meta id="meta-smil1.0-h" name="License" content="Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)" />
		<meta id="meta-smil1.0-i" name="License URL" content="http://creativecommons.org/licenses/by-sa/3.0/" />
		<!-- Metadata about the SMIL presentation content -->
		<meta id="meta-smil1.0-j" name="Title" content="Common objects" />
		<meta id="meta-smil1.0-k" name="Intro" content="Every day objects that people carry and use." />
		<meta id="meta-smil1.0-l" name="Level" content="CEFRL A1" />
		<meta id="meta-smil1.0-m" name="Language" content="en" />
		<layout>
			<!-- Published for MILAs. No layout data necessary. -->
		</layout>
	</head>
	
	<body>
		
		<seq>
			<par id="keyword">
				<text>an address book</text>
				<text>a book</text>
				<text>cigarettes</text>
				<text>coins</text>
				<text>a comb</text>
				<text>a credit card</text>
				<text>a diary</text>
				<text>a dictionary</text>
				<text>a file</text>
				<text>glasses</text>
				<text>an ID card</text>
				<text>keys</text>
				<text>a lighter</text>
				<text>a lipstick</text>
				<text>a magazine</text>
				<text>matches</text>
				<text>a mobile</text>
				<text>a newspaper</text>
				<text>a pen</text>
				<text>a pencil</text>
				<text>a photo</text>
				<text>photos</text>
				<text>a watch</text>
				<text>a wallet</text>
				<text>an umbrella</text>
				<text>tissues</text>
				<text>sunglasses</text>
				<text>stamps</text>
				<text>a stamp</text>
				<text>a purse</text>
			</par>
		</seq>
		
	</body>
</smil>
```