<?php
/*
Plugin Name: BBBS Enrollment
*/

require_once(__DIR__ . "/files/bbbs-enrollment.php");
require_once(__DIR__ . "/files/bbbs-install.php");


register_activation_hook( __FILE__, 'bbbs_install' );
add_shortcode( 'enrollmentstatus', 'enrollment_status' );
?>