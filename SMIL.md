# SMIL #

SMIL, Sequenced Multimedia Integration Language, is a W3C recommended XML markup language for describing multimedia presentations. It defines markup for timing, layout, animations, visual transitions, and media embedding, among other things. SMIL allows the presentation of media items such as text, images, video, and audio, as well as links to other SMIL presentations, and files from multiple web servers. SMIL markup is written in XML, and has similarities to HTML. For more information see [Wikipedia.org](http://en.wikipedia.org/wiki/Synchronized_Multimedia_Integration_Language).


---


## SMIL and Moodle ##

Moodle 1.9's file repository system recognises SMIL files as text and conveniently allows teachers and admins to edit them in situ.


---


## Blog article ##

I wrote an article on my blog about SMIL in relation to elearning:

  * [What’s SMIL and why should we use it?](http://blog.matbury.com/2011/10/23/whats-smil-and-why-should-we-use-it/)


---


## Recommendations ##

I recommend using SMIL 2.1 as a data tree structure for XML driven Flash applications. Implementations may use as much or as little of the data provided in SMIL files to generate a particular learning interaction.


---


## Examples ##

Your browser may or may not support SMIL file types. If not, your browser will attempt to download the files. You can view them with any text or XML editor. [Notepad++](http://notepad-plus-plus.org/) is a good open source text and XML editor.

  * [Slide show presentation](http://matbury.com/assets/slide_show.smil) (Triggers download in some browsers)

  * [Learning activity data](http://matbury.com/assets/elem_common_objects.smil) (Triggers download in some browsers)


---


## W3C.org SMIL Documentation ##

  * [Synchronized Multimedia Integration Language (SMIL 2.1)](http://www.w3.org/2005/SMIL21/SMIL21.dtd) documentation on W3C.org.

  * [SMIL 2.1 namespace reference page](http://www.w3.org/2005/SMIL21/Language) (2006/11/24).


---


## Example SMIL for MILAs ##

Matt Bury's Multimedia Interactive Learning Applications (MILAs) use SMIL XML files to load external multimedia and dynamically generate activities and games. Here's an example:

```
<?xml version="1.0" encoding="utf-8"?>
<smil xmlns="http://www.w3.org/2005/SMIL21/Language" version="2.1">
	
	<head>
		<!-- Metadata about the SMIL presentation -->
		<meta id="meta-smil1.0-a" name="Creator" content="Matt Bury" />
		<meta id="meta-smil1.0-b" name="Creator URL" content="http://matbury.com/" />
		<meta id="meta-smil1.0-c" name="Creator email" content="matt@...y.com" />
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
		<meta id="meta-smil1.0-n" name="Custom path" content="mila_cc" />
		<layout>
			<!-- Published for MILAs. No layout data necessary. -->
		</layout>
	</head>
	
	<body>
	
		<seq>
			<par id="question">
				<audio id="normal" src="commonobjects/mp3/what_is_it.mp3" />
				<audio id="stretched" src="commonobjects/mp3/what_is_it_str.mp3" />
				<text>What is it?</text>
				<img title="" src="commonobjects/pix/addressbook1.jpg" />
			</par>
			<par id="answer">
				<audio id="normal" src="commonobjects/mp3/addressbook_its_an.mp3" />
				<audio id="stretched" src="commonobjects/mp3/addressbook_its_an_str.mp3" />
				<text>It's an address book.</text>
				<img title="address book" src="commonobjects/pix/addressbook1.jpg" />
			</par>
			<par id="gapfill">
				<text id="beg"></text>
				<text id="mid">It's an</text>
				<text id="end">address book.</text>
			</par>
			<par id="keyword">
				<text>an address book</text>
			</par>
		</seq>
		
		<seq>
			<par id="question">
				<audio id="normal" src="commonobjects/mp3/what_is_it.mp3" />
				<audio id="stretched" src="commonobjects/mp3/what_is_it_str.mp3" />
				<text>What is it?</text>
				<img title="" src="commonobjects/pix/book1.jpg" />
			</par>
			<par id="answer">
				<audio id="normal" src="commonobjects/mp3/book_its_a.mp3" />
				<audio id="stretched" src="commonobjects/mp3/book_its_a_str.mp3" />
				<text>It's a book.</text>
				<img title="book" src="commonobjects/pix/book1.jpg" />
			</par>
			<par id="gapfill">
				<text id="beg"></text>
				<text id="mid">It's a</text>
				<text id="end">book.</text>
			</par>
			<par id="keyword">
				<text>a book</text>
			</par>
		</seq>
		
		<seq>
			<par id="question">
				<audio id="normal" src="commonobjects/mp3/what_are_they.mp3" />
				<audio id="stretched" src="commonobjects/mp3/what_are_they_str.mp3" />
				<text>What are they?</text>
				<img title="" src="commonobjects/pix/cigarettes3.jpg" />
			</par>
			<par id="answer">
				<audio id="normal" src="commonobjects/mp3/cigarettes_theyre.mp3" />
				<audio id="stretched" src="commonobjects/mp3/cigarettes_theyre_str.mp3" />
				<text>They're cigarettes.</text>
				<img title="cigarettes" src="commonobjects/pix/cigarettes3.jpg" />
			</par>
			<par id="gapfill">
				<text id="beg"></text>
				<text id="mid">They're</text>
				<text id="end">cigarettes.</text>
			</par>
			<par id="keyword">
				<text>cigarettes</text>
			</par>
		</seq>
		
	</body>
</smil>
```

For more info on MILAs see: http://blog.matbury.com/call-software/

For live demos of MILAs deployed in Moodle 1.9 see: http://moodle.matbury.com/course/view.php?id=17