<?php if (! defined('ABSPATH')) { exit; } ?>
<div class="eng-dashboard-grid">
    <div class="eng-card">
        <h2><?php esc_html_e('Client Dashboard', 'engintenia-platform'); ?></h2>
        <p><?php esc_html_e('Manage your projects and review incoming proposals.', 'engintenia-platform'); ?></p>
    </div>
    <div class="eng-card">
        <h3><?php esc_html_e('My Projects', 'engintenia-platform'); ?></h3>
        <ul class="eng-list"><?php foreach ($projects as $project) : ?><li><?php echo esc_html($project->post_title); ?> - <?php echo esc_html(get_post_meta($project->ID, 'eng_budget', true)); ?></li><?php endforeach; ?></ul>
    </div>
    <div class="eng-card">
        <h3><?php esc_html_e('Received Proposals', 'engintenia-platform'); ?></h3>
        <ul class="eng-list"><?php foreach ($proposals as $proposal) : ?><li><?php echo esc_html($proposal->post_title); ?> (<?php echo esc_html(get_post_meta($proposal->ID, 'eng_quote', true)); ?>) - <?php echo esc_html($proposal->post_content); ?><form method="post" style="display:grid;gap:8px;margin-top:8px"><?php wp_nonce_field('eng_send_message'); ?><input type="hidden" name="eng_action" value="submit_message"><input type="hidden" name="receiver_id" value="<?php echo esc_attr((string) $proposal->post_author); ?>"><input type="text" name="message" placeholder="<?php esc_attr_e('Send acceptance message', 'engintenia-platform'); ?>"><button class="eng-btn eng-btn-sm" type="submit"><?php esc_html_e('Accept Contractor', 'engintenia-platform'); ?></button></form></li><?php endforeach; ?></ul>
    </div>
    <div class="eng-card">
        <h3><?php esc_html_e('Messages', 'engintenia-platform'); ?></h3>
        <ul class="eng-list"><?php foreach ($messages as $message) : $sender = get_userdata((int) $message->post_author); ?><li><strong><?php echo esc_html($sender ? $sender->display_name : __('Unknown sender', 'engintenia-platform')); ?>:</strong> <?php echo esc_html($message->post_content); ?></li><?php endforeach; ?></ul>
    </div>
</div>
