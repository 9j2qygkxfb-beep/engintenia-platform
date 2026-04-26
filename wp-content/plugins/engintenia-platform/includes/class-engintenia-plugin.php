<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Plugin {
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->includes();
		$this->hooks();
	}

	private function includes() {
		require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-roles.php';
		require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-post-types.php';
		require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-subscriptions.php';
		require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-proposals.php';
		require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-notifications.php';
		require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-dashboard.php';
		require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-rest.php';
		require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-shortcodes.php';
	}

	private function hooks() {
		register_activation_hook( ENGINTENIA_PLATFORM_PATH . 'engintenia-platform.php', array( 'Engintenia_Roles', 'activate' ) );
		register_activation_hook( ENGINTENIA_PLATFORM_PATH . 'engintenia-platform.php', array( 'Engintenia_Post_Types', 'activate' ) );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( 'Engintenia_Post_Types', 'register' ) );
		add_action( 'init', array( 'Engintenia_Shortcodes', 'register' ) );
		add_action( 'init', array( 'Engintenia_Dashboard', 'register_actions' ) );

		add_action( 'admin_menu', array( 'Engintenia_Subscriptions', 'admin_menu' ) );
		add_action( 'admin_post_engintenia_update_subscription', array( 'Engintenia_Subscriptions', 'handle_admin_update' ) );
		add_action( 'admin_post_engintenia_register', array( 'Engintenia_Dashboard', 'handle_register' ) );
		add_action( 'admin_post_nopriv_engintenia_register', array( 'Engintenia_Dashboard', 'handle_register' ) );
		add_action( 'admin_post_engintenia_submit_project', array( 'Engintenia_Dashboard', 'handle_project_submit' ) );
		add_action( 'admin_post_engintenia_submit_subscription', array( 'Engintenia_Subscriptions', 'handle_submit' ) );
		add_action( 'admin_post_engintenia_submit_proposal', array( 'Engintenia_Proposals', 'handle_submit' ) );

		add_action( 'publish_eng_project', array( 'Engintenia_Notifications', 'notify_subcontractors_new_project' ), 10, 2 );

		add_action( 'rest_api_init', array( 'Engintenia_REST', 'register_routes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'engintenia-platform', false, dirname( plugin_basename( ENGINTENIA_PLATFORM_PATH . 'engintenia-platform.php' ) ) . '/languages' );
	}

	public function enqueue_assets() {
		wp_enqueue_style( 'engintenia-platform', ENGINTENIA_PLATFORM_URL . 'assets/css/style.css', array(), ENGINTENIA_PLATFORM_VERSION );
	}
}
