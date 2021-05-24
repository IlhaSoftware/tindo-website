<?php

require_once locate_template('theme_options.php');

function rockcontent_remove_scripts()
{
    wp_dequeue_style('rock-content-styles');
    wp_deregister_style('rock-content-styles');

    // Removes the parent themes stylesheet and scripts from global-templates/enqueue.php
}

add_action('wp_enqueue_scripts', 'rockcontent_remove_scripts', 20);

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{
    $the_theme = wp_get_theme();

    wp_register_style('google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700', array(), null);
    wp_enqueue_style('google-fonts');

    wp_enqueue_style('child-rockcontent-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(),
        $the_theme->get('Version'));
    $custom_css = rock_theme_get_customizer_css();
    wp_add_inline_style('child-rockcontent-styles', $custom_css);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

function add_child_theme_textdomain()
{
    load_child_theme_textdomain('rock-content-child', get_stylesheet_directory() . '/languages');
}

add_action('after_setup_theme', 'add_child_theme_textdomain');

function custom_excerpt_length($length)
{
    return 20;
}

add_filter('excerpt_length', 'custom_excerpt_length', 999);

add_image_size('entry', 510, 392, true);
add_image_size('post-single', 1110, 508, true);
add_image_size('highlight-square', 555, 450, true);
add_image_size('highlight-vertical', 285, 470, true);
add_image_size('highlight-mobile', 445, 445, true);

add_action('after_switch_theme', 'rock_set_theme_options');

if ( ! function_exists('rock_set_theme_options')) {
    function rock_set_theme_options()
    {
        set_theme_mod('rock_sidebar_position', 'ads');
    }
}

if ( ! function_exists('rock_post_category')) {
    function rock_post_category()
    {
        // SHOW YOAST PRIMARY CATEGORY, OR FIRST CATEGORY
        $category   = get_the_category();
        $useCatLink = true;

        // If post has a category assigned.
        if ($category) {
            $category_display = '';
            $category_link    = '';
            if (class_exists('WPSEO_Primary_Term')) {
                // Show the post's 'Primary' category, if this Yoast feature is available, & one is set
                $wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_id());
                $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
                $term               = get_term($wpseo_primary_term);
                if (is_wp_error($term)) {
                    // Default to first category (not Yoast) if an error is returned
                    $category_display = $category[0]->name;
                    $category_link    = get_category_link($category[0]->term_id);
                } else {
                    // Yoast Primary category
                    $category_display = $term->name;
                    $category_link    = get_category_link($term->term_id);
                }
            } else {
                // Default, display the first category in WP's list of assigned categories
                $category_display = $category[0]->name;
                $category_link    = get_category_link($category[0]->term_id);
            }
            // Display category
            if ( ! empty($category_display)) {
                if ($useCatLink == true && ! empty($category_link)) {

                    echo '<a href="' . $category_link . '">' . htmlspecialchars($category_display) . '</a>';

                } else {
                    echo htmlspecialchars($category_display);
                }
            }
        }
    }
}

function rock_posted_on()
{
    echo "<time class='posted-on'>" . get_the_date() . "</time>";
}

function crunchify_init()
{
    add_filter('comment_form_defaults', 'crunchify_comments_form_defaults');
}

add_action('after_setup_theme', 'crunchify_init');

function crunchify_comments_form_defaults($default)
{

    $default['comment_notes_before'] = '';
    $default['comment_notes_after']  = '';

    return $default;
}

function rock_published_posts()
{
    return wp_count_posts('post');
}