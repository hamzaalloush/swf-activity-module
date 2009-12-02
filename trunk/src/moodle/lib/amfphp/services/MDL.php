<?php
// author: Matt Bury, http://matbury.com, matbury@gmail.com
// MDL class provides a single object to provide necessary Moodle data for Flash remoting

/*
Please note: this is an experimental class. I hope that eventually a new module will evolve
to make deploying Flash e-learning resources and recording data from them in Moodle much
simpler and more dynamic.
*/
 
class MDL
{
	public $logged_in; // boolean
	// course - $COURSE object is not working
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
	public $data_path; // path to file.php - passed in through FlashVars, to be deprecated
	
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
			//
			$this->user_id = $USER->id;
			$this->user_username = $USER->username;
			$this->user_firstname = $USER->firstname;
			$this->user_lastname = $USER->lastname;
			//
			$this->data_path = $CFG->wwwroot . '/file.php/' . $this->course_id . '/'; // Already passed in through FlashVars to be deprecated
			$this->prefix = $CFG->prefix;
        } else {
            $this->logged_in = false;
        }
	}
	/**
	*Check if user is logged in
	*@returns boolean
	*/
	public function is_logged_in()
	{
		if($this->logged_in)
		{
			return true;
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