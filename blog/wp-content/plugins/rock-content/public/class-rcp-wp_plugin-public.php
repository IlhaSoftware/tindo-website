<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rockcontent.com/
 * @since      2.4.0
 *
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/public
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Wp_plugin_Public {
  /**
   * The ID of this plugin.
   *
   * @since    2.4.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;
  /**
   * The version of this plugin.
   *
   * @since    2.4.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;
  /**
   * Initialize the class and set its properties.
   *
   * @since    2.4.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version           The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }
  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    2.4.0
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/class-rcp-wp_plugin-chorus-integrations.js', $this->version, false );
  }
}