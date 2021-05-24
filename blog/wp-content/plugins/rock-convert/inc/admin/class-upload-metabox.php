<?php

namespace Rock_Convert\Inc\Admin;

class Upload_Metabox {

  function __construct() {
    add_action( 'add_meta_boxes', array( $this, 'setup_box' ) );
  }

  function setup_box() {
    add_meta_box(
      'rock_convert_banner',
      __( 'Upload de imagem', 'rock-convert-upload' ),
      array( $this, 'rock_convert_media_upload' ),
      'cta',
      'normal',
      'low' );
  }

  function rock_convert_media_upload() {
    wp_enqueue_media();
    wp_enqueue_script( 'meta-box-media', plugins_url('js/media.js', __FILE__ ), array('jquery') );
    wp_nonce_field( 'nonce_action', 'nonce_name' );
    // one or more
    $field_names = array( '_rock_convert_image_media' );
    foreach ( $field_names as $name ) {
      $value = $rawvalue = get_post_meta( get_the_id(), $name, true );
      $name = esc_attr( $name );
      $value = esc_attr( $value );
      echo "<input type='hidden' id='$name-value'  class='small-text'       name='rock-convert-media[$name]' value='$value' />";
      echo "<input type='button' id='$name'        class='button button-primary rock-convert-upload-button' value='Selecionar imagem' />";
      echo "<input type='button' id='$name-remove' class='button rock-convert-upload-button-remove' value='Remover' />";
      $image = ! $rawvalue ? '' : wp_get_attachment_image( $rawvalue, 'full', false, array('style' => 'max-width:100%;height:auto;') );
      echo "<div class='rock-convert-image-preview'>$image</div>";
      echo '<br />';
    }
  }
}
