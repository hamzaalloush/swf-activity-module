<?php  // $Id: grade.php,v 1.1.2.1 2010/02/01 matbury Exp $

    require_once("../../config.php");

    $id   = required_param('id', PARAM_INT); // Course module ID

    if (! $cm = get_coursemodule_from_id('swf', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $swf = get_record('swf', 'id', $cm->instance)) {
        error('swf ID was incorrect');
    }

    if (! $course = get_record('course', 'id', $swf->course)) {
        error('Course is misconfigured');
    }

    require_login($course->id, false, $cm);

    if (has_capability('mod/swf:viewreports', get_context_instance(CONTEXT_MODULE, $cm->id))) {
        redirect('report.php?id='.$cm->id);
    } else {
        redirect('view.php?id='.$cm->id);
    }

?>
