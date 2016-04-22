

## Introduction ##

This page lists changes and updates to the SWF Activity Module. They are listed in reverse chronological order.


---


### 2011/03/17 ###

  * Administration > Modules > Activities > SWF settings panel added. Sets default parameters for new instances on a site wide basis.
  * Full browser Flash feature now added.
  * Student grade book report page added. Lists all instances of SWF Activity Module for the current course and displays current user's grade details for all of them.


---


### 2011/03/04 ###

  * Administration > Modules > Activities > SWF settings panel to be added soon. Set default parameters for new instances on a site wide basis.
  * A full browser Flash feature will soon be added - 100% width, 100% height of browser window.
  * Add FlashVars.fullbrowser:String (true/false) parameter so that Flash apps can detect full browser mode and adapt accordingly.


---


### 2010/12/04 ###

  * Updated AMFPHP 1.9 to be compatible with PHP 5.3.
  * Changed com.matbury.sam.data.Amf.as class file to handle errors generated by LMS/CMS's if they throw PHP 5.3 deprecated function warnings.


---


### 2010/09/25 ###

  * Included code to insert entries into Moodle participation reports.
  * Included URL to Moodle's grade book user report in FlashVars embed code.
  * Added gradebook property to FlashVars.as file, i.e. FlashVars.gradebook.


---


### 2010/06/14 ###

**Milestone:** No significant compatibility problems or bugs have been reported or detected. Module is ready for production use.


---


### 2010/05/30 ###

Fixed FlashVars bug in lib.php where the noCache query string was interfering with FlashVars values when no xmlurl value was present.


---


### 2010/05/23 ###

No longer supporting swf\_interactions and swf\_interaction\_data tables in installer packages. I simply don't have the time to develop the framework and interfaces for editing learning interaction databases in this way.

XML files are the way ahead now and, although they're not as fast and dynamic, they offer much more flexibility.


---


### 2010/02/18 ###

Updated report.php page to display the following user reports for each user on a selected SWF Activity Module instance:
  * Date Taken - (last attempt)
  * Time Taken - (last attempt)
  * Grade - (last attempt)
  * Duration - (last attempt)
  * Attempts - Total number of attempts
  * Total Attempts Duration - Total duration of all attempts
  * Average Grade - Average grade of all attempts
  * Feedback - (last attempt)


---


### 2010/02/17 ###

  * Uploaded xml\_word\_search.swf Flash learning application which generates word searches for external XML files and pushes grades and feedback into Moodle grade book.


---


### 2010/02/13 ###

  * Started work on ErrorReport classes for both ActionScript (client-side) and PHP (server-side). The idea is to provide detailed information about users' computer, Flash Player and Flash learning application to IT support staff when users experience technical problems. Will simultaneously send detailed reports as emails to course teachers and admins.


---


### 2010/02/08 ###

  * install.xml, backuplib.php and restorelib.php successfully tested.
  * Updated com.matbury.sam.data package (Amf.as and FlashVars.as)
  * Removed hard-coded values from Grades.php and successfully tested with Flash client (grade\_pusher.swf).
  * Created moodle/mod/swf/report.php and grade.php (a redirect so that only teachers and admins can view the reports) scripts which are navigated to from Moodle grade book and displays user grades for the selected SWF Activity Module instance. Includes a "Total Time" column which shows time spent by users accumulatively on the selected activity.


---


### 2010/02/05 ###

  * Created new moodle/lib/amfphp/services/Grades.php class. It simply leverages moodle/lib/gradelib.php::update\_grade() to insert or update grades in the grade\_grades table.


---


### 2010/02/03 ###

  * Added grade\_update() function (moodle/lib/gradelib.php) to moodle/mod/swf/lib.php. Now, SWF instances have corresponding grade item in Moodle grade book
  * Also added gradetype, grademax and grademin parameters to swf DB table and mod\_form.php to manage grade items for corresponding SWF instance.
  * Updated install.xml. Removed swf\_interactions and swf\_interaction\_data tables. Now, XML is the only provided method of loading learning interaction data into Flash applications.


---


### 2009/12/21 ###

  * Gradebook.php and Access.php scripts taking shape and functioning well. MDL.php can now be deprecated.
  * Started work on Flash grade book client to display and edit grades.


---


### 2009/12/08 ###

  * Backup, restore and upgrade scripts have been completed. Not thoroughly tested however. Please try it out on your development server and report any bugs in the issues tracker.
  * Uploaded the preliminary stages of the skinning API library. Optionally, Flash e-learning interactions can load in a skin file that provides a standardised set of user interface graphics to change their visual appearance. A similar concept to CSS and themes.


---


### 2009/12/07 ###

  * New Google Code project site created. Old site has become corrupted, the Wiki is uneditable and some links have been changed by SpamBots.