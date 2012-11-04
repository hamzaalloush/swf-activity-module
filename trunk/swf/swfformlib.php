<?php  // $Id: locallib.php,v 1.1 2010/02/02 matbury Exp $
/**
* Library of functions and constants for module swf
* 
* @author Matt Bury - matbury@gmail.com - http://matbury.com/
* @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
* @package swf
*/

/*
*    Copyright (C) 2009  Matt Bury - matbury@gmail.com - http://matbury.com/
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

// ------------------------------------------------------------ mod_form.php ---------------------------------------------------------- //

// The following function for mod_form.php all return arrays for the drop down lists in the SWF Activity Module instance form

/*
* Create align parameter array list
* @return array
*/
function swf_list_align()
{
	$swf_align_array = array('center' => 'center',
							'left' => 'left',
							'right' => 'right');
	return $swf_align_array;
}

/*
* Create allow networking parameter array list
* @return array
*/
function swf_list_allownetworking()
{
	$swf_list_allownetworking = array('all' => 'all',
									'internal' => 'internal',
									'none' => 'none');
	return $swf_list_allownetworking;
}

/*
* Create allow script access parameter array list
* @return array
*/
function swf_list_allowscriptaccess()
{
	$swf_list_allowscriptaccess = array('always' => 'always',
										'sameDomain' => 'sameDomain',
										'never' => 'never');
	return $swf_list_allowscriptaccess;
}

/*
* Create playback quality parameter array list
* @return array
*/
function swf_list_quality()
{
	$swf_list_quality = array('best' => 'best',
							'high' => 'high',
							'medium' => 'medium',
							'autohigh' => 'autohigh',
							'autolow' => 'autolow',
							'low' => 'low');
	return $swf_list_quality;
}

/*
* Create stage align parameter array list
* @return array
*/
function swf_list_salign()
{
	$swf_list_salign = array('tl' => 'top left',
							'tr' => 'top right',
							'bl' => 'bottom left',
							'br' => 'bottom right',
							'l' => 'left',
							't' => 'top',
							'r' => 'right',
							'b' => 'bottom');
	return $swf_list_salign;
}

/*
* Create stage scale mode parameter array list
* @return array
*/
function swf_list_scale()
{
	$swf_list_scale = array('showall' => 'showall',
							'noborder' => 'noborder',
							'exactfit' => 'exactfit',
							'noscale' => 'noscale');
	return $swf_list_scale;
}

/*
* Create skins parameter array list
* TODO - replace this function with one that searches a specified directory for skin SWFs
* @return array
*/
function swf_list_skins()
{
	$swf_list_skins = array('default' => '',
							'skins/gradient_square_blue.swf' => 'No skins available');
	return $swf_list_skins;
}

/*
* Create true/false parameter array list
* @return array
*/
function swf_list_truefalse()
{
	$swf_list_truefalse = array('true' => 'true',
								'false' => 'false');
	return $swf_list_truefalse;
}

/*
* Create true/false parameter array list
* @return array
*/
function swf_list_widthpercent()
{
	$swf_list_widthpercent = array('true' => 'true',
								'false' => 'false');
	return $swf_list_widthpercent;
}

/*
* Create window mode parameter array list
* @return array
*/
function swf_list_wmode()
{
	$swf_list_wmode = array('window' => 'window',
							'opaque' => 'opaque',
							'transparent' => 'transparent',
							'direct' => 'direct',
							'gpu' => 'gpu');
	return $swf_list_wmode;
}

/*
* Create grading parameter array list
* Create an associative array from 0 - 100
*
* @return array
*/
function swf_list_gradevalues()
{
	$swf_list_gradevalues = array();
	for($i = 0; $i < 101; $i++) {
		$swf_list_gradevalues["$i"] = "$i";
	}
	return $swf_list_gradevalues;
}

/*
* Create gradetype array 0 - 3
* @return array
*/
function swf_list_gradetype()
{
	$swf_list_gradetype = array('0' => 'none',
							'1' => 'value',
							//'2' => 'scale',
							'3' => 'text');
	return $swf_list_gradetype;
}

/**
* Set grading settings if grade_item record exists, else set defaults
* @return object
*/
function swf_set_grade_item($swf_id)
{
	if($record = get_record('grade_items','iteminstance',$swf_id))
	{
		$swf_grade_item = new stdClass;
		$swf_grade_item->id = $swf_id;
		$swf_grade_item->gradetype = $record->gradetype;
		$swf_grade_item->grademax = $record->grademax;
		$swf_grade_item->grademin = $record->grademin;
		$swf_grade_item->gradepass = $record->gradepass;
		if(update_record('swf',$swf_grade_item))
		{
			return $swf_grade_item;
		}
	}
	return false;
}

/*
* Create grade scale parameter array list - Not yet implemented
* @return array
*/
/*function swf_list_scale() {
	$swf_list_scale = array('' => 'Use no scale');
	return $swf_list_scale;
}*/

/**
* Create an array of all swf files in moodledata/[courseid]/swf/
* @return array
*/
function swf_get_swfs($courseid)
{
	global $CFG;
	$swf_swfs = array('' => 'Select a Flash application');
	$swf_moodledata_swfs = $CFG->dataroot.'/'.$courseid.'/swf/*.swf';
	// Search course files /swf/ directory
	foreach (glob($swf_moodledata_swfs) as $swf_filename) {
		$swf_path_parts = pathinfo($swf_filename);
		$swf_filename = 'swf/'.$swf_path_parts['basename'];
		$swf_swfs[$swf_filename] = $swf_path_parts['basename'];
	}
	return $swf_swfs;
}

/**
* Create an array of all XML files in moodledata/[courseid]/xml/
* @return array
*/
function swf_get_xmls($courseid)
{
	global $CFG;
	$swf_xmls = array('' => 'none');
	$swf_moodledata_xmls = $CFG->dataroot.'/'.$courseid.'/xml/*.xml';
	// Search course files /xml/ directory
	foreach (glob($swf_moodledata_xmls) as $swf_filename) {
		$swf_path_parts = pathinfo($swf_filename);
		$swf_filename = 'xml/'.$swf_path_parts['basename'];
		$swf_xmls[$swf_filename] = $swf_path_parts['basename'];
	}
	return $swf_xmls;
}

/**
* @param $swf object - SWF Activity Module instance object
* @return $swf_history object - object containing int and string values of elapsed module instance times
*
*/
function swf_get_grade_history($swf,$userid=NULL)
{
	global $USER;
	global $CFG;
	
	// Set to current user if not set
	if($userid == NULL)
	{
		$userid = $USER->id;
	}
	
	// Get grade item instance
	$swf_query = 'SELECT * FROM `'.$CFG->prefix.'grade_items` WHERE `itemmodule`=\'swf\' AND `iteminstance`='.$swf->id.'';
	$swf_grade_item = get_record_sql($swf_query);
	
	// Get grades history for grade item by user ID
	$swf_query = 'SELECT * FROM `'.$CFG->prefix.'grade_grades_history` WHERE `itemid`='.$swf_grade_item->id.' AND `userid`='.$userid.'';
	$records = get_records_sql($swf_query);
	
	// Set upper time limit. 24 hours should be high enough!
	$swf_maxtime = 86399;
	
	// Create object to store calculated attempts
	$swf_history = new stdClass();
	$swf_history->attempts = 0;
	$swf_history->highestgrade = 0;
	$swf_history->lowestgrade = 0;
	$swf_history->averagegrade = 0;
	$swf_history->longesttime = 0;
	$swf_history->shortesttime = $swf_maxtime;
	$swf_history->averagetime = 0;
	$swf_history->totaltime = 0;
	$swf_history->duration_graph = '';
	$swf_history->grade_graph = '';
	
	// Check that we have some records to iterate through
	if($records) {
		// Iterate through records
		foreach($records as $record)
		{
			// Discount anything over 24 hours (Flash has most likely recorded milliseconds)
			if($record->feedbackformat < $swf_maxtime)
			{
				$swf_history->totaltime += $record->feedbackformat;
				$swf_history->attempts += 1;
				$swf_history->averagegrade += $record->finalgrade;
				// Find longest time but 
				if($swf_history->longesttime < $record->feedbackformat)
				{
					$swf_history->longesttime = $record->feedbackformat;
				}
				// Find shortest time but discount 0 values
				if($swf_history->shortesttime > $record->feedbackformat && $record->feedbackformat > 0)
				{
					$swf_history->shortesttime = $record->feedbackformat;
				}
				// Add duration history graph
				$swf_record_feedbackformat = (int)($record->feedbackformat / 10);
				$swf_record_feedbackformat_gif = 'pix/black.gif';
				if($swf_record_feedbackformat > 150)
				{
					$swf_record_feedbackformat = 150;
					$swf_record_feedbackformat_gif = 'pix/red.gif';
				}
				$swf_history->duration_graph .= '<img src="'.$swf_record_feedbackformat_gif.'" height="'.$swf_record_feedbackformat.'" width="5" align="bottom" title="'.get_string('reportduration','swf').': '.swf_convert_seconds_to_string($record->feedbackformat).'" />';
				$swf_history->grade_graph .= '<img src="pix/green.gif" height="'.(int)($record->finalgrade / 2).'" width="5" align="bottom" title="'.get_string('reportaveragegradehistory','swf').': '.(int)$record->finalgrade.'%" />';
			}
		}
	}
	// If there's no records we need to set shortest time to 0
	if($swf_history->shortesttime >= $swf_maxtime)
	{
		$swf_history->shortesttime = 0;
	}
	
	// Don't divide by 0
	if($swf_history->averagegrade > 0) {
		$swf_history->averagegrade = round($swf_history->averagegrade / $swf_history->attempts,2);
	}
	if($swf_history->averagetime > 0) {
		$swf_history->averagetime = round($swf_history->totaltime / $swf_history->attempts);
	}
	$swf_history->str_averagetime = swf_convert_seconds_to_string($swf_history->averagetime);
	$swf_history->str_longesttime = swf_convert_seconds_to_string($swf_history->longesttime);
	$swf_history->str_shortesttime = swf_convert_seconds_to_string($swf_history->shortesttime);
	$swf_history->str_totaltime = swf_convert_seconds_to_string($swf_history->totaltime);
	
	return $swf_history;
}

/**
* Gets grade item gradepass for corresponding SWF Activity Module id
* @param $swf_id int - id of SWF Activity Module (not course instance id)
* @return string - Corresponding grade item gradepass value and user message
*/
function swf_get_current_grade_item($swf_id)
{
	if (! $cm = get_record('course_modules', 'id', $swf_id))
	{
    	$swf_grade = get_string('gradenotfound','swf');
    }
    if (! $swf = get_record('swf', 'id', $cm->instance))
	{
        $swf_grade = get_string('gradenotfound','swf');
    }
	if($swf_grade_item = get_record('grade_items','iteminstance',$swf->id))
	{
		$swf_grade = floor($swf_grade_item->gradepass);
	} else {
		$swf_grade = get_string('gradenotfound','swf');
	}
	return $swf_grade.'% '.get_string('gradeupdate', 'swf');
}

/**
* Utility method that convert seconds into time elapsed format string
* @param $seconds int - elapsed time in seconds
* @return string - weeks days h m s
*/
function swf_convert_seconds_to_string($seconds)
{
	if($seconds < 1)
	{
		return ' - ';
	}
	$swf_values = array('weeks' => (int) ($seconds / 86400 / 7), 
							'days' => $seconds / 86400 % 7, 
							'h' => $seconds / 3600 % 24, 
							'm' => $seconds / 60 % 60, 
							's' => $seconds % 60);
	$swf_totaltime = array(); 
	$swf_added = false; 
	foreach ($swf_values as $swf_k => $swf_v) { 
		if ($swf_v > 0 || $swf_added) { 
			$swf_added = true; 
			$swf_totaltime[] = $swf_v.$swf_k; 
		}
	}
	$swf_time = join(' ', $swf_totaltime);
	return $swf_time;
}

?>