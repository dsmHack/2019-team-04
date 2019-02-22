<?php
/**
 * Plugin Name: BBBS Enrollment
 */

/**
 * Shortcode for checking enrollment status for a specific form id
 * for the currently logged in user.
 *
 * @param int $formid Form id for specific form.
 */
function enrollment_status( $formid ) {
	// Check if user is logged in.
	$user_id = get_current_user_id();
	if ($user_id == 0) {
    	echo 'You are currently not logged in.';
	} else {
		$returnval = get_entries($formid, $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID ));
		return $returnval;
	}
}
add_shortcode( 'enrollmentstatus', 'enrollment_status' );
