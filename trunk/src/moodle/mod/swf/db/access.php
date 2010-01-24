<?php  // $Id$
/**
 * Capability definitions for SWF Activity Module.
 *
 * For naming conventions, see lib/db/access.php.
 * 
 * If you edit these capabilities, they won't take effect until you upgrade the module version.
 */
$mod_swf_capabilities = array(

    // Ability to see that swf exists, and the basic information about it.
	// For guests+
    'mod/swf:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'guest' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    ),
	
    // View user's own swf grades.
	// For students+
    'mod/swf:viewowngrades' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
			'guest' => CAP_PREVENT,
			'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    ),
	
    // View all swf grades in course.
	// For teachers+
    'mod/swf:viewallgrades' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
			'guest' => CAP_PREVENT,
			'student' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    ),
	
    // Edit all swf grades in course.
	// For teachers+
    'mod/swf:updategrades' => array(
		'riskbitmask' => RISK_PERSONAL,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
			'guest' => CAP_PREVENT,
			'student' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    )
	
    // Edit all swf grades in course.
	// For teachers+
    'mod/swf:editgrades' => array(
		'riskbitmask' => RISK_PERSONAL,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
			'guest' => CAP_PREVENT,
			'student' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    )
);
?>
