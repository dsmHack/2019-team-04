<?php

global $jal_db_version;
$bbbs_db_version = '0.1';

function bbbs_install() {
	global $wpdb;
	global $bbbs_db_version;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$charset_collate = $wpdb->get_charset_collate();
    

	$table_name = $wpdb->prefix . 'bbbs_enrollment_form_completed';
	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        gf_form_id mediumint(8) unsigned NOT NULL,
		PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta( $sql );
    

	add_option( 'bbbs_db_version', $bbbs_db_version );
}

/*
function jal_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'liveshoutbox';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $welcome_name, 
			'text' => $welcome_text, 
		) 
	);
}
*/

//register_activation_hook( __FILE__, 'bbbs_install' );
//register_activation_hook( __FILE__, 'bbbs_install_data' );