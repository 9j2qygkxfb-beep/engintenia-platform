<?php
/**
 * Front page template.
 *
 * @package Engintenia_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="hero">
	<div class="container">
		<h1><?php esc_html_e( 'A WordPress marketplace theme for Engintenia', 'engintenia-theme' ); ?></h1>
		<p><?php esc_html_e( 'This is a native WordPress theme structure (not static HTML), ready to pair with the Engintenia platform plugin and WordPress pages/menus.', 'engintenia-theme' ); ?></p>
		<a class="btn" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize Theme', 'engintenia-theme' ); ?></a>
	</div>
</section>
<section class="container">
	<div class="grid">
		<div class="card">
			<h3><?php esc_html_e( 'Native templates', 'engintenia-theme' ); ?></h3>
			<p><?php esc_html_e( 'Uses header.php, footer.php, front-page.php, and index.php for WordPress rendering flow.', 'engintenia-theme' ); ?></p>
		</div>
		<div class="card">
			<h3><?php esc_html_e( 'Menu support', 'engintenia-theme' ); ?></h3>
			<p><?php esc_html_e( 'Assign your primary navigation from Appearance → Menus.', 'engintenia-theme' ); ?></p>
		</div>
		<div class="card">
			<h3><?php esc_html_e( 'Plugin compatible', 'engintenia-theme' ); ?></h3>
			<p><?php esc_html_e( 'Works with shortcodes from the Engintenia Platform plugin on any page.', 'engintenia-theme' ); ?></p>
		</div>
	</div>
</section>
<?php
get_footer();
