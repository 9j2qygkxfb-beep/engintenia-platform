<?php if (! defined('ABSPATH')) { exit; } ?>
<div class="eng-card">
    <h2><?php esc_html_e('Projects Marketplace', 'engintenia-platform'); ?></h2>
    <form method="get" class="eng-grid eng-grid-4">
        <select name="country">
            <option value=""><?php esc_html_e('All Countries', 'engintenia-platform'); ?></option>
            <?php foreach ($countries as $item) : ?>
                <option value="<?php echo esc_attr($item); ?>" <?php selected($country, $item); ?>><?php echo esc_html($item); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="budget" value="<?php echo esc_attr($budget); ?>" placeholder="<?php esc_attr_e('Budget', 'engintenia-platform'); ?>">
        <select name="category">
            <option value="0"><?php esc_html_e('All Categories', 'engintenia-platform'); ?></option>
            <?php foreach ($categories as $term) : ?>
                <option value="<?php echo esc_attr((string) $term->term_id); ?>" <?php selected($category, $term->term_id); ?>><?php echo esc_html($term->name); ?></option>
            <?php endforeach; ?>
        </select>
        <button class="eng-btn" type="submit"><?php esc_html_e('Filter', 'engintenia-platform'); ?></button>
    </form>
</div>

<?php foreach ($projects as $project) : ?>
    <article class="eng-card eng-project">
        <h3><?php echo esc_html($project->post_title); ?></h3>
        <p><?php echo esc_html(wp_trim_words($project->post_content, 35)); ?></p>
        <ul class="eng-meta">
            <li><strong><?php esc_html_e('Budget', 'engintenia-platform'); ?>:</strong> <?php echo esc_html(get_post_meta($project->ID, 'eng_budget', true)); ?></li>
            <li><strong><?php esc_html_e('Duration', 'engintenia-platform'); ?>:</strong> <?php echo esc_html(get_post_meta($project->ID, 'eng_duration', true)); ?></li>
            <li><strong><?php esc_html_e('Location', 'engintenia-platform'); ?>:</strong> <?php echo esc_html(get_post_meta($project->ID, 'eng_country', true)); ?></li>
        </ul>

        <?php if ($this->can_view_client_details($project->ID)) : ?>
            <p><strong><?php esc_html_e('Client Contact', 'engintenia-platform'); ?>:</strong> <?php echo esc_html(get_post_meta($project->ID, 'eng_client_contact', true)); ?></p>
        <?php else : ?>
            <p class="eng-lock"><?php esc_html_e('Subscribe to unlock client contact details.', 'engintenia-platform'); ?></p>
        <?php endif; ?>

        <?php if (is_user_logged_in() && in_array('eng_contractor', wp_get_current_user()->roles, true)) : ?>
            <?php if ($this->is_subscribed(get_current_user_id())) : ?>
                <form method="post" class="eng-form">
                    <?php wp_nonce_field('eng_submit_proposal'); ?>
                    <input type="hidden" name="eng_action" value="submit_proposal">
                    <input type="hidden" name="project_id" value="<?php echo esc_attr((string) $project->ID); ?>">
                    <textarea name="proposal_message" required placeholder="<?php esc_attr_e('Proposal message', 'engintenia-platform'); ?>"></textarea>
                    <input type="text" name="proposal_quote" required placeholder="<?php esc_attr_e('Your quotation', 'engintenia-platform'); ?>">
                    <button class="eng-btn" type="submit"><?php esc_html_e('Submit Proposal', 'engintenia-platform'); ?></button>
                </form>
            <?php else : ?>
                <p class="eng-lock"><?php esc_html_e('Subscription required to submit proposals.', 'engintenia-platform'); ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </article>
<?php endforeach; ?>
