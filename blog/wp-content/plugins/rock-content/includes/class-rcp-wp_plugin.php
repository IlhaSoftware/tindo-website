<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://rockcontent.com/
 * @since      1.0.0
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/includes
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Wp_plugin {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Rcp_Wp_plugin_Loader $loader Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The rest client that's responsible for responding to rest calls made to this plugin from outside wordpress
   *
   * @since    1.0.0
   * @access   protected
   * @var      Rcp_Wp_plugin_Rest $rest_client
   */
  protected $rest_client;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string $plugin_name The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string $version The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {

    $this->plugin_name = 'rcp-wp_plugin';
    $this->version     = '2.4.0';

    $this->load_dependencies();
    $this->define_admin_hooks();
    $this->define_public_hooks();
    $this->define_rest_endpoints();
    $this->define_chorus_analytics_hook();
    $this->define_chorus_integrations_hook();

  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Rcp_Wp_plugin_Loader. Orchestrates the hooks of the plugin.
   * - Rcp_Wp_plugin_i18n. Defines internationalization functionality.
   * - Rcp_Wp_plugin_Admin. Defines all hooks for the admin area.
   * - Rcp_Wp_plugin_Public. Defines all hooks for the public side of the site.
   * - Rcp_Wp_plugin_Chorus_Analytics. Defines Chorus analytics.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rcp-exception.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rcp-response.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rcp-authentication.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rcp-requirements.php';

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rcp-wp_plugin-loader.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rcp-wp_plugin-admin.php';

    /**
     * Libs required to upload files
     */
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rcp-wp_plugin-rest.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rcp-wp_plugin-chorus-analytics.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rcp-wp_plugin-chorus-integrations.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-rcp-wp_plugin-public.php';

    $this->loader = new Rcp_Wp_plugin_Loader();
  }


  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {
    global $pagenow;

    $plugin_admin = new Rcp_Wp_plugin_Admin( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'admin_menu', $plugin_admin, 'rcp_plugin_menu' );
    $this->loader->add_action( 'admin_init', $plugin_admin, 'rcp_initialize_layout_options' );

    if ( ! $plugin_admin->integrated() && $pagenow == 'plugins.php' ) {
      $this->loader->add_action( 'admin_notices', $plugin_admin, 'activation_notice' );
    }

    if ( isset( $_GET["integrated"] ) && $_GET["integrated"] == "success" ) {
      $this->loader->add_action( "admin_notices", $plugin_admin, 'activation_success' );
    }
  }

  /**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.4.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Rcp_Wp_plugin_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

  /**
   * Define rest endpoints used by this plugin
   *
   * @since 1.0.0
   */
  protected function define_rest_endpoints() {
    $rest_client = new Rcp_Wp_plugin_Rest( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'init', $rest_client, 'rcp_define_endpoints' );

    $this->loader->add_action( 'template_redirect', $rest_client, 'intercept_request' );
  }

  private function define_chorus_analytics_hook() {
    $analytics = new Rcp_Wp_plugin_Chorus_Analytics();

    $this->loader->add_action( 'wp_head', $analytics, 'get_analytics_tpl' );
  }

  private function define_chorus_integrations_hook() {
    $integrations = new Rcp_Wp_plugin_Chorus_Integrations();

    $this->loader->add_action( 'wp_footer', $integrations, 'get_integrations_tpl' );
  }

  /**
   * Helper function to rename array keys.
   *
   * @since 1.0.0
   */
  public static function _rename_arr_key( $oldkey, $newkey, array &$arr ) {
    if ( array_key_exists( $oldkey, $arr ) ) {
      $arr[ $newkey ] = $arr[ $oldkey ];
      unset( $arr[ $oldkey ] );

      return true;
    } else {
      return false;
    }
  }

  public static function debug( $array ) {
    echo "<pre>";
    print_r( $array );
    echo "</pre>";
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Rcp_Wp_plugin_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }
}
