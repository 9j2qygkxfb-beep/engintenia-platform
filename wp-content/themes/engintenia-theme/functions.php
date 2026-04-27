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
 * Enqueue styles and scripts.
 */
function engintenia_theme_assets() {
	$theme = wp_get_theme();

	wp_enqueue_style(
		'engintenia-theme-style',
		get_stylesheet_uri(),
		array(),
		$theme->get( 'Version' )
	);

	wp_enqueue_script(
		'engintenia-theme-main',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		$theme->get( 'Version' ),
		true
	);
}


/**
 * Get a page URL by slug with a fallback URL.
 *
 * @param string $slug Page slug.
 * @param string $fallback_url Fallback URL.
 * @return string
 */
function engintenia_theme_get_page_url_or_fallback( $slug, $fallback_url ) {
	$page = get_page_by_path( sanitize_title( $slug ) );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page->ID );
	}

	return $fallback_url;
}

add_filter( 'get_search_form', 'engintenia_theme_filter_search_form' );
/**
 * Use project-first defaults in the global search form.
 *
 * @param string $form Search form markup.
 * @return string
 */
function engintenia_theme_filter_search_form( $form ) {
	$search_action = esc_url( home_url( '/' ) );
	$query_value   = get_search_query();

	ob_start();
	?>
	<form role="search" method="get" class="project-search-form" action="<?php echo $search_action; ?>">
		<label class="screen-reader-text" for="eng-search"><?php esc_html_e( 'Search projects', 'engintenia-theme' ); ?></label>
		<input id="eng-search" type="search" name="s" value="<?php echo esc_attr( $query_value ); ?>" placeholder="<?php esc_attr_e( 'Try: Industrial security installation in Dallas', 'engintenia-theme' ); ?>" required>
		<input type="hidden" name="post_type" value="eng_project">
		<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search Projects', 'engintenia-theme' ); ?></button>
	</form>
	<?php

	return ob_get_clean();
}
