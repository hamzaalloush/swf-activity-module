<?php // $Id: report.php,v 0.1 2010/02/01 matbury Exp $

// This script uses installed report plugins to print swf reports

    require_once('../../config.php');
    require_once('lib.php');
	
	$id = optional_param('id',0,PARAM_INT);    // Course Module ID, or
    $a = optional_param('a',0,PARAM_INT);     // swf ID
	$userid = optional_param('userid',0,PARAM_INT);

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
	
    add_to_log($course->id, 'swf', 'report', 'report.php?id='.$cm->id, "$swf->id", "$cm->id");
	
	/// Print the page header
    $strswfs = get_string('modulenameplural', 'swf');
    $strswf  = get_string('modulename', 'swf');
	
	$navigation = build_navigation(get_string('report', 'swf'), $cm);
	print_header_simple(format_string($swf->name), '', $navigation, '', '', true, '', navmenu($course, $cm));
	

/// Print report...
	
	require_once($CFG->libdir.'/gradelib.php');
	
	// Get course users
	if($swf_users = get_course_users($course->id))
	{
		$swf_user_ids = array();
		foreach($swf_users as $swf_user)
		{
			array_push($swf_user_ids,$swf_user->id);
		}
	}
	
	// Get course users' grades
	$swf_grades = grade_get_grades($course->id, 'mod', 'swf', $swf->id, $swf_user_ids);
	
	echo '<div align="center">
	<table width="90%" border="0" cellpadding="10">
	<tr>
    <td><strong>User</strong></td>
    <td><strong>Date</strong></td>
    <td><strong>Time</strong></td>
    <td><strong>Grade</strong></td>
	<td><strong>Total Time</strong></td>
    <td><strong>Feedback</strong></td>
  </tr>';
	foreach($swf_users as $swf_user)
	{
		//
		$swf_grade = $swf_grades->items[0]->grades[$userid]->feedback;
		echo $swf_grade;
		if($swf_grade == '-')
		{
			$swf_date_graded = '-';
			$swf_time_graded = '-';
			$swf_total_time = '-';
			$swf_feedback = '-';
		} else {
			$swf_date_graded = date('d-m-Y',$swf_grades->items[0]->grades[$swf_user->id]->dategraded);
			$swf_time_graded = date('H:i:s',$swf_grades->items[0]->grades[$swf_user->id]->dategraded);
			// Show total time spent recorded on mod instance 
			$swf_feedbackformat = $swf_grades->items[0]->grades[$swf_user->id]->feedbackformat;
			$vals = array('weeks' => (int) ($swf_feedbackformat / 86400 / 7), 
							'days' => $swf_feedbackformat / 86400 % 7, 
							'h' => $swf_feedbackformat / 3600 % 24, 
							'm' => $swf_feedbackformat / 60 % 60, 
							's' => $swf_feedbackformat % 60);
			$ret = array(); 
			$added = false; 
			foreach ($vals as $k => $v) { 
				if ($v > 0 || $added) { 
					$added = true; 
					$ret[] = $v . $k; 
				}
			}
			$swf_total_time = join(' ', $ret); 
			//
			$swf_feedback_full = $swf_grades->items[0]->grades[$swf_user->id]->feedback;
			$swf_feedback = substr($swf_grades->items[0]->grades[$swf_user->id]->feedback,0,50).'...';
		}
		
		echo '<tr>
				<td>
					<a href="'.$CFG->wwwroot.'/user/view.php?id='.$swf_user->id.'&course='.$course->id.'" title="'.$swf_user->firstname.' '.$swf_user->lastname.'">
					<img src="'.$CFG->wwwroot.'/user/pix.php/'.$swf_user->id.'/f1.jpg" width="35" height="35" align="absmiddle" alt="'.$swf_user->firstname.' '.$swf_user->lastname.'" /> '.$swf_user->firstname.' '.$swf_user->lastname.'</a>
				</td>
				<td>'.$swf_date_graded.'</td>
				<td>'.$swf_time_graded.'</td>
				<td>'.$swf_grade.'</td>
				<td>'.$swf_total_time.'</td>
				<td><a href="'.$CFG->wwwroot.'/mod/swf/feedback.php?id='.$cm->id.'&userid='.$swf_user->id.'" id="feedback" title="'.$swf_feedback_full.'" target="_blank">'.$swf_feedback.'</a></td>
			  </tr>';
	}
	echo '</table>
		</div>';

/// Print footer
//
    print_footer($course);

?>