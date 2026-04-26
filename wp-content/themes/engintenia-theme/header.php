<?php
/**
 * Theme header template.
 *
 * @package Engintenia_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<div class="container header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Engintenia home', 'engintenia-theme' ); ?>">
			<span class="brand-mark" aria-hidden="true"></span>
			<span class="brand-text"><?php bloginfo( 'name' ); ?></span>
		</a>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'engintenia-theme' ); ?>">
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav-menu',
					)
				);
				?>
			<?php else : ?>
				<ul class="nav-menu">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'engintenia-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/projects' ) ); ?>"><?php esc_html_e( 'Projects', 'engintenia-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/register' ) ); ?>"><?php esc_html_e( 'Register', 'engintenia-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Login', 'engintenia-theme' ); ?></a></li>
				</ul>
			<?php endif; ?>
		</nav>
	</div>
</header>
<main>
