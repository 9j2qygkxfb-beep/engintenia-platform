<?php
/* Template Name: Contractors Directory */
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <section class="glass page-shell">
        <h1><?php esc_html_e('Top Contractors', 'engintenia-theme'); ?></h1>
        <p><?php esc_html_e('Compare verified specialists by rating, category, and experience.', 'engintenia-theme'); ?></p>
        <?php echo do_shortcode('[eng_contractors_list]'); ?>
    </section>
</main>
<?php get_footer();
