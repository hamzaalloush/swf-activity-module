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

class GlobalObjects
{
	private $access;
	
	public function __construct()
	{
		// Check current user's capabilities
		require_once('Access.php');
		$this->access = new Access();
	}
	
	/**
	* Get $CFG object
	* @param int instance
	* @param int swfId
	* @return obj
	*/
	public function get_cfg($instance,$swfId)
	{
		global $CFG;
		$capabilities = $this->access->get_capabilities($instance,$swfId); //($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			return $CFG;
		}
		return false;
	}
	
	/**
	* Get $COURSE object
	* @param int instance
	* @param int swfId
	* @return obj
	*/
	public function get_course($instance,$swfId)
	{
		global $CFG;
		$capabilities = $this->access->get_capabilities($instance,$swfId); //($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			global $COURSE;
			return $COURSE;
		}
		return false;
	}

	/**
	* Get $USER object
	* @param int instance
	* @param int swfId
	* @return obj
	*/
	public function get_user($instance,$swfId)
	{
		global $CFG;
		$capabilities = $this->access->get_capabilities($instance,$swfId); //($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			global $USER;
			return $USER;
		}
		return false;
	}

	/**
	* Get $SESSION object
	* @param int instance
	* @param int swfId
	* @return obj
	*/
	public function get_session($instance,$swfId)
	{
		global $CFG;
		$capabilities = $this->access->get_capabilities($instance,$swfId); //($obj['instance'],$obj['swfId']);
		if ($capabilities->is_logged_in && $capabilities->view_all_grades){
			global $SESSION;
			return $SESSION;
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