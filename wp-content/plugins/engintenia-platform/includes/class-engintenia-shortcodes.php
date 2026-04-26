<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Shortcodes {
	public static function register() {
		add_shortcode( 'eng_register_form', array( __CLASS__, 'register_form' ) );
		add_shortcode( 'eng_projects_list', array( __CLASS__, 'projects_list' ) );
		add_shortcode( 'eng_project_submit', array( __CLASS__, 'project_submit_form' ) );
		add_shortcode( 'eng_subscription_form', array( __CLASS__, 'subscription_form' ) );
		add_shortcode( 'eng_company_dashboard', array( __CLASS__, 'company_dashboard' ) );
		add_shortcode( 'eng_subcontractor_dashboard', array( __CLASS__, 'subcontractor_dashboard' ) );
	}

	public static function register_form() {
		if ( is_user_logged_in() ) {
			return '<p>' . esc_html__( 'You are already logged in.', 'engintenia-platform' ) . '</p>';
		}
		ob_start();
		?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="eng-form">
			<?php wp_nonce_field( 'eng_register', 'eng_register_nonce' ); ?>
			<input type="hidden" name="action" value="engintenia_register" />
			<label><?php esc_html_e( 'Email', 'engintenia-platform' ); ?></label>
			<input type="email" name="email" required />
			<label><?php esc_html_e( 'Password', 'engintenia-platform' ); ?></label>
			<input type="password" name="password" required />
			<label><?php esc_html_e( 'Role', 'engintenia-platform' ); ?></label>
			<select name="role" required><option value="eng_company"><?php esc_html_e( 'Company', 'engintenia-platform' ); ?></option><option value="eng_subcontractor"><?php esc_html_e( 'Subcontractor', 'engintenia-platform' ); ?></option></select>
			<label><?php esc_html_e( 'Country', 'engintenia-platform' ); ?></label><input type="text" name="country" required />
			<label><?php esc_html_e( 'City', 'engintenia-platform' ); ?></label><input type="text" name="city" required />
			<label><?php esc_html_e( 'Specialization', 'engintenia-platform' ); ?></label><input type="text" name="specialization" required />
			<label><?php esc_html_e( 'Phone', 'engintenia-platform' ); ?></label><input type="text" name="phone" required />
			<button type="submit"><?php esc_html_e( 'Create Account', 'engintenia-platform' ); ?></button>
		</form>
		<?php
		return ob_get_clean();
	}

	public static function project_submit_form() {
		if ( ! is_user_logged_in() || ! in_array( 'eng_company', (array) wp_get_current_user()->roles, true ) ) {
			return '<p>' . esc_html__( 'Only company accounts can post projects.', 'engintenia-platform' ) . '</p>';
		}
		$terms = get_terms( array( 'taxonomy' => 'eng_project_category', 'hide_empty' => false ) );
		ob_start();
		?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="eng-form">
			<?php wp_nonce_field( 'eng_submit_project', 'eng_project_nonce' ); ?>
			<input type="hidden" name="action" value="engintenia_submit_project" />
			<label><?php esc_html_e( 'Title', 'engintenia-platform' ); ?></label><input type="text" name="title" required />
			<label><?php esc_html_e( 'Description', 'engintenia-platform' ); ?></label><textarea name="description" required></textarea>
			<label><?php esc_html_e( 'Budget', 'engintenia-platform' ); ?></label><input type="text" name="budget" required />
			<label><?php esc_html_e( 'Country', 'engintenia-platform' ); ?></label><input type="text" name="country" required />
			<label><?php esc_html_e( 'City', 'engintenia-platform' ); ?></label><input type="text" name="city" required />
			<label><?php esc_html_e( 'Category', 'engintenia-platform' ); ?></label>
			<select name="category" required>
				<?php foreach ( $terms as $term ) : ?>
					<option value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option>
				<?php endforeach; ?>
			</select>
			<button type="submit"><?php esc_html_e( 'Publish Project', 'engintenia-platform' ); ?></button>
		</form>
		<?php
		return ob_get_clean();
	}

	public static function projects_list() {
		$query = new WP_Query(
			array(
				'post_type'      => 'eng_project',
				'posts_per_page' => 20,
			)
		);
		ob_start();
		echo '<div class="eng-grid">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<article class="eng-card">';
			echo '<h3><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
			echo '<p>' . esc_html( wp_trim_words( get_the_excerpt(), 25 ) ) . '</p>';
			echo '<p><strong>' . esc_html__( 'Budget:', 'engintenia-platform' ) . '</strong> ' . esc_html( get_post_meta( get_the_ID(), '_eng_budget', true ) ) . '</p>';
			echo '</article>';
		}
		wp_reset_postdata();
		echo '</div>';
		return ob_get_clean();
	}

	public static function subscription_form() {
		if ( ! is_user_logged_in() || ! in_array( 'eng_subcontractor', (array) wp_get_current_user()->roles, true ) ) {
			return '<p>' . esc_html__( 'Only subcontractors can subscribe.', 'engintenia-platform' ) . '</p>';
		}

		$status = Engintenia_Subscriptions::has_active_subscription( get_current_user_id() ) ? __( 'Active', 'engintenia-platform' ) : __( 'Inactive', 'engintenia-platform' );
		ob_start();
		?>
		<div class="eng-subscription-box">
			<p><strong><?php esc_html_e( 'Subscription plan:', 'engintenia-platform' ); ?></strong> $20 / <?php esc_html_e( 'month', 'engintenia-platform' ); ?></p>
			<p><strong><?php esc_html_e( 'Payment Method:', 'engintenia-platform' ); ?></strong> <?php esc_html_e( 'Bank Transfer Only', 'engintenia-platform' ); ?></p>
			<p><strong><?php esc_html_e( 'Current status:', 'engintenia-platform' ); ?></strong> <?php echo esc_html( $status ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" class="eng-form">
				<?php wp_nonce_field( 'eng_subscription_submit', 'eng_subscription_nonce' ); ?>
				<input type="hidden" name="action" value="engintenia_submit_subscription" />
				<label><?php esc_html_e( 'Upload transfer receipt image', 'engintenia-platform' ); ?></label>
				<input type="file" name="receipt_file" accept="image/*" required />
				<button type="submit"><?php esc_html_e( 'Submit for Approval', 'engintenia-platform' ); ?></button>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function company_dashboard() {
		if ( ! is_user_logged_in() || ! in_array( 'eng_company', (array) wp_get_current_user()->roles, true ) ) {
			return '';
		}
		$projects = get_posts( array( 'post_type' => 'eng_project', 'author' => get_current_user_id(), 'numberposts' => 50 ) );
		$proposals = get_posts( array( 'post_type' => 'eng_proposal', 'numberposts' => 100 ) );
		ob_start();
		echo '<h2>' . esc_html__( 'Company Dashboard', 'engintenia-platform' ) . '</h2>';
		echo do_shortcode( '[eng_project_submit]' );
		echo '<h3>' . esc_html__( 'Your Projects', 'engintenia-platform' ) . '</h3><ul>';
		foreach ( $projects as $project ) {
			echo '<li>' . esc_html( $project->post_title ) . '</li>';
		}
		echo '</ul><h3>' . esc_html__( 'Received Proposals', 'engintenia-platform' ) . '</h3><ul>';
		foreach ( $proposals as $proposal ) {
			$project_id = (int) get_post_meta( $proposal->ID, '_eng_project_id', true );
			if ( get_post_field( 'post_author', $project_id ) !== (string) get_current_user_id() ) {
				continue;
			}
			echo '<li>' . esc_html( $proposal->post_title ) . '</li>';
		}
		echo '</ul>';
		return ob_get_clean();
	}

	public static function subcontractor_dashboard() {
		if ( ! is_user_logged_in() || ! in_array( 'eng_subcontractor', (array) wp_get_current_user()->roles, true ) ) {
			return '';
		}

		$proposals     = get_posts( array( 'post_type' => 'eng_proposal', 'author' => get_current_user_id(), 'numberposts' => 100 ) );
		$notifications = get_user_meta( get_current_user_id(), 'eng_notifications', true );
		$notifications = is_array( $notifications ) ? array_reverse( $notifications ) : array();

		ob_start();
		echo '<h2>' . esc_html__( 'Subcontractor Dashboard', 'engintenia-platform' ) . '</h2>';
		echo do_shortcode( '[eng_subscription_form]' );
		echo '<h3>' . esc_html__( 'Submitted Offers', 'engintenia-platform' ) . '</h3><ul>';
		foreach ( $proposals as $proposal ) {
			echo '<li>' . esc_html( $proposal->post_title ) . '</li>';
		}
		echo '</ul><h3>' . esc_html__( 'Notifications', 'engintenia-platform' ) . '</h3><ul>';
		foreach ( $notifications as $notice ) {
			echo '<li>' . esc_html( $notice['date'] . ' - ' . $notice['message'] ) . '</li>';
		}
		echo '</ul>';
		return ob_get_clean();
	}
}
