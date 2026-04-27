<?php
if (! defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="container section">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article class="glass">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php
get_footer();
