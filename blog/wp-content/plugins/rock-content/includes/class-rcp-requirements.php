<?php

/**
 * Class Rcp_Requirements
 *
 * @since 1.0.3
 */
class Rcp_Requirements {

  /**
   * @var
   */
  public $minimum_php_version;

  /**
   * Rcp_Requirements constructor.
   *
   * @param $minimum_php_version
   */
  public function __construct( $minimum_php_version ) {
    $this->minimum_php_version = $minimum_php_version;
  }

  /**
   * @return bool
   */
  public function valid_php_version() {
    return ( version_compare( PHP_VERSION, $this->minimum_php_version ) >= 0 );
  }

  /**
   * @return bool
   */
  public function open_ssl_enabled() {
    return extension_loaded( 'openssl' );
  }

  /**
   * @return bool
   */
  public function valid() {
    return $this->valid_php_version() && $this->open_ssl_enabled();
  }

}
