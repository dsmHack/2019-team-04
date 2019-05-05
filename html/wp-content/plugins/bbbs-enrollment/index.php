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
add_shortcode( 'registerredirect', 'register_redirect' );

add_action('admin_menu', 'bbbs_volunteer_menu');

function register_redirect() {
    if (is_user_logged_in() && is_page()) {
        wp_redirect('/enrollment-forms');
        exit;
    }
}

add_filter( 'gform_form_settings', 'my_custom_form_setting', 10, 2 );
function my_custom_form_setting( $settings, $form ) {
    $val = rgar($form, 'form_visibility');

    $options = array(
        "volunteer_staff" => "Volunteer/Staff",
        "staff_only"=> "Staff Only"
    );

    $markup = '
        <tr>
            <th><label for="form_visibility">Form Visibility</label></th>
            <td>
                <select name="form_visibility">';
    foreach($options as $value=>$title) {
        $selected = ($val == $value) ? "selected": "";
        $markup .= sprintf('<option value="%s" %s>%s</option>',$value,$selected,$title);
    }
    $markup .= '
                </select>
            </td>
        </tr>';

    $settings[ __( 'Form Basics', 'gravityforms' ) ]['form_visibility'] = $markup;


    $orderVal = rgar($form, 'form_order');
    $markup = '
        <tr>
            <th><label for="form_order">Form Order</label></th>
            <td>
                <input type="text" name="form_order" value="' . $orderVal . '">
            </td>
        </tr>';

    $settings[ __( 'Form Basics', 'gravityforms' ) ]['form_order'] = $markup;

    return $settings;
}
 
// save your custom form setting
add_filter( 'gform_pre_form_settings_save', 'save_my_custom_form_setting' );
function save_my_custom_form_setting($form) {
    $form['form_visibility'] = rgpost( 'form_visibility' );
    $form['form_order'] = rgpost( 'form_order' );
    return $form;
}

?>