<?php  // $Id: lib.php,v 1.1 2010/02/02 matbury Exp $
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
 
require_once('swfformlib.php'); // functions for mod_form.php
require_once('swfviewlib.php'); // functions for view.php
 
function swf_add_instance($swf)
{
    
	$swf->timecreated = time();
	$swf->timemodified = time();
	// Try to store it in the database.
	if (!$swf->id = insert_record('swf', $swf))
	{
		return false;
	}
	
	// Insert corresponding grade item into grade book
	swf_grade_item_update($swf);
	
    return $swf->id;
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function swf_update_instance($swf)
{
    $swf->timemodified = time();
	// Update the database.
	$swf->id = $swf->instance;
	if (!update_record('swf', $swf))
	{
		return false;  // some error occurred
	}
	// Update changes to corresponding grade_items record
	swf_grade_item_update($swf);
	
    return true;
}

/**
 * Inserts or updates corresponding grade item in grade book
 * 
 * @param object $swf
 * @return boolean or int??
 **/
function swf_grade_item_update($swf)
{
	global $CFG;
	
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }
	
    /*if (array_key_exists('cmidnumber', $swf)) { //it may not be always present
        $params = array('itemname'=>$swf->name, 'idnumber'=>$swf->cmidnumber);
    } else {
        $params = array('itemname'=>$swf->name);
    }*/
	
	$params = array('itemname' => $swf->name);
	// If set grade is more than 0, otherwise don't grade
    if ($swf->grademax > 0) {
        $params['gradetype'] = $swf->gradetype;
        $params['grademax']  = $swf->grademax;
        $params['grademin']  = $swf->grademin;
        $params['gradepass']  = 60; //$swf->gradepass; // This isn't working. Gives 0 value.
    } else {
		$params['gradetype'] = GRADE_TYPE_NONE;
    }
	
	// Insert/Update grade item
	//grade_update($source, $courseid, $itemtype, $itemmodule, $iteminstance, $itemnumber, $grades=NULL, $itemdetails=NULL)
	return grade_update('mod/swf', $swf->course, 'mod', 'swf', $swf->id, 0, NULL, $params);
}

/**
 * Given an ID of an instance of this module, 
 * this function will permanently delete the instance 
 * and any data that depends on it. 
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function swf_delete_instance($id)
{

    if (! $swf = get_record('swf', 'id', "$id")) {
        return false;
    }
    $result = true;

    if (! delete_records('swf', 'id', "$swf->id")) {
        $result = false;
    }
	
	// TODO - update changes to corresponding grade_items record
	//quiz_grade_item_delete($swf);
	
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
function swf_user_outline($course, $user, $mod, $swf)
{
	
	global $CFG;
	require_once($CFG->libdir.'/gradelib.php');
	$grades = grade_get_grades($course->id, 'mod', 'swf', $swf->id, $user->id);
	if (empty($grades->items[0]->grades))
	{
		return null;
	} else {
		$grade = reset($grades->items[0]->grades);
	}
	
	$result = new stdClass;
	$result->info = get_string('grade') . ': ' . $grade->str_long_grade;
	$result->time = $grade->dategraded;
	return $result;
	
	/*
	if($logs = get_records_select("log", "userid = '$user->id' AND module = 'swf' AND action = 'view' AND info = '$swf->id'", "time ASC")) {

        $numviews = count($logs);
        $lastlog = array_pop($logs);
        $result = new object();
        $result->info = get_string("numviews", "", $numviews);
        $result->time = $lastlog->time;
        return $result;
    }
    return NULL;
	*/
}

/**
 * Print a detailed representation of what a user has done with 
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function swf_user_complete($course, $user, $mod, $swf)
{
    global $CFG;
	require_once($CFG->libdir.'/gradelib.php');
	$grades = grade_get_grades($course->id,'mod','swf',$swf->id,$user->id);
	if (!empty($grades->items[0]->grades)) {
		$grade = reset($grades->items[0]->grades);
		echo '<p>'.get_string('grade').': '.$grade->str_long_grade.'</p>';
		if ($grade->str_feedback) {
			echo '<p>'.get_string('feedback').': '.$grade->str_feedback.'<p/>';
		}
	}
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
function swf_print_recent_activity($course, $isteacher, $timestart)
{
    global $CFG;
	$result = false;

    return $result;  //  True if anything was printed, otherwise false 
}

/**
 * 
 *
 * @uses $CFG
 * @return array
 **/
function swf_get_view_actions() {
    //return array('view');
	return array('view', 'view all', 'report');
}

/**
 * 
 *
 * @uses $CFG
 * @return array
 **/
function swf_get_post_actions() {
    //return array();
	return array('attempt', 'submit');
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
function swf_cron ()
{
    //global $CFG;
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
function swf_grades($swfid)
{
	global $CFG;
	global $COURSE;
	require_once($CFG->libdir.'/gradelib.php');
	if($grades = grade_get_grades($COURSE->id,'mod','swf',$swfid))
	{
		return $grades;
	}
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
function swf_get_participants($swfid)
{
    return false;
}

/**
 * This function returns if a scale is being used by one swf
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $swfid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
function swf_scale_used($swfid,$scaleid) {
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

?>