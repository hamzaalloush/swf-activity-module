<?php
/**
*    Copyright (C) 2009  Matt Bury - matbury@gmail.com - http://matbury.com/
*
*    This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* Checks current user's login status and capabilities
*
* @author Matt Bury - matbury@gmail.com
* @version $Id: view.php,v 1.0 2009/01/28 matbury Exp $
* @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
* @package lib/amfphp/services
**/

class Access
{
	// Single function so no class variables
	
	public function __contruct()
	{
		// Do nothing here
	}
	
	/**
	* Check login and capabilities of current user as defined in swf/db/access.php
	* Either parameter can be used
	* @param int course module ID
	* @param int swf ID
	* @return obj
	*
	*/
	public function get_capabilities($id = null, $a = null)
	{
		// Object to store results
		$obj = new object();
		
		// Check user is logged in
		if(isloggedin())
		{
			$obj->is_logged_in = true;
		} else {
			$obj->is_logged_in = false;
			return $obj; // Not logged in so stop here.
		}
		
		// Get module instance data
		// Return errors and stop if misconfigured
		if ($id) {
			if (! $cm = get_record('course_modules', 'id', $id))
			{
				$obj->error = 'Course Module ID was incorrect';
				return $obj; // Error so stop here and tell client what happened
			}
			
			if (! $course = get_record('course', 'id', $cm->course))
			{
				$obj->error = 'Course is misconfigured';
				return $obj; // Error so stop here and tell client what happened
			}
			
			if (! $swf = get_record('swf', 'id', $cm->instance))
			{
				$obj->error = 'Course module is incorrect';
				return $obj; // Error so stop here and tell client what happened
			}
		
		} else {
			if (! $swf = get_record('swf', 'id', $a))
			{
				$obj->error = 'Course module is incorrect';
				return $obj; // Error so stop here and tell client what happened
			}
			
			if (! $course = get_record('course', 'id', $swf->course))
			{
				$obj->error = 'Course is misconfigured';
				return $obj; // Error so stop here and tell client what happened
			}
			
			if (! $cm = get_coursemodule_from_instance('swf', $swf->id, $course->id))
			{
				$obj->error =  'Course Module ID was incorrect';
				return $obj; // Error so stop here and tell client what happened
			}
		}
		
		// Access current user's capabilities as defined in mod/swf/db/access.php
		$context = get_context_instance(CONTEXT_MODULE, $cm->id);
		
		// Store module details
		$obj->course = $cm->course;
		$obj->swfname = $swf->name;
		$obj->courseobject = $course;
		$obj->cmid = $cm->id;
		
		// Check if current user can view module (is guest)
		if (has_capability('mod/swf:view', $context))
		{
			$obj->view_mod = true;
		} else {
			$obj->view_mod = false;
		}
		// Check if current user can view own grades (is user)
		if (has_capability('mod/swf:viewowngrades', $context))
		{
			$obj->view_own_grades = true;
		} else {
			$obj->view_own_grades = false;
		}
		// Check if current user can view all grades (is teacher)
		if (has_capability('mod/swf:viewallgrades', $context))
		{
			$obj->view_all_grades = true;
		} else {
			$obj->view_all_grades = false;
		}
		// Add on context object
		$obj->context = $context;
		return $obj;
	}
	
	public function __destruct()
	{
		// Do nothing here.
	}
}
