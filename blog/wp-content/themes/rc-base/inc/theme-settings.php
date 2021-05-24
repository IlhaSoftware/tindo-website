<?php
/**
 * Check and setup theme's default settings
 *
 * @package rock content
 *
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


function rock_add_body_class()
{
    add_filter('body_class', function ($classes) {
        return array_merge($classes, array('logo-' . get_theme_mod('rock_logo_position')));
    });
}

if ( ! function_exists('rock_setup_theme_default_settings')) {
    function rock_setup_theme_default_settings()
    {

        // check if settings are set, if not set defaults.
        // Caution: DO NOT check existence using === always check with == .
        // Latest blog posts style.
        $rock_posts_index_style = get_theme_mod('rock_posts_index_style');
        if ('' == $rock_posts_index_style) {
            set_theme_mod('rock_posts_index_style', 'default');
        }

        // Sidebar position.
        $rock_sidebar_position = get_theme_mod('rock_sidebar_position');
        if ('' == $rock_sidebar_position) {
            set_theme_mod('rock_sidebar_position', 'right');
        }

        // Container width.
        $rock_container_type = get_theme_mod('rock_container_type');
        if ('' == $rock_container_type) {
            set_theme_mod('rock_container_type', 'container');
        }

        // Logo position
        $rock_logo_position = get_theme_mod('rock_logo_position');
        if ('' == $rock_logo_position) {
            set_theme_mod('rock_logo_position', 'head');
        }

        rock_add_body_class();
    }
}
