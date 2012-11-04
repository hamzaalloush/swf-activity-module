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
        redirect('report.php?id='.$cm->id); // Teacher version of report = one instance + all course users
    } else {
		redirect('index.php?id='.$course->id); // Student version of report = all course instances + current user
	}

?>
