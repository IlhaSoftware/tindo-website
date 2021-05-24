<?php

namespace Rock_Convert\Inc\Admin;

class Custom_Post {

  public $labels;

  public $args;

  public $custom_post_type = "cta";

  public function __construct() {
    $this->set_labels();
    $this->set_args();
    $this->register();
  }

  public function set_labels() {
    $this->labels = array(
      'name'                => _x( 'CTAs', 'Post Type General Name', 'twentythirteen' ),
      'singular_name'       => _x( 'CTA', 'Post Type Singular Name', 'twentythirteen' ),
      'menu_name'           => __( 'Rock Convert', 'twentythirteen' ),
      'parent_item_colon'   => __( 'Parent CTA', 'twentythirteen' ),
      'all_items'           => __( 'Todos CTAs', 'twentythirteen' ),
      'view_item'           => __( 'Visualizar CTA', 'twentythirteen' ),
      'add_new_item'        => __( 'Novo CTA', 'twentythirteen' ),
      'add_new'             => __( 'Novo', 'twentythirteen' ),
      'edit_item'           => __( 'Alterar CTA', 'twentythirteen' ),
      'update_item'         => __( 'Atualizar CTA', 'twentythirteen' ),
      'search_items'        => __( 'Buscar CTA', 'twentythirteen' ),
      'not_found'           => __( 'Não encontrato', 'twentythirteen' ),
      'not_found_in_trash'  => __( 'Não encontrado na lixeira', 'twentythirteen' ),
    );
  }

  public function set_args() {
    $this->args = array(
      'label'               => __( 'cta', 'twentythirteen' ),
      'description'         => __( 'Banners de CTA', 'twentythirteen' ),
      'labels'              => $this->labels,
      'supports'            => array('title'),
      'hierarchical'        => false,
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => false,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
      'taxonomies'          => array('category'),
      'menu_icon'           => plugin_dir_url( __FILE__ ) . 'img/rockcontent.png',
    );
  }

  public function register() {
    register_post_type( $this->custom_post_type, $this->args );
  }
}
