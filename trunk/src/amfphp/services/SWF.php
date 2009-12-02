<?php 
/**
* class SWF.php connects SWF module to Moodle DB
* 
* @author Matt Bury - matbury@gmail.com - http://matbury.com
* 
* @method getInteractionInfo()
* @method getInteractionData()
* @method getFeedback()
* @method setGrade()
*
*
*/

class SWF
{
	private $mdl;
	
	public function __construct()
	{
		// create moodle authentication and user variables object
		require('MDL.php');
		$this->mdl = new MDL();
	}
	
	/**
	* Get interaction information from mdl_swf_interactions
	* @param $id int
	* @returns object
	*/
	public function getInteractionInfo($id)
	{	
		// Add Moodle prefix to DB table name
		$amftable = $this->mdl->prefix.'swf_interactions';
		
		// Get DB record
		$swf_query = 'SELECT * FROM `'.$amftable.'` WHERE `id`=\''.$id.'\'';
		$swf_result = mysql_query($swf_query);
		
		// Check that query returned a valid result
		if(!$swf_result)
		{
			return 'DB item id = '.$id.' does not exist in '.$amftable.' or you are not permitted to access this database.';
		}
		
		// Store data in object
		$swf_info->amftable = mysql_result($swf_result,0,'amftable');
		$swf_info->course = mysql_result($swf_result,0,'course');
		$swf_info->id = mysql_result($swf_result,0,'id');
		$swf_info->intro = mysql_result($swf_result,0,'intro');
		$swf_info->introformat = mysql_result($swf_result,0,'introformat');
		$swf_info->name = mysql_result($swf_result,0,'name');
		$swf_info->timecreated = mysql_result($swf_result,0,'timecreated');
		$swf_info->timemodified = mysql_result($swf_result,0,'timemodified');
		
		return $swf_info;
	}
	
	/**
	* Inserts new record in swf_interactions
	* @param name
	* @param intro
	* @param amftable
	* @returns boolean
	*/
	
	// TODO - Change these 3 parameters to a single object
	
	public function setInteractionInfo($swf_name,$swf_intro,$swf_amftable)
	{
		// Check that necessary data exists in object
		if(!$swf_name)
		{
			return 'Required parameter "name" not set.';
		}
		if(!$swf_intro)
		{
			return 'Required parameter "intro" not set.';
		}
		if(!$swf_amftable)
		{
			$swf_amftable = 'swf_interaction_data';
		}
		
		// Escape any MySQL special characters
		$swf_name = mysql_real_escape_string($swf_name);
		$swf_intro = mysql_real_escape_string($swf_intro);
		$swf_amftable = mysql_real_escape_string($swf_amftable);
		
		// Insert data
		$swf_query = 'INSERT INTO `'.$this->mdl->prefix.'swf_interactions` (`id`, `course`, `name`, `intro`, `introformat`, `amftable`, `timecreated`, `timemodified`) VALUES (NULL, \''.$this->mdl->course_id.'\', \''.$swf_name.'\', \''.$swf_intro.'\', \'0\', \''.$swf_amftable.'\', \''.time().'\', \''.time().'\');';
		
		// Attempt to insert record
		if(mysql_query($swf_query))
		{
			// Return success message
			$swf_return->id = mysql_insert_id();
			$swf_return->message = 'New learning interaction info record has been created. name = '.$swf_name.', intro = '.$swf_intro.' and amftable = '.$swf_amftable.'.';
			return $swf_return;
		} else {
			// Return error message
			$swf_return->id = 0;
			$swf_return->message = 'Unable to create new learning interaction: name = '.$swf_name.', intro = '.$swf_intro.' and amftable = '.$swf_amftable.'.';
			return $swf_return;
		}
	}
	
	/**
	* Get interaction data
	* @param interaction
	* @returns array
	*/
	public function getInteractionData($interaction)
	{
		// get amftable
		$swf_query = 'SELECT `amftable` FROM `'.$this->mdl->prefix.'swf_interactions` WHERE `id`=\''.$interaction.'\' ';
		$swf_result = mysql_query($swf_query);
		
		// If table name isn't returned, set to default
		if($swf_result)
		{
			$amftable = 'swf_interaction_data';
			$swf_error = 'Learning interaction data table at '.$interaction.' not found. Default "swf_interaction_data" table was set. ';
		} else {
			$amftable = mysql_result($swf_result,0,'amftable');
		}
		
		// Add Moodle DB prefix
		$amftable = $this->mdl->prefix.$amftable;
		
		// Get interaction data
		$swf_query = 'SELECT * FROM `'.$amftable.'` WHERE `interaction`=\''.$interaction.'\' ORDER BY `ordernum` ASC ';
		$swf_result = mysql_query($swf_query);
		
		if(!$swf_result) {
			return ' The learning interaction '.$interaction.' either does not exist or could not be accessed.';
		}
		// Convert MySQL result into array of objects
		$rows = mysql_num_rows($swf_result);
		if($rows < 1)
		{
			$swf_error .= 'Sorry, I couldn\'t find the resources for this learning interaction in id = '.$interaction.'.';
			return $swf_error;
		}
		/*
		TODO - should make this loop more dynamic:
		Get the number of columns
		Get the column names
		Create the objects accordingly
		Push objects into array
		*/
		for ($i = 0; $i < $rows; $i++)
		{
			$swf_interaction_data[$i]->ordernum = mysql_result($swf_result,$i,'ordernum');
			$swf_interaction_data[$i]->pmp3 = mysql_result($swf_result,$i,'pmp3');
			$swf_interaction_data[$i]->qmp3 = mysql_result($swf_result,$i,'qmp3');
			$swf_interaction_data[$i]->cmp3 = mysql_result($swf_result,$i,'cmp3');
			$swf_interaction_data[$i]->wmp3 = mysql_result($swf_result,$i,'wmp3');
			$swf_interaction_data[$i]->smp3 = mysql_result($swf_result,$i,'smp3');
			$swf_interaction_data[$i]->image = mysql_result($swf_result,$i,'image');
			$swf_interaction_data[$i]->video = mysql_result($swf_result,$i,'video');
			$swf_interaction_data[$i]->ptext = mysql_result($swf_result,$i,'ptext');
			$swf_interaction_data[$i]->qtext = mysql_result($swf_result,$i,'qtext');
			$swf_interaction_data[$i]->ctext = mysql_result($swf_result,$i,'ctext');
			$swf_interaction_data[$i]->wtext = mysql_result($swf_result,$i,'wtext');
			$swf_interaction_data[$i]->keyword = mysql_result($swf_result,$i,'keyword');
			$swf_interaction_data[$i]->timecreated = mysql_result($swf_result,$i,'timecreated');
			$swf_interaction_data[$i]->timemodified = mysql_result($swf_result,$i,'timemodified');
			/* Not using this bit at the moment and it's causing errors in Flash
			// create array of correct answers
			$swf_interaction_data[$i]->ctext = explode("|",$swf_interaction_data[$i]->ctext);
			// create array of wrong answers
			$swf_interaction_data[$i]->wtext = explode("|",$swf_interaction_data[$i]->wtext);
			*/
		}
		return $swf_interaction_data;
	}
	
	/**
	* Inserts complete set of new interaction data 
	* @param object
	* @returns string
	*/
	// TODO - Test for admin/editing teacher priviledges
	/*
	public function setInteractionData()
	{
		// Construct test object
		$interaction_data->interaction = 9999;
		for($i = 0; $i < 9; $i++)
		{
			$interaction_data->objects[$i]->ordernum = $i;
			$interaction_data->objects[$i]->pmp3 = 'mp3/pmp3.mp3';
			$interaction_data->objects[$i]->qmp3 = 'mp3/qmp3.mp3';
			$interaction_data->objects[$i]->cmp3 = 'mp3/cmp3.mp3';
			$interaction_data->objects[$i]->wmp3 = 'mp3/wmp3.mp3';
			$interaction_data->objects[$i]->smp3 = 'mp3/smp3.mp3';
			$interaction_data->objects[$i]->image = 'pix/image.jpg';
			$interaction_data->objects[$i]->video = 'flv/video.flv';
			$interaction_data->objects[$i]->ptext = 'This is some paragraph text.';
			$interaction_data->objects[$i]->qtext = 'Is this a question?';
			$interaction_data->objects[$i]->ctext = 'This is a correct answer.';
			$interaction_data->objects[$i]->wtext = 'This is a wrong answer';
			$interaction_data->objects[$i]->keyword = 'a key word';
		}
		
		// Construct SQL query
		$swf_query = 'INSERT INTO `'.$this->mdl->prefix.'swf_interaction_data` (`id`,`interaction`,`ordernum`,`pmp3`,`qmp3`,`cmp3`,`wmp3`,`smp3`,`image`,`video`,`ptext`,`qtext`,`ctext`,`wtext`,`keyword`,`timecreated`,`timemodified`) VALUES ';
		$rows = count($interaction_data->objects);
		for($i = 0; $i < $rows; $i++)
		{
			$interaction = $interaction_data->interaction;
			$ordernum = $interaction_data->objects[$i]->ordernum;
			$pmp3 = $interaction_data->objects[$i]->pmp3;
			$qmp3 = $interaction_data->objects[$i]->qmp3;
			$cmp3 = $interaction_data->objects[$i]->cmp3;
			$wmp3 = $interaction_data->objects[$i]->wmp3;
			$smp3 = $interaction_data->objects[$i]->smp3;
			$image = $interaction_data->objects[$i]->image;
			$video = $interaction_data->objects[$i]->video;
			$ptext = $interaction_data->objects[$i]->ptext;
			$qtext = $interaction_data->objects[$i]->qtext;
			$ctext = $interaction_data->objects[$i]->ctext;
			$wtext = $interaction_data->objects[$i]->wtext;
			$keyword = $interaction_data->objects[$i]->keyword;
			$swf_query .= ' (NULL,\''.$interaction.'\',\''.$ordernum.'\',\''.$pmp3.'\',\''.$qmp3.'\',\''.$cmp3.'\',\''.$wmp3.'\',\''.$smp3.'\',\''.$image.'\',\''.$video.'\',\''.$ptext.'\',\''.$qtext.'\',\''.$ctext.'\',\''.$wtext.'\',\''.$keyword.'\',\''.time().'\',\''.time().'\')';
			// Insert commas between rows
			if($i < $rows - 1) {
				$swf_query .= ',';
			}
		}
		// Call SQL query. Returns true if successful
		return mysql_query($swf_query);
	}
	*/
	/**
	* Get feedback data
	* @returns array
	*/
	/*
	public function getFeedback()
	{
		$swf_query = 'SELECT * FROM `mdl_swf_feedback` WHERE `course`=\''.$this->mdl->course_id.'\'';
		if(!$swf_result = mysql_query($swf_query))
		{
			return false;
		}
		
		$rows = mysql_num_rows($swf_result);
		for ($i = 0; $i < $rows; $i++)
		{
			$swf_feedback[$i]->name = mysql_result($swf_result,$i,'name');
			$swf_feedback[$i]->mp3 = mysql_result($swf_result,$i,'mp3');
		}
		return $swf_feedback;
	}
	*/
	/**
	* @param $rslts object
	* @returns boolean
	*/
	/*
	public function setGrade($rslts)
	{
		// find out if user grade exists for this interaction
		
		// if not, insert new grade
		
		// if so, update grade
		
		$course = $this->mdl->course_id; // Only works in Moodle 1.8
		$user = $this->mdl->user_id;
		$instance = $rslts->instance;
		$swfid = $rslts->swfid;
		$numquestions = $rslts->numquestions;
		$numanswered = $rslts->numanswered;
		$numcorrect = $rslts->numcorrect;
		$numattempts = $rslts->numattempts;
		$timeelapsed = $rslts->timeelapsed;
		$timecreated = time();
		$amftable = $this->mdl->prefix.'swf_grades';
		$swf_query = 'INSERT INTO `mdl_swf_grades` ('', '$course', '$user', '$instance', '$swfid', '$numquestions', '$numanswered', '$numcorrect', '$timeelapsed', '$timecreated') ';
		
		
		// return obj;
		return true;
	}
	*/
	/**
	* @param $rslts object
	* @returns boolean
	*/
	/*
	public function getGrades()
	{
		$swf_query = 'SELECT * FROM `'.$this->mdl->prefix.'swf_grades` WHERE `user`=\''.$this->mdl->user_id.'\' AND `course`=\''.$this->mdl->course_id.'\' ';
		
		if(!$swf_result = mysql_query($swf_query)) {
			return false;
		}
		
		return $swf_result;
	}
	*/
	/**
	*cleans up objects and variables for garbage collector
	*@returns nothing
	*/
	public function __destruct()
	{
		unset($mdl);
	}
}
