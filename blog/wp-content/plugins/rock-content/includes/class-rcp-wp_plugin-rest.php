<?php

/**
 * This class has all code necessary to handle the endpoints defined in Rcp_Wp_plugin
 *
 * @since      1.0.0
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/includes
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Wp_plugin_Rest {

  /**
   *
   */
  public static $errors = array(
    /**
     * Token errors
     */
    "INVALID_TOKEN"          => "TK01",
    "TOKEN_NOT_PROVIDED"     => "TK02",
    /**
     * Integration errors
     */
    "INTEGRATION_FAILED"     => "IT01",
    /**
     * Publish post errors
     */
    "INVALID_POST_FIELDS"    => "PP01",
    "INVALID_WP_POST_FIELDS" => "PP02",
    /**
     * List post errors
     */
    "POST_STATUS_REQUIRED"   => "LP01",
    /**
     * Find post errors
     */
    "POST_ID_REQUIRED"       => "FP01",
    "POST_NOT_FOUND"         => "FP02"
  );

  /**
   * List of endpoints used by this plugin
   *
   * @var array
   */
  public $endpoints = array(
    "ACTIVATE"              => array( "method" => "post", "endpoint" => "rcp-activate-plugin", "authentication" => "rcp_authentication" ),
    "PUBLISH_POST"          => array( "method" => "post", "endpoint" => "rcp-publish-content", "authentication" => "rcp_authentication" ),
    "DISCONNECT"            => array( "method" => "get", "endpoint" => "rcp-disconnect-plugin", "authentication" => "rcp_authentication" ),
    "LIST_POSTS"            => array( "method" => "get", "endpoint" => "rcp-list-posts", "authentication" => "rcp_authentication" ),
    "LIST_CATEGORIES"       => array( "method" => "get", "endpoint" => "rcp-list-categories", "authentication" => "rcp_authentication" ),
    "LIST_USERS"            => array( "method" => "get", "endpoint" => "rcp-list-users", "authentication" => "rcp_authentication" ),
    "FIND_POST"             => array( "method" => "get", "endpoint" => "rcp-find-post", "authentication" => "rcp_authentication" ),
    "VERSION"               => array( "method" => "get", "endpoint" => "rcp-wp-version", "authentication" => "rcp_authentication" ),
    "CONNECT_TO_STAGE"      => array( "method" => "post", "endpoint" => "rcp-connect-plugin-to-stage", "authentication" => "chorus_wp_authentication" ),
    "ADD_CONTACT"           => array( "method" => "post", "endpoint" => "rcp-add-contact", "authentication" => "public" ),
    "SITE_INFO"             => array( "method" => "get", "endpoint" => "rcp-site-info", "authentication" => "public" ),
  );

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $plugin_name The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $version The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   *
   * @param      string $plugin_name The name of this plugin.
   * @param      string $version The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version     = $version;
    $this->response    = new Rcp_Response();
    $this->admin       = new Rcp_Wp_plugin_Admin( $plugin_name, $version );
  }

  /**
   * @since 1.0.0
   */
  public function rcp_define_endpoints() {
    add_rewrite_endpoint( $this->endpoints["ACTIVATE"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["PUBLISH_POST"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["DISCONNECT"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["LIST_CATEGORIES"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["LIST_POSTS"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["LIST_USERS"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["FIND_POST"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["VERSION"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["CONNECT_TO_STAGE"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["SITE_INFO"]["endpoint"], EP_ROOT );
    add_rewrite_endpoint( $this->endpoints["ADD_CONTACT"]["endpoint"], EP_ROOT );

    // Flag checking is necessary because flush_rewrite_rules() is an expensive operation.
    if ( ! get_option( 'rcp_rewrite_rules_were_flushed', false ) ) {
      flush_rewrite_rules();
      update_option( 'rcp_rewrite_rules_were_flushed', true );
    }
  }

  /**
   * @since 1.0.0
   */
  public function intercept_request() {
    global $wp_query;
    foreach ( $this->endpoints as $name => $endpoint ) {

      if ( isset( $wp_query->query_vars[ $endpoint["endpoint"] ] ) ) {
        $data = Rcp_Authentication::preserved_data( $endpoint["method"] );

        switch ( $endpoint["authentication"] ) {
          case "rcp_authentication":
            $data = Rcp_Authentication::authenticate( $endpoint["method"] );
            break;
          case "chorus_wp_authentication":
            $username = sanitize_text_field( $data["username"] );
            $password = sanitize_text_field( $data["password"] );
            $user = wp_authenticate( $username, $password );

            if ( is_wp_error( $user ) ) {
              Rcp_Response::respond_with( 403, array(
                "error" => "Invalid username and/or password"
              ) );
              die;
            }
            break;
          case "public":
            break;
        }

        $func = "handle_" . str_replace( "-", "_", $endpoint["endpoint"] ) . "_request";
        $this->$func( $data );

        exit;
      }
    }

    return;
  }

  /**
   * @param $data
   *
   * @since 1.0.0
   *
   * @since 2.0.0
   */
  public function handle_rcp_disconnect_plugin_request( $data ) {
    $disconnected_at = $this->admin->disconnect();

    if($data["application_version"] == "RC2") {
      Rcp_Response::respond_with( 200, array(
        "disconnected_at" => $disconnected_at
      ) );
    } else {
      Rcp_Response::respond_with( 200, array(
        "success" => "wordpress disconnected successfully"
      ) );
    }
  }

  /**
   * @since 1.0.0
   *
   * @since 2.0.0
   */
  public function handle_rcp_activate_plugin_request( $data = null ) {
    if ( $activated_at = $this->admin->integrate() ) {
      if($data["application_version"] == "RC2") {
        $this->rc2_activate_response($activated_at);
      } else {
        $this->rcp_activate_response($activated_at);
      }
    } else {
      Rcp_Response::respond_with( 500, array(
        "error_code" => self::$errors["INTEGRATION_FAILED"],
        "errors"     => array( "integration failed" )

      ) );
    }
  }

  /**
   * @since 2.0.0
   */
  public function rcp_activate_response($activated_at) {
    Rcp_Response::respond_with( 200, array(
      "success"      => "wordpress was successfully integrated",
      "activated_at" => $activated_at
    ) );
  }

  /**
   * @since 2.0.0
   */
  public function rc2_activate_response($activated_at) {
    Rcp_Response::respond_with( 200, array(
      "credentials" => array(
        "url"   => $this->admin->get_url(),
        "token" => $this->admin->get_token()
      ),
      "data"        => array(
        "activated_at"      => $activated_at,
        "rcp_version"       => $this->version,
        "wordpress_version" => get_bloginfo( 'version' ),
        "php_version"       => PHP_VERSION
      )
    ) );
  }

  /**
   * @since   1.0.0
   *
   * @since   1.2.1
   *
   * @since   1.2.2
   *
   * @since   2.0.0
   *
   */
  public function handle_rcp_publish_content_request( $data ) {

    $post = array(
      'post_title'   => sanitize_text_field( $data["post_title"] ),
      'post_content' => $data["post_content"],
      'post_status'  => sanitize_text_field( $data["post_status"] ),
      'post_author'  => $data["post_author"]
    );

    if ($data["application_version"] == "RC2") {
      if ( isset( $data["post_category"] ) ) {
        $post["post_category"] = array( $data["post_category"] );
      }
    } else {
      if ( isset( $data["terms"]["category"] ) ) {
        $post["post_category"] = $data["terms"]["category"];
      }
    }

    if ( !empty( $data["post_tags"] ) ) {
      $tags = explode(",", $data["post_tags"]);
      $post["tags_input"] = $tags;
    }

    if ( !empty( $data["post_name"] ) ) {
      $post["post_name"] = $data["post_name"];
    }

    $featured_image      = $data["featured_image"];
    $featured_image_name = sanitize_text_field( $data["featured_image_name"] );

    try {
      $this->validate_post_content_request( $post );

      $post_id = $this->publish_post( $post );

      update_post_meta($post_id, 'published_by_studio', true);

      if ( ! empty( $featured_image ) ) {
        $this->upload_featured_image( $featured_image, $post_id, $featured_image_name );
      }

      $post = $this->find_post( $post_id );

      Rcp_Response::respond_with( 200, $post );

    } catch ( Rcp_Wp_Exception $e ) {
      Rcp_Response::respond_with( $e->getCode(), $e->GetOptions() );
    }
  }

  /**
   * @param $post
   *
   * @since 1.0.0
   */
  private function validate_post_content_request( $post ) {
    $errors = array();
    if ( empty( $post["post_title"] ) ) {
      $errors["post_title"] = "post_title is required";
    }
    if ( empty( $post["post_content"] ) ) {
      $errors["post_content"] = "post_content is required";
    }
    if ( empty( $post["post_status"] ) ) {
      $errors["post_status"] = "post_status is required";
    }

    if ( ! empty( $errors ) ) {
      throw new Rcp_Wp_Exception( null, 403, null,
        array(
          "error_code" => self::$errors["INVALID_POST_FIELDS"],
          "errors"     => $errors
        ) );
    }
  }

  /**
   * @param array $post_attrs
   *
   * @return int|WP_Error
   * @throws Rcp_Wp_Exception
   *
   * @since 1.0.0
   */
  private function publish_post( $post_attrs = array() ) {

    $post_id = wp_insert_post( $post_attrs );

    if ( is_wp_error( $post_id ) ) {
      $errors = $post_id->get_error_messages();

      $errors["error_code"] = self::$errors["INVALID_WP_POST_FIELDS"];
      throw new Rcp_Wp_Exception( null, 403, null, $errors );
    }

    return $post_id;
  }

  /**
   * @param $image_url
   * @param $post_id
   *
   * @since 1.0.0
   *
   * @since 1.1.0
   *
   * @since 1.2.0
   *
   * @since 1.2.2
   */
  private function upload_featured_image( $image_url, $post_id, $image_name = null ) {
    $image_url = $this->remove_query_strings( $image_url );
    $src       = @media_sideload_image( $image_url, $post_id, $image_name, "src" );
    $attach_id = $this->get_attatchment_id( $src );
    update_post_meta( $post_id, '_thumbnail_id', $attach_id );
  }

  /**
   * @param $image_url
   *
   * @return mixed
   *
   * @since 1.1.0
   */
  function get_attatchment_id( $image_url ) {
    global $wpdb;
    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );

    return $attachment[0];
  }

  /**
   * @param $url
   *
   * @return string
   *
   * @since 1.0.0
   */
  private function remove_query_strings( $url ) {
    $pos = strpos( $url, "?" );

    if ( $pos !== false ) {
      $url = substr( $url, 0, $pos );
    }

    return $url;
  }

  /**
   * @param $id
   *
   * @return array|bool
   *
   * @since 1.0.0
   */
  private function find_post( $id ) {
    $post_object = get_post( $id );

    if ( ! $post_object ) {
      return false;
    }

    $post = get_object_vars( $post_object );
    $post = $this->parametrize_post_response( $post );

    return $post;
  }

  /**
   * @param $post
   *
   * @since 1.0.0
   */
  private function parametrize_post_response( $post ) {
    Rcp_Wp_plugin::_rename_arr_key( "ID", "post_id", $post );
    Rcp_Wp_plugin::_rename_arr_key( "post_author", "author", $post );

    $post["featured_image"] = wp_get_attachment_url( get_post_thumbnail_id( $post["post_id"] ) );
    $post["terms"]          = $this->get_post_categories( $post["post_id"] );
    $post["link"]           = get_permalink( $post["post_id"] );


    return $post;
  }

  /**
   * @param $post_id
   *
   * @since 1.0.0
   */
  private function get_post_categories( $post_id ) {
    $post_categories = wp_get_post_categories( $post_id );
    $cats            = array();

    foreach ( $post_categories as $c ) {
      $cat    = get_category( $c );
      $cats[] = $this->parametrize_category( $cat );
    }

    return $cats;
  }

  /**
   * @param $category
   *
   * @return array
   *
   * @since 1.0.0
   */
  private function parametrize_category( $category ) {
    return array(
      "term_id"  => (int) $category->term_id,
      "name"     => $category->name,
      "slug"     => $category->slug,
      "taxonomy" => "category"
    );
  }

  /**
   * @param $category
   *
   * @return array
   *
   * @since 1.0.1
   */
  private function parametrize_user( $user ) {
    return array(
      "user_id"      => (int) $user->data->ID,
      "display_name" => $user->data->user_login,
      "email"        => $user->data->user_email,
      "roles"        => $user->roles
    );
  }

  /**
   * @since 1.0.0
   */
  public function handle_rcp_wp_version_request( $data = null ) {

    Rcp_Response::respond_with( 200, array(
      "software_version" => array(
        "value" => get_bloginfo( 'version' )
      ),
      "rcp_version"      => array(
        "value" => $this->version
      )
    ) );
  }

  /**
   * @since 1.0.0
   */
  public function handle_rcp_list_posts_request( $data = null ) {

    try {
      $this->validate_get_posts_request( $data );

      $posts = get_posts( $this->build_get_posts_params( $data ) );

      foreach ( $posts as $i => $post ) {
        $posts[ $i ] = $this->parametrize_post_response( get_object_vars( $post ) );
      }

      Rcp_Response::respond_with( 200, $posts );
    } catch ( Rcp_Wp_Exception $e ) {
      Rcp_Response::respond_with( $e->getCode(), $e->GetOptions() );
    }
  }

  /**
   * @throws Rcp_Wp_Exception
   *
   * @since 1.0.0
   */
  private function validate_get_posts_request( $data ) {
    $errors = array();

    if ( ! isset( $data['post_status'] ) ) {
      $errors[] = "post_status parameter is required";
    }

    if ( ! empty( $errors ) ) {
      throw new Rcp_Wp_Exception( null, 403, null, array(
        "error_code" => self::$errors["POST_STATUS_REQUIRED"],
        "errors"     => $errors
      ) );
    }
  }

  /**
   * @since 1.0.0
   */
  private function build_get_posts_params( $data ) {
    $params = array();

    $params["posts_per_page"] = isset( $data["number"] ) ? intval( $data["number"] ) : 20;
    $params["offset"]         = isset( $data["offset"] ) ? intval( $data["offset"] ) : 0;
    $params["post_status"]    = isset( $data["post_status"] ) ? sanitize_text_field( $data["post_status"] ) : "publish";
    $params["post_type"]      = isset( $data["post_type"] ) ? sanitize_text_field( $data["post_type"] ) : "post";

    return $params;
  }

  /**
   * @since 1.0.0
   */
  public function handle_rcp_list_categories_request() {
    $categories = $this->get_filtered_categories();

    Rcp_Response::respond_with( 200, $categories );
  }

  /**
   * @return array
   *
   * @since 1.0.0
   */
  private function get_filtered_categories() {
    $categories = get_categories();
    $filtered   = array();

    foreach ( $categories as $i => $category ) {
      array_push( $filtered, $this->parametrize_category( $category ) );
    }

    return $filtered;
  }

  /**
   * @since 1.0.0
   */
  public function handle_rcp_list_users_request( $data = null ) {
    $users = $this->get_filtered_users();

    Rcp_Response::respond_with( 200, $users );
  }

  /**
   * @return array
   *
   * @since 1.0.0
   *
   * @updated 1.0.1
   */
  private function get_filtered_users() {
    $users    = get_users();
    $filtered = array();

    foreach ( $users as $i => $user ) {
      array_push( $filtered, $this->parametrize_user( $user ) );
    }

    return $filtered;
  }

  /**
   * @param null $data
   *
   * @since 1.0.0
   */
  public function handle_rcp_find_post_request( $data = null ) {
    if ( empty( $data["post_id"] ) || ! intval( $data["post_id"] ) ) {
      return Rcp_Response::respond_with( 400, array(
        "error_code" => self::$errors["POST_STATUS_REQUIRED"],
        "errors"     => array( "Post ID is required" )
      ) );
      exit;
    }

    $id = $data["post_id"];

    if ( $post = $this->find_post( $id ) ) {
      Rcp_Response::respond_with( 200, $post );
    } else {
      Rcp_Response::respond_with( 404, array(
        "error_code" => self::$errors["POST_NOT_FOUND"],
        "errors"     => array( "Post not found" )
      ) );
    }
  }

  /**
   * @since 2.4.0
   */
  public function handle_rcp_site_info_request( $data = null ) {
    Rcp_Response::respond_with( 200, array(
      "site_name" => array(
        "value" => get_bloginfo( "name" )
      ),
      "chorus_connected" => array(
        "value" => get_option( "rcp_chorus_connected", false )
      )
    ) );
  }

  /**
   * @since 2.4.0
   */
  public function handle_rcp_connect_plugin_to_stage_request( $data ) {
    try {
      $this->validate_connect_plugin_to_stage_request( $data );

      if ( get_option( "rcp_chorus_connected", false ) ) {
        Rcp_Response::respond_with( 409, array(
          "success" => false,
          "message" => "wordpress site is already connected"
        ) );
        die;
      }

      $connection_token = md5( microtime() );

      update_option( "rcp_chorus_connected", true );
      update_option( "rcp_chorus_connection_token", $connection_token );
      update_option( "rcp_chorus_disconnect_hook_url", $data["disconnect_hook_url"] );
      update_option( "rcp_chorus_integration_services_urls", stripslashes( $data["integration_services_urls"]) );
      update_option( "rcp_chorus_analytics_enabled", true );
      update_option( "rcp_chorus_integrations_enabled", true );
      update_option( "rcp_chorus_analytics_write_key", sanitize_text_field( $data["analytics_write_key"] ) );
      update_option( "rcp_chorus_analytics_domain", sanitize_text_field( $data["analytics_domain"] ) );
      update_option( "rcp_chorus_blog_id", sanitize_text_field( $data["blog_id"] ) );
      update_option( "rcp_chorus_user_id", sanitize_text_field( $data["user_id"] ) );

      Rcp_Response::respond_with( 200, array(
        "success"         => true,
        "connectionToken" => $connection_token,
        "message"         => "wordpress site connected successfully"
      ) );
    } catch ( Rcp_Wp_Exception $e ) {
      Rcp_Response::respond_with( $e->getCode(), $e->GetOptions() );
    }
  }

  /**
   * @since 2.4.0
   */
  public function handle_rcp_add_contact_request( $data = null ) {
    $site_url = site_url();
    $http_origin = get_http_origin();

    if ( $site_url !== $http_origin ) {
      Rcp_Response::respond_with( 403 );
      die;
    }

    try {
      $this->validate_add_contact_request( $data );

      $group = $data["group"];
      $email = $data["email"];
      $blog_name = get_bloginfo( "name" );
      $blog_id = get_option( "rcp_chorus_blog_id" );
      $user_id = get_option( "rcp_chorus_user_id" );
      $analytics_write_key = get_option( "rcp_chorus_analytics_write_key" );
      $analytics_domain = get_option( "rcp_chorus_analytics_domain" );
      $hubspot_cookie = $_COOKIE["hubspotutk"];
      $integration_services_urls = json_decode( get_option( "rcp_chorus_integration_services_urls" ), true );

      $mailchimp_service_url = $integration_services_urls["mailchimp"];
      $rdstation_service_url = $integration_services_urls["rdstation"];
      $hubspot_service_url = $integration_services_urls["hubspot"];

      $request_headers = [ "Content-type" => "application/json" ];
      $request_data = [
        "blogId"   => $blog_id,
        "blogName" => $blog_name,
        "email"    => $email
      ];

      $requests = [
        [
          "url"     => "https://$analytics_domain/event/collect",
          "type"    => "POST",
          "headers" => $request_headers,
          "data"    => json_encode( [
            "collection" => "chorus_ghost_integration_leads",
            "api"        => [ "api_key" => $analytics_write_key ],
            "properties" => json_encode( $request_data )
          ] )
        ],
        [
          "url"     => "$mailchimp_service_url/user/$user_id/lists/$group/members",
          "type"    => "POST",
          "headers" => $request_headers,
          "data"    => json_encode( $request_data )
        ],
        [
          "url"     => "$rdstation_service_url/user/$user_id/contacts",
          "type"    => "POST",
          "headers" => $request_headers,
          "data"    => json_encode( array_merge( $request_data, [ "group" => $group ] ) )
        ],
        [
          "url"     => "$hubspot_service_url/user/$user_id/contacts",
          "type"    => "POST",
          "headers" => $request_headers,
          "data"    => json_encode( array_merge( $request_data, [ "group" => $group, "hutk" => $hubspot_cookie ] ) )
        ]
      ];

      Requests::request_multiple($requests);
    } catch ( Rcp_Wp_Exception $e ) {
      Rcp_Response::respond_with( $e->getCode(), $e->GetOptions() );
    }
  }

  /**
   * @since 2.4.0
   */
  private function validate_add_contact_request( $data ) {
    $group = $data["group"];
    $email = $data["email"];
    $allowed_groups = [ "commenters", "subscribers", "others" ];
    $errors = array();

    if ( empty( $group ) ) {
      $errors["group"] = "group is required";
    } else if ( ! in_array( $group, $allowed_groups ) ) {
      $errors["group"] = "group is invalid";
    }

    if ( empty( $email ) ) {
      $errors["email"] = "email is required";
    } else if ( ! is_email( $email ) ) {
      $errors["email"] = "email is invalid";
    }

    if ( ! empty( $errors ) ) {
      throw new Rcp_Wp_Exception( null, 400, null,
        array( "errors" => $errors ) );
    }
  }

  /**
   * @since 2.4.0
   */
  private function validate_connect_plugin_to_stage_request( $data ) {
    $errors = array();

    if ( empty( $data["analytics_write_key"] ) ) {
      $errors["analytics_write_key"] = "analytics_write_key is required";
    }
    if ( empty( $data["analytics_domain"] ) ) {
      $errors["analytics_domain"] = "analytics_domain is required";
    }
    if ( empty( $data["blog_id"] ) ) {
      $errors["blog_id"] = "blog_id is required";
    }
    if ( empty( $data["user_id"] ) ) {
      $errors["user_id"] = "user_id is required";
    }
    if ( empty( $data["disconnect_hook_url"] ) ) {
      $errors["disconnect_hook_url"] = "disconnect_hook_url is required";
    }
    if ( empty( $data["integration_services_urls"] ) ) {
      $errors["integration_services_urls"] = "integration_services_urls is required";
    }

    if ( ! empty( $errors ) ) {
      throw new Rcp_Wp_Exception( null, 400, null,
        array( "errors" => $errors ) );
    }
  }
}
