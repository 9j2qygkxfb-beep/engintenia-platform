<?php
/* Template Name: User Dashboard */
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <div class="glass">
        <h1><?php esc_html_e('Dashboard', 'engintenia-theme'); ?></h1>
        <?php
        if (is_user_logged_in() && in_array('eng_client', wp_get_current_user()->roles, true)) {
            echo do_shortcode('[eng_company_dashboard]');
        } elseif (is_user_logged_in() && in_array('eng_contractor', wp_get_current_user()->roles, true)) {
            echo do_shortcode('[eng_contractor_dashboard]');
        } else {
            echo do_shortcode('[eng_register_form]');
        }
        ?>
    </div>
</main>
<?php get_footer();
