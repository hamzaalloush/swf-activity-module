<?php  // $Id: view.php,v 1.0 2009/01/28 matbury Exp $

/*
*    Copyright (C) 2009  Matt Bury - matt@matbury.com - http://matbury.com/
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
 * This page prints a particular instance of swf
 *
 * @author Matt Bury - matt@matbury.com
 * @version $Id: view.php,v 1.0 2009/01/28 matbury Exp $
 * @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
 * @package swf
 **/

	require_once('../../config.php');
    require_once('lib.php');
	
    $id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
    $a  = optional_param('a', 0, PARAM_INT);  // swf ID
	
    if ($id) {
        if (! $cm = get_record('course_modules', 'id', $id)) {
            error('Course Module ID was incorrect');
        }

        if (! $course = get_record('course', 'id', $cm->course)) {
            error('Course is misconfigured');
        }

        if (! $swf = get_record('swf', 'id', $cm->instance)) {
            error('Course module is incorrect');
        }

    } else {
        if (! $swf = get_record('swf', 'id', $a)) {
            error('Course module is incorrect');
        }
        if (! $course = get_record('course', 'id', $swf->course)) {
            error('Course is misconfigured');
        }
        if (! $cm = get_coursemodule_from_instance('swf', $swf->id, $course->id)) {
            error('Course Module ID was incorrect');
        }
    }

    require_login($course->id);

    add_to_log($course->id, 'swf', 'view', "view.php?id=$cm->id", "$swf->name", $cm->id); // Add view to Moodle log
	
	$swf->instance = $id; // Add course module ID
	$swf->courseobject = $course; // Add course module ID
	
	$strswfs = get_string('modulenameplural', 'swf');
	$strswf  = get_string('modulename', 'swf');
	
	/*
	######################################################## Print view page ########################################################
	*/
	// Define the nextinstance and sequencedinstances variables
	swf_get_instance_grade($swf);
	swf_get_next_instance($swf);
	
	if($swf->sequenced == 'true')
	{// Instance is in a sequence
		if($swf->gradeuser >= $swf->gradepass) 
		{// User has passed
			if($swf->allowreview == 'true')
			{// User can review passed instances
				
				//___________________________________________________________________Start Flash embed
				if($swf->fullbrowser != 'true')
				{
					// Print Flash with Moodle navigation
					swf_print_nav($swf,$id,$cm,$course,$strswf);
					echo swf_print_body($swf);
					print_footer($course);
					//End.
				} else {
					// Print full browser Flash 100% x 100% (no Moodle naviagtion)
					echo swf_print_fullbrowser($swf);
					//End.
				}
				//___________________________________________________________________End Flash embed
				
			} else {// User can't review passed instances
				//Print sequenced instances table
				swf_print_nav($swf,$id,$cm,$course,$strswf);
				echo swf_print_sequenced_instances($swf);
				print_footer($course);
				//End.
			}
			
		} else {// User hasn't passed
			
			if($swf->instance != $swf->nextinstance)
			{// This isn't next instance in sequence
				//Print sequenced instances table
				swf_print_nav($swf,$id,$cm,$course,$strswf);
				echo swf_print_sequenced_instances($swf);
				print_footer($course);
				//End.
				
			} else {// This is next instance in sequence
					
				//___________________________________________________________________Start Flash embed
				if($swf->fullbrowser != 'true')
				{
					// Print Flash with Moodle navigation
					swf_print_nav($swf,$id,$cm,$course,$strswf);
					echo swf_print_body($swf);
					print_footer($course);
					//End.
				} else {
					// Print full browser Flash 100% x 100% (no Moodle naviagtion)
					echo swf_print_fullbrowser($swf);
					//End.
				}
				//___________________________________________________________________End Flash embed
			}
		}
		
	} else {// Instance isn't in a sequence
		
		//___________________________________________________________________Start Flash embed
		if($swf->fullbrowser != 'true')
		{
			// Print Flash with Moodle navigation
			swf_print_nav($swf,$id,$cm,$course,$strswf);
			echo swf_print_body($swf);
			print_footer($course);
			//End.
		} else {
			// Print full browser Flash 100% x 100% (no Moodle naviagtion)
			echo swf_print_fullbrowser($swf);
			//End.
		}
		//___________________________________________________________________End Flash embed
		
	}
	
	// For development and testing purposes only:
	//echo swf_print_fullbrowser($swf);
	//echo swf_print_body($swf);
	//echo print_object($swf);
	//print_footer($course);
?>