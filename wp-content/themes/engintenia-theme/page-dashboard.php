<?php
/* Template Name: User Dashboard */
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <section class="glass page-shell dashboard-layout">
        <aside class="dashboard-sidebar">
            <h2><?php esc_html_e('Workspace', 'engintenia-theme'); ?></h2>
            <ul>
                <li><a href="#dashboard"><?php esc_html_e('Dashboard', 'engintenia-theme'); ?></a></li>
                <li><a href="#projects"><?php esc_html_e('My Projects', 'engintenia-theme'); ?></a></li>
                <li><a href="#proposals"><?php esc_html_e('Proposals', 'engintenia-theme'); ?></a></li>
                <li><a href="#subscription"><?php esc_html_e('Subscription', 'engintenia-theme'); ?></a></li>
            </ul>
        </aside>
        <div class="dashboard-content">
            <h1 id="dashboard"><?php esc_html_e('Dashboard', 'engintenia-theme'); ?></h1>
            <?php
            if (is_user_logged_in() && in_array('eng_company', wp_get_current_user()->roles, true)) {
                echo do_shortcode('[eng_company_dashboard]');
            } elseif (is_user_logged_in() && in_array('eng_subcontractor', wp_get_current_user()->roles, true)) {
                echo do_shortcode('[eng_subcontractor_dashboard]');
            } else {
                echo do_shortcode('[eng_register_form]');
            }
            ?>
        </div>
    </section>
</main>
<?php get_footer();
