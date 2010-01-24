<?php  // $Id: lib.php,v 1.0 2009/09/28 matbury Exp $
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

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will create a new instance and return the id number 
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted swf record
 */
function swf_add_instance($swf) {
    
	$swf->timecreated = time();
	// Try to store it in the database.
	if (!$swf->id = insert_record('swf', $swf)) {
		return false;
	}
	//$swf->id = insert_record('swf', $swf);
	// Add corresponding grade item
	swf_insert_grade_item($swf);
    return $swf->id;
}

/**
 * Insert module instance in swf_grade_items table for grade book
 * TODO - merge with update_grade_item
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function swf_insert_grade_item($swf) {
	
	// Define parameters
	$swf_grade_item->course = $swf->course;
	$swf_grade_item->name = $swf->name;
	$swf_grade_item->intro = $swf->intro;
	$swf_grade_item->swfid = $swf->id;
	$swf_grade_item->grademax = $swf->grademax;
	$swf_grade_item->gradepass = $swf->gradepass;
	$swf_grade_item->feedback = $swf->feedback;
	$swf_grade_item->feedbacklink = $swf->feedbacklink;
	$swf_grade_item->timecreated = $swf->timecreated;
	// Insert new record
	if (!insert_record('swf_grade_items',$swf_grade_item)) {
		return false;
	}
	
	return true;
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function swf_update_instance($swf) {

    $swf->timemodified = time();
	// Update the database.
	$swf->id = $swf->instance;
	if (!update_record("swf", $swf)) {
		return false;  // some error occurred
	}
	// Ammend changes to corresponding swf_grade_items record
	swf_update_grade_item($swf);
	
    return true;
}

/**
 * Insert module instance in swf_grade_items table for grade book
 * TODO - merge with insert_grade_item
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function swf_update_grade_item($swf) {
	
	// get original record
	$swf_grade_item = get_record('swf_grade_items','swfid',$swf->id);
	
	// change parameters record
	$swf_grade_item->course = $swf->course;
	$swf_grade_item->name = $swf->name;
	$swf_grade_item->swfid = $swf->id;
	$swf_grade_item->grademax = $swf->grademax;
	$swf_grade_item->gradepass = $swf->gradepass;
	$swf_grade_item->feedback = $swf->feedback;
	$swf_grade_item->feedbacklink = $swf->feedbacklink;
	$swf_grade_item->timemodified = $swf->timemodified;
	
	// update record
	if (!update_record('swf_grade_items',$swf_grade_item)) {
		return false;
	}
	
	return true;
}

/**
 * Given an ID of an instance of this module, 
 * this function will permanently delete the instance 
 * and any data that depends on it. 
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function swf_delete_instance($id) {

    if (! $swf = get_record('swf', 'id', "$id")) {
        return false;
    }
    $result = true;

    if (! delete_records('swf', 'id', "$swf->id")) {
        $result = false;
    }
	
	// Delete swf_grade_items entry
	if (! delete_records('swf_grade_items', 'swfid', "$swf->id")) {
        $result = false;
    }
	return $result;
}

/**
 * Return a small object with summary information about what a 
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
function swf_user_outline($course, $user, $mod, $swf) {
	
	/*global $CFG;
	require_once("$CFG->libdir/gradelib.php");
	$grades = grade_get_grades($course->id, 'mod', 'swf', $swf->id, $user->id);
	if (empty($grades->items[0]->grades)) {
		return null;
	} else {
		$grade = reset($grades->items[0]->grades);
	}
	
	$result = new stdClass;
	$result->info = get_string('grade') . ': ' . $grade->str_long_grade;
	$result->time = $grade->dategraded;*/
	return $result;
	
}

/**
 * Print a detailed representation of what a user has done with 
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function swf_user_complete($course, $user, $mod, $swf) {
    /*global $CFG;
	require_once($CFG->libdir.'/gradelib.php');
	$grades = grade_get_grades($course->id,'mod','swf',$swf->id,$user->id);
	if (!empty($grades->items[0]->grades)) {
		$grade = reset($grades->items[0]->grades);
		echo '<p>'.get_string('grade').': '.$grade->str_long_grade.'</p>';
		if ($grade->str_feedback) {
			echo '<p>'.get_string('feedback').': '.$grade->str_feedback.'<p/>';
		}
	}*/
    return true;
}

/**
 * Given a course and a time, this module should find recent activity 
 * that has occurred in swf activities and print it out. 
 * Return true if there was output, or false is there was none. 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function swf_print_recent_activity($course, $isteacher, $timestart) {
    global $CFG;

    return false;  //  True if anything was printed, otherwise false 
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such 
 * as sending out mail, toggling flags etc ... 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function swf_cron () {
    global $CFG;

    return true;
}

/**
 * Must return an array of grades for a given instance of this module, 
 * indexed by user.  It also returns a maximum allowed grade.
 * 
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $swfid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 **/
function swf_grades($swfid) {
   return NULL;
}

/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of swf. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $swfid ID of an instance of this module
 * @return mixed boolean/array of students
 **/
function swf_get_participants($swfid) {
    return false;
}

/**
 * This function returns if a scale is being used by one swf
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $swfid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
function swf_scale_used ($swfid,$scaleid) {
    $return = false;

    //$rec = get_record("swf","id","$swfid","scale","-$scaleid");
    //
    //if (!empty($rec)  && !empty($scaleid)) {
    //    $return = true;
    //}
   
    return $return;
}

/**
 * Checks if scale is being used by any instance of swf.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any swf
 */
function swf_scale_used_anywhere($scaleid) {
    if ($scaleid and record_exists('swf', 'grade', -$scaleid)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Execute post-install custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function swf_install() {
     return true;
}

/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function swf_uninstall() {
    return true;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////// Specific generic functions for SWF Activity Module //////////////////////////////////////


// ------------------------------------------------------------ mod_form.php ---------------------------------------------------------- //

// The following function for mod_form.php all return arrays for the drop down lists in the SWF Activity Module instance form

/**
* Retrieve list of available interactions for current course
* Only for database driven learning interaction data
*
* @param $swf_courseid int
* @return array
*/
function swf_get_interactions($swf_courseid) {
	$swf_interactions = get_records('swf_interactions', 'course', $swf_courseid);
	$swf_select_interaction = array('' => 'none');
	if($swf_interactions) {
		foreach($swf_interactions as $swf_interaction)
		{
			$swf_select_interaction[$swf_interaction->id] = $swf_value->name;
		}
		unset($swf_interaction);
	}
	return $swf_select_interaction;
}

/*
* Create align parameter array list
* @return array
*/
function swf_list_align() {
	$swf_align_array = array('center' => 'center',
							'left' => 'left',
							'right' => 'right');
	return $swf_align_array;
}

/*
* Create allow networking parameter array list
* @return array
*/
function swf_list_allownetworking() {
	$swf_list_allownetworking = array('all' => 'all',
									'internal' => 'internal',
									'none' => 'none');
	return $swf_list_allownetworking;
}

/*
* Create allow script access parameter array list
* @return array
*/
function swf_list_allowscriptaccess() {
	$swf_list_allowscriptaccess = array('always' => 'always',
										'sameDomain' => 'sameDomain',
										'never' => 'never');
	return $swf_list_allowscriptaccess;
}

/*
* Create grading parameter array list
* Create an associative array from 0 - 100
*
* @return array
*/
function swf_list_gradevalues() {
	$swf_list_gradevalues = array();
	for($i = 0; $i < 101; $i++) {
		$swf_list_gradevalues["$i"] = "$i";
	}
	return $swf_list_gradevalues;
}

/*
* Create playback quality parameter array list
* @return array
*/
function swf_list_quality() {
	$swf_list_quality = array('best' => 'best',
							'high' => 'high',
							'medium' => 'medium',
							'autohigh' => 'autohigh',
							'autolow' => 'autolow',
							'low' => 'low');
	return $swf_list_quality;
}

/*
* Create stage align parameter array list
* @return array
*/
function swf_list_salign() {
	$swf_list_salign = array('tl' => 'top left',
							'tr' => 'top right',
							'bl' => 'bottom left',
							'br' => 'bottom right',
							'l' => 'left',
							't' => 'top',
							'r' => 'right',
							'b' => 'bottom');
	return $swf_list_salign;
}

/*
* Create stage scale mode parameter array list
* @return array
*/
function swf_list_scale() {
	$swf_list_scale = array('showall' => 'showall',
							'noborder' => 'noborder',
							'exactfit' => 'exactfit',
							'noscale' => 'noscale');
	return $swf_list_scale;
}

/*
* Create skins parameter array list
* TODO - replace this function with one that searches a specified directory for skin SWFs
* @return array
*/
function swf_list_skins() {
	$swf_list_skins = array('default' => '',
							'skins/gradient_square_blue.swf' => 'Square blue gradient',
							'skins/shiny_round_red.swf' => 'Shiny round red');
	return $swf_list_skins;
}

/*
* Create true/false parameter array list
* @return array
*/
function swf_list_truefalse() {
	$swf_list_truefalse = array('true' => 'true',
								'false' => 'false');
	return $swf_list_truefalse;
}

/*
* Create true/false parameter array list
* @return array
*/
function swf_list_widthpercent() {
	$swf_list_widthpercent = array('true' => 'true',
								'false' => 'false');
	return $swf_list_widthpercent;
}

/*
* Create window mode parameter array list
* @return array
*/
function swf_list_wmode() {
	$swf_list_wmode = array('window' => 'window',
							'opaque' => 'opaque',
							'transparent' => 'transparent',
							'direct' => 'direct',
							'gpu' => 'gpu');
	return $swf_list_wmode;
}

// ------------------------------------------------------------ view.php ---------------------------------------------------------- //

/**
* Construct Javascript SWFObject embed code for <head> section of view.php
* Note: '?'.time() is used to prevent browser caching for XML and SWF files.
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_header_js($swf)
{
	// Build Javascript code for view.php print_header() function
	$swf_header_js = '<script type="text/javascript" src="swfobject/swfobject.js"></script>
		<script type="text/javascript">
			swfobject.registerObject("myFlashContent", "'.$swf->version.'");
		</script>';
	// Don't show default dotted outline around Flash Player window in Firefox 3
	$swf_header_js .= '<style type="text/css" media="screen">
    		object { outline:none; }
		</style>';
		
	return $swf_header_js;
}

/**
* Build absolute URLs
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_build_paths($swf)
{
	global $CFG;
	
	$swf->coursepage = $CFG->wwwroot.'/course/view.php?id='.$swf->course;
	$swf->gateway = $CFG->wwwroot.'/lib/amfphp/gateway.php';
	$swf->moodledata = $CFG->wwwroot.'/file.php/'.$swf->course.'/';
	$swf->swfurl = $swf->moodledata.$swf->swfurl;
	//$swf->percent = '%';
	// Don't pass in empty vars
	if($swf->configxml != '')
	{
		$swf->configxml = '&configXml='.$CFG->wwwroot.'/file.php/'.$swf->course.'/'.$swf->configxml;
	}
	
	if($swf->xmlurl != '')
	{
		$swf->xmlurl = '&xmlUrl='.$CFG->wwwroot.'/file.php/'.$swf->course.'/'.$swf->xmlurl;
	}
	
	if($swf->apikey != '')
	{
		$swf->apikey = '&apiKey='.$swf->apikey;
	}
	
	// FlashVars name value pairs
	if($swf->name1 != '' && $swf->value1 != '')
	{
		$swf->namevalue1 = '&'.$swf->name1.'='.$swf->value1;
	} else {
		$swf->namevalue1 = '';
	}
	
	if($swf->name2 != '' && $swf->value2 != '')
	{
		$swf->namevalue2 = '&'.$swf->name2.'='.$swf->value2;
	} else {
		$swf->namevalue2 = '';
	}
	
	if($swf->name3 != '' && $swf->value3 != '')
	{
		$swf->namevalue3 = '&'.$swf->name3.'='.$swf->value3;
	} else {
		$swf->namevalue3 = '';
	}
	
	if($swf->interaction == 0)
	{
		$swf->interaction = '';
	} else {
		$swf->interaction = '&interaction='.$swf->interaction;
	}
}

/**
* Print FlashVars
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_flashvars($swf)
{
	$swf_flashvars = 'course='.$swf->course.
						'&coursePage='.$swf->coursepage.
						'&gateway='.$swf->gateway.
						'&gradeMax='.$swf->grademax.
						'&gradePass='.$swf->gradepass.
						'&instance='.$swf->instance.
						'&moodleData='.$swf->moodledata.
						'&startTime='.time().
						'&skin='.$swf->skin.
						'&swfId='.$swf->id.
						$swf->interaction.
						$swf->apikey.
						$swf->configxml.
						$swf->namevalue1.
						$swf->namevalue2.
						$swf->namevalue3.
						$swf->xmlurl;
	
	return $swf_flashvars;
}

/**
* Construct Javascript SWFObject embed code for <body> section of view.php
*
* @param $swf (mdl_swf DB record for current SWF module instance)
* @return string
*/
function swf_print_body($swf)
{
	global $CFG;
	
	swf_build_paths($swf);
	$swf_time = time();
	//
	$swf_body = '<div align="'.$swf->align.'">
	<object id="myFlashContent" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$swf->width.'" height="'.$swf->height.'" align="middle">
				<param name="movie" value="'.$swf->swfurl.'?stopCache='.$swf_time.'" />
				<param name="play" value="'.$swf->play.'" />
				<param name="loop" value="'.$swf->loopswf.'" />
				<param name="menu" value="'.$swf->menu.'" />
				<param name="quality" value="'.$swf->quality.'" />
				<param name="scale" value="'.$swf->scale.'" />
				<param name="salign" value="'.$swf->salign.'" />
				<param name="wmode" value="'.$swf->wmode.'" />
				<param name="bgcolor" value="#'.$swf->bgcolor.'" />
				<param name="devicefont" value="'.$swf->devicefont.'" />
				<param name="seamlesstabbing" value="'.$swf->seamlesstabbing.'" />
				<param name="allowfullscreen" value="'.$swf->allowfullscreen.'" />
				<param name="allowscriptaccess" value="'.$swf->allowscriptaccess.'" />
				<param name="allownetworking" value="'.$swf->allownetworking.'" />
				<param name="flashvars" value="'.swf_print_flashvars($swf).'" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="'.$swf->swfurl.'?stopCache='.$swf_time.'" width="'.$swf->width.'" height="'.$swf->height.'" align="'.$swf->align.'">
					<param name="play" value="'.$swf->play.'" />
					<param name="loop" value="'.$swf->loopswf.'" />
					<param name="menu" value="'.$swf->menu.'" />
					<param name="quality" value="'.$swf->quality.'" />
					<param name="scale" value="'.$swf->scale.'" />
					<param name="salign" value="'.$swf->salign.'" />
					<param name="wmode" value="'.$swf->wmode.'" />
					<param name="bgcolor" value="#'.$swf->bgcolor.'" />
					<param name="devicefont" value="'.$swf->devicefont.'" />
					<param name="seamlesstabbing" value="'.$swf->seamlesstabbing.'" />
					<param name="allowfullscreen" value="'.$swf->allowfullscreen.'" />
					<param name="allowscriptaccess" value="'.$swf->allowscriptaccess.'" />
					<param name="allownetworking" value="'.$swf->allownetworking.'" />
					<param name="flashvars" value="'.swf_print_flashvars($swf).'" />
				<!--<![endif]-->
<div align="center">
  '.get_string('embederror1','swf').$swf->version.get_string('embederror2','swf').'
  <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" border=0/></a></p>
  <p><a href="http://matbury.com/" title="SWF Activity Module developed by Matt Bury">by matbury.com</a></p>
</div>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
			</div>';
	//
	//return $swf_body.print_object($swf);
	return $swf_body;
}

/// End of mod/swf/lib.php
?>