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
	<div class="container">
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
