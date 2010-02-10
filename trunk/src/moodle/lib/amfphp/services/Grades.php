<?php
/** This service handles grades for SWF Activity Module
* 
*
* @author Matt Bury - matbury@gmail.com
* @version $Id: view.php,v 0.2 2009/02/21 matbury Exp $
* @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
* Copyright (C) 2009  Matt Bury
*
* This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.

All methods in this service class are course-wide only. 

Method List

public methods:
	amf_ping() // returns string 'Ping!' to check if connection was successful
	
	amf_grade_update() // creates or updates grade in grade_grades DB table
*/

class Grades
{
	private $access;
	private $users;
	
	public function __construct()
	{
		// Check current user's capabilities
		require_once('Access.php');
		$this->access = new Access();
		// Get user's profile details
		require_once('Users.php');
		$this->users = new Users();
	}
	
	// ------------------------------------------------------------ PUBLIC FUNCTION ------------------------------------------------------------ //
	/** Test gateway - a simple 'ping' function to test that NetConnection.connect() has been correctly established
	* 
	* @return boolean
	*/
	public function amf_ping($obj = null)
	{
		return 'Ping!';
	}
	
	/**
	* 
	* @param obj->instance int course instance ID
	* @param obj->swfid int swf instance ID
	* @param obj->feedback int/text feedback for user either elapsed time in seconds or text
	* @param obj->feedbackformat int format of feedback
	* @param obj->rawgrade int grade before calculation as a percentage, scale, etc.
	* @return array of grade information objects (scaleid, name, grade and locked status, etc.) indexed with itemnumbers
	*/
	public function amf_grade_update($obj)
	{
		// Dummy values for testing in service browser
		// Get the instance and swfid values from any instance of a SWF Activity Module
		// and these values will work
		/*
		$obj['instance'] = 3; // Course module ID, i.e. mod/swf/view.php?id=3
		$obj['swfid'] = 2; // SWF Activity Module ID
		$obj['feedback'] = 'This is some sample feedback and here\'s a link: http://blog.matbury.com/';
		$obj['feedbackformat'] = rand(29,3800); // time elapsed
		$obj['rawgrade'] = rand(0,100);
		*/
		
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfid']);
		// If there was a problem with authentication, return the error message
		if(!empty($capabilities->error))
		{
			return $capabilities->error;
		}
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_own_grades)
		{
			global $CFG;
			global $USER;
			
			require_once($CFG->libdir.'/gradelib.php');
			
			// Create grade object to pass in grade data
			$grade = new stdClass();
			$grade->swfid = $obj['swfid'];
			$grade->feedback = $obj['feedback'];
			$grade->feedbackformat = $obj['feedbackformat'];
			$grade->rawgrade = $obj['rawgrade'];
			$grade->timemodified = time();
			$grade->userid = $USER->id;
			$grade->usermodified = $USER->id;
			
			// Get grade item for grademax and grademin values
			// If SWF mod instance gradetype is set to none, no grade item is created
			if(!$record = get_record('grade_items','iteminstance',$grade->swfid))
			{
				// Send error string back to Flash client
				return 'Grade item '.$grade->swfid.' does not exist.';
			}
			
			// Set grade min and max values
			$grade->rawgrademax = $record->grademax;
			$grade->rawgrademin = $record->grademin;
			
			// Set upper time limit of 24 hours
			// Assume that Flash app. sent time in milliseconds if it's higher and divide by 1000
			$swf_maxtime = 86399;
			if($grade->feedbackformat > $swf_maxtime)
			{
				$grade->feedbackformat = round($grade->feedbackformat / 1000);
			}
			
			// Insert or update grade
			grade_update('mod/swf', $capabilities->course, 'mod', 'swf', $grade->swfid, 0, $grade, NULL);
			
			// Return updated grade item
			$result = grade_get_grades($capabilities->course, 'mod', 'swf', $grade->swfid, $grade->userid);
			// Only return the single grade object - simpler for Flash apps. to handle
			return $result->items[0]->grades[$grade->userid];
		}
	}
	
	/**
	* Returns grade for the specified grade_item and user
	* @param obj->instance int course instance ID
	* @param obj->swfid int swf module ID
	* @param obj->userid int (optional) specify ID of user if teacher or admin
	* @return array of grade information objects (scaleid, name, grade and locked status, etc.) indexed with itemnumbers
	*/
	public function amf_grade_get_grades($obj)
	{
		// Dummy values for testing in service browser
		/*
		$obj['instance'] = 3;
		$obj['swfid'] = 2;
		$obj['userid'] = 2;
		*/
		
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfid']);
		// If there was a problem with authentication, return the error message
		if(!empty($capabilities->error))
		{
			return $capabilities->error;
		}
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_own_grades)
		{
			global $CFG;
			global $USER;
			
			require_once($CFG->libdir.'/gradelib.php');
			
			// Only users with capabilties can view other users' grades
			if($capabilities->view_all_grades)
			{
				$userid = $obj['userid'];
			} else {
				$userid = $USER->id;
			}
			
			$result = grade_get_grades($capabilities->course, 'mod', 'swf', $obj['swfid'], $userid);
			// Only return the single grade object
			return $result->items[0]->grades[$userid];
		}
	}
	
	// -----------------------------------------------------------------------------------------------------------
	
	/**
	* Clean up objects and variables for garbage collector
	*
	*/
	public function __destruct()
	{
		unset($access);
	}
}
// No, I haven't forgotten the closing PHP tag. It's not necessary here.