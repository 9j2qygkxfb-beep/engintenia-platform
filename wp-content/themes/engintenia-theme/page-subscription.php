<?php
/* Template Name: Subscription */
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <section class="glass page-shell pricing-shell">
        <h1><?php esc_html_e('Contractor Subscription', 'engintenia-theme'); ?></h1>
        <div class="pricing-card">
            <p class="price">$20<span>/month</span></p>
            <ol>
                <li><?php esc_html_e('Bank transfer', 'engintenia-theme'); ?></li>
                <li><?php esc_html_e('Upload receipt', 'engintenia-theme'); ?></li>
                <li><?php esc_html_e('Admin approval', 'engintenia-theme'); ?></li>
            </ol>
        </div>
        <?php echo do_shortcode('[eng_subscription_form]'); ?>
    </section>
</main>
<?php get_footer();
