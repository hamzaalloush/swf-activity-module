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

/// Open the selected swf report and display it

    $mode = clean_param($mode, PARAM_SAFEDIR);

    if (! is_readable('report/'.$mode.'/report.php')) {
        error('Report not known ('.$mode.')');
    }

    include('report/default.php');  // Parent class
    include('report/'.$mode.'/report.php');

    $report = new quiz_report();

    if (! $report->display($swf, $cm, $course)) {             // Run the report!
        error('Error occurred during pre-processing!');
    }

/// Print footer

    print_footer($course);

?>