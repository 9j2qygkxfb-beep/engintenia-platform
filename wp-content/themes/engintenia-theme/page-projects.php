<?php
/* Template Name: Projects Directory */
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <section class="glass page-shell">
        <h1><?php esc_html_e('Projects Marketplace', 'engintenia-theme'); ?></h1>
        <p><?php esc_html_e('Explore engineering jobs with powerful filters and rich project cards.', 'engintenia-theme'); ?></p>
        <?php echo do_shortcode('[eng_projects_list]'); ?>
    </section>
</main>
<?php get_footer();
