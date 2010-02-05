<?php // $Id: report.php,v 0.1 2010/02/01 matbury Exp $

// This script uses installed report plugins to print swf reports

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
	
    add_to_log($course->id, 'swf', 'report', 'report.php?id='.$cm->id, "$swf->id", "$cm->id");
	
	/// Print the page header
    $strswfs = get_string('modulenameplural', 'swf');
    $strswf  = get_string('modulename', 'swf');
	
	$navigation = build_navigation(get_string('report', 'swf'), $cm);
	print_header_simple(format_string($swf->name), '', $navigation, '', '', true, '', navmenu($course, $cm));
	

/// Print report...

    //error('Reports not yet implemented');
	
	require_once($CFG->libdir.'/gradelib.php');
	
	//$swf_grades = grade_get_grades($course->id, 'mod', 'swf', $swf->id);
	$swf_grades = grade_get_grades($course->id, 'mod', 'quiz', 10);
	
	print_object($swf_grades);
	
/*
object Object
(
    [items] => Array
        (
            [0] => object Object
                (
                    [itemnumber] => 0
                    [scaleid] => 
                    [name] => Grade Item 2
                    [grademin] => 0.00000
                    [grademax] => 100.00000
                    [gradepass] => 0.00000
                    [locked] => 
                    [hidden] => 
                    [grades] => Array
                        (
                        )

                )

        )

    [outcomes] => Array
        (
        )

)
*/
	
	echo '<div align="center">
	<table width="80%" border="0" cellpadding="10">
	<tr>
    <td><strong>SWF Name</strong></td>
    <td><strong>Minimum Grade</strong></td>
    <td><strong>Maximum Grade</strong></td>
    <td><strong>Pass Grade</strong></td>
  </tr>';
	foreach($swf_grades->items as $swf_grade_item)
	{
		echo '<tr>
				<td>'.$swf_grade_item->name.'</td>
				<td>'.$swf_grade_item->grademin.'</td>
				<td>'.$swf_grade_item->grademax.'</td>
				<td>'.$swf_grade_item->gradepass.'</td>
			  </tr>';
		
	}
	echo '</table>
		</div>';

/// Print footer

    print_footer($course);

?>