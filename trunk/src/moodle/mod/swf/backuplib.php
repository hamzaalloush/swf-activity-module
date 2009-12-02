<?php // $Id: view.php,v 1.0 2009/01/28 matbury Exp $

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


    //This php script contains all the stuff to backup
    //swf mods

    //This is the "graphical" structure of the swf mod:
    //
    //                           swf                                      
    //                        (CL,pk->id)
    //                            |
    //                            |
    //                       swf_accesses
    //                     (UL,pk->id, fk->swfid)
    //                            |
    //                            |
    //                            |
    //                       swf_answers
    //                    (UL,pk->id,fk->accessid) 
    //
    // Meaning: pk->primary key field of the table
    //          fk->foreign key to link with parent
    //          nt->nested field (recursive data)
    //          CL->course level info
    //          UL->user level info
    //          files->table may have files)
    //
    //-----------------------------------------------------------

    function swf_backup_mods($bf,$preferences) {
        
        global $CFG;

        $status = true;

        //Iterate over swf table
        $swfs = get_records ("swf","course",$preferences->backup_course,"id");
        if ($swfs) {
            foreach ($swfs as $swf) {
                //Start mod
                fwrite ($bf,start_tag("MOD",3,true));
                //Print swf data
                fwrite ($bf,full_tag("ID",4,false,$swf->id, false));
                //tags will not be UTF encoded as they are already in UTF format 
                fwrite ($bf,full_tag("MODTYPE",4,false,"swf", false));
                fwrite ($bf,full_tag("NAME",4,false,$swf->name, false));
                fwrite ($bf,full_tag("MOVIENAME",4,false,$swf->moviename, false));
                fwrite ($bf,full_tag("GRADE",4,false,$swf->grade, false));
                fwrite ($bf,full_tag("GRADINGMETHOD",4,false,$swf->gradingmethod, false));
                fwrite ($bf,full_tag("SHOWGRADES",4,false,$swf->showgrades, false));
                fwrite ($bf,full_tag("SHOWHEADER",4,false,$swf->showheader, false));
                fwrite ($bf,full_tag("TO_CONFIG",4,false,$swf->to_config, false));
                fwrite ($bf,full_tag("CONFIG",4,false,$swf->config, false));
                fwrite ($bf,full_tag("Q_NO",4,false,$swf->q_no, false));
                fwrite ($bf,full_tag("ANSWERS",4,false,$swf->answers, false));
                fwrite ($bf,full_tag("FEEDBACK",4,false,$swf->feedback, false));
                fwrite ($bf,full_tag("GUESTFEEDBACK",4,false,$swf->guestfeedback, false));
                fwrite ($bf,full_tag("USESPLASH",4,false,$swf->usesplash, false));
                fwrite ($bf,full_tag("SPLASH",4,false,$swf->splash, false));
                fwrite ($bf,full_tag("SPLASHFORMAT",4,false,$swf->splashformat, false));
                fwrite ($bf,full_tag("USEPRELOADER",4,false,$swf->usepreloader, false));
                fwrite ($bf,full_tag("FONTS",4,false,$swf->fonts, false));
                fwrite ($bf,full_tag("TIMEMODIFIED",4,false,$swf->timemodified, false));
                //if we've selected to backup users info, then execute backup_swf_accesss
                if ($preferences->mods["swf"]->userinfo) {
                    $status = backup_swf_accesses($bf,$preferences,$swf->id);
                }
                //End mod
                $status =fwrite ($bf,end_tag("MOD",3,true));
            }
        }
        return $status;
    }

    //Backup swf_accesss contents (executed from swf_backup_mods)
    function backup_swf_accesses ($bf,$preferences,$swf) {

        global $CFG;

        $status = true;

        $swf_accesses = get_records("swf_accesses","swfid",$swf,"id");
        //If there is submissions
        if ($fswf_accesses) {
            //Write start tag
            $status =fwrite ($bf,start_tag("ACCESSES",4,true));
            //Iterate over each access
            foreach ($swf_accesses as $swf_access) {
                //Start access
                $status =fwrite ($bf,start_tag("ACCESS",5,true));
                //Print submission contents
                fwrite ($bf,full_tag("ID",6,false,$swf_access->id, false));
                fwrite ($bf,full_tag("USERID",6,false,$swf_access->userid, false));
                fwrite ($bf,full_tag("TIMEMODIFIED",6,false,$swf_access->timemodified, false));
                //Now print answers to xml
                $status = backup_swf_answers ($bf, $preferences, $swf_access->id);
                //End access
                $status =fwrite ($bf,end_tag("ACCESS",5,true));
            }
            //Write end tag
            $status =fwrite ($bf,end_tag("ACCESSES",4,true));
        }
        return $status;
    }
    
    //Backup swf_answers contents (executed from backup_swf_accesss )
    function backup_swf_answers ($bf,$preferences,$access) {

        global $CFG;

        $status = true;

        $swf_answers = get_records("swf_answers","accessid",$access,"id");
        //If there is submissions
        if ($swf_answers) {
            //Write start tag
            $status =fwrite ($bf,start_tag("ANSWERS",6,true));
            //Iterate over each answer
            foreach ($swf_answers as $swf_answer) {
                //Start answer
                $status =fwrite ($bf,start_tag("ANSWER",7,true));
                //Print submission contents
                fwrite ($bf,full_tag("ID",8,false,$swf_answer->id, false));
                fwrite ($bf,full_tag("ANSWER",8,false,$swf_answer->answer, false));
                fwrite ($bf,full_tag("Q_NO",8,false,$swf_answer->q_no, false));
                fwrite ($bf,full_tag("GRADE",8,false,$swf_answer->grade, false));
                //End answer
                $status =fwrite ($bf,end_tag("ANSWER",7,true));
            }
            //Write end tag
            $status =fwrite ($bf,end_tag("ANSWERS",6,true));
        }
        return $status;
    }
   
   ////Return an array of info (name,value)
   function swf_check_backup_mods($course,$user_data=false,$backup_unique_code) {
        //First the course data
        $info[0][0] = get_string("modulenameplural","swf");
        if ($ids = swf_ids ($course)) {
            $info[0][1] = count($ids);
        } else {
            $info[0][1] = 0;
        }

        //Now, if requested, the user_data
        if ($user_data) {
            $info[1][0] = get_string("accesses","swf");
            if ($ids = swf_access_ids_by_course ($course)) 
            {
                $info[1][1] = count($ids);
                
            } else 
            {
                $info[1][1] = 0;
            }
            $info[2][0] = get_string("answers","swf");
            if ($ids = swf_answer_ids_by_course ($course)) {
                $info[2][1] = count($ids);
            } else 
            {
                $info[2][1] = 0;
            }
        }
        return $info;
    }






    // INTERNAL FUNCTIONS. BASED IN THE MOD STRUCTURE

    //Returns an array of swfs id
    function swf_ids ($course) {

        global $CFG;

        return get_records_sql ("SELECT f.id, f.course
                                 FROM {$CFG->prefix}swf f
                                 WHERE f.course = '$course'");
    }
   
    //Returns an array of swf_accesss id
    function swf_access_ids_by_course ($course) {

        global $CFG;
        $sql="SELECT acc.id , acc.swfid
                                 FROM {$CFG->prefix}swf_accesses acc,
                                      {$CFG->prefix}swf f
                                 WHERE f.course = '$course' AND
                                       acc.swfid = f.id";
        return get_records_sql ($sql);
    }
    function swf_answer_ids_by_course ($course) {

        global $CFG;
        $sql="SELECT ans.id , acc.swfid
                                 FROM {$CFG->prefix}swf_answers ans,
                                      {$CFG->prefix}swf_accesses acc,
                                      {$CFG->prefix}swf f
                                 WHERE ans.accessid = acc.id AND
                                         f.course = '$course' AND
                                       acc.swfid = f.id";
        return get_records_sql ($sql);
    }
?>
