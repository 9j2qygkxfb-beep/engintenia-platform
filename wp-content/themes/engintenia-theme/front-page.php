<?php
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<section class="hero">
    <div class="container">
        <div class="glass">
            <h1><?php esc_html_e('Hire Elite Engineering Contractors', 'engintenia-theme'); ?></h1>
            <p><?php esc_html_e('A premium engineering and construction marketplace connecting clients with verified subcontractors.', 'engintenia-theme'); ?></p>
            <div class="cta-row">
                <a class="btn" href="<?php echo esc_url(home_url('/register')); ?>"><?php esc_html_e('Get Started', 'engintenia-theme'); ?></a>
                <a class="btn alt" href="<?php echo esc_url(home_url('/subscription')); ?>"><?php esc_html_e('Become Contractor', 'engintenia-theme'); ?></a>
            </div>
            <form method="get" action="<?php echo esc_url(home_url('/projects')); ?>" class="search">
                <input type="text" name="s" placeholder="<?php esc_attr_e('Search projects or contractors', 'engintenia-theme'); ?>">
                <button class="btn" type="submit"><?php esc_html_e('Search', 'engintenia-theme'); ?></button>
            </form>
        </div>
    </div>
</section>

<section class="section">
    <div class="container grid grid-3">
        <?php foreach (['CCTV', 'Networking', 'Solar', 'Security', 'Electrical', 'MEP'] as $category) : ?>
            <div class="glass"><h3><?php echo esc_html($category); ?></h3></div>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="container grid grid-3">
        <div class="glass"><h3><?php esc_html_e('Featured Projects', 'engintenia-theme'); ?></h3><?php echo do_shortcode('[eng_projects_list]'); ?></div>
        <div class="glass"><h3><?php esc_html_e('Top Contractors', 'engintenia-theme'); ?></h3><?php echo do_shortcode('[eng_contractors_list]'); ?></div>
        <div class="glass"><h3><?php esc_html_e('How It Works', 'engintenia-theme'); ?></h3><ol><li><?php esc_html_e('Clients post projects', 'engintenia-theme'); ?></li><li><?php esc_html_e('Contractors submit proposals', 'engintenia-theme'); ?></li><li><?php esc_html_e('Client selects contractor and starts work', 'engintenia-theme'); ?></li></ol></div>
    </div>
</section>

<section class="section">
    <div class="container stats">
        <div class="glass stat"><h3>1200+</h3><p><?php esc_html_e('Projects Posted', 'engintenia-theme'); ?></p></div>
        <div class="glass stat"><h3>850+</h3><p><?php esc_html_e('Verified Contractors', 'engintenia-theme'); ?></p></div>
        <div class="glass stat"><h3>94%</h3><p><?php esc_html_e('Success Rate', 'engintenia-theme'); ?></p></div>
    </div>
</section>

<section class="section">
    <div class="container grid grid-3">
        <div class="glass"><p>“<?php esc_html_e('Engintenia helped us scale our projects with trusted MEP experts.', 'engintenia-theme'); ?>”</p></div>
        <div class="glass"><p>“<?php esc_html_e('The subscription model keeps the contractor pool serious and professional.', 'engintenia-theme'); ?>”</p></div>
        <div class="glass"><p>“<?php esc_html_e('Fast proposals, clear communication, quality execution.', 'engintenia-theme'); ?>”</p></div>
    </div>
</section>
<?php
get_footer();
