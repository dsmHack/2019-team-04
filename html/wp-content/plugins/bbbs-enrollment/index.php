<?php
/*
Plugin Name: BBBS Enrollment
*/

require_once(__DIR__ . "/files/bbbs-admin-reporting.php");
require_once(__DIR__ . "/files/bbbs-enrollment.php");
require_once(__DIR__ . "/files/bbbs-install.php");
require_once(__DIR__ . "/files/bbbs-status.php");
require_once(__DIR__ . "/files/includes/RemoteStorage.php");

require_once(__DIR__ . "/../../../../vendor/autoload.php");

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

/*
add_action("gform_after_update_entry","adjust_associated_user",10,3);
function adjust_associated_user($form, $entry_id, $original_entry) {

    echo "<pre>";
    var_dump($entry_id);
    var_dump($original_entry);
    echo "</pre>";
    die();
}
*/


add_action( 'gform_after_submission', 'set_post_content', 10, 2 );
function set_post_content( $entry, $form ) {

    $userId = array_reduce($form['fields'],function($acc,$cur) use ($entry) {
        if ($cur->label == "uid") {
            if (array_key_exists($cur->id,$entry)) {
                $acc = $entry[$cur->id];
            }
        }
        return $acc;
    },null);


    if ($userId) {
        $entry['created_by'] = $userId;
        GFAPI::update_entry($entry);
    }

    // handle remote file upload



    // get all fileupload fields
    $fileUploadFields = array_filter($form['fields'], function($cur) {
        return $cur->type == "fileupload";
    });


    if (count($fileUploadFields) > 0) {
        $basePath = ABSPATH;
        $config = array(
            "region" => S3_UPLOADS_REGION,
            "key" => S3_UPLOADS_KEY,
            "secret" => S3_UPLOADS_SECRET,
            "bucket" => S3_UPLOADS_BUCKET
        );
        $rs = new RemoteStorage($config);

        $user = get_user_by('id',$userId);

        $keyPrefix = $userId . "-" . $user->display_name . "/" . $form['title'];

        foreach ($fileUploadFields as $field) {
            $id = $field->id;

            $entryValue = $entry[$id];
            $localPath = $basePath . substr($entryValue,strpos($entryValue,"wp-content"));

            if (file_exists($localPath)) {
                $s3Url = $rs->transfer($localPath,$keyPrefix);
                if ($s3Url !== false) {
                    $entry[$id] = $s3Url;
                    unlink($localPath);
                }
            }
        }
    }

    /*
    echo "<pre>";

    var_dump($fileUploadFields);

    var_dump($userId);
    var_dump($entry);
    var_dump($form);
    echo "</pre>";
    die();
    */

    /*
    use ->id to get the value from the entry
    index 2,  ->type == "fileupload"
    */
 
    /*
    //getting post
    $post = get_post( $entry['post_id'] );
 
    //changing post content
    $post->post_content = 'Blender Version:' . rgar( $entry, '7' ) . "<br/> <img src='" . rgar( $entry, '8' ) . "'> <br/> <br/> " . rgar( $entry, '13' ) . " <br/> <img src='" . rgar( $entry, '5' ) . "'>";
 
    //updating post
    wp_update_post( $post );
    */
}

?>