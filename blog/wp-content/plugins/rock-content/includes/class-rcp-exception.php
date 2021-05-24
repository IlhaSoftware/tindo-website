<?php

/**
 * Custom exception to use in RCP Plugin
 *
 * The only difference between this exception and a common exception is that you can pass an array as a message
 * in the last parameter.
 *
 * @since      1.0.0
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/includes
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Wp_Exception extends \Exception {

  /**
   * @var array
   *
   * @since 1.0.0
   */
  private $_options;

  /**
   * @param string $message
   * @param int $code
   * @param Exception $previous
   * @param array $options
   *
   * @since 1.0.0
   */
  public function __construct(
    $message,
    $code = 0,
    Exception $previous = null,
    $options = array( 'params' )
  ) {
    parent::__construct( $message, $code, $previous );

    $this->_options = $options;
  }

  /**
   * @return array
   *
   * @since 1.0.0
   */
  public function GetOptions() {
    return $this->_options;
  }
}
