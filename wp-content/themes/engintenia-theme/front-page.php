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
		'number'  => 4,
		'orderby' => 'registered',
		'order'   => 'DESC',
	)
);

$categories = array(
	array(
		'icon'  => '📹',
		'title' => __( 'CCTV Systems', 'engintenia-theme' ),
	),
	array(
		'icon'  => '🌐',
		'title' => __( 'Networking', 'engintenia-theme' ),
	),
	array(
		'icon'  => '☀️',
		'title' => __( 'Solar Installations', 'engintenia-theme' ),
	),
	array(
		'icon'  => '🛡️',
		'title' => __( 'Security', 'engintenia-theme' ),
	),
	array(
		'icon'  => '⚡',
		'title' => __( 'Electrical Works', 'engintenia-theme' ),
	),
	array(
		'icon'  => '🏗️',
		'title' => __( 'MEP Contracting', 'engintenia-theme' ),
	),
);
?>
<section class="hero-section">
	<div class="hero-overlay" aria-hidden="true"></div>
	<div class="container hero-grid">
		<div class="hero-copy reveal-up">
			<p class="eyebrow"><?php esc_html_e( 'ENGINTENIA MARKETPLACE', 'engintenia-theme' ); ?></p>
			<h1><?php esc_html_e( 'Hire top engineering contractors', 'engintenia-theme' ); ?></h1>
			<p class="hero-subtitle"><?php esc_html_e( 'Connect with vetted professionals for CCTV, networking, security, solar, and complex engineering delivery.', 'engintenia-theme' ); ?></p>
			<div class="hero-actions">
				<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/register' ) ); ?>"><?php esc_html_e( 'Get Started', 'engintenia-theme' ); ?></a>
				<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/register' ) ); ?>"><?php esc_html_e( 'Become Contractor', 'engintenia-theme' ); ?></a>
			</div>
		</div>
		<div class="hero-card reveal-up">
			<h3><?php esc_html_e( 'Search projects and services', 'engintenia-theme' ); ?></h3>
			<form class="project-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="eng-search"><?php esc_html_e( 'Search projects', 'engintenia-theme' ); ?></label>
				<input id="eng-search" type="search" name="s" placeholder="<?php esc_attr_e( 'Try: Solar maintenance in Houston', 'engintenia-theme' ); ?>" required>
				<input type="hidden" name="post_type" value="eng_project">
				<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search Projects', 'engintenia-theme' ); ?></button>
			</form>
		</div>
	</div>
</section>

<section class="section-block">
	<div class="container">
		<div class="section-heading reveal-up">
			<p class="eyebrow"><?php esc_html_e( 'CATEGORIES', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'Explore high-demand engineering services', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="categories-grid">
			<?php foreach ( $categories as $category ) : ?>
				<article class="card category-card reveal-up">
					<span class="category-icon" aria-hidden="true"><?php echo esc_html( $category['icon'] ); ?></span>
					<h3><?php echo esc_html( $category['title'] ); ?></h3>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section-block section-surface">
	<div class="container">
		<div class="section-heading heading-row reveal-up">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'FEATURED PROJECTS', 'engintenia-theme' ); ?></p>
				<h2><?php esc_html_e( 'Latest opportunities from Engintenia companies', 'engintenia-theme' ); ?></h2>
			</div>
			<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/projects' ) ); ?>"><?php esc_html_e( 'View All Projects', 'engintenia-theme' ); ?></a>
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
			<h2><?php esc_html_e( 'Top-rated specialists ready for deployment', 'engintenia-theme' ); ?></h2>
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
						<p class="rating" aria-label="<?php esc_attr_e( 'Rated 5 out of 5', 'engintenia-theme' ); ?>">★★★★★</p>
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

<section class="section-block section-surface">
	<div class="container">
		<div class="section-heading reveal-up">
			<p class="eyebrow"><?php esc_html_e( 'HOW IT WORKS', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'From requirement to execution in 3 steps', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="feature-grid steps-grid">
			<article class="card step-card reveal-up">
				<div class="step-icon" aria-hidden="true">📝</div>
				<h3><?php esc_html_e( 'Post project brief', 'engintenia-theme' ); ?></h3>
				<p><?php esc_html_e( 'Share scope, timeline, and preferred budget in minutes.', 'engintenia-theme' ); ?></p>
			</article>
			<article class="card step-card reveal-up">
				<div class="step-icon" aria-hidden="true">🤝</div>
				<h3><?php esc_html_e( 'Compare top bids', 'engintenia-theme' ); ?></h3>
				<p><?php esc_html_e( 'Review proposals, ratings, and experience side by side.', 'engintenia-theme' ); ?></p>
			</article>
			<article class="card step-card reveal-up">
				<div class="step-icon" aria-hidden="true">🚀</div>
				<h3><?php esc_html_e( 'Hire and manage', 'engintenia-theme' ); ?></h3>
				<p><?php esc_html_e( 'Award the project and track delivery through your dashboard.', 'engintenia-theme' ); ?></p>
			</article>
		</div>
	</div>
</section>

<section class="section-block testimonials-wrap">
	<div class="container">
		<div class="section-heading reveal-up">
			<p class="eyebrow"><?php esc_html_e( 'TESTIMONIALS', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'What clients say about Engintenia', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="testimonials-slider reveal-up" data-slider>
			<article class="card testimonial-card is-active">
				<p><?php esc_html_e( 'Engintenia helped us close contractor hiring in 5 days instead of 3 weeks. The quality of specialists was exceptional.', 'engintenia-theme' ); ?></p>
				<strong><?php esc_html_e( 'Operations Director, Nexa Infra', 'engintenia-theme' ); ?></strong>
			</article>
			<article class="card testimonial-card">
				<p><?php esc_html_e( 'We sourced certified CCTV and security teams for multiple branches through one streamlined platform.', 'engintenia-theme' ); ?></p>
				<strong><?php esc_html_e( 'Procurement Lead, Sentinel Group', 'engintenia-theme' ); ?></strong>
			</article>
			<article class="card testimonial-card">
				<p><?php esc_html_e( 'The interface feels premium and our PM team can monitor every job from one central workspace.', 'engintenia-theme' ); ?></p>
				<strong><?php esc_html_e( 'Project Manager, Voltaxis Energy', 'engintenia-theme' ); ?></strong>
			</article>
		</div>
	</div>
</section>
<?php
get_footer();
