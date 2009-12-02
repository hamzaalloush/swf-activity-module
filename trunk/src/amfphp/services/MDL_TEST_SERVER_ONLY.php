<?php
// author: Matt Bury, http://matbury.com, matbury@gmail.com
// MDL class provides a single object to provide necessary Moodle data for Flash remoting

/*
WARNING!!! - DO NOT PUT THIS CLASS ON A PUBLIC SERVER OR ANYWHERE WHERE IT IS ACCESSIBLE TO ANYONE EXCEPT THE DEVELOPER
This service class exposes all sensitive data related to a Moodle installation including passwords, user names, etc. In
other words, use this class on your devlopment server ONLY.

Please note: this is an experimental class. I hope that eventually a new module will evolve
to make deploying Flash e-learning resources and recording data from them in Moodle much
simpler and more dynamic.
*/
 
class MDL
{
	public $logged_in; // boolean
	// course - these only work in Moodle 1.8
	public $course; // object
	public $course_id; // int
	public $course_fullname; // string
	public $course_shortname; // string
	// user
	public $user_id; // int
	public $user_username; // string
	public $user_firstname; // string
	public $user_lastname; // string
	// moodle
	public $prefix; // moodle DB table prefix (mdl_)
	
	public function __construct()
	{
		global $CFG;
		require_once('../../../config.php');
		if (isguestuser() || isloggedin()){
            $this->logged_in = true;
			global $USER;
			global $SESSION;
			global $COURSE;
			// set variables
			// For the moment, course ID is passed to SWF app. via FlashVars
			$this->course_id = $SESSION->cal_course_referer; // Works in Moodle 1.8, doesn't work in Moodle 1.9
			$this->course = get_record('course', 'id', $this->course_id);
			$this->course_fullname = $coursename = filter_text(get_field('course', 'fullname', 'id', $this->course_id));
			$this->course_shortname = $coursename = filter_text(get_field('course', 'shortname', 'id', $this->course_id));
			// user
			$this->user_id = $USER->id;
			$this->user_username = $USER->username;
			$this->user_firstname = $USER->firstname;
			$this->user_lastname = $USER->lastname;
			// moodle
			$this->data_path = $CFG->wwwroot . '/file.php/' . $this->course_id . '/';
			$this->prefix = $CFG->prefix;
        } else {
            $this->logged_in = false;
        }
	}
	// ################################### COURSE PARAMETERS ###################################
	/**
	*gets current user's course record
	*@returns object
	*/
	private function get_course()
	{
		if($this->logged_in) {
			return $this->course;
		} else {
			return false;
		}
	}
	/**
	*gets current user's course id
	*@returns int - only in Moodle 1.8
	*/
	public function get_course_id()
	{
		if($this->logged_in) {
			return $this->course_id;
		} else {
			return false;
		}
	}
	/**
	*gets current user's course full name
	*@returns string
	*/
	public function get_course_fullname()
	{
		if($this->logged_in) {
			return $this->course_fullname;
		} else {
			return false;
		}
	}
	/**
	*gets current user's course short name
	*@returns string
	*/
	public function get_course_shortname()
	{
		if($this->logged_in) {
			return $this->course_shortname;
		} else {
			return false;
		}
	}
	// #################################### USER PARAMETERS ####################################
	/**
	*gets current user's id
	*@returns int
	*/
	public function get_user_id()
	{
		if($this->logged_in) {
			return $this->user_id;
		} else {
			return false;
		}
	}
	/**
	*gets current user's username
	*@returns string
	*/
	public function get_username()
	{
		if($this->logged_in) {
			return $this->user_username;
		} else {
			return false;
		}
	}
	/**
	*gets current user's first name
	*@returns string
	*/
	public function get_user_firstname()
	{
		if($this->logged_in) {
			return $this->user_firstname;
		} else {
			return false;
		}
	}
	/**
	*gets current user's last name
	*@returns string
	*/
	public function get_user_lastname()
	{
		if($this->logged_in) {
			return $this->user_lastname;
		} else {
			return false;
		}
	}
	/*
	######################################## MOODLE OBJECTS ########################################
	*/
	/**
	*gets current user's last name
	*@returns string
	*/
	public function get_prefix()
	{
		if($this->logged_in) {
			return $this->prefix;
		} else {
			return false;
		}
	}
	/**
	*gets Moodle $COURSE object
	*@returns object
	*/
	public function get_course_object()
	{
		if($this->logged_in) {
			global $COURSE;
			return $COURSE;
		} else {
			return false;
		}
	}
	/**
	*gets Moodle $USER object
	*@returns object
	*/
	public function get_user_object()
	{
		if($this->logged_in) {
			global $USER;
			return $USER;
		} else {
			return false;
		}
	}
	/**
	*gets Moodle $SESSION object
	*@returns object
	*/
	public function get_session_object()
	{
		if($this->logged_in) {
			global $SESSION;
			return $SESSION;
		} else {
			return false;
		}
	}
	/**
	*gets Moodle $CFG object
	*@returns object
	*/
	public function get_cfg_object()
	{
		if($this->logged_in) {
			global $CFG;
			return $CFG;
		} else {
			return false;
		}
	}
	/**
	*cleans up variables for garbage collector
	*@returns nothing
	*/
	public function __destruct()
	{
		unset($this->logged_in);
		unset($this->mdl_course_id);
		unset($this->mdl_user_id);
		unset($this->mdl_data_path);
	}
}
// No, I haven't forgotten the closing PHP tag! It's no longer recommended practice for classes.