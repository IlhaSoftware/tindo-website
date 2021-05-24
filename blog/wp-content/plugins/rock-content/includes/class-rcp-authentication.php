<?php

/**
 * This class has all code necessary to authenticate requests to this plugin
 *
 * @since      1.0.0
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/includes
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Authentication {

  /**
   * @param null $method
   *
   * @return mixed
   *
   * @since 1.0.0
   *
   */
  public static function authenticate( $method = null ) {
    if ( empty( $_SERVER['HTTP_RCP_TOKEN'] ) ) {
      Rcp_Response::respond_with( 403, array(
        "error_code" => Rcp_Wp_plugin_Rest::$errors["TOKEN_NOT_PROVIDED"],
        "error"      => "RCP Token is required"
      ) );
      die;
    }

    $token = self::get_token();

    $decrypted_data = self::decrypt_data( $_SERVER['HTTP_RCP_TOKEN'], $token );

    if ( ! self::valid( $decrypted_data ) ) {
      Rcp_Response::respond_with( 401, array(
        "error_code" => Rcp_Wp_plugin_Rest::$errors["INVALID_TOKEN"],
        "error"      => "Invalid token"
      ) );
      die;
    } else {
      update_option( "rcp_timestamp", $decrypted_data );
    }

    return self::preserved_data( $method );
  }

  /**
   * @return mixed|void
   *
   * @since 1.0.0
   */
  public static function get_token() {
    return get_option( 'rcp_token' );
  }

  /**
   * @param $data
   *
   * @return string
   *
   * @since 1.0.0
   */
  private static function decrypt_data( $data, $key ) {
    $val = str_replace( array( '_', '-', '.' ), array( '+', '/', '=' ), $data );

    $data             = base64_decode( $val );
    $iv_length        = openssl_cipher_iv_length( 'AES-256-CBC' );
    $body_data        = substr( $data, $iv_length );
    $iv               = substr( $data, 0, $iv_length );
    $base64_body_data = base64_encode( $body_data );

    return openssl_decrypt( $base64_body_data, 'AES-256-CBC', $key, 0, $iv );
  }

  /**
   * @param $token
   *
   * @return bool
   *
   * @since 1.0.0
   */
  public static function valid( $data ) {
    return isset( $data ) && self::bigintval( $data ) > self::bigintval( get_option( "rcp_timestamp" ) );
  }

  /**
   * @param $value
   *
   * @return string
   */
  public static function bigintval( $value ) {
    return number_format( (float) $value, 0, '', '' );
  }

  /**
   * @param $method
   *
   * @return mixed
   */
  public static function preserved_data( $method ) {
    $response = array();

    if ( $method == "post" && isset( $_POST["data"] ) ) {
      $response = $_POST["data"];
    } elseif ( $method == "get" && isset( $_GET["data"] ) ) {
      $response = $_GET["data"];
    }

    if ( !isset($_SERVER["HTTP_APP_VERSION"]) ) {
      $response["application_version"] = "RC";
    } else {
      $response["application_version"] = $_SERVER["HTTP_APP_VERSION"];
    }

    return $response;
  }
}
