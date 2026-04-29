<?php
/* Template Name: Projects Directory */
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <div class="glass page-shell">
        <h1><?php esc_html_e('Projects Marketplace', 'engintenia-theme'); ?></h1>
        <p><?php esc_html_e('Filter engineering projects by country, category, and budget to find your best-fit opportunities.', 'engintenia-theme'); ?></p>
        <?php echo do_shortcode('[eng_projects_list]'); ?>
    </div>
</main>
<?php get_footer();
