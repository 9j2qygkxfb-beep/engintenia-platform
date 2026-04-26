<?php
/**
 * Plugin Name: Engintenia Platform
 * Description: Global contractor/subcontractor marketplace platform.
 * Version: 1.0.0
 * Author: Engintenia
 * Text Domain: engintenia-platform
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ENGINTENIA_PLATFORM_VERSION', '1.0.0' );
define( 'ENGINTENIA_PLATFORM_PATH', plugin_dir_path( __FILE__ ) );
define( 'ENGINTENIA_PLATFORM_URL', plugin_dir_url( __FILE__ ) );

require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-plugin.php';

Engintenia_Plugin::instance();
