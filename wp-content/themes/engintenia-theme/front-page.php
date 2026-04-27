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

$featured_projects_query = new WP_Query(
	array(
		'post_type'      => 'eng_project',
		'posts_per_page' => 6,
		'post_status'    => 'publish',
	)
);

$featured_contractors = get_users(
	array(
		'role'    => 'eng_subcontractor',
		'number'  => 6,
		'orderby' => 'registered',
		'order'   => 'DESC',
	)
);

$register_page_url = engintenia_theme_get_page_url_or_fallback( 'register', wp_registration_url() );
$projects_page_url = engintenia_theme_get_page_url_or_fallback( 'projects', home_url( '/?post_type=eng_project' ) );
?>
<section class="hero-section">
	<div class="hero-overlay" aria-hidden="true"></div>
	<div class="container hero-grid">
		<div class="hero-copy reveal-up">
			<p class="eyebrow"><?php esc_html_e( 'PREMIUM ENGINEERING MARKETPLACE', 'engintenia-theme' ); ?></p>
			<h1><?php esc_html_e( 'Hire Elite Engineering Contractors', 'engintenia-theme' ); ?></h1>
			<p class="hero-subtitle"><?php esc_html_e( 'Scale from one site to nationwide delivery with verified contractors, transparent milestones, and enterprise-ready project governance.', 'engintenia-theme' ); ?></p>
			<div class="hero-actions">
				<a class="btn btn-primary" href="<?php echo esc_url( $register_page_url ); ?>"><?php esc_html_e( 'Get Started', 'engintenia-theme' ); ?></a>
				<a class="btn btn-outline" href="<?php echo esc_url( $projects_page_url ); ?>"><?php esc_html_e( 'Browse Projects', 'engintenia-theme' ); ?></a>
			</div>
		</div>
		<div class="hero-card reveal-up">
			<h3><?php esc_html_e( 'Search premium projects and specialists', 'engintenia-theme' ); ?></h3>
			<?php get_search_form(); ?>
		</div>
	</div>
</section>

<?php if ( have_posts() ) : ?>
	<section class="section-block intro-content">
		<div class="container reveal-up">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	</section>
<?php endif; ?>

<section class="section-block section-surface">
	<div class="container">
		<div class="section-heading heading-row reveal-up">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'FEATURED PROJECTS', 'engintenia-theme' ); ?></p>
				<h2><?php esc_html_e( 'Latest opportunities from leading Engintenia companies', 'engintenia-theme' ); ?></h2>
			</div>
			<a class="btn btn-outline" href="<?php echo esc_url( $projects_page_url ); ?>"><?php esc_html_e( 'View All Projects', 'engintenia-theme' ); ?></a>
		</div>
		<div class="feature-grid project-grid">
			<?php if ( $featured_projects_query->have_posts() ) : ?>
				<?php while ( $featured_projects_query->have_posts() ) : ?>
					<?php
					$featured_projects_query->the_post();
					$budget   = get_post_meta( get_the_ID(), '_eng_budget', true );
					$location = get_post_meta( get_the_ID(), '_eng_location', true );
					?>
					<article class="card project-card reveal-up">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
						<div class="card-meta">
							<span><?php esc_html_e( 'Budget', 'engintenia-theme' ); ?>: <?php echo $budget ? esc_html( $budget ) : esc_html__( 'To be discussed', 'engintenia-theme' ); ?></span>
							<span><?php esc_html_e( 'Location', 'engintenia-theme' ); ?>: <?php echo $location ? esc_html( $location ) : esc_html__( 'Remote / On-site', 'engintenia-theme' ); ?></span>
						</div>
						<a class="btn btn-outline" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Project', 'engintenia-theme' ); ?></a>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<article class="card reveal-up">
					<h3><?php esc_html_e( 'No projects yet', 'engintenia-theme' ); ?></h3>
					<p><?php esc_html_e( 'New projects will appear here automatically once they are published via Engintenia.', 'engintenia-theme' ); ?></p>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section-block">
	<div class="container">
		<div class="section-heading reveal-up">
			<p class="eyebrow"><?php esc_html_e( 'TOP CONTRACTORS', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'Top-rated specialists ready for enterprise deployment', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="feature-grid contractor-grid">
			<?php if ( ! empty( $featured_contractors ) ) : ?>
				<?php foreach ( $featured_contractors as $contractor ) : ?>
					<?php
					$specialization = get_user_meta( $contractor->ID, 'specialization', true );
					$location       = get_user_meta( $contractor->ID, 'city', true );
					?>
					<article class="card contractor-card reveal-up">
						<?php echo get_avatar( $contractor->ID, 72, '', $contractor->display_name, array( 'class' => 'contractor-avatar' ) ); ?>
						<h3><?php echo esc_html( $contractor->display_name ); ?></h3>
						<p><?php echo esc_html( $specialization ? $specialization : __( 'Specialized contractor', 'engintenia-theme' ) ); ?></p>
						<div class="card-meta">
							<span><?php echo esc_html( $location ? $location : __( 'Location available on profile', 'engintenia-theme' ) ); ?></span>
						</div>
					</article>
				<?php endforeach; ?>
			<?php else : ?>
				<article class="card reveal-up">
					<h3><?php esc_html_e( 'Contractors onboarding', 'engintenia-theme' ); ?></h3>
					<p><?php esc_html_e( 'Featured contractor profiles will appear once users register as subcontractors.', 'engintenia-theme' ); ?></p>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
