<?php
/**
 * Custom hooks.
 *
 * @package rock content
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists('rock_site_info')) {
    /**
     * Add site info hook to WP hook library.
     */
    function rock_site_info()
    {
        do_action('rock_site_info');
    }
}

if ( ! function_exists('rock_add_site_info')) {
    add_action('rock_site_info', 'rock_add_site_info');

    /**
     * Add site info content.
     */
    function rock_add_site_info()
    {
        $site_info = '<span class="copyright__text">Site criado por <a href="https://rockcontent.com/">Rock Content</a>.</span>';

        echo apply_filters('rock_site_info_content', $site_info); // WPCS: XSS ok.
    }
}
