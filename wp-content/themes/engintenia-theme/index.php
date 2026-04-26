<?php
/**
 * Main template fallback.
 *
 * @package Engintenia_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="container" style="padding:2rem 0 3rem;">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="card" style="margin-bottom:1rem;">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<div class="card">
			<p><?php esc_html_e( 'No content found yet.', 'engintenia-theme' ); ?></p>
		</div>
	<?php endif; ?>
</section>
<?php
get_footer();
