<?php
/*
Plugin Name: BBBS Enrollment
*/

require_once(__DIR__ . "/files/bbbs-admin-reporting.php");
require_once(__DIR__ . "/files/bbbs-enrollment.php");
require_once(__DIR__ . "/files/bbbs-install.php");
require_once(__DIR__ . "/files/bbbs-status.php");


register_activation_hook( __FILE__, 'bbbs_install' );
add_shortcode( 'enrollmentstatus', 'enrollment_status' );
add_shortcode( 'status', 'bbbs_status' );

add_action('admin_menu', 'bbbs_volunteer_menu');
?>