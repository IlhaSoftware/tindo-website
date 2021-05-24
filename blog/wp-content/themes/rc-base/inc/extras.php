<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package rockcontent
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_filter('body_class', 'rock_body_classes');

if ( ! function_exists('rock_body_classes')) {
    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     *
     * @return array
     */
    function rock_body_classes($classes)
    {
        // Adds a class of group-blog to blogs with more than 1 published author.
        if (is_multi_author()) {
            $classes[] = 'group-blog';
        }
        // Adds a class of hfeed to non-singular pages.
        if ( ! is_singular()) {
            $classes[] = 'hfeed';
        }

        return $classes;
    }
}

// Removes tag class from the body_class array to avoid Bootstrap markup styling issues.
add_filter('body_class', 'rock_adjust_body_class');

if ( ! function_exists('rock_adjust_body_class')) {
    /**
     * Setup body classes.
     *
     * @param string $classes CSS classes.
     *
     * @return mixed
     */
    function rock_adjust_body_class($classes)
    {

        foreach ($classes as $key => $value) {
            if ('tag' == $value) {
                unset($classes[$key]);
            }
        }

        return $classes;

    }
}

// Filter custom logo with correct classes.
add_filter('get_custom_logo', 'rock_change_logo_class');

if ( ! function_exists('rock_change_logo_class')) {
    /**
     * Replaces logo CSS class.
     *
     * @param string $html Markup.
     *
     * @return mixed
     */
    function rock_change_logo_class($html)
    {

        $html = str_replace('class="custom-logo"', 'class="img-fluid"', $html);
        $html = str_replace('class="custom-logo-link"', 'class="navbar-brand custom-logo-link"', $html);
        $html = str_replace('alt=""', 'title="Home" alt="logo"', $html);

        return $html;
    }
}

/**
 * Display navigation to next/previous post when applicable.
 */

if ( ! function_exists('rock_post_nav')) {
    function rock_post_nav()
    {
        // Don't print empty markup if there's nowhere to navigate.
        $previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
        $next     = get_adjacent_post(false, '', false);

        if ( ! $next && ! $previous) {
            return;
        }
        ?>
        <nav class="container navigation post-navigation">
            <h2 class="sr-only"><?php _e('Post navigation', 'rockcontent'); ?></h2>
            <div class="row nav-links justify-content-between">
                <?php

                if (get_previous_post_link()) {
                    previous_post_link('<span class="nav-previous">%link</span>',
                        _x('<i class="fa fa-angle-left"></i>&nbsp;%title', 'Previous post link', 'rockcontent'));
                }
                if (get_next_post_link()) {
                    next_post_link('<span class="nav-next">%link</span>',
                        _x('%title&nbsp;<i class="fa fa-angle-right"></i>', 'Next post link', 'rockcontent'));
                }
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->

        <?php
    }
}

if ( ! function_exists('rock_get_social_networks')) {
    function rock_get_social_networks()
    {
        $networks = array('facebook', 'twitter', 'instagram', 'pinterest', 'youtube', 'linkedin', 'whatsapp');
        $social   = array();
        foreach ($networks as $network) {
            $link = get_theme_mod('social_' . $network);
            if ( ! empty($link)) {
                $social[] = array(
                    'slug' => $network,
                    'url'  => $link
                );
            }
        }

        return $social;
    }
}

if ( ! function_exists('rock_map_social_icon')) {
    function rock_map_social_icon($slug)
    {
        switch ($slug) {
            case 'facebook':
                return 'fa-facebook-official';
                break;
            case 'twitter':
                return 'fa-twitter';
                break;
            case 'instagram':
                return 'fa-instagram';
                break;
            case 'linkedin':
                return 'fa-linkedin';
                break;
            case 'pinterest':
                return 'fa-pinterest';
                break;
            case 'youtube':
                return 'fa-youtube-play';
                break;
            case 'whatsapp':
                return 'fa-whatsapp';
                break;                
        }
    }
}

if ( ! function_exists('rock_reading_time')):
    /**
     * Display post reading time in minutes
     *
     * @param $post_content
     *
     * @return string
     */
    function rock_reading_time($post_content)
    {
        $word_count  = str_word_count(strip_tags($post_content));
        $readingtime = ceil($word_count / 200);

        $est = sprintf( // WPCS: XSS OK.
            esc_html(_nx(
                '1 minute to read',
                '%1$s minutes to read',
                $readingtime,
                'minutes to read',
                'rockcontent'
            )),
            number_format_i18n($readingtime)

        );

        return $est;
    }
endif;

if(!function_exists('rock_logo_position')) {
    function rock_logo_position() {
        return get_theme_mod('rock_logo_position');
    }
}

if(!function_exists('rock_logo_navbar')) {
    function rock_logo_navbar() {
        return "navbar" == rock_logo_position();
    }
}

if(!function_exists('rock_logo_head')) {
    function rock_logo_head() {
        return "head" == rock_logo_position();
    }
}
