<?php
/* Template Name: Contractors Directory */
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <div class="glass">
        <h1><?php esc_html_e('Contractors', 'engintenia-theme'); ?></h1>
        <?php echo do_shortcode('[eng_contractors_list]'); ?>
    </div>
</main>
<?php get_footer();
