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
    	return;
	} else {
		// Check if entry exists.
		$returnval = GFAPI::get_entries($formid, $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID ));
		// Get form title for formid.
		$forminfo = GFAPI::get_form($formid);
		if (isset($returnval)) {
			return $forminfo['title'] . ' complete!';
		} else {
			return $forminfo['title'] . ' not complete';
		}
	}
}
add_shortcode( 'enrollmentstatus', 'enrollment_status' );
