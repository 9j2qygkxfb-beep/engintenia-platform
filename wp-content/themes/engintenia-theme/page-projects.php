<?php
/* Template Name: Projects Directory */
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <div class="glass">
        <h1><?php esc_html_e('Projects', 'engintenia-theme'); ?></h1>
        <?php echo do_shortcode('[eng_projects_list]'); ?>
    </div>
</main>
<?php get_footer();
