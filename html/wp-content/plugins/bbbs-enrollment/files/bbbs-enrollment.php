<?php
/**
 * Plugin Name: BBBS Enrollment
 */

/**
 * Shortcode for checking enrollment status.
 * Meant for volunteer users.
 */
function enrollment_status() {
	// Check if user is logged in.
	$user_id = get_current_user_id();
	$user = wp_get_current_user();
	if ($user_id == 0) {
    	return;
	} else {
		// Get all form ids.
		$enrollForms = new EnrollmentForms();
		$formids = $enrollForms->getAllFormIDs();

		// Get all from ids for current volunteer user.
		$returnval = GFAPI::get_entries(0, $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID ));
		$volunteerids = array_column($returnval, 'id');

		// Check which form IDs are missing from submitted forms.
		$missingforms = array_diff($formids, $volunteerids);

		// Print list of all forms,
		// showing whether or not each has an entry.
		$allForms = $enrollForms->getAllForms();
		foreach($allForms as $form) {
			if ($form['is_active']) {
				if (in_array($form['id'], $missingforms)) {
					echo "<h2>" . $form['title'] . " ❌</h2>";
				}
				else {
					echo "<h2>" . $form['title'] . " ✅</h2>";
				}
			}
		}
	}
}
add_shortcode( 'enrollmentstatus', 'enrollment_status' );
