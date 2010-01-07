<?php
/** This service handles user info for SWF Activity Module
* 
*
* @author Matt Bury - matbury@gmail.com
* @version $Id: view.php,v 0.1 2009/02/21 matbury Exp $
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
*/

class Users
{
	private $access;
	private $groups;
	
	public function __construct()
	{
		// Authenticate and check current user's capabilities
		require_once('Access.php');
		$this->access = new Access();
		// Groups API
		require_once('Groups.php');
		$this->groups = new Groups();
	}
	
	// ------------------------------------------------------------ PUBLIC FUNCTIONS ------------------------------------------------------------ //
	/** Get user by ID
	* 
	* @param obj
	* @return obj
	*/
	public function get_user($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		$obj['userId'] = 3;
		//
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_own_grades){
			//
			$record = get_record('user','id',$obj['userId']);
			// Only send safe data, i.e. no passwords, user names, etc.
			if($record) {
				$result = $this->convert_record_to_object($record);
				return $result;
			}
		}
		return false;
	}
	
	/** Get current user by ID
	* 
	* @param obj
	* @return obj
	*/
	public function get_self($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		//
		global $USER;
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_own_grades){
			$record = get_record('user','id',$USER->id);
			// Only send safe data, i.e. no passwords, user names, etc.
			if($record) {
				$result = $this->convert_record_to_object($record);
				return $result;
			}
		}
		return false;
	}
	
	/** Get all groups on current course
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_groups($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		//
		global $CFG;
		global $USER;
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			
			$records = user_group($capabilities->course, $USER->id);
			$result = array();
			foreach($records as $record)
			{
				$group_item = new object();
				$group_item->courseid = $record->courseid;
				$group_item->description = $record->description;
				$group_item->id = $record->id;
				$group_item->name = $record->name;
				$group_item->timecreated = $record->timecreated;
				$group_item->timemodified = $record->timemodified;
				$group_item->picture = $record->picture;
				$group_item->hidepicture = $record->hidepicture;
				array_push($result,$record);
			}
			return $result;
		}
		return false;
	}
	
	/** Get all groups on current course
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_all_groups($obj) {
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		//
		global $CFG;
		global $USER;
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			//groups_get_all_groups($courseid, $userid=0, $groupingid=0, $fields='g.*')
			return groups_get_all_groups($capabilities->course, $userid=0, $groupingid=0, $fields='g.*');
		}
		return false;
	}
	
	/** Get all users in group on current course - !!!not working yet!
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_group_users($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		//
		global $CFG;
		global $USER;
		
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_own_grades){
			
			$records = user_group($capabilities->course, $USER->id);
			if(!$records){
				$g_id = 0;
			} else {
				foreach($records as $record=>$xxx) {
					$g_id = $xxx->id;
				}
			}
			//
			return $records; // returns groups
			//
			if($records)
			{
				$result = $this->convert_records_to_array($records);
				return $result;
			}
		}
		return false;
	}

	/** Get all users on current course
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_course_users($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		//
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$records = get_course_users($capabilities->course);
			if($records)
			{
				$result = $this->convert_records_to_array($records);
				return $result;
			}
		}
		return false;
	}
	
	/** Get all teachers on current course
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_course_teachers($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		//
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_own_grades)
		{
			$records = get_course_teachers($capabilities->course);
			if($records)
			{
				$result = $this->convert_records_to_array($records);
				return $result;
			}
		}
		return false;
	}
	
	// ------------------------------------------------------------ PRIVATE FUNCTIONS ------------------------------------------------------------ //
	/** Convert single DB user record to an object that's safe to send client-side
	* 
	* @param obj
	* @return array
	*/
	private function convert_record_to_object($record)
	{
		global $CFG;
		$result = new object();
		$result->firstname = $record->firstname;
		$result->lastname = $record->lastname;
		$result->fullname = $record->firstname.' '.$record->lastname; // more convenient way to access it
		$result->city = $record->city;
		$result->country = $record->country;
		$result->email = $record->email;
		$result->skype = $record->skype;
		$result->msn = $record->msn;
		$result->yahoo = $record->yahoo;
		$result->aim = $record->aim;
		$result->phone1 = $record->phone1;
		$result->phone2 = $record->phone2;
		$result->url = $records->url;
		$result->timezone = $record->timezone;
		$result->avatar = $CFG->wwwroot.'/user/pix.php/'.$record->id.'/f2.jpg'; // path to small user pic
		$result->picture = $CFG->wwwroot.'/user/pix.php/'.$record->id.'/f1.jpg'; // path to large user pic
		return $result;
	}
	
	/** Convert DB user records to array that's safe to send client-side
	* 
	* @param obj
	* @return array
	*/
	private function convert_records_to_array($records)
	{
		global $CFG;
		$result = array();
		foreach($records as $record)
		{
				$userData = new object();
				$userData->firstname = $record->firstname;
				$userData->lastname = $record->lastname;
				$userData->fullname = $record->firstname.' '.$record->lastname; // more convenient way to access it
				$userData->city = $record->city;
				$userData->country = $record->country;
				$userData->email = $record->email;
				$userData->skype = $record->skype;
				$userData->msn = $record->msn;
				$userData->yahoo = $record->yahoo;
				$userData->aim = $record->aim;
				$userData->phone1 = $record->phone1;
				$userData->phone2 = $record->phone2;
				$userData->url = $records->url;
				$userData->timezone = $record->timezone;
				$userData->avatar = $CFG->wwwroot.'/user/pix.php/'.$record->id.'/f2.jpg'; // path to small user pic
				$userData->picture = $CFG->wwwroot.'/user/pix.php/'.$record->id.'/f1.jpg'; // path to large user pic
				array_push($result,$userData);
		}
		return $result;
	}
	// -----------------------------------------------------------------------------------------------------------
	
	/** Clean up objects and variables for garbage collector
	* 
	*/
	public function __destruct()
	{
		unset($access);
	}
}
// No, I haven't forgotten the closing PHP tag. It's not necessary here.