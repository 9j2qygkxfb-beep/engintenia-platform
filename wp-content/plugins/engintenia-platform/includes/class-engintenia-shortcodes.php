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
		add_shortcode( 'eng_contractors_list', array( __CLASS__, 'contractors_list' ) );
	}

	public static function countries() {
		return array( 'UAE','Saudi Arabia','Qatar','Kuwait','Bahrain','Oman','Egypt','Jordan','Lebanon','Morocco','Algeria','Tunisia','Germany','France','Italy','Spain','Netherlands','Sweden','USA','Canada','South Africa','Nigeria','Kenya' );
	}

	private static function country_options( $selected = '' ) {
		$html = '';
		foreach ( self::countries() as $country ) {
			$html .= '<option value="' . esc_attr( $country ) . '" ' . selected( $selected, $country, false ) . '>' . esc_html( $country ) . '</option>';
		}
		return $html;
	}

	public static function register_form() { /* shortened */
		if ( is_user_logged_in() ) { return '<p>' . esc_html__( 'You are already logged in.', 'engintenia-platform' ) . '</p>'; }
		ob_start(); ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="eng-form">
		<?php wp_nonce_field( 'eng_register', 'eng_register_nonce' ); ?><input type="hidden" name="action" value="engintenia_register" />
		<label>Email</label><input type="email" name="email" required />
		<label>Password</label><input type="password" name="password" required />
		<label>Role</label><select name="role" required><option value="eng_company">Client</option><option value="eng_subcontractor">Contractor</option></select>
		<label>Country</label><select name="country" required><?php echo wp_kses_post( self::country_options() ); ?></select>
		<label>City</label><input type="text" name="city" required />
		<label>Specialization</label><input type="text" name="specialization" required />
		<label>Phone</label><input type="text" name="phone" required /><button type="submit">Create Account</button></form><?php
		return ob_get_clean();
	}

	public static function project_submit_form() {
		if ( ! is_user_logged_in() || ! in_array( 'eng_company', (array) wp_get_current_user()->roles, true ) ) { return '<p>Only client accounts can post projects.</p>'; }
		$terms = get_terms( array( 'taxonomy' => 'eng_project_category', 'hide_empty' => false ) ); ob_start(); ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="eng-form"><?php wp_nonce_field( 'eng_submit_project', 'eng_project_nonce' ); ?><input type="hidden" name="action" value="engintenia_submit_project" />
		<label>Title</label><input type="text" name="title" required /><label>Description</label><textarea name="description" required></textarea>
		<label>Budget</label><input type="text" name="budget" required /><label>Duration</label><input type="text" name="duration" required />
		<label>Country</label><select name="country" required><?php echo wp_kses_post( self::country_options() ); ?></select><label>City</label><input type="text" name="city" required />
		<label>Category</label><select name="category" required><?php foreach ( $terms as $term ) : ?><option value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option><?php endforeach; ?></select>
		<button type="submit">Publish Project</button></form><?php return ob_get_clean();
	}

	public static function projects_list() {
		$meta_query = array();
		if ( ! empty( $_GET['country'] ) ) { $meta_query[] = array( 'key' => '_eng_country', 'value' => sanitize_text_field( wp_unslash( $_GET['country'] ) ) ); }
		$query = new WP_Query( array( 'post_type' => 'eng_project','posts_per_page' => 20,'meta_query' => $meta_query ) );
		ob_start(); echo '<div class="eng-grid">'; while ( $query->have_posts() ) { $query->the_post(); echo '<article class="eng-card"><h3><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3><p>' . esc_html( wp_trim_words( get_the_content(), 18 ) ) . '</p><p><strong>Country:</strong> ' . esc_html( get_post_meta( get_the_ID(), '_eng_country', true ) ) . ' <strong>Budget:</strong> ' . esc_html( get_post_meta( get_the_ID(), '_eng_budget', true ) ) . ' <strong>Duration:</strong> ' . esc_html( get_post_meta( get_the_ID(), '_eng_duration', true ) ) . '</p></article>'; } wp_reset_postdata(); echo '</div>'; return ob_get_clean();
	}
	public static function contractors_list(){ $u=get_users(array('role'=>'eng_subcontractor','number'=>8));ob_start();echo '<div class="eng-grid">';foreach($u as $c){echo '<article class="eng-card"><h4>'.esc_html($c->display_name).'</h4><p>⭐ '.esc_html(get_user_meta($c->ID,'eng_rating',true) ?: '4.8').'</p><p>'.esc_html(get_user_meta($c->ID,'eng_specialization',true)).' • '.esc_html(get_user_meta($c->ID,'eng_experience',true) ?: '7 years').'</p></article>';}echo '</div>';return ob_get_clean();}
	public static function subscription_form() { if ( ! is_user_logged_in() || ! in_array( 'eng_subcontractor', (array) wp_get_current_user()->roles, true ) ) { return '<p>Only contractors can subscribe.</p>'; } ob_start();?>
		<div class="eng-subscription-box"><p><strong>Subscription plan:</strong> $20 / month</p><p><strong>Payment Method:</strong> Manual bank transfer only</p><p>Bank: Engintenia Operations Bank | IBAN: ENGI-2026-009922</p><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" class="eng-form"><?php wp_nonce_field( 'eng_subscription_submit', 'eng_subscription_nonce' ); ?><input type="hidden" name="action" value="engintenia_submit_subscription" />
		<label>Name</label><input type="text" name="payer_name" required/><label>Amount (USD)</label><input type="number" name="amount" value="20" required/><label>Transfer Date</label><input type="date" name="transfer_date" required/><label>Upload receipt image</label><input type="file" name="receipt_file" accept="image/*" required /><button type="submit">Submit for Manual Review</button></form></div><?php return ob_get_clean(); }
	public static function company_dashboard(){return '<h2>Client Dashboard</h2>'.do_shortcode('[eng_project_submit]').'<h3>View proposals inside each project page.</h3>';}
	public static function subcontractor_dashboard(){return '<h2>Contractor Dashboard</h2>'.do_shortcode('[eng_subscription_form]').'<h3>Browse projects and submit proposals after approval.</h3>';}
}
