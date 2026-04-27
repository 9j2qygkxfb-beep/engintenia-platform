<?php if (! defined('ABSPATH')) { exit; } ?>
<div class="eng-card">
    <h2><?php esc_html_e('Contractor Subscription - $20 / month', 'engintenia-platform'); ?></h2>
    <p><?php esc_html_e('Payment method: Manual Bank Transfer only.', 'engintenia-platform'); ?></p>
    <p><strong><?php esc_html_e('Bank:', 'engintenia-platform'); ?></strong> Engintenia Business Bank<br>
    <strong><?php esc_html_e('IBAN:', 'engintenia-platform'); ?></strong> AE00 0000 0000 0000 0000 000<br>
    <strong><?php esc_html_e('SWIFT:', 'engintenia-platform'); ?></strong> ENGIAEXX</p>
    <p><strong><?php esc_html_e('Current status:', 'engintenia-platform'); ?></strong> <?php echo esc_html($status ? $status : __('not submitted', 'engintenia-platform')); ?></p>
</div>

<form method="post" enctype="multipart/form-data" class="eng-card eng-form">
    <?php wp_nonce_field('eng_submit_subscription'); ?>
    <input type="hidden" name="eng_action" value="submit_subscription">
    <input name="receipt_name" required placeholder="<?php esc_attr_e('Transfer sender name', 'engintenia-platform'); ?>">
    <input type="number" step="0.01" name="receipt_amount" required value="20">
    <input type="date" name="receipt_date" required>
    <input type="file" name="receipt_image" required accept="image/*">
    <button class="eng-btn" type="submit"><?php esc_html_e('Upload Receipt', 'engintenia-platform'); ?></button>
</form>
