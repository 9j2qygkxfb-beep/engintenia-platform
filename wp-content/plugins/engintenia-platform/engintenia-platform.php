<?php
/**
 * Plugin Name: Engintenia Platform
 * Plugin URI: https://engintenia.example.com
 * Description: Engineering marketplace core plugin for projects, proposals, subscriptions, messaging, and ratings.
 * Version: 1.0.0
 * Author: Engintenia
 * Text Domain: engintenia-platform
 * Domain Path: /languages
 */

if (! defined('ABSPATH')) {
    exit;
}

define('ENGINTENIA_PLATFORM_VERSION', '1.0.0');
define('ENGINTENIA_PLATFORM_PATH', plugin_dir_path(__FILE__));
define('ENGINTENIA_PLATFORM_URL', plugin_dir_url(__FILE__));

require_once ENGINTENIA_PLATFORM_PATH . 'includes/class-engintenia-platform.php';

Engintenia_Platform::instance();
