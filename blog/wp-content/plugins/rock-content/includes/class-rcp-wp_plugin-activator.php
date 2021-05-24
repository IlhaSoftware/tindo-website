<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://rockcontent.com/
 * @since      1.0.0
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/includes
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Wp_plugin_Activator extends Rcp_Wp_plugin {

  /**
   * @since   1.0.0
   */
  public function activate() {
    $this->setup_config_data();
  }

  /**
   * @since   1.0.0
   *
   * @since   1.0.3
   */
  private function setup_config_data() {
    if ( ! get_option( "rcp_token" ) ) {
      update_option( "rcp_token", $this->generate_token() );
      update_option( "rcp_activated_at", date( 'Y-m-d H:i:s' ) );
      update_option( "rcp_timestamp", 1 );
      update_option( "rcp_integrated_at", null );
      update_option( "rcp_deactivated_at", null );
    } else {
      if ( ! get_option( "rcp_integrated_at" ) ) {
        update_option( "rcp_integrated_at", date( 'Y-m-d H:i:s' ) );
      }
      update_option( "rcp_updated_at", date( 'Y-m-d H:i:s' ) );
    }
  }

  /**
   * Gera um token unico e aleatorio
   *
   * @return string
   *
   * @since   1.0.0
   */
  private function generate_token() {
    return md5( microtime() );
  }

}
