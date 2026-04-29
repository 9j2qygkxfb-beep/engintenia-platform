<?php if (! defined('ABSPATH')) { exit; }
$lang = function_exists('engintenia_current_language') ? engintenia_current_language() : 'en';
$languages = ['en' => 'EN', 'ar' => 'AR', 'fr' => 'FR'];
?>
<!doctype html>
<html <?php language_attributes(); ?> dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
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
            <?php foreach ($languages as $key => $label) : ?>
                <a href="<?php echo esc_url(add_query_arg('lang', $key)); ?>" class="<?php echo $lang === $key ? 'active' : ''; ?>"><?php echo esc_html($label); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</header>
