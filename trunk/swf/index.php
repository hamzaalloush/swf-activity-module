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

    //require_login
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
	echo '<div align="left">
		<p>
		<table width="1600" border="0" cellpadding="5">
		<tr background="pix/gradient.jpg">
		<td><strong>&nbsp;</strong></td>
		<td width="340"><strong>&nbsp;</strong></td>
		<td colspan="6"><strong>'.get_string('reportlastattempt','swf').'</strong></td>
		<td colspan="6"><strong>'.get_string('reportallattempts','swf').'</strong></td>
	  </tr>';
	  
	  echo '<tr background="pix/gradient.jpg">
		<td><strong>'.get_string('reportsection','swf').'</strong></td>
		<td align="center"><strong>'.get_string('reportactivity','swf').'</strong></td>
		<td align="center"><strong>'.get_string('reportdate','swf').'</strong></td>
		<td align="center"><strong>'.get_string('reporttime','swf').'</strong></td>
		<td align="center"><strong>'.get_string('reportgrade','swf').'</strong></td>
		<td align="center"><strong>'.get_string('gradepass','swf').'</strong></td>
		<td align="center" colspan="2"><strong>'.get_string('reportduration','swf').'</strong></td>
		<td align="center"><strong>'.get_string('reportattempts','swf').'</strong></td>
		<td align="center" colspan="2"><strong>'.get_string('reportdurationtotal','swf').'</strong></td>
		
		<td align="center"><strong>'.get_string('reportaveragegrade','swf').'</strong></td>
		<td align="center"><strong>'.get_string('reportaveragegradehistory','swf').'</strong></td>
		<td align="center"><strong>'.get_string('reportfeedback','swf').'</strong></td>
	  </tr>';
	//<td align="center"><strong>'.get_string('reportdurationtotalhistory','swf').'</strong></td>
	/// Get all instances of SWFs for current course
    if ($swf_instances = get_all_instances_in_course('swf', $course)) {
	
		//print_object($swf_instances);
		
		$swf_hundred = 100;
		$swf_divide_by = 7;
		$swf_grey = true; // Background colors of table rows switch between grey and white
		$swf_total_course_grade = 0;
		$swf_total_course_attempts = 0;
		$swf_total_course_average_grade = 0;
		$swf_total_course_time = 0;
		$swf_grades_length = count($swf_instances);
		$swf_total_instances_passed = 0;
		
		foreach($swf_instances as $swf_instance)
		{
			// grade_get_grades($courseid, $itemtype, $itemmodule, $iteminstance, $userid_or_ids=null)
			$swf_grade = grade_get_grades($course->id, 'mod', 'swf', $swf_instance->id, $USER->id);
			
			// Write "-" if the grade is empty
			$swf_feedbackformat = $swf_grade->items[0]->grades[$USER->id]->feedbackformat;
			if($swf_grade->items[0]->grades[$USER->id]->str_grade == '-')
				{
					$swf_date_graded = '-';
					$swf_time_graded = '-';
					$swf_grade->items[0]->grades[$USER->id]->str_grade = '';
					$swf_str_totaltime = '';
					$swf_feedback = '-';
				} else {
					$swf_date_graded = date('l',$swf_grade->items[0]->grades[$USER->id]->dategraded).'<br/>'.date('d\/m\/Y',$swf_grade->items[0]->grades[$USER->id]->dategraded);
					$swf_time_graded = date('H:i:s',$swf_grade->items[0]->grades[$USER->id]->dategraded);
					// Show total time spent recorded on mod instance
					$swf_str_totaltime = swf_convert_seconds_to_string($swf_feedbackformat);
					$swf_feedback = $swf_grade->items[0]->grades[$USER->id]->str_feedback;
				}
				
				// Get calculated elapsed times for activity instance
				$swf_grade_history = swf_get_grade_history($swf_instance,$USER->id);
				
				if($swf_grade_history->str_totaltime == ' - ')
				{
					$swf_grade_history->str_totaltime = '';
				}
				// Switch between grey and white background color for rows in table
				if($swf_grey)
				{
					$swf_bgcolor = '#EEEEEE';
					$swf_grey = false;
				} else {
					$swf_bgcolor = '#FFFFFF';
					$swf_grey = true;
				}
				
				// Add up stats for course totals and averages
				$swf_total_course_grade += $swf_grade->items[0]->grades[$USER->id]->grade;
				$swf_total_course_grade_percent = $swf_total_course_grade / $swf_grades_length;
				$swf_total_course_average_grade += $swf_grade_history->averagegrade;
				$swf_total_course_attempts += $swf_grade_history->attempts;
				$swf_total_course_time += $swf_grade_history->totaltime;
				
				$swf_passed_tick = '';
				
				if($swf_grade->items[0]->gradepass < $swf_grade->items[0]->grades[$USER->id]->grade)
				{
					$swf_passed_tick = '<img src="pix/tick.png" width="20" height="19" alt="Completed" />';
					$swf_total_instances_passed++;
				}
				
				// Draw rows of table
				echo '<tr>
					<td align="center" bgcolor="'.$swf_bgcolor.'"><strong>'.$swf_instance->section.'</strong></td>
						<td bgcolor="'.$swf_bgcolor.'">
							<a href="view.php?a='.$swf_instance->id.'" title="'.$swf_grade->items[0]->name.'">
							<img src="icon.gif" width="16" height="16" align="absmiddle" alt="'.$swf_grade->items[0]->name.'" /> '.$swf_grade->items[0]->name.'</a>
						</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.$swf_date_graded.'</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.$swf_time_graded.'</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.(float)$swf_grade->items[0]->grades[$USER->id]->str_grade.'%<br/><img src="pix/green.gif" width="'.(int)$swf_grade->items[0]->grades[$USER->id]->grade.'" height="6" alt="green" /><img src="pix/red.gif" width="'.(int)($swf_hundred - $swf_grade->items[0]->grades[$USER->id]->grade).'" height="6" alt="red" />
						</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.(int)$swf_grade->items[0]->gradepass.'% '.$swf_passed_tick.'</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'"><img src="pix/black.gif" width="6" height="'.(int)($swf_feedbackformat / $swf_divide_by).'" /></td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.$swf_str_totaltime.'</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.$swf_grade_history->attempts.'</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.$swf_grade_history->duration_graph.'</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.$swf_grade_history->str_totaltime.'</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.$swf_grade_history->grade_graph.'</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.(int)$swf_grade_history->averagegrade.'%</td>
						<td align="center" bgcolor="'.$swf_bgcolor.'">'.$swf_feedback.'</td>
					  </tr>';
				}
				
				// Print summary of course
				$swf_total_course_grade_percent = $swf_total_course_grade / $swf_grades_length;
				$swf_bgcolor = '#CCCCFF';
				$swf_passed_tick = '';
				if($swf_total_instances_passed == $swf_grades_length)
				{
					$swf_passed_tick = '<img src="pix/tick.png" width="20" height="19" alt="Completed" />';
				}
				echo '<tr>
					<td bgcolor="'.$swf_bgcolor.'"><strong>&nbsp;</strong></td>
						<td bgcolor="'.$swf_bgcolor.'"><strong><img src="icon.gif" width="16" height="16" align="absmiddle" alt="SWF"/> Course total / '.$swf_grades_length.' activities</strong></td>
						<td bgcolor="'.$swf_bgcolor.'" align="center">&nbsp;</td>
						<td bgcolor="'.$swf_bgcolor.'" align="center">&nbsp;</td>
						<td bgcolor="'.$swf_bgcolor.'" align="center"><strong>'.(int)$swf_total_course_grade_percent.'%<br/><img src="pix/green.gif" width="'.$swf_total_course_grade_percent.'" height="6" alt="green" /><img src="pix/red.gif" width="'.($swf_hundred - $swf_total_course_grade_percent).'" height="6" alt="red" /></strong></td>
						<td bgcolor="'.$swf_bgcolor.'" align="center"><strong>'.$swf_total_instances_passed.'/'.$swf_grades_length.' '.$swf_passed_tick.'</strong></td>
						<td bgcolor="'.$swf_bgcolor.'" align="center"><strong>&nbsp;</strong></td>
						<td bgcolor="'.$swf_bgcolor.'" align="center"><strong>&nbsp;</strong></td>
						<td bgcolor="'.$swf_bgcolor.'" align="center"><strong>'.$swf_total_course_attempts.'</strong></td>
						<td bgcolor="'.$swf_bgcolor.'" colspan="2" align="center"><strong>'.swf_convert_seconds_to_string($swf_total_course_time).'</strong></td>
						<td bgcolor="'.$swf_bgcolor.'" align="center"><strong><img src="pix/green.gif" width="'.($swf_total_course_average_grade / $swf_grades_length).'" height="6" alt="green" /><img src="pix/red.gif" width="'.($swf_hundred - ($swf_total_course_average_grade / $swf_grades_length)).'" height="6" alt="red" /></strong></td>
						<td bgcolor="'.$swf_bgcolor.'" align="center"><strong>'.(int)($swf_total_course_average_grade / $swf_grades_length).'%</strong></td>
						<td bgcolor="'.$swf_bgcolor.'">&nbsp;</td>
					  </tr>
				</table></p></div>';
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