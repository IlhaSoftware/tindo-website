<?php
/**
 * Rock Content Theme Customizer
 *
 * @package rockcontent
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if ( ! function_exists('rock_customize_register')) {
    /**
     * Register basic customizer support.
     *
     * @param object $wp_customize Customizer reference.
     */
    function rock_customize_register($wp_customize)
    {
        $wp_customize->get_setting('blogname')->transport         = 'postMessage';
        $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
        $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

        // Accent color
        $wp_customize->add_setting('header_background', array(
            'default'           => '#fff',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_background', array(
            'section' => 'colors',
            'label'   => 'Fundo do cabeçalho',
        )));

        // Primary color
        $wp_customize->add_setting('primary_color', array(
            'default'           => '#263473',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
            'section' => 'colors',
            'label'   => 'Cor primária',
        )));

        // Primary color
        $wp_customize->add_setting('secondary_color', array(
            'default'           => '#263473',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
            'section' => 'colors',
            'label'   => 'Cor secundária',
        )));

        // Accent color
        $wp_customize->add_setting('accent_color', array(
            'default'           => '#1bb7d0',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
            'section' => 'colors',
            'label'   => 'Cor de contraste',
        )));

        // Link color
        $wp_customize->add_setting('link_color', array(
            'default'           => '#000000',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color', array(
            'section' => 'colors',
            'label'   => 'Cor dos links',
        )));
    }
}
add_action('customize_register', 'rock_customize_register');

if ( ! function_exists('rock_theme_customize_register')) {
    /**
     * Register individual settings through customizer's API.
     *
     * @param WP_Customize_Manager $wp_customize Customizer reference.
     */
    function rock_theme_customize_register($wp_customize)
    {

        // Theme layout settings.
        $wp_customize->add_section('rock_theme_layout_options', array(
            'title'       => __('Theme Layout Settings', 'rockcontent'),
            'capability'  => 'edit_theme_options',
            'description' => __('Container width and sidebar defaults', 'rockcontent'),
            'priority'    => 160,
        ));

        /**
         * Select sanitization function
         *
         * @param string $input Slug to sanitize.
         * @param WP_Customize_Setting $setting Setting instance.
         *
         * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
         */
        function rock_theme_slug_sanitize_select($input, $setting)
        {

            // Ensure input is a slug (lowercase alphanumeric characters, dashes and underscores are allowed only).
            $input = sanitize_key($input);

            // Get the list of possible select options.
            $choices = $setting->manager->get_control($setting->id)->choices;

            // If the input is a valid key, return it; otherwise, return the default.
            return (array_key_exists($input, $choices) ? $input : $setting->default);

        }

        $wp_customize->add_setting('rock_container_type', array(
            'default'           => 'container',
            'type'              => 'theme_mod',
            'sanitize_callback' => 'rock_theme_slug_sanitize_select',
            'capability'        => 'edit_theme_options',
        ));

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'rock_container_type', array(
                    'label'       => __('Container Width', 'rockcontent'),
                    'description' => __('Choose between Bootstrap\'s container and container-fluid', 'rockcontent'),
                    'section'     => 'rock_theme_layout_options',
                    'settings'    => 'rock_container_type',
                    'type'        => 'select',
                    'choices'     => array(
                        'container'       => __('Fixed width container', 'rockcontent'),
                        'container-fluid' => __('Full width container', 'rockcontent'),
                    ),
                    'priority'    => '10',
                )
            ));

        $wp_customize->add_setting('rock_sidebar_position', array(
            'default'           => 'right',
            'type'              => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
        ));

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'rock_sidebar_position', array(
                    'label'             => __('Sidebar Positioning', 'rockcontent'),
                    'description'       => __('Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.',
                        'rockcontent'),
                    'section'           => 'rock_theme_layout_options',
                    'settings'          => 'rock_sidebar_position',
                    'type'              => 'select',
                    'sanitize_callback' => 'rock_theme_slug_sanitize_select',
                    'choices'           => array(
                        'right' => __('Right sidebar', 'rockcontent'),
                        'left'  => __('Left sidebar', 'rockcontent'),
                        'both'  => __('Left & Right sidebars', 'rockcontent'),
                        'none'  => __('No sidebar', 'rockcontent'),
                    ),
                    'priority'          => '20',
                )
            ));

        $wp_customize->add_setting('rock_logo_position', array(
            'default'           => 'head',
            'type'              => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
        ));

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'rock_logo_position', array(
                    'label'             => __('Logo Positioning', 'rockcontent'),
                    'description'       => __('Choose where the logo should be displayed',
                        'rockcontent'),
                    'section'           => 'rock_theme_layout_options',
                    'settings'          => 'rock_logo_position',
                    'type'              => 'select',
                    'sanitize_callback' => 'rock_theme_slug_sanitize_select',
                    'choices'           => array(
                        'head' => __('In Middle', 'rockcontent'),
                        'navbar'  => __('On Navbar', 'rockcontent'),
                    ),
                    'priority'          => '20',
                )
            ));


        $wp_customize->add_section('rock_theme_social_options', array(
            'title'       => __('Redes Sociais', 'rockcontent'),
            'capability'  => 'edit_theme_options',
            'description' => __('Links para as redes sociais', 'rockcontent'),
            'priority'    => 160,
        ));

        // Facebook
        $wp_customize->add_setting('social_facebook', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_facebook', array(
            'section' => 'rock_theme_social_options',
            'label'   => esc_html__('Facebook', 'theme'),
            'type'    => 'text',
        )));

        // Twitter
        $wp_customize->add_setting('social_twitter', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_twitter', array(
            'section' => 'rock_theme_social_options',
            'label'   => esc_html__('Twitter', 'theme'),
            'type'    => 'text',
        )));

        // Instagram
        $wp_customize->add_setting('social_instagram', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_instagram', array(
            'section' => 'rock_theme_social_options',
            'label'   => esc_html__('Instagram', 'theme'),
            'type'    => 'text',
        )));

        // Linkedin
        $wp_customize->add_setting('social_linkedin', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_linkedin', array(
            'section' => 'rock_theme_social_options',
            'label'   => esc_html__('Linkedin', 'theme'),
            'type'    => 'text',
        )));

        // Pinterest
        $wp_customize->add_setting('social_pinterest', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_pinterest', array(
            'section' => 'rock_theme_social_options',
            'label'   => esc_html__('Pinterest', 'theme'),
            'type'    => 'text',
        )));

        // Youtube
        $wp_customize->add_setting('social_youtube', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_youtube', array(
            'section' => 'rock_theme_social_options',
            'label'   => esc_html__('Youtube', 'theme'),
            'type'    => 'text',
        )));
        
        // WhatsApp
        $wp_customize->add_setting('social_whatsapp', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_whatsapp', array(
            'section' => 'rock_theme_social_options',
            'label'   => esc_html__('WhatsApp', 'theme'),
            'type'    => 'text',
        )));

    }
} // endif function_exists( 'rock_theme_customize_register' ).
add_action('customize_register', 'rock_theme_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
if ( ! function_exists('rock_customize_preview_js')) {
    /**
     * Setup JS integration for live previewing.
     */
    function rock_customize_preview_js()
    {
        wp_enqueue_script('rock_customizer', get_template_directory_uri() . '/js/customizer.js',
            array('customize-preview'), '20130508', true
        );
    }
}
add_action('customize_preview_init', 'rock_customize_preview_js');
