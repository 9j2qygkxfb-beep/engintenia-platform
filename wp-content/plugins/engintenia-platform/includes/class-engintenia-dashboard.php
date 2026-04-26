<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Dashboard {
	public static function register_actions() {
		add_filter( 'the_content', array( __CLASS__, 'inject_project_meta' ) );
	}

	public static function inject_project_meta( $content ) {
		if ( ! is_singular( 'eng_project' ) ) {
			return $content;
		}
		$post_id = get_the_ID();
		$budget  = get_post_meta( $post_id, '_eng_budget', true );
		$country = get_post_meta( $post_id, '_eng_country', true );
		$city    = get_post_meta( $post_id, '_eng_city', true );
		$owner   = get_user_by( 'id', get_post_field( 'post_author', $post_id ) );

		$show_contact = is_user_logged_in() && ( Engintenia_Subscriptions::has_active_subscription( get_current_user_id() ) || get_current_user_id() === (int) $owner->ID || current_user_can( 'manage_options' ) );
		$contact_html = '<p><strong>' . esc_html__( 'Contact details hidden. Subscription required.', 'engintenia-platform' ) . '</strong></p>';

		if ( $show_contact && $owner ) {
			$phone        = get_user_meta( $owner->ID, 'eng_phone', true );
			$contact_html = '<ul><li><strong>' . esc_html__( 'Company', 'engintenia-platform' ) . ':</strong> ' . esc_html( $owner->display_name ) . '</li><li><strong>' . esc_html__( 'Email', 'engintenia-platform' ) . ':</strong> ' . esc_html( $owner->user_email ) . '</li><li><strong>' . esc_html__( 'Phone', 'engintenia-platform' ) . ':</strong> ' . esc_html( $phone ) . '</li></ul>';
		}

		$meta = '<div class="eng-project-meta"><p><strong>' . esc_html__( 'Budget', 'engintenia-platform' ) . ':</strong> ' . esc_html( $budget ) . '</p><p><strong>' . esc_html__( 'Location', 'engintenia-platform' ) . ':</strong> ' . esc_html( $country . ', ' . $city ) . '</p>' . $contact_html . self::proposal_form( $post_id ) . '</div>';

		return $content . $meta;
	}

	public static function proposal_form( $project_id ) {
		if ( ! is_user_logged_in() || ! in_array( 'eng_subcontractor', (array) wp_get_current_user()->roles, true ) || ! Engintenia_Subscriptions::has_active_subscription( get_current_user_id() ) ) {
			return '';
		}
		ob_start();
		?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" class="eng-form">
			<?php wp_nonce_field( 'eng_submit_proposal', 'eng_proposal_nonce' ); ?>
			<input type="hidden" name="action" value="engintenia_submit_proposal" />
			<input type="hidden" name="project_id" value="<?php echo esc_attr( $project_id ); ?>" />
			<label><?php esc_html_e( 'Proposal Message', 'engintenia-platform' ); ?></label>
			<textarea name="proposal_message" required></textarea>
			<label><?php esc_html_e( 'Quotation File', 'engintenia-platform' ); ?></label>
			<input type="file" name="quotation_file" />
			<button type="submit"><?php esc_html_e( 'Send Proposal', 'engintenia-platform' ); ?></button>
		</form>
		<?php
		return ob_get_clean();
	}

	public static function handle_register() {
		if ( ! isset( $_POST['eng_register_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['eng_register_nonce'] ) ), 'eng_register' ) ) {
			wp_die( esc_html__( 'Invalid form.', 'engintenia-platform' ) );
		}

		$email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$password = sanitize_text_field( wp_unslash( $_POST['password'] ?? '' ) );
		$role     = sanitize_key( wp_unslash( $_POST['role'] ?? '' ) );

		if ( ! in_array( $role, array( 'eng_company', 'eng_subcontractor' ), true ) ) {
			wp_die( esc_html__( 'Invalid role.', 'engintenia-platform' ) );
		}

		$user_id = wp_create_user( $email, $password, $email );
		if ( is_wp_error( $user_id ) ) {
			wp_die( esc_html( $user_id->get_error_message() ) );
		}

		wp_update_user( array( 'ID' => $user_id, 'role' => $role ) );
		update_user_meta( $user_id, 'eng_country', sanitize_text_field( wp_unslash( $_POST['country'] ?? '' ) ) );
		update_user_meta( $user_id, 'eng_city', sanitize_text_field( wp_unslash( $_POST['city'] ?? '' ) ) );
		update_user_meta( $user_id, 'eng_specialization', sanitize_text_field( wp_unslash( $_POST['specialization'] ?? '' ) ) );
		update_user_meta( $user_id, 'eng_phone', sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) ) );

		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );
		wp_safe_redirect( home_url() );
		exit;
	}

	public static function handle_project_submit() {
		if ( ! is_user_logged_in() || ! in_array( 'eng_company', (array) wp_get_current_user()->roles, true ) ) {
			wp_die( esc_html__( 'Only companies can post projects.', 'engintenia-platform' ) );
		}
		if ( ! isset( $_POST['eng_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['eng_project_nonce'] ) ), 'eng_submit_project' ) ) {
			wp_die( esc_html__( 'Invalid request.', 'engintenia-platform' ) );
		}

		$project_id = wp_insert_post(
			array(
				'post_type'    => 'eng_project',
				'post_status'  => 'publish',
				'post_title'   => sanitize_text_field( wp_unslash( $_POST['title'] ?? '' ) ),
				'post_content' => wp_kses_post( wp_unslash( $_POST['description'] ?? '' ) ),
				'post_author'  => get_current_user_id(),
			)
		);

		if ( $project_id ) {
			update_post_meta( $project_id, '_eng_budget', sanitize_text_field( wp_unslash( $_POST['budget'] ?? '' ) ) );
			update_post_meta( $project_id, '_eng_country', sanitize_text_field( wp_unslash( $_POST['country'] ?? '' ) ) );
			update_post_meta( $project_id, '_eng_city', sanitize_text_field( wp_unslash( $_POST['city'] ?? '' ) ) );
			if ( ! empty( $_POST['category'] ) ) {
				wp_set_object_terms( $project_id, sanitize_text_field( wp_unslash( $_POST['category'] ) ), 'eng_project_category' );
			}
		}

		wp_safe_redirect( wp_get_referer() ?: home_url() );
		exit;
	}
}
