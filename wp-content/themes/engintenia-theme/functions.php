<?php

if (! defined('ABSPATH')) {
    exit;
}

function engintenia_theme_setup()
{
    load_theme_textdomain('engintenia-theme', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption']);
    register_nav_menus([
        'primary' => __('Primary Menu', 'engintenia-theme'),
    ]);
}
add_action('after_setup_theme', 'engintenia_theme_setup');

function engintenia_theme_assets()
{
    wp_enqueue_style('engintenia-theme-style', get_stylesheet_uri(), [], '1.1.0');
}
add_action('wp_enqueue_scripts', 'engintenia_theme_assets');

function engintenia_current_language()
{
    $lang = isset($_GET['lang']) ? sanitize_key(wp_unslash($_GET['lang'])) : '';
    $allowed = ['en', 'ar', 'fr'];

    if (! in_array($lang, $allowed, true)) {
        $lang = isset($_COOKIE['engintenia_lang']) ? sanitize_key(wp_unslash($_COOKIE['engintenia_lang'])) : 'en';
    }

    if (! in_array($lang, $allowed, true)) {
        $lang = 'en';
    }

    return $lang;
}

function engintenia_apply_language_cookie()
{
    if (isset($_GET['lang'])) {
        setcookie('engintenia_lang', engintenia_current_language(), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
    }
}
add_action('init', 'engintenia_apply_language_cookie');

function engintenia_body_classes($classes)
{
    $lang = engintenia_current_language();
    $classes[] = 'lang-' . $lang;

    if ($lang === 'ar') {
        $classes[] = 'eng-rtl';
    }

    return $classes;
}
add_filter('body_class', 'engintenia_body_classes');

function engintenia_is_rtl($is_rtl)
{
    return engintenia_current_language() === 'ar' ? true : $is_rtl;
}
add_filter('locale', function ($locale) {
    return engintenia_current_language() === 'ar' ? 'ar' : $locale;
});
add_filter('is_rtl', 'engintenia_is_rtl');
