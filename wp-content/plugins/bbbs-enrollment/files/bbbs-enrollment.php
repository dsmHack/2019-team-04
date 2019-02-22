<?php
/**
 * Plugin Name: BBBS Enrollment
 */

function enrollment_status( $params ) {

	return "<h1>enrollment</h1>"; // has to return all the HTML
}
add_shortcode( 'enrollmentstatus', 'enrollment_status' );

?>