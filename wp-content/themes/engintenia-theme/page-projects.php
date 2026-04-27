<?php
/**
 * Template Name: Projects Directory
 *
 * @package Engintenia_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="section-block">
	<div class="container">
		<div class="section-heading reveal-up">
			<p class="eyebrow"><?php esc_html_e( 'PROJECTS', 'engintenia-theme' ); ?></p>
			<h1><?php the_title(); ?></h1>
		</div>
		<div class="card reveal-up page-content-card">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
		<div class="reveal-up">
			<?php echo do_shortcode( '[eng_projects_list]' ); ?>
		</div>
	</div>
</section>
<?php
get_footer();
