<?php
/*
This is an original test service provided by Jamie Pratt
On Moodle.org
I've updated it for PHP 5.
*/
class UserName
{
	public function __construct()
	{
		
	}
	/**
	*checks whether you're logged in to Moodle
	*@returns user login status
	*/
    public function loggedInAs() {
        global $USER;
        if (isguestuser()){
            return get_string('loggedinasguest', 'moodle');
        }else if (isloggedin()){
            return get_string('loggedinas', 'moodle', fullname($USER));
        } else {
            return get_string('loggedinnot');
        }
    }
}
?>
