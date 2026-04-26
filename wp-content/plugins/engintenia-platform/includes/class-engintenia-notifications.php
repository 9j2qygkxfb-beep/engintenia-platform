<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Notifications {
	public static function add_notification( $user_id, $message ) {
		$notifications   = get_user_meta( $user_id, 'eng_notifications', true );
		$notifications   = is_array( $notifications ) ? $notifications : array();
		$notifications[] = array(
			'message' => sanitize_text_field( $message ),
			'date'    => current_time( 'mysql' ),
		);
		update_user_meta( $user_id, 'eng_notifications', $notifications );
	}

	public static function notify_subcontractors_new_project( $post_id, $post ) {
		if ( 'eng_project' !== $post->post_type ) {
			return;
		}

		$users = get_users( array( 'role' => 'eng_subcontractor' ) );
		foreach ( $users as $user ) {
			if ( ! Engintenia_Subscriptions::has_active_subscription( $user->ID ) ) {
				continue;
			}
			self::add_notification( $user->ID, sprintf( __( 'New project posted: %s', 'engintenia-platform' ), $post->post_title ) );
			wp_mail( $user->user_email, __( 'New Project Available', 'engintenia-platform' ), sprintf( __( 'A new project is live: %s', 'engintenia-platform' ), $post->post_title ) );
		}
	}
}
