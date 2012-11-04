<?php //$Id: mod_form.php,v 1.0 2009/09/28 matbury Exp $

/*
*    Copyright (C) 2009  Matt Bury - matbury@gmail.com - http://matbury.com/
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* Creates instance of SWF activity module
* Adapted from mod_form.php template by Jamie Pratt
*
* By Matt Bury - http://matbury.com/ - matbury@gmail.com
* @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
*
* DB Table name (mdl_)swf
*
* REQUIRED PARAMETERS:
* @param swfurl
* @param width
* @param height
* @param version
*
* LEARNING INTERACTION DATA PARAMETERS:
* @param xmlurl
* @param name1
* @param value1
* @param name2
* @param value2
* @param name3
* @param value3
* @param grading
* @param feedback
* @param feedbacklink
*
* OPTIONAL PARAMETERS:
* @param apikey
* @param play
* @param loopswf
* @param menu
* @param quality
* @param scale
* @param salign
* @param wmode
* @param bgcolor
* @param devicefont
* @param seamlesstabbing
* @param allowfullscreen
* @param allowscriptaccess
* @param allownetworking
* @param align
* @param skin
* @param configxml
* 
*/

require_once ('moodleform_mod.php');

class mod_swf_mod_form extends moodleform_mod {

	function definition() {
		
		global $CFG;
		global $COURSE;
		$mform =& $this->_form;
		
		// If we're updating the module instance, we need the ID to get the grade_items record
		$swf_id = optional_param('update', 0, PARAM_INT);  // swf update ID
		
//-------------------------------------------------------------------------------
    /// Adding the "general" fieldset, where all the common settings are shown
        $mform->addElement('header', 'general', get_string('general', 'form'));
    /// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('swfname', 'swf'), array('size'=>'64'));
		$mform->setType('name', PARAM_TEXT);
		$mform->addRule('name', null, 'required', null, 'client');
    /// Adding the optional "intro" and "introformat" pair of fields
    	$mform->addElement('htmleditor', 'intro', get_string('swfintro', 'swf'));
		$mform->setType('intro', PARAM_RAW);
		$mform->setDefault('intro', 'intro');
		$mform->addRule('intro', get_string('required'), 'required', null, 'client');
        $mform->setHelpButton('intro', array('writing', 'richtext'), false, 'editorhelpbutton');
        $mform->addElement('format', 'introformat', get_string('format'));

//-------------------------------------------------------------------------------
	
	// Example from: http://docs.moodle.org/en/Development:lib/formslib.php_Form_Definition
	// REQUIRED header
	$mform->addElement('header', 'swfrequired', get_string('swfrequired', 'swf'));
	$mform->setHelpButton('swfrequired', array('swf_required', get_string('swfrequired', 'swf'), 'swf'));
	//swfurl - SWF file select/upload
	if(!empty($CFG->swf_file_select_type))
	{
			if($CFG->swf_file_select_type == 'list')
		{
			$mform->addElement('select', 'swfurl', get_string('swfurl', 'swf'), swf_get_swfs($COURSE->id)); //drop down list
		} else {
			$mform->addElement('choosecoursefile', 'swfurl', get_string('swfurl', 'swf'), array('courseid'=>$COURSE->id)); //default file picker
		}
	}
	//
	$mform->addRule('swfurl', get_string('required'), 'required', null, 'client');
	$mform->setType('swfurl', PARAM_NOTAGS);
	//width
	$mform->addElement('text', 'width', get_string('width', 'swf'), array('size'=>'9'));
	$mform->addRule('width', get_string('required'), 'required', null, 'client');
	$mform->setType('width', PARAM_NOTAGS);
	if(!$CFG->swf_default_width) {
		$CFG->swf_default_width = '900';
	}
	$mform->setDefault('width', $CFG->swf_default_width);
	//height
	$mform->addElement('text', 'height', get_string('height', 'swf'), array('size'=>'9'));
	$mform->addRule('height', get_string('required'), 'required', null, 'client');
	$mform->setType('height', PARAM_NOTAGS);
	if(!$CFG->swf_default_height) {
		$CFG->swf_default_height = '570';
	}
	$mform->setDefault('height', $CFG->swf_default_height);
	//fullbrowser
	/* */
	$mform->addElement('select', 'fullbrowser', get_string('fullbrowser', 'swf'), swf_list_truefalse());
	$mform->setType('fullbrowser', PARAM_NOTAGS);
	if(!$CFG->swf_default_fullbrowser) {
		$CFG->swf_default_fullbrowser = 'false';
	}
	$mform->setDefault('fullbrowser', $CFG->swf_default_fullbrowser);
	
	//version
	$mform->addElement('text', 'version', get_string('version', 'swf'), array('size'=>'9'));
	$mform->addRule('version', get_string('required'), 'required', null, 'client');
	$mform->setType('version', PARAM_NOTAGS);
	if(!$CFG->swf_default_version) {
		$CFG->swf_default_version = '9.0.115';
	}
	$mform->setDefault('version', $CFG->swf_default_version);
	
	//----------------------------------------------------------------------------------------
	// OPTIONAL PARAMETERS
	
	// XML header ----------------------------------------------------------------------------- 
	$mform->addElement('header', 'xml', get_string('xml', 'swf'));
	$mform->setHelpButton('xml', array('swf_xmlurl', get_string('xml', 'swf'), 'swf'));
	//xmlurl
	$mform->addElement('choosecoursefile', 'xmlurl', get_string('xmlurl', 'swf'), array('courseid'=>$COURSE->id)); // uncomment for file picker
	//$mform->addElement('select', 'xmlurl', get_string('xmlurl', 'swf'), swf_get_xmls($COURSE->id)); // uncomment for drop down list of files in moodledata/[courseid]/xml/
	$mform->setType('xmlurl', PARAM_NOTAGS);
	
	// FlashVars header -----------------------------------------------------------------------
	$mform->addElement('header', 'flashvars', get_string('flashvars', 'swf'));
	$mform->setHelpButton('flashvars', array('swf_flashvars', get_string('flashvars', 'swf'), 'swf'));
	// attributes for flashvars text areas
	$swf_flashvars_att = 'wrap="virtual" rows="3" cols="57"';
	$swf_flashvars_name = array('size'=>'75');
	// name1
	$mform->addElement('text', 'name1', get_string("name", "swf"), $swf_flashvars_name);
	$mform->setType('name1', PARAM_NOTAGS);
	// value1
	$mform->addElement('textarea', 'value1', get_string("value", "swf"), $swf_flashvars_att);
	$mform->setType('value1', PARAM_NOTAGS);
	// name2
	$mform->addElement('text', 'name2', get_string("name", "swf"), $swf_flashvars_name);
	$mform->setType('name2', PARAM_NOTAGS);
	// value2
	$mform->addElement('textarea', 'value2', get_string("value", "swf"), $swf_flashvars_att);
	$mform->setType('value2', PARAM_NOTAGS);
	// name3
	$mform->addElement('text', 'name3', get_string("name", "swf"), $swf_flashvars_name);
	$mform->setType('name3', PARAM_NOTAGS);
	// value3
	$mform->addElement('textarea', 'value3', get_string("value", "swf"), $swf_flashvars_att);
	$mform->setType('value3', PARAM_NOTAGS);
	
	// Extras header -----------------------------------------------------------------------
	$mform->addElement('header', 'extras', get_string('extras', 'swf'));
	$mform->setHelpButton('extras', array('swf_extras', get_string('extras', 'swf'), 'swf'));
	//
	$mform->addElement('htmleditor', 'notes', get_string('notes', 'swf'));
	$mform->setType('notes', PARAM_RAW);
	
	// ----------------------------------------------------- Grading header ----------------------------------------------------- //
	// grading
	$mform->addElement('header', 'grading', get_string('grading', 'swf'));
	$mform->setHelpButton('grading', array('swf_grading', get_string('grading', 'swf'), 'swf'));
	
	// gradetype
	$mform->addElement('select', 'gradetype', get_string('gradetype', 'swf'), swf_list_gradetype());
	$mform->setDefault('gradetype', '1');
	// grademax
	$mform->addElement('select', 'grademax', get_string('grademax', 'swf'), swf_list_gradevalues());
	$mform->setDefault('grademax', '100');
	// grademin
	$mform->addElement('select', 'grademin', get_string('grademin', 'swf'), swf_list_gradevalues());
	$mform->setDefault('grademin', '0');
	// gradepass
	if($swf_id) // If we're updating the module instance, then prevent user from editing passgrade
	{
		$mform->addElement('static', 'description', get_string('gradepass', 'swf'), swf_get_current_grade_item($swf_id));
	} else {
		$mform->addElement('static', 'description', get_string('gradepass', 'swf'), '0% '.get_string('gradeupdate', 'swf'));
		//$mform->addElement('select', 'gradepass', get_string('gradepass', 'swf'), swf_list_gradevalues());
		/*if(!$CFG->swf_default_gradepass)
		{
			$CFG->swf_default_gradepass = 60;
		}
		$mform->setDefault('gradepass', $CFG->swf_default_gradepass);*/
	}
	// sequence
	$mform->addElement('select', 'sequenced', get_string('sequenced', 'swf'), swf_list_truefalse());
	if(!$CFG->swf_default_sequenced) {
		$CFG->swf_default_sequenced = 'false';
	}
	$mform->setDefault('sequenced', $CFG->swf_default_sequenced);
	// allowreview
	$mform->addElement('select', 'allowreview', get_string('allowreview', 'swf'), swf_list_truefalse());
	if(!$CFG->swf_default_allowreview) {
		$CFG->swf_default_allowreview = 'true';
	}
	$mform->setDefault('allowreview', $CFG->swf_default_allowreview);
	// feedback
	$mform->addElement('textarea', 'feedback', get_string('feedback', 'swf'), $swf_flashvars_att);
	$mform->setType('feedback', PARAM_NOTAGS);
	// feedbacklink
	$mform->addElement('choosecoursefile', 'feedbacklink', get_string('feedbacklink', 'swf'), array('courseid'=>$COURSE->id));
	$mform->setType('feedbacklink', PARAM_NOTAGS);
	
	//-------------------------------------------- Advanced header --------------------------------------------
	// advanced
	$mform->addElement('header', 'advanced', get_string('advanced', 'swf'));
	$mform->setHelpButton('advanced',  array('swf_advanced', get_string('advanced', 'swf'), 'swf'));
	// skin
	$mform->addElement('select', 'skin', get_string('skin', 'swf'), swf_list_skins());
	$mform->setDefault('skin', '');
	$mform->setAdvanced('skin');
	//apikey
	$mform->addElement('text', 'apikey', get_string('apikey', 'swf'), array('size'=>'75'));
	$mform->setType('apikey', PARAM_NOTAGS);
	$mform->setAdvanced('apikey');
	// align
	$mform->addElement('select', 'align', get_string('align', 'swf'), swf_list_align());
	if(!$CFG->swf_default_align) {
		$CFG->swf_default_align = 'left';
	}
	$mform->setDefault('align', $CFG->swf_default_align);
	$mform->setAdvanced('align');
	//play
	$mform->addElement('select', 'play', get_string('play', 'swf'), swf_list_truefalse());
	if(!$CFG->swf_default_play) {
		$CFG->swf_default_play = 'true';
	}
	$mform->setDefault('play', $CFG->swf_default_play);
	$mform->setAdvanced('play');
	//loop
	$mform->addElement('select', 'loopswf', get_string('loop', 'swf'), swf_list_truefalse());
	if(!$CFG->swf_default_loop) {
		$CFG->swf_default_loop = 'true';
	}
	$mform->setDefault('loopswf', $CFG->swf_default_loop);
	$mform->setAdvanced('loopswf');
	//menu
	$mform->addElement('select', 'menu', get_string('menu', 'swf'), swf_list_truefalse());
	if(!$CFG->swf_default_menu) {
		$CFG->swf_default_menu = 'true';
	}
	$mform->setDefault('menu', $CFG->swf_default_menu);
	$mform->setAdvanced('menu');
	//quality
	$mform->addElement('select', 'quality', get_string('quality', 'swf'), swf_list_quality());
	if(!$CFG->swf_default_quality) {
		$CFG->swf_default_quality = 'best';
	}
	$mform->setDefault('quality', $CFG->swf_default_quality);
	$mform->setAdvanced('quality');
	//scale
	$mform->addElement('select', 'scale', get_string('scale', 'swf'), swf_list_scale());
	if(!$CFG->swf_default_scale) {
		$CFG->swf_default_scale = 'noscale';
	}
	$mform->setDefault('scale', $CFG->swf_default_scale);
	$mform->setAdvanced('scale');
	//salign
	$mform->addElement('select', 'salign', get_string('salign', 'swf'), swf_list_salign());
	if(!$CFG->swf_default_salign) {
		$CFG->swf_default_salign = 'tl';
	}
	$mform->setDefault('salign', $CFG->swf_default_salign);
	$mform->setAdvanced('salign');
	//wmode
	$mform->addElement('select', 'wmode', get_string('wmode', 'swf'), swf_list_wmode());
	if(!$CFG->swf_default_wmode) {
		$CFG->swf_default_wmode = 'direct';
	}
	$mform->setDefault('wmode', $CFG->swf_default_wmode);
	$mform->setAdvanced('wmode');
	//bgcolor
	$mform->addElement('text', 'bgcolor', get_string('bgcolor', 'swf'), array('size'=>'20'));
	$mform->setType('bgcolor', PARAM_NOTAGS);
	if(!$CFG->swf_default_bgcolor) {
		$CFG->swf_default_bgcolor = 'FFFFFF';
	}
	$mform->setDefault('bgcolor', $CFG->swf_default_bgcolor);
	$mform->setAdvanced('bgcolor');
	//devicefont
	$mform->addElement('select', 'devicefont', get_string('devicefont', 'swf'), swf_list_truefalse());
	if(!$CFG->swf_default_devicefont) {
		$CFG->swf_default_devicefont = 'true';
	}
	$mform->setDefault('devicefont', $CFG->swf_default_devicefont);
	$mform->setAdvanced('devicefont');
	//seamlesstabbing
	$mform->addElement('select', 'seamlesstabbing', get_string('seamlesstabbing', 'swf'), swf_list_truefalse());
	if(!$CFG->swf_default_seamlesstabbing) {
		$CFG->swf_default_seamlesstabbing = 'true';
	}
	$mform->setDefault('seamlesstabbing', $CFG->swf_default_seamlesstabbing);
	$mform->setAdvanced('seamlesstabbing');
	//allowfullscreen
	$mform->addElement('select', 'allowfullscreen', get_string('allowfullscreen', 'swf'), swf_list_truefalse());
	if(!$CFG->swf_default_allowfullscreen) {
		$CFG->swf_default_allowfullscreen = 'false';
	}
	$mform->setDefault('allowfullscreen', $CFG->swf_default_allowfullscreen);
	$mform->setAdvanced('allowfullscreen');
	//allowscriptaccess
	$mform->addElement('select', 'allowscriptaccess', get_string('allowscriptaccess', 'swf'), swf_list_allowscriptaccess());
	if(!$CFG->swf_default_allowscriptaccess) {
		$CFG->swf_default_allowscriptaccess = 'sameDomain';
	}
	$mform->setDefault('allowscriptaccess', $CFG->swf_default_allowscriptaccess);
	$mform->setAdvanced('allowscriptaccess');
	//allownetworking
	$mform->addElement('select', 'allownetworking', get_string('allownetworking', 'swf'), swf_list_allownetworking());
	if(!$CFG->swf_default_allownetworking) {
		$CFG->swf_default_allownetworking = 'all';
	}
	$mform->setDefault('allownetworking', $CFG->swf_default_allownetworking);
	$mform->setAdvanced('allownetworking');
	//configxml - Configuration XML file select/upload
	$mform->addElement('choosecoursefile', 'configxml', get_string('configxml', 'swf'), array('courseid'=>$COURSE->id));
	$mform->setType('configxml', PARAM_NOTAGS);
	$mform->setAdvanced('configxml');
	
//-------------------------------------------------------------------------------
        // add standard elements, common to all modules
		$this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();

	}
}

?>
