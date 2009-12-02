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
	* Get grade retrieves last grade for current SWF Activity instance
	* parameter object
	* returns object
	*/
	public function getGrade($obj)
	{
		// check if grade for SWF Activity instance exists
		//$obj->instance;
		
		// If grade exists, return it
		//return $grade;
		
		// If grade doesn't exist, insert new one
		//return false;
	}
	/**
	* Set grade for current SWF Activity instance
	* parameter object
	* returns object
	*/
	public function setGrade($obj)
	{
		// check if grade for SWF Activity instance exists
		//$obj->instance;
		
		// If grade exists, update it
		
		// If grade doesn't exist, insert new one
		
		// Report grade status updated, inserted or failed
		return $last_grade;
	}
	/**
	*cleans up objects and variables for garbage collector
	*@returns nothing
	*/
	public function __destruct()
	{
		unset($mdl);
	}
}