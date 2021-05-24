<?php

namespace Rock_Convert\Inc\Admin;

class Custom_Meta_Box {

  public function __construct() {
    if ( is_admin() ) {
      add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
      add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
    }

  }

  public function init_metabox() {
    add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
    add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
  }

  public function add_metabox() {
    add_meta_box(
      'rock-convert-meta',
      __( 'Configurações', 'textdomain' ),
      array( $this, 'render_metabox' ),
      'cta',
      'normal',
      'high'
    );

  }

  public function render_metabox( $post ) {
    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'rock_convert_inner_custom_box', 'rock_convert_inner_custom_box_nonce' );

    // Use get_post_meta to retrieve an existing value from the database.
    $title = get_post_meta( $post->ID, '_rock_convert_title', true );
    $source = get_post_meta( $post->ID, '_rock_convert_utm_source', true );
    $medium = get_post_meta( $post->ID, '_rock_convert_utm_medium', true );
    $position = get_post_meta( $post->ID, '_rock_convert_position', true );

    if(empty($position)) {
      $position = "bottom";
    }

    // Display the form, using the current value.
?>
    <p>
      <label for="rock_convert_title">
        <strong><?php _e( 'Link', 'textdomain' ); ?></strong>
      </label>
      <input type="text" id="rock_convert_title" name="rock_convert_title" value="<?php echo esc_attr( $title ); ?>" size="55" style="width: 100%" />
      <em>Ex: http://www.meusite.com.br/download-ebook</em>
    </p>

    <p>
      <label for="rock_convert_utm_source">
        <strong><?php _e( 'UTM Source', 'textdomain' ); ?></strong>
      </label>
      <input type="text" id="rock_convert_utm_source" name="rock_convert_utm_source" value="<?php echo esc_attr( $source ); ?>" size="55" style="width: 100%" />
      <em>Ex: blog, newsletter, email</em>
    </p>

    <p>
      <label for="rock_convert_utm_medium">
        <strong><?php _e( 'UTM Medium', 'textdomain' ); ?></strong>
      </label>
      <input type="text" id="rock_convert_utm_medium" name="rock_convert_utm_medium" value="<?php echo esc_attr( $medium ); ?>" size="55" style="width: 100%" />
      <em>Ex: blog, newsletter, email</em>
    </p>

    <p>
      <label>
        <strong>Posição do CTA</strong>
      </label>
    </p>

    <p>
      <div>
        <input type="radio" id="rock_convert_position_top" name="rock_convert_position" <?php echo esc_attr($position) == "top" ? "checked" : ""; ?> value="top" />
        <label for="rock_convert_position_top">Acima do conteúdo</label>
      </div>
      <br/>
      <div>
        <input type="radio" id="rock_convert_position_bottom" name="rock_convert_position" <?php echo esc_attr($position) == "bottom" ? "checked" : ""; ?> value="bottom" />
        <label for="rock_convert_position_bottom">Abaixo do conteúdo</label>
      </div>
    </p>

<?php  //
  }

  public function save_metabox( $post_id, $post ) {
    // Check if nonce is set.
    $nonce = $_POST['rock_convert_inner_custom_box_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'rock_convert_inner_custom_box' ) ) {
      return $post_id;
    }

    // Check if user has permissions to save data.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }

    // Check if not an autosave.
    if ( wp_is_post_autosave( $post_id ) ) {
      return;
    }

    // Check if not a revision.
    if ( wp_is_post_revision( $post_id ) ) {
      return;
    }

    // Sanitize the user input.
    $title    = sanitize_text_field( $_POST['rock_convert_title'] );
    $source   = sanitize_text_field( $_POST['rock_convert_utm_source'] );
    $medium   = sanitize_text_field( $_POST['rock_convert_utm_medium'] );
    $position = sanitize_text_field( $_POST['rock_convert_position'] );

    // Update the meta field.
    update_post_meta( $post_id, '_rock_convert_title', $title );
    update_post_meta( $post_id, '_rock_convert_utm_source', $source );
    update_post_meta( $post_id, '_rock_convert_utm_medium', $medium );
    update_post_meta( $post_id, '_rock_convert_position', $position );

    // Update image field
    $image = array_map( 'intval', $_POST['rock-convert-media'] ); //sanitize
    foreach ( $image as $k => $v ) {
      update_post_meta( $post_id, $k, $v ); //save
    }
  }
}
