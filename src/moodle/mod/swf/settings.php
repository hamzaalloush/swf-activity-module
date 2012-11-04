<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package mod-swf
 * @copyright  2012 Matt Bury (http://matbury.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
	
	require_once($CFG->dirroot.'/mod/swf/lib.php');
	
	/*
	 ----------------------------------- Set default parameters for new instances of SWF Activity Module ----------------------------------- 
	*/
	
	// set file picker or drop down list to select Flash apps to be deployed
	$swf_file_select_type_options = array('picker'=>'moodledata file picker', 'list'=>'moodledata swf file list');
	$settings->add(new admin_setting_configselect('swf_file_select_type', get_string('swfurl', 'swf'), get_string('swfurlinfo', 'swf'), 'picker', $swf_file_select_type_options));
	// moodledata file path WARNING! Experimental. DO NOT ACTIVATE ON A LIVE SITE!!!
	//$settings->add(new admin_setting_configtext('swf_moodledata', get_string('moodledata', 'swf'), get_string('moodledatainfo', 'swf'), $CFG->wwwroot.'/file.php', PARAM_TEXT));
	//width
	$settings->add(new admin_setting_configtext('swf_default_width', get_string('width', 'swf'), '', '100%', PARAM_TEXT));
	//height
	$settings->add(new admin_setting_configtext('swf_default_height', get_string('height', 'swf'), '', '570', PARAM_TEXT));																		
	// fullbrowser
	$settings->add(new admin_setting_configselect('swf_default_fullbrowser', get_string('fullbrowser', 'swf'), get_string('fullbrowserinfo', 'swf'), 'false', swf_list_truefalse()));																		
	// version
	$settings->add(new admin_setting_configtext('swf_default_version', get_string('version', 'swf'), '', '9.0.115', PARAM_TEXT));
	// gradepass
	//$settings->add(new admin_setting_configtext('swf_default_gradepass', get_string('gradepass', 'swf'), '', 60, PARAM_INT));
	// sequenced
	$settings->add(new admin_setting_configselect('swf_default_sequenced', get_string('sequenced', 'swf'), get_string('sequencedinfo', 'swf'), 'false', swf_list_truefalse()));
	// allowreview
	$settings->add(new admin_setting_configselect('swf_default_allowreview', get_string('allowreview', 'swf'), get_string('allowreviewinfo', 'swf'), 'false', swf_list_truefalse()));
	// align
	$settings->add(new admin_setting_configselect('swf_default_align', get_string('align', 'swf'), '', 'middle', swf_list_align()));
	// play
	$settings->add(new admin_setting_configselect('swf_default_play', get_string('play', 'swf'), '', 'true', swf_list_truefalse()));																		
	// loop
	$settings->add(new admin_setting_configselect('swf_default_loop', get_string('loop', 'swf'), '', 'true', swf_list_truefalse()));																		
	// menu
	$settings->add(new admin_setting_configselect('swf_default_menu', get_string('menu', 'swf'), '', 'true', swf_list_truefalse()));																		
	// quality
	$settings->add(new admin_setting_configselect('swf_default_quality', get_string('quality', 'swf'), '', 'best', swf_list_quality()));																		
	// scale
	$settings->add(new admin_setting_configselect('swf_default_scale', get_string('scale', 'swf'), '', 'noscale', swf_list_scale()));																		
	// salign
	$settings->add(new admin_setting_configselect('swf_default_salign', get_string('salign', 'swf'), '', 'tl', swf_list_salign()));																		
	// wmode
	$settings->add(new admin_setting_configselect('swf_default_wmode', get_string('wmode', 'swf'), '', 'direct', swf_list_wmode()));																		
	// bgcolor
	$settings->add(new admin_setting_configtext('swf_default_bgcolor', get_string('bgcolor', 'swf'), '', 'FFFFFF', PARAM_TEXT));																		
	// devicefont
	$settings->add(new admin_setting_configselect('swf_default_devicefont', get_string('devicefont', 'swf'), '', 'true', swf_list_truefalse()));																		
	// seamlesstabbing
	$settings->add(new admin_setting_configselect('swf_default_seamlesstabbing', get_string('seamlesstabbing', 'swf'), '', 'true', swf_list_truefalse()));
	// allowfullscreen
	$settings->add(new admin_setting_configselect('swf_default_allowfullscreen', get_string('allowfullscreen', 'swf'), '', 'false', swf_list_truefalse()));																		
	// allowscriptaccess
	$settings->add(new admin_setting_configselect('swf_default_allowscriptaccess', get_string('allowscriptaccess', 'swf'), '', 'sameDomain', swf_list_allowscriptaccess()));																		
	// allownetworking
	$settings->add(new admin_setting_configselect('swf_default_allownetworking', get_string('allownetworking', 'swf'), '', 'all', swf_list_allownetworking()));	
//