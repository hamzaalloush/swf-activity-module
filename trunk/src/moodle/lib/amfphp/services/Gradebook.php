<?php // $Id: view.php,v 0.2 2009/02/21 matbury Exp $
/**
 * This service handles grades for SWF Activity Module
 *
 * @author Matt Bury - matbury@gmail.com
 * @version $Id: view.php,v 0.2 2009/02/21 matbury Exp $
 * @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
 * 
 **/
 
/**    Copyright (C) 2009  Matt Bury
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

class Gradebook
{
	private $mdl;
	
	public function __construct()
	{
		// create moodle authentication and user variables object
		require('MDL.php');
		$this->mdl = new MDL();
	}
	/**
	* Get all swf_grades records by course
	* @param int course
	* @return array
	*/
	public function get_grades_by_course($course)
	{
		if ($this->mdl->logged_in){
			$records = get_records('swf_grades','course',$course);
		}
		return $records;
	}
	/**
	* Get all swf records by course
	* @param int course
	* @return array
	*/
	public function get_swfs_by_course($course)
	{
		if ($this->mdl->logged_in){
			$records = get_records('swf','course',$course);
		}
		return $records;
	}
	/**
	* Get all swf_grades records by user
	* @param int userid
	* @return array
	*/
	public function get_grades_by_user($userid)
	{
		if ($this->mdl->logged_in){
			$records = get_records('swf_grades','userid',$userid);
		}
		return $records;
	}
	/**
	* Get all swf_grades records by user and course
	* @param int userid
	* @param int course
	* @return array
	*/
	public function get_grades_by_user_and_course($userid,$course)
	{
		if ($this->mdl->logged_in){
			$table = $this->mdl->prefix.'swf_grades';
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
	public function get_user($userid)
	{
		if ($this->mdl->logged_in){
			$table = $this->mdl->prefix.'user';
			$record = get_record('user','id',$userid);
			// Filter out sensitive data such as password and user name
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
	* Get user's permissions
	* @param int userid
	* @return string
	*/
	public function get_user_permissions()
	{
		if ($this->mdl->logged_in){
			$context = get_context_instance(CONTEXT_MODULE, 68);
			$user_permissions = has_capability('mod/swf:view', $context);
		}
		return $context;
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