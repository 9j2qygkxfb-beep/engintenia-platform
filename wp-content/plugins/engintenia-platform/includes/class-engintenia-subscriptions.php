<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Subscriptions {
	public static function has_active_subscription( $user_id ) {
		$expires_at = get_user_meta( $user_id, 'eng_subscription_expires_at', true );
		return $expires_at && strtotime( $expires_at ) >= current_time( 'timestamp' );
	}

	public static function handle_submit() {
		if ( ! is_user_logged_in() ) {
			wp_die( esc_html__( 'You must be logged in.', 'engintenia-platform' ) );
		}
		if ( ! isset( $_POST['eng_subscription_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['eng_subscription_nonce'] ) ), 'eng_subscription_submit' ) ) {
			wp_die( esc_html__( 'Invalid request.', 'engintenia-platform' ) );
		}

		$user_id = get_current_user_id();
		if ( ! in_array( 'eng_subcontractor', (array) wp_get_current_user()->roles, true ) ) {
			wp_die( esc_html__( 'Only subcontractors can subscribe.', 'engintenia-platform' ) );
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$receipt_id = media_handle_upload( 'receipt_file', 0 );
		if ( is_wp_error( $receipt_id ) ) {
			wp_die( esc_html__( 'Please upload a valid receipt image.', 'engintenia-platform' ) );
		}

		$subscription_id = wp_insert_post(
			array(
				'post_type'   => 'eng_subscription',
				'post_status' => 'publish',
				'post_title'  => sprintf( __( 'Subscription Request #%d', 'engintenia-platform' ), $user_id ),
				'post_author' => $user_id,
			)
		);

		if ( $subscription_id ) {
			update_post_meta( $subscription_id, '_eng_subscription_status', 'pending' );
			update_post_meta( $subscription_id, '_eng_subscription_receipt_id', $receipt_id );
			update_post_meta( $subscription_id, '_eng_subscription_amount', '20' );
		}

		wp_safe_redirect( wp_get_referer() ?: home_url() );
		exit;
	}

	public static function admin_menu() {
		add_menu_page(
			__( 'Engintenia Subscriptions', 'engintenia-platform' ),
			__( 'Eng Subscriptions', 'engintenia-platform' ),
			'manage_options',
			'engintenia-subscriptions',
			array( __CLASS__, 'render_admin_page' ),
			'dashicons-money-alt'
		);
	}

	public static function render_admin_page() {
		$query = new WP_Query(
			array(
				'post_type'      => 'eng_subscription',
				'posts_per_page' => 100,
			)
		);
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Subscription Approvals', 'engintenia-platform' ); ?></h1>
			<table class="widefat striped">
				<thead><tr><th><?php esc_html_e( 'User', 'engintenia-platform' ); ?></th><th><?php esc_html_e( 'Receipt', 'engintenia-platform' ); ?></th><th><?php esc_html_e( 'Status', 'engintenia-platform' ); ?></th><th><?php esc_html_e( 'Action', 'engintenia-platform' ); ?></th></tr></thead>
				<tbody>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php
					$user_id = get_post_field( 'post_author', get_the_ID() );
					$user    = get_user_by( 'id', $user_id );
					$status  = get_post_meta( get_the_ID(), '_eng_subscription_status', true );
					$receipt = wp_get_attachment_url( (int) get_post_meta( get_the_ID(), '_eng_subscription_receipt_id', true ) );
					?>
					<tr>
						<td><?php echo esc_html( $user ? $user->user_email : '' ); ?></td>
						<td><?php if ( $receipt ) : ?><a href="<?php echo esc_url( $receipt ); ?>" target="_blank"><?php esc_html_e( 'View Receipt', 'engintenia-platform' ); ?></a><?php endif; ?></td>
						<td><?php echo esc_html( ucfirst( $status ) ); ?></td>
						<td>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
								<?php wp_nonce_field( 'eng_admin_subscription' ); ?>
								<input type="hidden" name="action" value="engintenia_update_subscription" />
								<input type="hidden" name="subscription_id" value="<?php echo esc_attr( get_the_ID() ); ?>" />
								<select name="status">
									<option value="pending"><?php esc_html_e( 'Pending', 'engintenia-platform' ); ?></option>
									<option value="approved"><?php esc_html_e( 'Approved', 'engintenia-platform' ); ?></option>
									<option value="rejected"><?php esc_html_e( 'Rejected', 'engintenia-platform' ); ?></option>
								</select>
								<button class="button button-primary"><?php esc_html_e( 'Update', 'engintenia-platform' ); ?></button>
							</form>
						</td>
					</tr>
				<?php endwhile; wp_reset_postdata(); ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function handle_admin_update() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'engintenia-platform' ) );
		}
		check_admin_referer( 'eng_admin_subscription' );

		$subscription_id = isset( $_POST['subscription_id'] ) ? absint( $_POST['subscription_id'] ) : 0;
		$status          = isset( $_POST['status'] ) ? sanitize_key( wp_unslash( $_POST['status'] ) ) : 'pending';
		update_post_meta( $subscription_id, '_eng_subscription_status', $status );

		if ( 'approved' === $status ) {
			$user_id = (int) get_post_field( 'post_author', $subscription_id );
			$expires = gmdate( 'Y-m-d H:i:s', strtotime( '+30 days', current_time( 'timestamp' ) ) );
			update_user_meta( $user_id, 'eng_subscription_expires_at', $expires );
			Engintenia_Notifications::add_notification( $user_id, __( 'Your subscription has been approved.', 'engintenia-platform' ) );
			wp_mail( get_userdata( $user_id )->user_email, __( 'Subscription Approved', 'engintenia-platform' ), __( 'Your Engintenia subscription is now active.', 'engintenia-platform' ) );
		}

		wp_safe_redirect( admin_url( 'admin.php?page=engintenia-subscriptions' ) );
		exit;
	}
}
