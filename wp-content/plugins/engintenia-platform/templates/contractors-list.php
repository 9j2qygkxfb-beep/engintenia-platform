<?php if (! defined('ABSPATH')) { exit; } ?>
<div class="eng-grid eng-grid-3">
    <?php foreach ($contractors as $contractor) : ?>
        <?php
        $reviews = get_posts([
            'post_type' => 'eng_review',
            'posts_per_page' => -1,
            'meta_key' => 'eng_contractor_id',
            'meta_value' => $contractor->ID,
        ]);
        $rating_sum = 0;
        foreach ($reviews as $review) {
            $rating_sum += (int) get_post_meta($review->ID, 'eng_rating', true);
        }
        $rating_avg = count($reviews) > 0 ? round($rating_sum / count($reviews), 1) : 0;
        ?>
        <article class="eng-card">
            <h3><?php echo esc_html($contractor->display_name); ?></h3>
            <p><strong><?php esc_html_e('Experience', 'engintenia-platform'); ?>:</strong> <?php echo esc_html(get_user_meta($contractor->ID, 'eng_experience', true)); ?></p>
            <p><strong><?php esc_html_e('Rating', 'engintenia-platform'); ?>:</strong> <?php echo esc_html((string) $rating_avg); ?> ★</p>
            <p><strong><?php esc_html_e('Portfolio', 'engintenia-platform'); ?>:</strong> <?php echo esc_html(get_user_meta($contractor->ID, 'eng_portfolio', true)); ?></p>
            <a class="eng-btn" href="<?php echo esc_url(add_query_arg('contractor_id', (string) $contractor->ID, home_url('/contact'))); ?>"><?php esc_html_e('Hire', 'engintenia-platform'); ?></a>
        </article>
    <?php endforeach; ?>
</div>
