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
?>
<section class="hero-section">
	<div class="container hero-grid">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'ENGINTENIA MARKETPLACE', 'engintenia-theme' ); ?></p>
			<h1><?php esc_html_e( 'Hire proven contractors for every engineering project.', 'engintenia-theme' ); ?></h1>
			<p class="hero-subtitle"><?php esc_html_e( 'Discover skilled subcontractors, post projects in minutes, and manage delivery from one modern platform.', 'engintenia-theme' ); ?></p>
			<div class="hero-actions">
				<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/register' ) ); ?>"><?php esc_html_e( 'Get Started', 'engintenia-theme' ); ?></a>
				<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/register' ) ); ?>"><?php esc_html_e( 'Become a Contractor', 'engintenia-theme' ); ?></a>
			</div>
		</div>
		<div class="hero-card">
			<h3><?php esc_html_e( 'Find projects fast', 'engintenia-theme' ); ?></h3>
			<form class="project-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="eng-search"><?php esc_html_e( 'Search projects', 'engintenia-theme' ); ?></label>
				<input id="eng-search" type="search" name="s" placeholder="<?php esc_attr_e( 'Search by title, trade, or city', 'engintenia-theme' ); ?>" required>
				<input type="hidden" name="post_type" value="eng_project">
				<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search Projects', 'engintenia-theme' ); ?></button>
			</form>
		</div>
	</div>
</section>

<section class="section-block">
	<div class="container">
		<div class="section-heading">
			<p class="eyebrow"><?php esc_html_e( 'HOW IT WORKS', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'Simple workflow for companies and contractors', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="feature-grid steps-grid">
			<article class="card">
				<span class="step-index">01</span>
				<h3><?php esc_html_e( 'Post your project', 'engintenia-theme' ); ?></h3>
				<p><?php esc_html_e( 'Create a project with your scope, budget, timeline, and location.', 'engintenia-theme' ); ?></p>
			</article>
			<article class="card">
				<span class="step-index">02</span>
				<h3><?php esc_html_e( 'Review proposals', 'engintenia-theme' ); ?></h3>
				<p><?php esc_html_e( 'Qualified contractors submit offers tailored to your requirements.', 'engintenia-theme' ); ?></p>
			</article>
			<article class="card">
				<span class="step-index">03</span>
				<h3><?php esc_html_e( 'Hire and collaborate', 'engintenia-theme' ); ?></h3>
				<p><?php esc_html_e( 'Select the right team and track progress from your dashboard.', 'engintenia-theme' ); ?></p>
			</article>
		</div>
	</div>
</section>

<section class="section-block section-surface">
	<div class="container">
		<div class="section-heading heading-row">
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
					$budget = get_post_meta( get_the_ID(), '_eng_budget', true );
					?>
					<article class="card project-card">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
						<div class="card-meta">
							<span><?php esc_html_e( 'Budget', 'engintenia-theme' ); ?>: <?php echo $budget ? esc_html( $budget ) : esc_html__( 'To be discussed', 'engintenia-theme' ); ?></span>
						</div>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<article class="card">
					<h3><?php esc_html_e( 'No projects yet', 'engintenia-theme' ); ?></h3>
					<p><?php esc_html_e( 'New projects will appear here automatically once they are published via Engintenia.', 'engintenia-theme' ); ?></p>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section-block">
	<div class="container">
		<div class="section-heading">
			<p class="eyebrow"><?php esc_html_e( 'FEATURED CONTRACTORS', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'Top subcontractors ready for your next job', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="feature-grid contractor-grid">
			<?php if ( ! empty( $featured_contractors ) ) : ?>
				<?php foreach ( $featured_contractors as $contractor ) : ?>
					<article class="card contractor-card">
						<h3><?php echo esc_html( $contractor->display_name ); ?></h3>
						<p><?php echo esc_html( get_user_meta( $contractor->ID, 'specialization', true ) ?: __( 'Specialized contractor', 'engintenia-theme' ) ); ?></p>
						<div class="card-meta">
							<span><?php echo esc_html( get_user_meta( $contractor->ID, 'city', true ) ?: __( 'Location available on profile', 'engintenia-theme' ) ); ?></span>
						</div>
					</article>
				<?php endforeach; ?>
			<?php else : ?>
				<article class="card">
					<h3><?php esc_html_e( 'Contractors onboarding', 'engintenia-theme' ); ?></h3>
					<p><?php esc_html_e( 'Featured contractor profiles will appear once users register as subcontractors.', 'engintenia-theme' ); ?></p>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section-block section-surface">
	<div class="container">
		<div class="section-heading">
			<p class="eyebrow"><?php esc_html_e( 'PRICING', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'Choose the plan that fits your growth stage', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="feature-grid pricing-grid">
			<article class="card pricing-card">
				<h3><?php esc_html_e( 'Starter', 'engintenia-theme' ); ?></h3>
				<p class="price"><?php esc_html_e( '$0', 'engintenia-theme' ); ?><span><?php esc_html_e( '/month', 'engintenia-theme' ); ?></span></p>
				<ul>
					<li><?php esc_html_e( 'Create account profile', 'engintenia-theme' ); ?></li>
					<li><?php esc_html_e( 'Browse all public projects', 'engintenia-theme' ); ?></li>
					<li><?php esc_html_e( 'Basic notifications', 'engintenia-theme' ); ?></li>
				</ul>
			</article>
			<article class="card pricing-card featured-plan">
				<span class="plan-badge"><?php esc_html_e( 'Most Popular', 'engintenia-theme' ); ?></span>
				<h3><?php esc_html_e( 'Professional', 'engintenia-theme' ); ?></h3>
				<p class="price"><?php esc_html_e( '$20', 'engintenia-theme' ); ?><span><?php esc_html_e( '/month', 'engintenia-theme' ); ?></span></p>
				<ul>
					<li><?php esc_html_e( 'Unlimited project bids', 'engintenia-theme' ); ?></li>
					<li><?php esc_html_e( 'Priority profile visibility', 'engintenia-theme' ); ?></li>
					<li><?php esc_html_e( 'Direct company notifications', 'engintenia-theme' ); ?></li>
				</ul>
			</article>
			<article class="card pricing-card">
				<h3><?php esc_html_e( 'Enterprise', 'engintenia-theme' ); ?></h3>
				<p class="price"><?php esc_html_e( 'Custom', 'engintenia-theme' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'Multi-team management', 'engintenia-theme' ); ?></li>
					<li><?php esc_html_e( 'Dedicated account support', 'engintenia-theme' ); ?></li>
					<li><?php esc_html_e( 'Custom workflow integrations', 'engintenia-theme' ); ?></li>
				</ul>
			</article>
		</div>
	</div>
</section>

<section class="section-block">
	<div class="container">
		<div class="section-heading">
			<p class="eyebrow"><?php esc_html_e( 'TESTIMONIALS', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'Trusted by engineering teams across the region', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="feature-grid testimonial-grid">
			<article class="card testimonial-card">
				<p><?php esc_html_e( '“Engintenia helped us reduce subcontractor hiring time by over 60% in our latest tower project.”', 'engintenia-theme' ); ?></p>
				<strong><?php esc_html_e( 'Operations Manager, GulfBuild Co.', 'engintenia-theme' ); ?></strong>
			</article>
			<article class="card testimonial-card">
				<p><?php esc_html_e( '“A clean marketplace with serious contractors. Our tender process is now much faster.”', 'engintenia-theme' ); ?></p>
				<strong><?php esc_html_e( 'Procurement Lead, Atlas MEP', 'engintenia-theme' ); ?></strong>
			</article>
			<article class="card testimonial-card">
				<p><?php esc_html_e( '“The subscription model is straightforward and we consistently receive qualified leads.”', 'engintenia-theme' ); ?></p>
				<strong><?php esc_html_e( 'Founder, Prime Electromech', 'engintenia-theme' ); ?></strong>
			</article>
		</div>
	</div>
</section>

<section class="section-block">
	<div class="container cta-banner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'READY TO SCALE?', 'engintenia-theme' ); ?></p>
			<h2><?php esc_html_e( 'Join Engintenia and start building better project teams today.', 'engintenia-theme' ); ?></h2>
		</div>
		<div class="hero-actions">
			<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/register' ) ); ?>"><?php esc_html_e( 'Create Account', 'engintenia-theme' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
