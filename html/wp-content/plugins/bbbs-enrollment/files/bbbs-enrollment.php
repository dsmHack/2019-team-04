<?php
/**
 * Plugin Name: BBBS Enrollment
 */

/**
 * Shortcode for checking enrollment status.
 * Meant for volunteer users.
 * Requires gf-form-locator.
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
		$volunteerids = array_column($returnval, 'form_id');

		// Check which form IDs are missing from submitted forms.
		$missingforms = array_diff($formids, $volunteerids);

		// Print list of all forms,
		// showing whether or not each has an entry.
		$allForms = $enrollForms->getAllForms();

		foreach($allForms as $form) {
			if ($form['is_active']) {
				// Fetch all form locations from the gf form locator table.
				// @see gf-form-locator plugin
				$formlocations = Form_Locations_Table::get_locations();
				$formlocationids = array_column($formlocations, 'form_id');
				
				// If the form id doesn't have an active entry.
				if (in_array($form['id'], $missingforms)) {
					// If the form id has a form location.
					if (in_array($form['id'], $formlocationids)) {
						// Get the array key. so we can fetch post id.
						$key = array_search($form['id'], array_column($formlocations, 'form_id'));
						$postid = $formlocations[$key]['post_id'];
						$formurl = get_permalink($postid);

						echo "<h2><a href=\"" . $formurl . "\" >" . $form['title'] . " ❌</a></h2>";
					}
					// If the form doesn't have a location, just print the form title.
					else {
						echo "<h2>" . $form['title'] . " ❌</h2>";
					}
				}
				// If the form has an entry, give a friendly checkbox.
				else {
					echo "<h2>" . $form['title'] . " ✅</h2>";
				}
			}
		}
	}
}
add_shortcode( 'enrollmentstatus', 'enrollment_status' );
