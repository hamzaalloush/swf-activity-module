<?php  // $Id: locallib.php,v 1.1 2010/02/02 matbury Exp $
/**
* Library of functions and constants for module swf
* 
* @author Matt Bury - matbury@gmail.com - http://matbury.com/
* @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
* @package swf
*/

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

// ------------------------------------------------------------ view.php ---------------------------------------------------------- //

/**
* Construct fullbrowser version of view.php
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_fullbrowser($swf)
{
	$swf_fullbrowser = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
	<title>'.$swf->name.'</title>';
	$swf_fullbrowser .= swf_print_css($swf);
	$swf_fullbrowser .= swf_print_js($swf);
	$swf_fullbrowser .= '</head>
	<body>';
	$swf_fullbrowser .= swf_print_swf_div($swf);
	$swf_fullbrowser .= '
	</body>';
	return $swf_fullbrowser;
}

/**
* Construct standard Moodle <head> and navigation 
*
* @param $swf - mdl_swf DB record for current SWF module instance
* @param $id - Moodle activity module instance ID
* @param $cm - Moodle course module object
* @param $course - Moodle course object
* @param $strswf - Module name, e.g. 'SWF' 
* @return string
*/
function swf_print_nav($swf,$id,$cm,$course,$strswf)
{
	$navigation = build_navigation($swf->name, $id);
	print_header_simple(format_string($swf->name), '', $navigation, '', swf_print_js($swf), true, update_module_button($cm->id, $course->id, $strswf), navmenu($course, $cm));
}

/**
* Construct Flash embed code for view.php
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_body($swf)
{
	$swf_body = swf_print_swf_div($swf);
	// Add notes under SWF embed window
	if($swf->notes != '')
	{
		$swf_body .= '<div>'.$swf->notes.'</div>';
	}
	$swf_body .= '</body>';
	return $swf_body;
}

/**
* Construct CCS for fullbrowser <head> section of view.php
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_css($swf)
{
	$swf_css = '
		<style type="text/css" media="screen">
			html, body, #myFlashContent { height:100%; }
			body { margin:0; padding:0; overflow:hidden; }
			object { outline:none; }
        </style>
		';
	return $swf_css;
}

/**
* Get user's grade for current module instance
* Note: This function must be called before swf_print_js or nextinstance will remain undefined
*
* @param $swf (mdl_swf DB record for current SWF module instance)
*/
function swf_get_instance_grade($swf)
{
	global $CFG;
	global $USER;
	
	// Get gradepass from grade book
	require_once($CFG->libdir.'/gradelib.php');
	
	if($swf_grade = grade_get_grades($swf->courseobject->id, 'mod', 'swf', $swf->id, $USER->id))
	{
		$swf->gradepass = $swf_grade->items[0]->gradepass;
		$swf->gradeuser = $swf_grade->items[0]->grades[$USER->id]->grade; // User's current grade
	} else {
		$swf->gradepass = 60; // Make default 60
		$swf->gradeuser = 0; // Make default 0
	}
}

/**
* Construct Javascript SWFObject embed code for <head> section of view.php
* Note: '?'.time() is used to prevent browser caching for XML and SWF files.
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_js($swf)
{
	global $CFG;
	global $USER;
	
	// Check for fullbrowser setting
	if($swf->fullbrowser != 'true')
	{
		$swf_width = $swf->width;
		$swf_height = $swf->height;
	} else {
		$swf_width = '100%';
		$swf_height = '100%';
	}
	
	// If you want to use a directory other than moodledata to keep SWFs, XML and multimedia, edit the following line.
	$swf_moodledata = $CFG->wwwroot.'/file.php/'.$swf->course.'/'; // e.g. http://mymoodle.com/file.php/2/
	
	$swf_nocache = '?nocache='.time();
	
	$swf_js = '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<script src="js/swfobject.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/swfaddress.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			var flashvars = {};
			flashvars.apikey = "'.$swf->apikey.'";
			flashvars.configxml = "'.$swf->configxml.$swf_nocache.'";
			flashvars.course = "'.$swf->course.'";
			flashvars.coursepage = "'.$CFG->wwwroot.'/course/view.php?id='.$swf->course.'";
			flashvars.fullbrowser = "'.$swf->fullbrowser.'";
			flashvars.gateway = "'.$CFG->wwwroot.'/lib/amfphp/gateway.php";
			flashvars.gradebook = "'.$CFG->wwwroot.'/grade/report/user/index.php?id='.$swf->course.'";
			flashvars.grademax = "'.$swf->grademax.'";
			flashvars.grademin = "'.$swf->grademin.'";
			flashvars.gradepass = "'.$swf->gradepass.'";
			flashvars.gradeuser = "'.$swf->gradeuser.'";
			flashvars.instance = "'.$swf->instance.'";
			flashvars.moodledata = "'.$swf_moodledata.'";
			flashvars.nextinstance = "'.$swf->nextinstance.'";
			flashvars.skin = "'.$swf->skin.'";
			flashvars.starttime = "'.time().'";
			flashvars.swfid = "'.$swf->id.'";
			flashvars.userid = "'.$USER->id.'";
			'.swf_print_pairs($swf).'
			flashvars.wwwroot = "'.$CFG->wwwroot.'/";
			flashvars.xmlurl = "'.$swf_moodledata.$swf->xmlurl.$swf_nocache.'";
			var params = {};
			params.allowfullscreen = "'.$swf->allowfullscreen.'";
			params.allownetworking = "'.$swf->allownetworking.'";
			params.allowscriptaccess = "'.$swf->allowscriptaccess.'";
			params.bgcolor = "'.$swf->bgcolor.'";
			params.devicefont = "'.$swf->devicefont.'";
			params.loop = "'.$swf->loopswf.'";
			params.menu = "'.$swf->menu.'";
			params.play = "'.$swf->play.'";
			params.quality = "'.$swf->quality.'";
			params.salign = "'.$swf->salign.'";
			params.scale = "'.$swf->scale.'";
			params.seamlesstabbing = "'.$swf->seamlesstabbing.'";
			params.wmode = "'.$swf->wmode.'";
			var attributes = {};
			swfobject.embedSWF("'.$swf_moodledata.$swf->swfurl.$swf_nocache.'", "myAlternativeContent", "'.$swf_width.'", "'.$swf_height.'", "'.$swf->version.'", "js/expressInstall.swf", flashvars, params, attributes);
		</script>
		';
	return $swf_js;
}

/**
* Construct name value pairs for <head> FlashVars JS in view.php
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_pairs($swf)
{
	// Build FlashVars - Don't print name value pairs if name is empty
	// pair 1
	if($swf->name1 != '')
	{
		$swf_flashvar1 = 'flashvars.'.$swf->name1.' = "'.$swf->value1.'";';
	} else {
		$swf_flashvar1 = '';
	}
	// pair 2
	if($swf->name2 != '')
	{
		$swf_flashvar2 = 'flashvars.'.$swf->name2.' = "'.$swf->value2.'";';
	} else {
		$swf_flashvar2 = '';
	}
	// pair 3
	if($swf->name3 != '')
	{
		$swf_flashvar3 = 'flashvars.'.$swf->name3.' = "'.$swf->value3.'";';
	} else {
		$swf_flashvar3 = '';
	}
	
	$swf_pairs = $swf_flashvar1.'
			'.$swf_flashvar2.'
			'.$swf_flashvar3;
	
	return $swf_pairs;
}

/**
* Find the first instance that the current user hasn't yet passed.
* Note: This function must be called before swf_print_js or nextinstance will remain undefined
*
* @param $swf (mdl_swf DB record for current SWF module instance)
*/
function swf_get_next_instance($swf)
{
	global $CFG;
	global $USER;
	
	require_once($CFG->libdir.'/gradelib.php');
	
	$swf->nextinstance = 0; // If no next instance is found leave nextinstance as 0
	
	// Get all instances of SWFs for current course
	if ($swf_instances = get_all_instances_in_course('swf', $swf->courseobject))
	{
		// Create an array of SWF Activity Module instances that are in the sequence for this course
		$swf_sequence_grades = array();
		
		foreach($swf_instances as $swf_instance)
		{
			if($swf_instance->sequenced == 'true')
			{
				// grade_get_grades($courseid, $itemtype, $itemmodule, $iteminstance, $userid_or_ids=null)
				// get grades for instances
				if($swf_grade = grade_get_grades($swf->courseobject->id, 'mod', 'swf', $swf_instance->id, $USER->id))
				{
					$swf_grade->coursemodule = $swf_instance->coursemodule;
					array_push($swf_sequence_grades, $swf_grade);
				}
			}
		}
		
		// We'll need these later if we need to print out a sequenced modules menu page
		$swf->sequencedinstances = $swf_sequence_grades;
		
		// Find first instance with user's grade lower than gradepass
		$swf_instances_length = count($swf_sequence_grades);
		for($i = 0; $i < $swf_instances_length; $i++)
		{
			if($swf_sequence_grades[$i]->items[0]->grades[$USER->id]->grade < $swf_sequence_grades[$i]->items[0]->gradepass)
			{
				$swf->nextinstance =  $swf_sequence_grades[$i]->coursemodule; // Store this in case we need it later
				$swf->nextinstancename =  $swf_sequence_grades[$i]->items[0]->name; // Store this in case we need it later
				break;
			}
		}
		//
		return $swf;
	}
}

/**
* Print sequenced module instances in a table
* Call swf_get_next_instance() before calling this function
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_sequenced_instances($swf)
{
	global $CFG;
	global $USER;
	
	$swf_sequenced_instances = '<table border="0" align="center" cellspacing="0" cellpadding="5">
  <tr>
    <td colspan="7"><p align="center"><strong>'.get_string('sequencedheader', 'swf').'</strong></p></td>
  </tr>
  <tr align="center">
    <td>&nbsp;</td>
    <td><strong>'.get_string('name', 'swf').'</strong></td>
    <td ><strong>'.get_string('reportaveragegradehistory', 'swf').'</strong></td>
    <td><strong>'.get_string('gradepass', 'swf').'</strong></td>
    <td><strong>'.get_string('reportdate', 'swf').'</strong></td>
    <td><strong>'.get_string('reporttime', 'swf').'</strong></td>
    <td>&nbsp;</td>
  </tr>';
	
	$swf_length = count($swf->sequencedinstances);
	$swf_all_completed = true;
	for($i = 0; $i < $swf_length; $i++)
	{
		$swf_sequenced_instances .= '  <tr>
		<td valign="middle"><img src="icon.gif" width="16" height="16" alt="SWF" /></td>
		<td>'.$swf->sequencedinstances[$i]->items[0]->name.'</td>
		<td align="center">'.$swf->sequencedinstances[$i]->items[0]->grades[$USER->id]->grade * 1 .'%</td>
		<td align="center">'.floor($swf->sequencedinstances[$i]->items[0]->gradepass).'%</td>';
		if($swf->sequencedinstances[$i]->items[0]->grades[$USER->id]->grade > 0)
		{
			$swf_sequenced_instances .= '
			<td align="center">'.date('d/m/Y',$swf->sequencedinstances[$i]->items[0]->grades[$USER->id]->dategraded).'</td>
			<td align="center">'.date('H:i:s',$swf->sequencedinstances[$i]->items[0]->grades[$USER->id]->dategraded).'</td>';
		} else {
			$swf_sequenced_instances .= '
			<td>&nbsp;</td>
			<td>&nbsp;</td>'; // leave blank for not attempted
		}
		if($swf->sequencedinstances[$i]->items[0]->grades[$USER->id]->grade < $swf->sequencedinstances[$i]->items[0]->gradepass) // Completed?
		{
			$swf_sequenced_instances .= '
			<td>&nbsp;</td>'; // leave blank for uncompleted
			$swf_all_completed = false;
		} else {
			
			$swf_sequenced_instances .= '
			<td valign="middle"><img src="pix/tick.png" width="20" height="19" alt="Completed" /></td>'; // Put a tick for completed
		}
			
		$swf_sequenced_instances .= '
		</tr>';
	}
	$swf_sequenced_instances .= '</table>'; //End table
	
	// Have all the module instances in the sequence been completed?
	if($swf_all_completed && $swf_length > 0)
	{
		$swf_sequenced_instances .= '<p align="center"><strong>'.get_string('sequencedcompleted', 'swf').'</strong></p>';
	} else {
		// Button to navigate to the next uncompleted module instance in sequence
		$swf_sequenced_instances .= '			<div align="center">
			  <form id="form1" name="form1" method="post" action="view.php?id='.$swf->nextinstance.'">
				<input type="submit" name="nxt" id="nxt" value="'.get_string('sequencednext', 'swf').$swf->nextinstancename.'" />
			  </form>
			</div>';
	}
	
	return $swf_sequenced_instances;
}

/**
* Print <div> that gets overwritten by SWFObject JS embed code
* If embed fails, contents of <div> will be displayed
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_swf_div($swf)
{
	$swf_div = '<div id="myAlternativeContent">
			<div align="center">
				'.get_string('embederror1','swf').$swf->version.get_string('embederror2','swf').'
				<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" border=0/></a></p>
				<p><a href="http://matbury.com/" title="SWF Activity Module developed by Matt Bury">By Matt Bury | matbury.com</a></p>
			</div>
		</div>';
	
	return $swf_div;
}
