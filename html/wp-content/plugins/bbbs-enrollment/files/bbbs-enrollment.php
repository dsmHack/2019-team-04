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

		$search_criteria = array(
			'status'        => 'active',
   			'field_filters' => array(
        			'mode' => 'any',
        			array(
            				'key'   => 'created_by',
            				'value' => $user->ID,
        			)
    			)
		);
		// Get all from ids for current volunteer user.
		$returnval = GFAPI::get_entries(0, $search_criteria);
		$volunteerids = array_column($returnval, 'form_id');

		// Check which form IDs are missing from submitted forms.
		$missingforms = array_diff($formids, $volunteerids);

		// Print list of all forms,
		// showing whether or not each has an entry.
		$allForms = $enrollForms->getVolunteerForms();

		ob_start();
		echo "<ul id=\"form-progress-list\" >";

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

						echo "<li><a href=\"" . $formurl . "\" >" . $form['title'] . " ❌</a></li>";
					}
					// If the form doesn't have a location, just print the form title.
					else {
						echo "<li>" . $form['title'] . " ❌</li>";

					}
				}
				// If the form has an entry, give a friendly checkbox.
				else {
					echo "<li>" . $form['title'] . " ✅</li>";
				}
			}
		}
		echo "</ul>";
		return ob_get_clean();
	}
}
add_shortcode( 'enrollmentstatus', 'enrollment_status' );
