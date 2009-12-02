AMFPHP for SWF Activity Module

Activity Module plugin Flash Remoting library package for Moodle by Matt Bury
matbury@gmail.com
http://matbury.com/

The SWF Activity Module provides a reliable, easy to use framework for deploying Flash and Flex Framework learning applications as learning interactions in Moodle. 
This package provides a gateway for AMF3 communication between Flash applications and Moodle.
For more information about AMFPHP see the project site at: http://www.amfphp.org/
The AMFPHP download package did not contain any copyright notices but I assume that it is open source as stated on the project website.

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

To install:
* Put/Upload the 'amfphp' directory to ***MOODLEROOT***/lib/
* VERY IMPORTANT: DO NOT UPLOAD THE "/lib/amfphp/services/MDL_TEST_SERVER_ONLY.php" file to a public server, it exposes vital sensitive data about Moodle and is for local testing only.
* ALSO VERY IMPORTANT: DO NOT UPLOAD the "/lib/amfphp/browser/" directory or the "/lib/amfphp/services/amfphp/" directory on a public server, this exposes your services to the public.

To use:
* On your test server, navigate to "http://localhost/moodle/lib/amfphp/browser/" (adjust for where your Moodle site is located) in your web browser to view the very useful AMFPHP Browser Flex application. This helps with testing and debugging your services.
* For more details, see the project wiki: http://code.google.com/p/moodle-swf/w/list

code.google.com project home page: http://code.google.com/p/moodle-swf/
Moodle Docs page: http://docs.moodle.org/en/SWF