<?php if (! defined('ABSPATH')) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">Engintenia</a>
        <nav class="main-nav">
            <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'fallback_cb' => false]); ?>
        </nav>
        <div class="lang-switcher" aria-label="Language switcher">
            <a href="#" class="active">EN</a>
            <span>|</span>
            <a href="#">AR</a>
            <span>|</span>
            <a href="#">FR</a>
        </div>
    </div>
</header>
