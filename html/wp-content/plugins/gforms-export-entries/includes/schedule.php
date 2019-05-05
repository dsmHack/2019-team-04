<?php

function export_gf_entries_schedule( $args ) {
	$settings = get_option( 'gfee_settings', array() );

	if ( is_array( $args ) ) {
		$args = $args[0];
	}

	if ( $settings['exports'][$args]['gfee_schedule_frequency'] == 'daily' ) {
		$start = date( 'm-d-y', strtotime( '-1 days' ) );
	} else if ( $settings['exports'][$args]['gfee_schedule_frequency'] == 'weekly' ) {
		$start = date( 'm-d-y', strtotime( '-7 days' ) );
	} else if ( $settings['exports'][$args]['gfee_schedule_frequency'] == 'monthly' ) {
        $start = date( 'm-d-y', strtotime( '-1 month' ) );        
	} else {
		$start = date( 'm-d-y', strtotime( '-1 days' ) );
	}

	$stop = date( 'm-d-y' );

	$file = gfee_generate_export( $args, $start, $stop );

	$admin_email = get_bloginfo( 'admin_email' );

	$site_name = get_bloginfo( 'name' );

	if ( isset( $settings['exports'][$args]['email_subject'] ) ) {
		$subject = $settings['exports'][$args]['email_subject'];
	} else {
		$subject = __( 'Form Entry Report for ', 'gforms-export-entries' ) . $site_name;
	}

	$subject = str_replace( '{site_name}', $site_name, $subject );
	$subject = do_shortcode( $subject );

	if ( isset( $settings['exports'][$args]['email_template'] ) ) {
		$body = html_entity_decode( $settings['exports'][$args]['email_template'] );
	} else {
		$body = __( 'Form Entry Report is attached for ', 'gforms-export-entries' ) . $site_name;
	}

	$body = str_replace( '{site_name}', $site_name, $body );
	$body = do_shortcode( $body );

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: multipart/mixed; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: ' . $site_name . ' <' . $admin_email . '>' . "\r\n";

	$attachments = array( $file );

	gfee_log( '++++++++++++++++++++++++++++++++++++' );
	gfee_log( 'Email attachment: ' . $file );
	if ( ! file_exists( $file ) ) {
		gfee_log( 'Email attachment does NOT exist: ' . $file );
	} else {
		gfee_log( 'Email attachment DOES exist: ' . $file );
	}

	$email_status = '';

	if ( strpos( $settings['exports'][$args]['email_to'], ',' ) !== false ) {
		$addresses = explode( ',', $settings['exports'][$args]['email_to'] );
		foreach( $addresses as $address ) {
			$address = trim( $address );
			$email_status = wp_mail( $address, $subject, $body, $headers, $attachments );
		}
	} else {
		$address = trim( $settings['exports'][$args]['email_to'] );
		$email_status = wp_mail( $address, $subject, $body, $headers, $attachments );
	}
	gfee_log( 'Email status: ' . $email_status );
	gfee_log( '++++++++++++++++++++++++++++++++++++' );
}

add_filter( 'wp_mail_content_type', 'gfee_set_content_type' );
function gfee_set_content_type( $content_type ) {
    return 'text/html';
}

function gfee_set_schedule( $settings, $export ) {
	$date_parts = explode( '-', $settings['exports'][ $export ]['schedule_start_date'] );
	$date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1];
	$schedule_date = $date . ' ' . $settings['exports'][ $export ]['hour'] . ':' . $settings['exports'][ $export ]['minute'];
	$timestamp = strtotime( $schedule_date );

	wp_clear_scheduled_hook( 'export_gfee_entries' );

	wp_schedule_event( $timestamp, $settings['exports'][ $export ]['gfee_schedule_frequency'], 'export_gfee_entries', array( $export ) );
}

/**
 * Create custom schedule frequencies
*/
add_filter('cron_schedules','gfee_new_frequencies');
function gfee_new_frequencies( $schedules ) {
   $schedules['weekly'] = array(
       'interval' => 604800,
       'display'=> 'Weekly'
   );

   $schedules['monthly'] = array(
       'interval' => 2592000,
       'display'=> 'Monthly'
   );


   return $schedules;
}

?>