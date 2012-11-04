<?php // $Id: report.php,v 0.1 2010/02/01 matbury Exp $
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
 * This script uses installed report plugins to print swf reports
 *
 * @author Matt Bury - matbury@gmail.com - http://matbury.com/
 * @version $Id: report.php,v 1.0 2010/02/10 matbury Exp $
 * @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
 * @package swf
 **/

// TODO - Students can't navigate to this report page. Need to add a link on a student page so they can see it.
// On mod/swf/index.php page? Logical.

    require_once('../../config.php');
    require_once('lib.php');
	
	global $USER;
	
	$id = optional_param('id',0,PARAM_INT);    // Course Module ID, or
    $a = optional_param('a',0,PARAM_INT);     // swf ID

    $mode = optional_param('mode', 'overview', PARAM_ALPHA);        // Report mode
	
	
    if (! $course = get_record('course', 'id', $id)) {
        error('Course ID is incorrect');
    }

    /*if ($id) {
        if (! $cm = get_coursemodule_from_id('swf', $id)) {
            error('There is no coursemodule with id '.$id);
        }

        if (! $course = get_record('course', 'id', $cm->course)) {
            error('Course is misconfigured');
        }

        if (! $swf = get_record('swf', 'id', $cm->instance)) {
            error('The swf with id '.$cm->instance.' corresponding to this coursemodule '.$id.' is missing');
        }

    } else {
        if (! $swf = get_record('swf', 'id', $a)) {
            error('There is no swf with id '.$a);
        }
        if (! $course = get_record('course', 'id', $swf->course)) {
            error('The course with id '.$swf->course.' that the swf with id '.$a.' belongs to is missing');
        }
        if (! $cm = get_coursemodule_from_instance('swf', $swf->id, $course->id)) {
            error('The course module for the swf with id '.$a.' is missing');
        }
    }*/

    //require_login($course, false, $cm);
	require_login($course->id);
	
	/// Print the page header
    $strswfs = get_string('modulenameplural', 'swf');
    $strswf  = get_string('modulename', 'swf');
	
	add_to_log($course->id, 'swf', 'report', "userreport.php?id=$course->id", $strswfs.' - '.get_string('reportstudent', 'swf'));
	
	$navlinks = array();
	$navlinks[] = array('name' => $strswfs.' '.get_string('reportstudent', 'swf'), 'link' => '', 'type' => 'activity');
	$navigation = build_navigation($navlinks);
	print_header_simple("$strswfs ".get_string('reportstudent', 'swf'), '', $navigation, '', '', true, '', navmenu($course));

/// Print report...
	
	require_once($CFG->libdir.'/gradelib.php');
	
	// Print table headers
	echo '<div align="center">
		<p>
		<table width="100%" border="0" cellpadding="5">
		<tr background="gradient.jpg">
		<td width="340"><strong>'.get_string('reportactivity','swf').'</strong></td>
		<td width="110"><strong>'.get_string('reportdate','swf').'</strong></td>
		<td width="110"><strong>'.get_string('reporttime','swf').'</strong></td>
		<td width="70"><strong>'.get_string('reportgrade','swf').'</strong></td>
		<td width="120"><strong>'.get_string('reportduration','swf').'</strong></td>
		<td width="80"><strong>'.get_string('reportattempts','swf').'</strong></td>
		<td width="140"><strong>'.get_string('reportdurationtotal','swf').'</strong></td>
		<td width="90"><strong>'.get_string('reportaveragegrade','swf').'</strong></td>
		<td><strong>'.get_string('reportfeedback','swf').'</strong></td>
	  </tr>';
	
	/// Get all instances of SWFs for current course
    if ($swf_instances = get_all_instances_in_course('swf', $course)) {
	
		//print_object($swf_instances);
		
		$swf_grey = true; // Background colors of table rows switch between grey and white
		
		foreach($swf_instances as $swf_instance)
		{
			// grade_get_grades($courseid, $itemtype, $itemmodule, $iteminstance, $userid_or_ids=null)
			$swf_grade = grade_get_grades($course->id, 'mod', 'swf', $swf_instance->id, $USER->id);
			
			//print_object($swf_grade->items[0]);
			
			// Write "-" if the grade is empty
			if($swf_grade->items[0]->grades[$USER->id]->str_grade == '-')
				{
					$swf_date_graded = '-';
					$swf_time_graded = '-';
					$swf_str_totaltime = '-';
					$swf_feedback = '-';
				} else {
					$swf_date_graded = date('l d\/m\/Y',$swf_grade->items[0]->grades[$USER->id]->dategraded);
					$swf_time_graded = date('H:i:s',$swf_grade->items[0]->grades[$USER->id]->dategraded);
					// Show total time spent recorded on mod instance 
					$swf_feedbackformat = $swf_grade->items[0]->grades[$USER->id]->feedbackformat;
					$swf_str_totaltime = swf_convert_seconds_to_string($swf_feedbackformat);
					$swf_feedback = $swf_grade->items[0]->grades[$USER->id]->str_feedback;
				}
			
			
				// Get calculated elapsed times for activity instance
				$swf_grade_history = swf_get_grade_history($swf_instance,$USER->id);
				
				// Switch between grey and white background color for rows in table
				if($swf_grey)
				{
					$swf_bgcolor = '#EEEEEE';
					$swf_grey = false;
				} else {
					$swf_bgcolor = '#FFFFFF';
					$swf_grey = true;
				}
				
				// Draw rows of table
				echo '<tr>
						<td bgcolor="'.$swf_bgcolor.'">
							<a href="view.php?a='.$swf_instance->id.'" title="'.$swf_grade->items[0]->name.'">
							<img src="icon.gif" width="16" height="16" align="absmiddle" alt="'.$swf_grade->items[0]->name.'" /> '.$swf_instance->section.' '.$swf_grade->items[0]->name.'</a>
						</td>
						<td bgcolor="'.$swf_bgcolor.'">'.$swf_date_graded.'</td>
						<td bgcolor="'.$swf_bgcolor.'">'.$swf_time_graded.'</td>
						<td bgcolor="'.$swf_bgcolor.'">'.$swf_grade->items[0]->grades[$USER->id]->str_grade.'</td>
						<td bgcolor="'.$swf_bgcolor.'">'.$swf_str_totaltime.'</td>
						<td bgcolor="'.$swf_bgcolor.'">'.$swf_grade_history->attempts.'</td>
						<td bgcolor="'.$swf_bgcolor.'">'.$swf_grade_history->str_totaltime.'</td>
						<td bgcolor="'.$swf_bgcolor.'">'.$swf_grade_history->averagegrade.'</td>
						<td bgcolor="'.$swf_bgcolor.'">'.$swf_feedback.'</td>
					  </tr>';
				}
				echo '</table>
						</p>
					</div>';
			} else {
				
		// There were no grades in the grade book for this activity
		echo '</table>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<h3 align="center">'.get_string('reportnoinstances','swf').'</h3>
		<p>&nbsp;</p>
		<p>&nbsp;</p>';
	}

/// Print footer
//
    print_footer($course);

?>