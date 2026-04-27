<?php if (! defined('ABSPATH')) { exit; } ?>
<div class="eng-card">
    <h2><?php esc_html_e('Contractor Dashboard', 'engintenia-platform'); ?></h2>
    <p><strong><?php esc_html_e('Subscription status:', 'engintenia-platform'); ?></strong> <?php echo esc_html($status ? $status : __('free', 'engintenia-platform')); ?></p>
</div>

<div class="eng-card">
    <h3><?php esc_html_e('My Proposals', 'engintenia-platform'); ?></h3>
    <ul>
        <?php foreach ($proposals as $proposal) : ?>
            <li><?php echo esc_html($proposal->post_title); ?> - <?php echo esc_html(get_post_meta($proposal->ID, 'eng_quote', true)); ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="eng-card">
    <h3><?php esc_html_e('Notifications', 'engintenia-platform'); ?></h3>
    <ul>
        <?php foreach ($notifications as $notice) : ?>
            <li><?php echo esc_html($notice['text']); ?> <small>(<?php echo esc_html($notice['date']); ?>)</small></li>
        <?php endforeach; ?>
    </ul>
</div>
