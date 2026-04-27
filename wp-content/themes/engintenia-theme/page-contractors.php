<?php
/**
 * Template Name: Contractors Directory
 *
 * @package Engintenia_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$contractors = get_users(
	array(
		'role'    => 'eng_subcontractor',
		'number'  => 50,
		'orderby' => 'registered',
		'order'   => 'DESC',
	)
);
?>
<section class="section-block">
	<div class="container">
		<div class="section-heading reveal-up">
			<p class="eyebrow"><?php esc_html_e( 'CONTRACTORS', 'engintenia-theme' ); ?></p>
			<h1><?php the_title(); ?></h1>
		</div>
		<div class="card reveal-up page-content-card">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
		<div class="feature-grid contractor-grid">
			<?php if ( ! empty( $contractors ) ) : ?>
				<?php foreach ( $contractors as $contractor ) : ?>
					<?php
					$specialization = get_user_meta( $contractor->ID, 'specialization', true );
					$city           = get_user_meta( $contractor->ID, 'city', true );
					$country        = get_user_meta( $contractor->ID, 'country', true );
					?>
					<article class="card contractor-card reveal-up">
						<?php echo get_avatar( $contractor->ID, 72, '', $contractor->display_name, array( 'class' => 'contractor-avatar' ) ); ?>
						<h3><?php echo esc_html( $contractor->display_name ); ?></h3>
						<p><?php echo esc_html( $specialization ? $specialization : __( 'Specialized contractor', 'engintenia-theme' ) ); ?></p>
						<p class="eng-muted"><?php echo esc_html( trim( $city . ', ' . $country, ', ' ) ); ?></p>
					</article>
				<?php endforeach; ?>
			<?php else : ?>
				<article class="card reveal-up">
					<h3><?php esc_html_e( 'No contractors found', 'engintenia-theme' ); ?></h3>
					<p><?php esc_html_e( 'Registered subcontractors will appear here automatically.', 'engintenia-theme' ); ?></p>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
