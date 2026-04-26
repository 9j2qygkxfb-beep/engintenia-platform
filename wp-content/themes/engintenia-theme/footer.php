<?php
/**
 * Theme footer template.
 *
 * @package Engintenia_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main>
<footer class="site-footer">
	<div class="container footer-grid">
		<div>
			<a class="brand footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			<p><?php esc_html_e( 'The engineering marketplace for projects, proposals, and trusted contractors.', 'engintenia-theme' ); ?></p>
		</div>
		<div>
			<h4><?php esc_html_e( 'Platform', 'engintenia-theme' ); ?></h4>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'engintenia-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/projects' ) ); ?>"><?php esc_html_e( 'Projects', 'engintenia-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/register' ) ); ?>"><?php esc_html_e( 'Register', 'engintenia-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Login', 'engintenia-theme' ); ?></a></li>
			</ul>
		</div>
		<div>
			<h4><?php esc_html_e( 'Company', 'engintenia-theme' ); ?></h4>
			<ul>
				<li><a href="#"><?php esc_html_e( 'About', 'engintenia-theme' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Contact', 'engintenia-theme' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Privacy', 'engintenia-theme' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Terms', 'engintenia-theme' ); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="container footer-bottom">
		<?php
		echo esc_html(
			sprintf(
				/* translators: %d: current year */
				__( '© %d Engintenia. All rights reserved.', 'engintenia-theme' ),
				intval( gmdate( 'Y' ) )
			)
		);
		?>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
