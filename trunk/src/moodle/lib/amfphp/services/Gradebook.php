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
	test_gateway() // returns boolean value true to check if connection was successful
	
	get_course_grade_items() // returns list of SWF Activity Module instances that are gradeable - raw data
	get_course_grades() // returns list of all user grades on current course - raw data
	get_course_grades_grid() // returns 2D array of course grades by user (rows) and grade items (columns) with calculated grade statistics
	get_course_user_grade_item_grades() // returns list of user grades for a specified grade item, i.e. single SWF Activity Module instance
	get_course_user_grades() // returns 2D array of course user grades (rows) and grade items (columns)

	insert_course_user_grade() // ronseal
	update_course_user_grade() // ronseal
	delete_course_user_grade() // ronseal

private methods:
	convert_records_to_array()
	calculate_grades_statistics()

*/

class Gradebook
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
	
	// ------------------------------------------------------------ PUBLIC FUNCTIONS ------------------------------------------------------------ //
	/** Test gateway - a simple 'ping' function to test that NetConnection.connect() has been correctly established
	* 
	* @return boolean
	*/
	public function test_gateway($obj = null)
	{
		return true;
	}
	
	/**
	* Gets all SWF Activity Module instances for current course
	* @param obj
	* @return obj
	*/
	public function get_course_grade_items($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_all_grades)
		{
			$records = get_records('swf_grade_items','course',$capabilities->course);
			$result = $this->convert_records_to_array($records);
			// Add human readable dates to each item
			$len = count($result);
			for($i = 0; $i < $len; $i++)
			{
				$result[$i]->datecreated = date('D d M Y',$result[$i]->timecreated);
				$result[$i]->datemodified = date('D d M Y',$result[$i]->timemodified);
			}
			return $result;
		}
		return false;
	}
	
	/**
	* Gets all grades for all SWF Activity Module instances on current course
	* @param obj
	* @return obj
	*/
	public function get_course_grades($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_all_grades)
		{
			$records = get_records('swf_grades','course',$capabilities->course);
			$result = $this->convert_records_to_array($records);
			return $result;
		}
		return false;
	}
	
	/** Gets 2D array of course users and grade items
	* 
	* @param obj
	* @return obj
	*/
	public function get_course_grades_grid($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_all_grades)
		{
			// users (rows)
			$course_users = $this->users->get_course_users($obj);
			$users_len = count($course_users);
			// grade items (columns)
			$course_grade_items = $this->get_course_grade_items($obj);
			$items_len = count($course_grade_items);
			// push grade items into users
			for($i = 0; $i < $users_len; $i++) // rows
			{
				$course_users[$i]->course_grade_items = array();
				for($j = 0; $j < $items_len; $j++) // columns
				{
					array_push($course_users[$i]->course_grade_items,$course_grade_items[$j]);
				}
			}
			return $course_users;
		}
		return false;
	}
	
	/** Gets user's grades for particular grade item the calculates min, max and average values for grade and time
	* 
	* @param obj
	* @return obj
	*/
	public function get_course_user_grade_item_grades($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		$obj['userId'] = 2;
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_own_grades)
		{
			global $CFG;
			global $USER;
			$swfid = $obj['swfId'];
			if($capabilities->view_all_grades)
			{
				$userid = $obj['userId']; // Can view all grades
			} else {
				$userid = $USER->id; // Can only view own grades
			}
			$table = $CFG->prefix.'swf_grades';
			$sql = "SELECT * FROM `$table` WHERE `userid`=$userid AND `swfid`=$swfid AND `course`=$capabilities->course ";
			$records = get_records_sql($sql);
			
			// Convert it to an array
			$result->user_grades = $this->convert_records_to_array($records);
			
			// Calculate grades summary
			if($result = $this->calculate_grades_statistics($result))
			{
				return $result;
			}
		}
		return false;
	}
	
	/** Gets current user's grades for all SWF Activity Module instances on current course.
	* If user doesn't have capability to view other users' grades, it defaults to their own.
	* 
	* @param obj
	* @return object
	*/
	public function get_course_user_grades($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		$obj['userId'] = 2;
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure user has permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_own_grades)
		{
			global $CFG;
			global $USER;
			if($capabilities->view_all_grades)
			{
				$userid = $obj['userId']; // Can view all grades
			} else {
				$userid = $USER->id; // Can only view own grades
			}
			$table = $CFG->prefix.'swf_grades';
			$sql = "SELECT * FROM `$table` WHERE `course`=$capabilities->course AND `userid`=$userid";
			$records = get_records_sql($sql);
			$result->user_grades = $this->convert_records_to_array($records);
			if($result = $this->calculate_grades_statistics($result))
			{
				return $result;
			}
		}
		return false;
	}
	
	/**
	* Inserts record into swf_grades of current user on current course
	* 
	* @param obj
	* @return object
	*/
	public function insert_course_user_grade($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		$obj['grade'] = 69;
		$obj['finished'] = 1;
		$obj['startTime'] = time();
		$obj['answerData'] = '99,55,34,21,90,87,76,56,54,34,12';
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->view_own_grades)
		{
			global $USER;
			// construct record object
			$record = new object();
			$record->userid = $USER->id;
			$record->course = $capabilities->course;
			$record->instance = $obj['instance'];
			$record->swfid = $obj['swfId'];
			$record->grade = $obj['grade'];
			$record->finished = $obj['finished'];
			$record->starttime = $obj['startTime'];
			$time = time();
			$record->endtime = $time;
			$record->timecreated = $time;
			$record->timemodified = $time;
			// http://docs.moodle.org/en/Development:Output_functions#format_string.28.29
			// function format_string ($string, $striplinks = true, $courseid=NULL )
			$record->answerdata = format_string($obj['answerData']);
			$result = insert_record('swf_grades',$record);
			return $result;
		}
		return false;
	}
	
	/**
	* Updates swf_grades record for specified grade on current course
	* Use for manually entering tutor feedback and grades in grade book 
	* 
	* @param obj
	* @return object
	*/
	public function update_course_user_grade($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		$obj['gradeId'] = 1;
		$obj['grade'] = 69;
		$obj['feedback'] = 'This is some feedback for a particular student for a particular activity.';
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->edit_grades)
		{
			global $USER;
			$record->id = $obj['gradeId'];
			$record->grade = $obj['grade'];
			// http://docs.moodle.org/en/Development:Output_functions#format_string.28.29
			// function format_text($text, $format=FORMAT_MOODLE, $options=NULL, $courseid=NULL )
			$record->feedback = format_text($obj['feedback'],FORMAT_PLAIN);
			$obj['feedbacklink'] = 'http://localhost/file.php/'.$capabilities->course.'/feedback/page_01.html';
			$record->feedback = format_string($obj['feedbacklink']);
			$result = update_record('swf_grades', $record);
			return $result;
		}
		return false;
	}
	
	/**
	* Gets all grades for all SWF Activity Module instances on current course
	* 
	* @param obj
	* @return object
	*/
	public function delete_course_user_grade($obj)
	{
		$obj['instance'] = 71;
		$obj['swfId'] = 4;
		$obj['gradeId'] = 1;
		// Get current user's capabilities
		$capabilities = $this->access->get_capabilities($obj['instance'],$obj['swfId']);
		// Make sure they have permission to call this function
		if ($capabilities->is_logged_in && $capabilities->edit_grades)
		{
			$result = delete_records('swf_grades','id',$obj['gradeId'],'course',$capabilities->course);
			return $result;
		}
		return false;
	}
	
	// ------------------------------------------------------------ PRIVATE FUNCTIONS ------------------------------------------------------------ //
	/**
	* Gets current user's grades for all SWF Activity Module instances on current course
	* @param object $records
	* @return array
	*/
	private function convert_records_to_array($records)
	{
		$result = array();
		foreach($records as $record)
		{
			array_push($result,$record);
		}
		return $result;
	}
	
	/** Calculates statistics for highest, lowest and average grades and times
	* 
	* @param object $records
	* @return array
	*/
	private function calculate_grades_statistics($result)
	{
		// Make sure there's some grades to calculate
		$result->attempts = count($result->user_grades);
		if($result->attempts > 0)
		{
			// Zero the grade statistics defaults
			$result->passed = 0;
			$result->failed = 0;
			$result->finished = 0;
			$result->unfinished = 0;
			$result->gradestotal = 0;
			$result->gradehighest = 0;
			$result->gradelowest = 1000;
			$result->timetotal = 0;
			$result->timelongest = 0;
			$result->timeshortest = 10000;
			//
			for($i = 0; $i < $result->attempts; $i++)
			{
			// Grades
				// sum all the grades
				$result->gradestotal += $result->user_grades[$i]->grade; 
				// find highest grade
				if($result->user_grades[$i]->grade > $result->gradehighest)
				{
					$result->gradehighest = $result->user_grades[$i]->grade; 
				}
				// find lowest grade
				if($result->user_grades[$i]->grade < $result->gradelowest)
				{
					$result->gradelowest = $result->user_grades[$i]->grade; 
				}
				// sum the passed and failed attempts
				if($result->user_grades[$i]->grade > $result->user_grades[$i]->passgrade)
				{
					$result->passed += 1; 
					$result->user_grades[$i]->passed = true;
				} else {
					$result->failed += 1;
					$result->user_grades[$i]->passed = false;
				}
				// sum the unfinished attempts
				if($result->user_grades[$i]->finished == 1)
				{
					$result->finished += 1; 
				} else {
					$result->unfinished += 1;
				}
			// Time
				// sum the total time spent
				$timeelapsed = ($result->user_grades[$i]->endtime - $result->user_grades[$i]->starttime);
				// add elapsed time values to each grade
				$result->user_grades[$i]->timeelapsed = $timeelapsed;
				// sum the total time
				$result->timetotal += $timeelapsed;
				// find longest time
				if($timeelapsed > $result->timelongest)
				{
					$result->timelongest = $timeelapsed; 
				}
				// find shortest time
				if($timeelapsed < $result->timeshortest)
				{
					$result->timeshortest = $timeelapsed; 
				}
				// change numbers to human readable dates & times - Check these with php.net reference as soon as possible_____________________________!!!
				$result->user_grades[$i]->timetaken = gmdate('h:m:s',$result->user_grades[$i]->starttime);
				$result->user_grades[$i]->datetaken = date('D d M Y',$result->user_grades[$i]->starttime);
				$result->user_grades[$i]->timeupdated = gmdate('h:m:s',$result->user_grades[$i]->endtime);
				$result->user_grades[$i]->dateupdated = date('D d M Y',$result->user_grades[$i]->endtime);
			}
		// calculate average grade
		$result->gradeaverage = $result->gradestotal / $result->attempts;
		// calculate average grade
		$result->timeaverage = $result->timetotal / $result->attempts;
		return $result;
		}
		return false;
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