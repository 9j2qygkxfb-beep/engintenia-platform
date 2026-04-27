<?php if (! defined('ABSPATH')) { exit; } ?>
<footer class="site-footer">
    <div class="container">
        <p><?php echo esc_html(date_i18n('Y')); ?> © Engintenia. <?php esc_html_e('All rights reserved.', 'engintenia-theme'); ?></p>
        <?php echo do_shortcode('[eng_language_switcher]'); ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
