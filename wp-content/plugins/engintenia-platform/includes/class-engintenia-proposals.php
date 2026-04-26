<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Proposals {
	public static function handle_submit() {
		if ( ! is_user_logged_in() ) {
			wp_die( esc_html__( 'Please login.', 'engintenia-platform' ) );
		}

		if ( ! isset( $_POST['eng_proposal_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['eng_proposal_nonce'] ) ), 'eng_submit_proposal' ) ) {
			wp_die( esc_html__( 'Invalid request.', 'engintenia-platform' ) );
		}

		$user_id    = get_current_user_id();
		$project_id = isset( $_POST['project_id'] ) ? absint( $_POST['project_id'] ) : 0;

		if ( ! Engintenia_Subscriptions::has_active_subscription( $user_id ) ) {
			wp_die( esc_html__( 'Active subscription required.', 'engintenia-platform' ) );
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$file_id = 0;
		if ( ! empty( $_FILES['quotation_file']['name'] ) ) {
			$file_id = media_handle_upload( 'quotation_file', $project_id );
			if ( is_wp_error( $file_id ) ) {
				$file_id = 0;
			}
		}

		$proposal_id = wp_insert_post(
			array(
				'post_type'    => 'eng_proposal',
				'post_status'  => 'publish',
				'post_title'   => sprintf( __( 'Proposal for Project #%d', 'engintenia-platform' ), $project_id ),
				'post_content' => isset( $_POST['proposal_message'] ) ? wp_kses_post( wp_unslash( $_POST['proposal_message'] ) ) : '',
				'post_author'  => $user_id,
			)
		);

		if ( $proposal_id ) {
			update_post_meta( $proposal_id, '_eng_project_id', $project_id );
			update_post_meta( $proposal_id, '_eng_quotation_file_id', $file_id );
			$company_id = (int) get_post_field( 'post_author', $project_id );
			Engintenia_Notifications::add_notification( $company_id, __( 'You received a new proposal.', 'engintenia-platform' ) );
			wp_mail( get_userdata( $company_id )->user_email, __( 'New Proposal', 'engintenia-platform' ), __( 'A subcontractor submitted a new proposal on your project.', 'engintenia-platform' ) );
		}

		wp_safe_redirect( wp_get_referer() ?: get_permalink( $project_id ) );
		exit;
	}
}
