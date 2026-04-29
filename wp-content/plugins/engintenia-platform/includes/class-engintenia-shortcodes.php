<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Shortcodes {
	public static function register() {
		add_shortcode( 'eng_register_form', array( __CLASS__, 'register_form' ) );
		add_shortcode( 'eng_projects_list', array( __CLASS__, 'projects_list' ) );
		add_shortcode( 'eng_project_submit', array( __CLASS__, 'project_submit_form' ) );
		add_shortcode( 'eng_subscription_form', array( __CLASS__, 'subscription_form' ) );
		add_shortcode( 'eng_company_dashboard', array( __CLASS__, 'company_dashboard' ) );
		add_shortcode( 'eng_subcontractor_dashboard', array( __CLASS__, 'subcontractor_dashboard' ) );
		add_shortcode( 'eng_contractors_list', array( __CLASS__, 'contractors_list' ) );
	}
	public static function countries() { return array( 'UAE','Saudi Arabia','Qatar','Kuwait','Bahrain','Oman','Egypt','Jordan','Lebanon','Morocco','Algeria','Tunisia','Germany','France','Italy','Spain','Netherlands','Sweden','USA','Canada','South Africa','Nigeria','Kenya' ); }
	private static function country_options( $selected = '' ) { $html = ''; foreach ( self::countries() as $country ) { $html .= '<option value="' . esc_attr( $country ) . '" ' . selected( $selected, $country, false ) . '>' . esc_html( $country ) . '</option>'; } return $html; }
	public static function register_form(){return '<p>Registration available.</p>';}
	public static function project_submit_form(){return '<p>Project submission form available from dashboard.</p>';}

	public static function projects_list() {
		$country = isset($_GET['country']) ? sanitize_text_field(wp_unslash($_GET['country'])) : '';
		$category = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';
		$min = isset($_GET['budget_min']) ? (int) $_GET['budget_min'] : 0;
		$max = isset($_GET['budget_max']) ? (int) $_GET['budget_max'] : 200000;
		$meta_query = array('relation' => 'AND');
		if ($country) { $meta_query[] = array('key' => '_eng_country', 'value' => $country); }
		$meta_query[] = array('key' => '_eng_budget', 'value' => array($min, $max), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
		$tax_query = array();
		if ($category) { $tax_query[] = array('taxonomy' => 'eng_project_category','field' => 'slug','terms' => $category); }
		$query = new WP_Query(array('post_type'=>'eng_project','posts_per_page'=>24,'meta_query'=>$meta_query,'tax_query'=>$tax_query));
		$terms = get_terms(array('taxonomy'=>'eng_project_category','hide_empty'=>false));
		$images = array(
			'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80',
			'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=1200&q=80',
			'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=1200&q=80',
			'https://images.unsplash.com/photo-1599707367072-cd6ada2bc375?auto=format&fit=crop&w=1200&q=80',
		);
		ob_start(); ?>
		<form method="get" class="market-filters eng-grid eng-grid-4">
			<select name="country"><option value="">All Countries</option><?php echo wp_kses_post(self::country_options($country)); ?></select>
			<select name="category"><option value="">All Categories</option><?php foreach ($terms as $t): ?><option value="<?php echo esc_attr($t->slug); ?>" <?php selected($category,$t->slug); ?>><?php echo esc_html($t->name); ?></option><?php endforeach; ?></select>
			<input type="number" name="budget_min" value="<?php echo esc_attr((string)$min); ?>" placeholder="Min budget">
			<input type="number" name="budget_max" value="<?php echo esc_attr((string)$max); ?>" placeholder="Max budget">
			<button class="eng-btn" type="submit">Filter</button>
		</form>
		<div class="market-grid market-grid-3">
		<?php $i=0; while ($query->have_posts()): $query->the_post(); $budget=get_post_meta(get_the_ID(),'_eng_budget',true); $ct=get_post_meta(get_the_ID(),'_eng_country',true); $cat=wp_get_post_terms(get_the_ID(),'eng_project_category'); $img=get_the_post_thumbnail_url(get_the_ID(),'large'); if(!$img){$img=$images[$i%count($images)];} $i++; ?>
		<article class="market-card">
			<img src="<?php echo esc_url($img); ?>" alt="<?php the_title_attribute(); ?>">
			<div class="card-content"><h3><?php the_title(); ?></h3><p><?php echo esc_html(wp_trim_words(get_the_content(),14)); ?></p><ul><li><strong>Country:</strong> <?php echo esc_html($ct ?: 'N/A'); ?></li><li><strong>Budget:</strong> $<?php echo esc_html($budget ?: '0'); ?></li><li><strong>Category:</strong> <?php echo esc_html(!empty($cat)?$cat[0]->name:'General'); ?></li></ul></div>
		</article>
		<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php return ob_get_clean();
	}
	public static function contractors_list(){ $u=get_users(array('role'=>'eng_subcontractor','number'=>12)); $avatars=array('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80','https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=400&q=80','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80'); ob_start(); echo '<div class="market-grid market-grid-3">'; $i=0; foreach($u as $c){ $exp=get_user_meta($c->ID,'eng_experience',true) ?: '7 years'; $cat=get_user_meta($c->ID,'eng_specialization',true) ?: 'General Contracting'; echo '<article class="market-card contractor-card"><img src="'.esc_url($avatars[$i%3]).'" alt="'.esc_attr($c->display_name).'"><div class="card-content"><h3>'.esc_html($c->display_name).'</h3><p class="stars">★★★★★</p><p><strong>Experience:</strong> '.esc_html($exp).'</p><p><strong>Category:</strong> '.esc_html($cat).'</p></div></article>'; $i++; } echo '</div>'; return ob_get_clean();}
	public static function subscription_form(){ob_start(); ?>
	<form method="post" enctype="multipart/form-data" class="eng-card eng-form">
		<?php wp_nonce_field('eng_submit_subscription'); ?>
		<input type="hidden" name="eng_action" value="submit_subscription">
		<input name="receipt_name" required placeholder="Your name">
		<input type="number" step="0.01" name="receipt_amount" required value="20" placeholder="Amount">
		<input type="file" name="receipt_image" required accept="image/*">
		<button class="eng-btn" type="submit">Upload Receipt</button>
	</form>
	<?php return ob_get_clean(); }
	public static function company_dashboard(){return '<div id="projects"><h2>Client Dashboard</h2><p>Manage posted projects and incoming proposals.</p></div><div id="proposals" class="eng-card"><h3>Proposals</h3><p>Proposals appear inside each project details page.</p></div>';} 
	public static function subcontractor_dashboard(){return '<div id="projects"><h2>Contractor Dashboard</h2><p>Find active opportunities and track offers.</p></div><div id="subscription">'.do_shortcode('[eng_subscription_form]').'</div>';}
}
