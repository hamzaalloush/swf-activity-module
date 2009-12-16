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
*/

class Gradebook
{
	private $mdl;
	private $access;
	
	public function __construct()
	{
		// Check current user's capabilities
		require_once('Access.php');
		$this->access = new Access();
		// Create MDL object that stores $CFG, $SESSION, $USER and $COURSE object data
		require('Mdl.php');
		$this->mdl = new Mdl();
	}
	/**
	* Test gateway
	* @return boolean
	*/
	public function test_gateway($obj = null)
	{
		return true;
	}
	/**
	* Get all swf_grades records by course
	* @param obj
	* @return array
	*/
	public function get_grades_by_course($obj)
	{
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			$course = $obj['course'];
			$records = get_records('swf_grades','course',$course);
		}
		return $records;
	}
	/**
	* Get all swf records by course
	* @param int course
	* @return array
	*/
	public function get_swfs_by_course($obj)
	{
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_own_grades){
			$course = $obj['course']; // For some reason, $obj->userId doesn't work.
			$records = get_records('swf','course',$course);
		}
		return $records;
	}
	/**
	* Get all swf_grades records by user
	* @param int userid
	* @return array
	*/
	public function get_grades_by_user($obj)
	{
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			$userid = $obj['userId'];
			$records = get_records('swf_grades','userid',$userid);
		}
		return $records;
	}
	/**
	* Insert record into swf_grades
	* @param obj
	* @return boolean
	*/
	public function insert_grade($obj)
	{
		$result = false;
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		///
		if ($capabilities->is_logged_in && $capabilities->view_own_grades){
			$record = new object();
			$record->userid = $this->mdl->user_id;
			$record->course = $obj['course'];
			$record->instance = $obj['instance'];
			$record->swfid = $obj['swfId'];
			$record->grade = $obj['grade'];
			$record->maxgrade = $obj['maxGrade'];
			$record->starttime = $obj['startTime'];
			$record->endtime = time();
			$record->timecreated = time();
			$record->timemodified = time();
			$result = insert_record('swf_grades',$record);
		}
		return $result;
	}
	/**
	* Delete record in swf_grades
	* @param obj
	* @return boolean
	*/
	public function delete_grade_by_id($obj)
	{
		$result = false;
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->edit_grades){
			$id = $obj['gradeId'];
			$userid = $this->mdl->user_id;
			$result = delete_records('swf_grades', 'id', $id, 'userid', $userid);
		}
		return $result;
	}
	/**
	* Update record in swf_grades
	* @param obj
	* @return boolean
	*/
	public function update_grade_by_id($obj)
	{
		$result = false;
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->edit_grades){
			$record->id = $obj['gradeId'];
			$record->grade = $obj['grade'];
			$record->userid = $this->mdl->user_id;
			$result = update_record('swf_grades', $record);
		}
		return $result;
	}
	/**
	* Get all swf_grades records by user and course
	* @param int userid
	* @param int course
	* @return array
	*/
	public function get_grades_by_user_and_course($obj)
	{
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->edit_grades){
			$table = $this->mdl->prefix.'swf_grades';
			$userid = $obj['userId'];
			$course = $obj['course'];
			$sql = "SELECT * FROM `$table` WHERE `userid`=$userid AND `course`=$course";
			$records = get_records_sql($sql);
		}
		return $records;
	}
	/**
	* Get user by ID
	* @param int id
	* @return obj
	*/
	public function get_user($obj)
	{
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_own_grades){
			$userid = $obj['userId'];
			$table = $this->mdl->prefix.'user';
			$record = get_record('user','id',$userid);
			// Only send safe data, i.e. no passwords, user names, etc.
			if($record) {
				$user_data = new object();
				$user_data->firstname = $record->firstname;
				$user_data->lastname = $record->lastname;
				$user_data->fullname = $record->firstname.' '.$record->lastname;
				$user_data->city = $record->city;
				$user_data->country = $record->country;
				$user_data->email = $record->email;
				$user_data->skype = $record->skype;
				$user_data->msn = $record->msn;
				$user_data->yahoo = $record->yahoo;
				$user_data->aim = $record->aim;
				$user_data->phone1 = $record->phone1;
				$user_data->phone2 = $record->phone2;
				$user_data->url = $record->url;
				$user_data->timezone = $record->timezone;
			}
		}
		return $user_data;
	}
	/**
	* Clean up objects and variables for garbage collector
	*
	*/
	public function __destruct()
	{
		unset($mdl);
	}
}
// No, I haven't forgotten the closing PHP tag. It's not necessary here.