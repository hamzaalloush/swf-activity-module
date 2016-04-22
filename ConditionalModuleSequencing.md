## Introduction ##

The latest build of the SWF Activity Module for Moodle includes support for conditional sequencing between SWF course module instances.

### Conditional sequencing ###

This is when a group of activities on a course must be completed in a specific order, i.e. learners must complete module 1 before they can attempt module 2. This feature currently only supports one group of SWF Activity Modules per course and the individual modules are selected with a simple true/false option on the module instance configuration form. The order of the sequence is set by the order of appearance of the module instances on the course page.


---


## How to use ##

  1. Select Add and activity... > SWF, to create a new SWF Activity Module instance
  1. Configure the instance as required
  1. Select Grading > Conditional sequencing > true
  1. Save


---


## How it works ##

  * Deployed Flash learning applications must be capable of saving grades in Moodle's grade book, otherwise SWF Activity Module instances cannot be completed.
  * Only one group of SWF Activity Modules can be sequenced per course.
  * Only SWF Activity Module instances that have Grading > Conditional sequencing > true selected are included in the sequence.
  * The order of the sequence is the same as the order of appearance of the module instances on the course page.
  * Learners must complete the first uncompleted module instance before they can attempt the next in the sequence.
  * Learners are shown a summary page of the sequenced module instances if they attempt a module instance out of sequence with a button that takes them to the next one in the sequence.
  * If the order of the modules is changed or added to before learners have completed all of them, they are simply required to do the first uncompleted module(s) in the new sequence. They do not have to re-attempt already completed instances.
  * Doesn't work with guest access, teachers or admins. You must login as a student to test it.

<img src='http://blog.matbury.com/wp-content/uploads/2011/10/swf_conditional_sequencing.gif' alt='SWF Activity Module conditional sequencing' width='476' height='420'>