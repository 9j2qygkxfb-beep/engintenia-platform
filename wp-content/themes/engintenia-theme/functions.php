<?php
/**
 * Theme setup for Engintenia Theme.
 *
 * @package Engintenia_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'engintenia_theme_setup' );
/**
 * Configure theme supports and menus.
 */
function engintenia_theme_setup() {
	load_theme_textdomain( 'engintenia-theme', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'engintenia-theme' ),
		)
	);
}

add_action( 'wp_enqueue_scripts', 'engintenia_theme_assets' );
/**
 * Enqueue styles.
 */
function engintenia_theme_assets() {
	wp_enqueue_style(
		'engintenia-theme-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
