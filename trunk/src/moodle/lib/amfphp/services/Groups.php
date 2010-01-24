<?php
/** This service calls functions of lib/gradelib.php API
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

class Groups
{
	private $access;
	private $obj;
	
	public function __construct()
	{
		// Authenticate and check current user's capabilities
		require_once('Access.php');
		$this->access = new Access();
		// For testing in AMFPHP browser
		$this->obj['instance'] = 71;
		$this->obj['swfId'] = 4;
		$this->obj['groupId'] = 2;
		$this->obj['groupingId'] = 0;
		$this->obj['groupName'] = 'Matt\'s Group';
		$this->obj['groupingName'] = 'Matt\'s Grouping';
		$this->obj['userId'] = 2;
	}
	
	
	// ------------------------------------------------------------ PUBLIC FUNCTIONS ------------------------------------------------------------ //
	/** Check if a group exists
	* 
	* @param instance
	* @param swfId
	* @return boolean
	*/
	public function group_exists($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			return groups_group_exists($this->obj['groupId']);
		}
		return 'error';
	}
	
	/** Get name of a group
	* 
	* @param instance
	* @param swfId
	* @return string
	*/
	public function get_group_name($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			return groups_get_group_name($this->obj['groupId']);
		}
		return false;
	}
	
	/** Get name of a grouping
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_grouping_name($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			return groups_get_grouping_name($this->obj['groupingId']);
		}
		return false;
	}
	
	/** Get a group by name
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_group_by_name($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			return groups_get_group_by_name($capabilities->course, $this->obj['groupName']);
		}
		return false;
	}
	
	/** Get a grouping by name
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_grouping_by_name($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			return groups_get_grouping_by_name($capabilities->course, $this->obj['groupingName']);
		}
		return false;
	}
	
	/** Get a group
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_group($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$record = groups_get_group($this->obj['groupId']);
			if($record)
			{
				$result = $this->convert_record_to_array($record);
				return $result;
			}
		}
		return false;
	}
	
	/** Get a grouping
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_grouping($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$record = groups_get_grouping($this->obj['groupingId']);
			if($record)
			{
				$result = $this->convert_record_to_array($record);
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
	public function get_all_groups($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			//groups_get_all_groups($courseid, $userid=0, $groupingid=0, $fields='g.*')
			$records = groups_get_all_groups($capabilities->course,/*$this->obj['userId'],*/$this->obj['groupingId']);
			if($records)
			{
				$result = $this->convert_records_to_array($records);
				return $result;
			}
		}
		return false;
	}
	
	/** Get user groups
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_user_groups($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$records = groups_get_user_groups($this->obj['groupingId'],$this->obj['userId']);
			if($records)
			{
				$result = $this->convert_records_to_array($records);
				return $result;
			}
		}
		return false;
	}
	
	/** Get all groupings
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_all_groupings($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$records = groups_get_all_groupings($capabilities->course);
			if($records)
			{
				$result = $this->convert_records_to_array($records);
				return $result;
			}
		}
		return false;
	}
	
	/** Check if user is a member of a group
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function is_member($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			return groups_is_member($this->obj['groupId'],$this->obj['userId']);
		}
		return false;
	}
	
	/** Check if user is member of any group
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function has_membership($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$cm = new object();
			$cm->groupingid = $this->obj['groupingId'];
			$cm->course = $capabilities->course;
			return groups_has_membership($cm, $this->obj['userId']);
		}
		return false;
	}
	
	/** Get members of a group
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_members($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$records = groups_get_members($this->obj['groupId']);
			if($records)
			{
				$result = $this->convert_user_records_to_array($records);
				return $result;
			}
		}
		return false;
	}
	
	/** Get members of a grouping
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_grouping_members($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$records = groups_get_grouping_members($this->obj['groupingId']);
			if($records)
			{
				$result = $this->convert_user_records_to_array($records);
				return $result;
			}
		}
		return false;
	}
	
	/** Get course group mode
	* 
	* @param instance
	* @param swfId
	* @return int
	*/
	public function get_course_groupmode($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			global $COURSE;
			//return groups_get_course_groupmode($COURSE);
			return $COURSE->groupmode;
		}
		return false;
	}
	
	/** Get activity group mode
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_activity_groupmode($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			global $COURSE;
			$cm = new object();
			$cm->course = $capabilities->course;
			return groups_get_activity_groupmode($cm,$COURSE);
		}
		return false;
	}
	
	/** Not tested yet!
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function print_course_menu($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			global $CFG;
			global $COURSE;
			$urlroot = $CFG->wwwroot; // not sure what this should be
			return groups_print_course_menu($COURSE, $urlroot);
		}
		return false;
	}
	
	/** Not tested yet!
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function print_activity_menu($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			global $CFG;
			global $COURSE;
			$cm = new object();
			$cm->groupingid = $this->obj['groupingId'];
			$cm->course = $capabilities->course;
			$cm->id = $this->obj['instance'];
			$urlroot = $CFG->wwwroot; // not sure what this should be
			return groups_print_activity_menu($cm, $urlroot);
		}
		return false;
	}
	
	/** Get group for an activity module instance
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_activity_group($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$cm = new object();
			$cm->groupingid = $this->obj['groupingId'];
			$cm->course = $capabilities->course;
			$cm->id = $this->obj['instance'];
			return groups_get_activity_group($cm);
		}
		return false;
	}
	
	/** 
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function get_activity_allowed_groups($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$cm = new object();
			$cm->groupingid = $this->obj['groupingId'];
			$cm->course = $capabilities->course;
			$cm->id = $this->obj['instance'];
			return groups_get_activity_allowed_groups($cm);
		}
		return false;
	}
	
	/** Not fully tested yet but seems to work
	* 
	* @param instance
	* @param swfId
	* @return array
	*/
	public function course_module_visible($obj) {
		//
		$capabilities = $this->access->get_capabilities($this->obj['instance'],$this->obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			//
			$cm = new object();
			$cm->groupingid = $this->obj['groupingId'];
			$cm->course = $capabilities->course;
			$cm->id = $this->obj['instance'];
			//$cm->groupmembersonly = ??;
			return groups_course_module_visible($cm);
		}
		return false;
	}
	
	// ------------------------------------------------------------ PRIVATE FUNCTIONS ------------------------------------------------------------ //
	/** Convert DB user record to object that's safe to send client-side
	* 
	* @param obj
	* @return array
	*/
	private function convert_record_to_array($record)
	{
		$result = new object();
		$result->courseid = $record->courseid;
		$result->description = $record->description;
		$result->id = $record->id;
		$result->name = $record->name;
		$result->timecreated = $record->timecreated;
		$result->timemodified = $record->timemodified;
		$result->picture = $record->picture;
		$result->hidepicture = $record->hidepicture;
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
	
	/** Convert DB user records to array that's safe to send client-side
	* 
	* @param obj
	* @return array
	*/
	private function convert_user_records_to_array($records)
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