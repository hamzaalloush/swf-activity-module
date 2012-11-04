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

    require_once('../../config.php');
    require_once('lib.php');
	
	$id = optional_param('id',0,PARAM_INT);    // Course Module ID, or
    $a = optional_param('a',0,PARAM_INT);     // swf ID

    $mode = optional_param('mode', 'overview', PARAM_ALPHA);        // Report mode

    if ($id) {
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
    }

    require_login($course, false, $cm);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/swf:viewreports', $context);
	
    //add_to_log($course->id, 'swf', 'report', "report.php?id=$cm->id", "$swf->id", "$cm->id");
	add_to_log($course->id, 'swf', 'report', "report.php?id=$cm->id", "$swf->name");
	
	/// Print the page header
    $strswfs = get_string('modulenameplural', 'swf');
    $strswf  = get_string('modulename', 'swf');
	
	$navigation = build_navigation(get_string('report', 'swf'), $cm);
	print_header_simple(format_string($swf->name), '', $navigation, '', '', true, '', navmenu($course, $cm));
	

/// Print report...
	
	require_once($CFG->libdir.'/gradelib.php');
	
	// Print table headers
	echo '<div align="center">
		<p>
		<table width="100%" border="0" cellpadding="0">
		<tr background="gradient.jpg">
		<td width="190"><strong>'.get_string('reportuser','swf').'</strong></td>
		<td width="110"><strong>'.get_string('reportdate','swf').'</strong></td>
		<td width="110"><strong>'.get_string('reporttime','swf').'</strong></td>
		<td width="70"><strong>'.get_string('reportgrade','swf').'</strong></td>
		<td width="120"><strong>'.get_string('reportduration','swf').'</strong></td>
		<td width="80"><strong>'.get_string('reportattempts','swf').'</strong></td>
		<td width="140"><strong>'.get_string('reportdurationtotal','swf').'</strong></td>
		<td width="90"><strong>'.get_string('reportaveragegrade','swf').'</strong></td>
		<td><strong>'.get_string('reportfeedback','swf').'</strong></td>
	  </tr>';
	  
	// Get course users
	$swf_user_ids = array();
	
	if($swf_users = get_course_users($course->id))
	{
		foreach($swf_users as $swf_user)
		{
			array_push($swf_user_ids,$swf_user->id);
		}
		
		// Get course users' grades
		$swf_grades = grade_get_grades($course->id, 'mod', 'swf', $swf->id, $swf_user_ids);
		
		$swf_grey = true;
		
		foreach($swf_users as $swf_user)
		{
			//
			$swf_grade = $swf_grades->items[0]->grades[$swf_user->id]->str_grade;
			if($swf_grade == '-')
			{
				$swf_date_graded = '-';
				$swf_time_graded = '-';
				$swf_str_totaltime = '-';
				$swf_feedback = '-';
			} else {
				$swf_date_graded = date('l d\/m\/Y',$swf_grades->items[0]->grades[$swf_user->id]->dategraded);
				$swf_time_graded = date('H:i:s',$swf_grades->items[0]->grades[$swf_user->id]->dategraded);
				// Show total time spent recorded on mod instance 
				$swf_feedbackformat = $swf_grades->items[0]->grades[$swf_user->id]->feedbackformat;
				$swf_str_totaltime = swf_convert_seconds_to_string($swf_feedbackformat);
				
				$swf_feedback = $swf_grades->items[0]->grades[$swf_user->id]->str_feedback;
			}
			
			// Get calculated elapsed times for activity instance
			$swf_grade_history = swf_get_grade_history($swf,$swf_user->id);
			
			if($swf_grey)
			{
				$swf_bgcolor = 'bgcolor="#EEEEEE"';
				$swf_grey = false;
			} else {
				$swf_bgcolor = 'bgcolor="#FFFFFF"';
				$swf_grey = true;
			}
			
			echo '<tr>
					<td '.$swf_bgcolor.'>
						<a href="'.$CFG->wwwroot.'/user/view.php?id='.$swf_user->id.'&course='.$course->id.'" title="'.$swf_user->firstname.' '.$swf_user->lastname.'">
						<img src="'.$CFG->wwwroot.'/user/pix.php/'.$swf_user->id.'/f1.jpg" width="35" height="35" align="absmiddle" alt="'.$swf_user->firstname.' '.$swf_user->lastname.'" /> '.$swf_user->firstname.' '.$swf_user->lastname.'</a>
					</td>
					<td '.$swf_bgcolor.'>'.$swf_date_graded.'</td>
					<td '.$swf_bgcolor.'>'.$swf_time_graded.'</td>
					<td '.$swf_bgcolor.'>'.$swf_grade.'</td>
					<td '.$swf_bgcolor.'>'.$swf_str_totaltime.'</td>
					<td '.$swf_bgcolor.'>'.$swf_grade_history->attempts.'</td>
					<td '.$swf_bgcolor.'>'.$swf_grade_history->str_totaltime.'</td>
					<td '.$swf_bgcolor.'>'.$swf_grade_history->averagegrade.'</td>
					<td '.$swf_bgcolor.'>'.$swf_feedback.'</td>
				  </tr>';
		}
		echo '</table>
				</p>
			</div>';
	} else {
		// There were no grades in the grade book for this activity
		echo '</table>
		<h2 align="center">'.get_string('reportnogrades','swf').'</h2>';
	}

/// Print footer
//
    print_footer($course);

?>