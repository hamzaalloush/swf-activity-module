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
	* @param obj
	* @return obj
	*/
	public function amf_grade_update($obj)
	{
		// Dummy values for testing in service browser
		$obj['instance'] = 3;
		$obj['swfId'] = 7;
		$obj['feedback'] = 720; // This should be the duration (e.g. 360 seconds)
		$obj['feedbackformat'] = 0;
		$obj['rawgrade'] = 100;
		
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_own_grades)
		{
			global $CFG;
			global $USER;
			
			require_once($CFG->libdir.'/gradelib.php');
			
			// Get grade item for grademax and grademin values
			// If SWF mod instance gradetype is set to none, no grade item is created
			if(!$record = get_record('grade_items','iteminstance',$obj['swfId']))
			{
				// Send error string back to Flash client
				return 'Grade item '.$obj['swfId'].' does not exist.';
			}
			
			$obj['rawgrademax'] = $record->grademax;
			$obj['rawgrademin'] = $record->grademin;
			$obj['timemodified'] = time();
			$obj['userid'] = $USER->id;
			$obj['usermodified'] = $USER->id;
			
			return grade_update('mod/swf', $capabilities->course, 'mod', 'swf', $obj['swfId'], 0, $obj, NULL);
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