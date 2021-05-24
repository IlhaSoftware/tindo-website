<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rockcontent.com/
 * @since             1.0.0
 * @package           Rcp_Wp_plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Rock Content
 * Plugin URI:        https://rockcontent.com/
 * Description:       Este fantástico plugin permite integrar o seu blog Wordpress com a plataforma Rock Content.
 * Version:           2.4.0
 * Author:            Rock Content
 * Author URI:        https://rockcontent.com/
 * Text Domain:       rcp-wp_plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

require plugin_dir_path( __FILE__ ) . 'includes/rcp-compatibility.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rcp-wp_plugin-activator.php
 */
function activate_rcp_wp_plugin() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-rcp-wp_plugin-activator.php';
  $activator = new Rcp_Wp_plugin_Activator();
  $activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rcp-wp_plugin-deactivator.php
 */
function deactivate_rcp_wp_plugin() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-rcp-wp_plugin-deactivator.php';
  Rcp_Wp_plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rcp_wp_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_rcp_wp_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rcp-wp_plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rcp_wp_plugin() {

  $plugin = new Rcp_Wp_plugin();
  $plugin->run();

}

run_rcp_wp_plugin();
