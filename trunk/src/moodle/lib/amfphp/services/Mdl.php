<?php
// author: Matt Bury, http://matbury.com, matbury@gmail.com
// MDL class provides a single object to provide necessary Moodle data for Flash remoting

/*
Please note: this is an experimental class. I hope that eventually a new module will evolve
to make deploying Flash e-learning resources and recording data from them in Moodle much
simpler and more dynamic.
*/
 
class Mdl
{
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
			global $USER;
			global $SESSION;
			global $COURSE;
			// set variables
			$this->user_id = $USER->id;
			$this->user_username = $USER->username;
			$this->user_firstname = $USER->firstname;
			$this->user_lastname = $USER->lastname;
			$this->prefix = $CFG->prefix;
        }
	}
	/**
	*cleans up variables for garbage collector
	*@returns nothing
	*/
	public function __destruct()
	{
		unset($this->user_id);
		unset($this->user_username);
		unset($this->user_firstname);
		unset($this->user_lastname);
		unset($this->prefix);
	}
}
// No, I haven't forgotten the closing PHP tag! It's no longer recommended practice for classes.