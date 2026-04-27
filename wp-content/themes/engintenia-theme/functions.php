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
    wp_enqueue_style('engintenia-theme-style', get_stylesheet_uri(), [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'engintenia_theme_assets');
