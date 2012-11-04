<?php  // $Id: grade.php,v 1.1.2.1 2010/02/01 matbury Exp $
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
 * This script redirects users from grade book to either report.php (teachers and admins)
 * or the corresponding SWF Activity Module instance (students)
 *
 * @author Matt Bury - matbury@gmail.com - http://matbury.com/
 * @version $Id: grade.php,v 1.0 2010/02/10 matbury Exp $
 * @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
 * @package swf
 **/
 
    require_once("../../../config.php");
	
	global $CFG;
	global $USER;
	
	// Requires moodle/lib/gradelib.php for grade_update() function
	require_once($CFG->libdir.'/gradelib.php');
	
    $id = required_param('id', PARAM_INT); // Course module ID
	$swf_id = required_param('swfid', PARAM_INT); // SWF ID
	$swf_grade = required_param('grade', PARAM_INT); // Grade 0 - 100
	$swf_feedbackformat = required_param('feedbackformat', PARAM_INT); // Duration in seconds
	// Feedback text???

    if (! $cm = get_coursemodule_from_id('swf', $id)) {
		echo 'errormessage=Course Module ID was incorrect';
    }

    if (! $swf = get_record('swf', 'id', $cm->instance)) {
		echo 'errormessage=swf ID was incorrect';
    }

    if (! $course = get_record('course', 'id', $swf->course)) {
		echo 'errormessage=Course is misconfigured';
    }
	
	// Require login
    require_login($course->id, false, $cm);
	
	// Create grade object to pass in grade data
	$grade = new stdClass();
	$grade->feedback = 'This is some feedback text.';
	$grade->feedbackformat = $swf_feedbackformat;
	$grade->instance = $id;
	$grade->rawgrade = $swf_grade;
	$grade->swfid = $swf_id;
	$grade->timemodified = time();
	$grade->userid = $USER->id;
	$grade->usermodified = $USER->id;
	
	// Insert or update grade
	// grade_update($source, $courseid, $itemtype, $itemmodule, $iteminstance, $itemnumber, $grades=NULL, $itemdetails=NULL)
	grade_update('mod/swf', $cm->course, 'mod', 'swf', $swf_id, 0, $grade, NULL);
	
	// Return updated grade item -> Doesn't work in AS 2.0
	//$result = grade_get_grades($cm->course, 'mod', 'swf', $swf_id, $USER->id);
	
	// Add view to Moodle log
	//add_to_log($cm->course, 'swf', 'submit grade', "view.php?id=$grade->instance", $cm->name);
			
	
	echo "feedback=$grade->feedback&feedbackformat=$swf_feedbackformat&instance=$grade->instance&rawgrade=$grade->rawgrade&swfid=$grade->swfid&timemodified=$grade->timemodified&userid=$grade->userid&usermodified=$grade->usermodified&course=$cm->course"

?>
