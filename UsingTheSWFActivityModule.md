

This page gives step by step instructions of how to deploy a Flash learning interaction using the SWF Activity Module.

**Please note:** The module does not come with any Flash learning applications. A word search activity is available in the downloads section of this project site for testing purposes.


---


## Turn editing on ##

Login to the desired Moodle course page and turn on editing.

<img src='http://matbury.com/tutorials/moodle_turn_editing_on.png' alt='Turn editing on' width='312' height='47' />

In the desired section of the course page select:

  * Add and activity... > SWF

The module form will appear.


---


## Name and description ##

<img src='http://matbury.com/tutorials/swf_mod_form_general.gif' alt='SWF Activity Module form' width='819' height='448' />

Enter a name and brief description of the learning interaction. The name will appear on the Moodle course page as a link to the learning interaction.


---


## Flash learning application parameters ##

<img src='http://matbury.com/tutorials/swf_module_parameters.gif' alt='SWF Activity Module paramters' width='852' height='477' />

Enter the following parameters:

### SWF Parameters ###

  * **SWF File** - The location in the Moodle course files directory of the Flash learning application. For security reasons, you cannot link to a Flash application outside the Moodle course files directory.
  * **Width** - The width of the Flash Player window
  * **Height** - The height of the Flash Player window
  * **Full browser** - Flash app takes up 100% height and 100% width of available browser window (not to be confused with full-screen Flash)
  * **Version** - Minimum Flash Player version required to play the Flash learning application. If a user's Flash Player version is too low, express install will be initiated. Users must have administrator privileges on their computer to successfully complete a Flash Player upgrade.

**Please note**: The SWF File parameter can be either a file picker (pop-up window) or a selection list. See more about customising this feature in [AdministrationSettings](http://code.google.com/p/swf-activity-module/wiki/AdministrationSettings)

### XML Learning Interaction Data ###

  * **XML Interaction Data** - XML is the most commonly used method of loading external data and files into Flash applications

### FlashVars Learning Interaction Data ###

There are 3 name-value pairs that can be entered here. Some Flash templates require custom name-value pairs to be provided and these parameters allow for maximum compatibility with 3rd party dynamic Flash applications.

  * **Name** - The name of the FlashVars parameter, e.g. words
  * **Value** - The data related to the name, e.g. apple,banana,cucumber,dandelion


---


## Advanced parameters ##

Advanced parameters are exposed by clicking on the "Show Advanced" button. In most cases, it is not necessary to change these. The defaults are at the most secure settings.

<img src='http://matbury.com/tutorials/swf_mod_form_advanced.gif' alt='SWF Activity Module paramters' width='809' height='459' />

  * **Skin** - Some Flash learning applications support a dynamic skinning feature whereby you can change their visual appearance with an externally loaded SWF skin file.
  * **API Key** - Some web services such as Google and Yahoo! require a key for accessing their APIs.
  * **Align**
  * **Auto Play**
  * **Loop Playback**
  * **Menu**
  * **Quality**
  * **Scale Mode**
  * **Stage Align**
  * **Window Mode**
  * **Background Color**
  * **Use Device Font**
  * **Seamless Tabbing**
  * **Allow Full Screen**
  * **Allow Script Access**
  * **Allow Networking**
  * **SWF Configuration** file (XML) - Some Flash learning applications can set all the parameters here with a single configuration XML file. This is particularly useful if you want to manage SWF Activity Module instances on a site-wide basis.


---
