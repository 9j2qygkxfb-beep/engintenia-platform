<?php if (! defined('ABSPATH')) { exit; } ?>
<form method="post" class="eng-card eng-form">
    <h3><?php esc_html_e('Post New Project', 'engintenia-platform'); ?></h3>
    <?php wp_nonce_field('eng_submit_project'); ?>
    <input type="hidden" name="eng_action" value="submit_project">
    <input name="project_title" required placeholder="<?php esc_attr_e('Project title', 'engintenia-platform'); ?>">
    <textarea name="project_description" required placeholder="<?php esc_attr_e('Project description', 'engintenia-platform'); ?>"></textarea>
    <input name="project_budget" required placeholder="<?php esc_attr_e('Budget', 'engintenia-platform'); ?>">
    <select name="project_country" required>
        <option value=""><?php esc_html_e('Select country', 'engintenia-platform'); ?></option>
        <?php foreach ($country_groups as $group_label => $countries) : ?>
            <optgroup label="<?php echo esc_attr($group_label); ?>">
                <?php foreach ($countries as $country_item) : ?>
                    <option value="<?php echo esc_attr($country_item); ?>"><?php echo esc_html($country_item); ?></option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
    </select>
    <select name="project_category" required>
        <option value=""><?php esc_html_e('Select category', 'engintenia-platform'); ?></option>
        <?php foreach ($categories as $term) : ?>
            <option value="<?php echo esc_attr((string) $term->term_id); ?>"><?php echo esc_html($term->name); ?></option>
        <?php endforeach; ?>
    </select>
    <input name="project_contact" required placeholder="<?php esc_attr_e('Client contact details', 'engintenia-platform'); ?>">
    <button class="eng-btn" type="submit"><?php esc_html_e('Publish Project', 'engintenia-platform'); ?></button>
</form>
