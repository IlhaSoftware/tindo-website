<?php
/**
 * Add Chorus integrations.
 *
 * This class defines all code necessary to insert chorus integrations code into pages.
 *
 * @link       https://rockcontent.com/
 * @since      2.4.0
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/includes
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Wp_plugin_Chorus_Integrations {

  /**
   * @since 2.4.0
   */
  public static function get_integrations_tpl() {
    if ( ! get_option( "rcp_chorus_integrations_enabled", false ) ) {
      return;
    }

    echo self::get_hubspot_embed_code();
  }

  /**
   * Get HubSpot embed code to capture their analytics.
   * https://knowledge.hubspot.com/articles/kcs_article/reports/install-the-hubspot-tracking-code
   *
   * @since    2.4.0
   */
  private static function get_hubspot_embed_code() {
    $chorusUserId = get_option( "rcp_chorus_user_id" );
    $integration_services_urls = json_decode( get_option( "rcp_chorus_integration_services_urls" ), true );
    $hubspot_service_url = $integration_services_urls["hubspot"];

    return "
      <!-- Start of HubSpot Embed Code -->
        <script type='text/javascript' id='hs-script-loader' async defer src='$hubspot_service_url/user/$chorusUserId/code'></script>
      <!-- End of HubSpot Embed Code -->
    ";
  }
}
