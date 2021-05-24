<?php

namespace Rock_Convert\Inc\Admin;

use Rock_Convert\Inc\Admin\CTA\Custom_Post_Type as Custom_Post_Type;
use Rock_Convert\Inc\Admin\Page_Settings as Page_Settings;

/**
 *
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */
class Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * The text domain of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_text_domain The text domain of this plugin.
     */
    private $plugin_text_domain;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param    string $plugin_name The name of this plugin.
     * @param    string $version The version of this plugin.
     * @param     string $plugin_text_domain The text domain of this plugin
     */
    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name        = $plugin_name;
        $this->version            = $version;
        $this->plugin_text_domain = $plugin_text_domain;
    }

    /**
     * @return bool
     */
    public static function analytics_enabled()
    {
        return intval(get_option('_rock_convert_enable_analytics')) == 1;
    }

    /**
     * @return bool
     */
    public static function hide_referral()
    {
        return intval(get_option('_rock_convert_powered_by_hidden')) == 1;
    }

    /**
     * Shows "analytics not connected" flash message
     *
     * @since 2.1.1
     */
    public function analytics_activation_notice()
    {
        $class   = 'notice notice-error';
        $message = __('<strong>Ops!</strong> Parece que você ainda não ativou a funcionalidade de analytics do Rock Convert!',
            'rock-convert');
        $url     = admin_url('edit.php?post_type=cta&page=rock-convert-settings');
        $link    = "<a href='$url' style='font-weight: bold;'>" . __('Clique aqui',
                'rock-convert') . "</a> para acessar a página de configurações e <strong>ativar</strong>.";
        printf('<div class="%1$s"><p>%2$s %3$s</p></div>', $class, $message, $link);
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since 1.0.0
     * @since 2.2.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/rock-convert-admin.css', array(),
            $this->version, 'all');
        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 1.0.0
     * @since 2.2.0
     */
    public function enqueue_scripts()
    {
        $params = array('ajaxurl' => admin_url('admin-ajax.php'));
        wp_enqueue_script('rock_convert_ajax_handle',
            plugin_dir_url(__FILE__) . 'js/rock-convert-admin.js',
            array('jquery', 'wp-color-picker'), $this->version, false);
        wp_localize_script('rock_convert_ajax_handle', 'params', $params);
    }

    public function register_cta_post_type()
    {
        new Custom_Post_Type();
    }

    public function register_settings_page()
    {
        $settings = new Page_Settings();
        $settings->register();
    }

    public function getting_started_page()
    {
        if (get_option('rock_convert_getting_started', false)) {
            delete_option('rock_convert_getting_started');
            wp_redirect("edit.php?post_type=cta&page=rock-convert-settings&tab=general");
            exit;
        }
    }

}
