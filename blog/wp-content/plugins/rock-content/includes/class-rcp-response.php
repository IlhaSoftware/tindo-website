<?php

/**
 * This class has all code necessary to create json responses
 *
 * @since      1.0.0
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/includes
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Response {

  /**
   * @param $status
   * @param array $body
   *
   * @throws Rcp_Wp_Exception
   *
   * @since 1.0.0
   */
  public static function respond_with( $status, $body = array() ) {
    self::set_response_header( $status );
    self::respond_as_json( $body );
  }

  /**
   * @param $status
   * @param $body
   *
   * @since 1.0.0
   *
   * @since 2.0.0
   */
  public static function set_response_header( $status ) {
    header( 'Content-type:application/json;charset=utf-8' );
    header( 'Responded-By:rcp-plugin' );
    header( 'Wordpress-Version: ' . get_bloginfo( 'version' ) );
    header( 'X-PHP-Response-Code: ' . $status, true, $status);
  }

  /**
   * @param $body
   *
   * @since 1.0.0
   */
  public static function respond_as_json( $body ) {
    echo json_encode( $body );
  }
}
