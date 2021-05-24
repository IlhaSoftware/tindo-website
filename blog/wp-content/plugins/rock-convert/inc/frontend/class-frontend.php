<?php

namespace Rock_Convert\Inc\Frontend;

use Rock_Convert\Inc\Admin\Admin;
use Rock_Convert\inc\admin\announcements\Announcement;
use Rock_Convert\Inc\Frontend\Widget\Banner;
use Rock_Convert\Inc\Frontend\Widget\Subscribe;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */
class Frontend
{

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
     * The text domain of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_text_domain The text domain of this plugin.
     */
    private $plugin_text_domain;

    /**
     * Initialize the class and set its properties.
     *
     * @since        1.0.0
     *
     * @param        string $plugin_name The name of this plugin.
     * @param        string $version The version of this plugin.
     * @param        string $plugin_text_domain The text domain of this plugin
     */
    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name        = $plugin_name;
        $this->version            = $version;
        $this->plugin_text_domain = $plugin_text_domain;
        $this->register_ctas();
        $this->register_widgets();
    }

    /**
     * Register CTAS
     */
    public function register_ctas()
    {
        new CTA();
        new Download();
    }

    public function register_widgets()
    {
        add_action('widgets_init', array($this, 'load_widgets'));
    }

    public function load_widgets()
    {
        $subscribe_form = new Subscribe();
        register_widget($subscribe_form);

        $banner = new Banner();
        register_widget($banner);
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/rock-convert-frontend.min.css', array(),
            $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        $analytics_enabled = Admin::analytics_enabled();
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $params = array(
            'ajaxurl'                    => admin_url('admin-ajax.php'),
            'track_cta_click_path'       => get_rest_url(null,
                'rock-convert/v2/analytics/cta/click/'),
            'track_cta_view_path'        => \get_rest_url(null, 'rock-convert/v2/analytics/cta/view/'),
            'announcements_bar_settings' => Announcement::options(),
            'analytics_enabled'          => $analytics_enabled
        );
        wp_enqueue_script($this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/rock-convert-frontend.min.js',
            array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'rconvert_params', $params);


        if ($analytics_enabled) {
            add_action('wp_head', array($this, 'stage'));
        }
    }

    public function stage()
    {
        $blog_name = esc_js(get_bloginfo('name'));
        $base      = "d3fvet2f19dmip.cloudfront.net";
        echo "<script>window.chorusAnalytics_blogName = '$blog_name'</script>";

        if (is_single()) {
            $post                = get_post();
            $post_id             = $post->ID;
            $post_type           = $post->post_type;
            $post_date           = time($post->post_date);
            $post_author         = get_the_author_meta('display_name', $post->post_author);
            $categoriesJSON      = wp_json_encode($this->get_post_categories($post_id));
            $tagsJSON            = wp_json_encode($this->get_post_tags($post_id));
            $word_count          = $this->get_post_word_count($post_id);
            $published_by_studio = get_post_meta($post_id, 'published_by_studio', true) ? 'true' : 'false';

            echo "<script>
                window.chorusAnalytics_postType = '$post_type';
                window.chorusAnalytics_postDate = $post_date;
                window.chorusAnalytics_postAuthor = '$post_author';
                window.chorusAnalytics_postCategories = '$categoriesJSON';
                window.chorusAnalytics_postTags = '$tagsJSON';
                window.chorusAnalytics_postWordCount = $word_count;
                window.chorusAnalytics_postPublishedByStudio = $published_by_studio;
            </script>";
        }

        echo '<script src="https://' . $base
             . '/stage.js" async="async"></script>';
    }

    /**
     * @param $postID
     */
    public function get_post_categories($postID)
    {
        $categories = get_the_category($postID);

        if ( ! $categories) {
            return '';
        }

        $cat_names = array_map(function ($cat) {
            return $cat->name;
        }, $categories);

        return wp_json_encode($cat_names);
    }

    /**
     * @param $postID
     */
    public function get_post_tags($postID)
    {
        $tags = get_the_tags($postID);

        if ( ! $tags) {
            return '';
        }

        $tag_names = array_map(function ($tag) {
            return $tag->name;
        }, $tags);

        return wp_json_encode($tag_names);
    }

    /**
     * @param $postID
     */
    public function get_post_word_count($postID)
    {
        $content = get_post($postID)->post_content;

        if ( ! $content) {
            return 0;
        }

        return str_word_count(strip_tags($content));
    }

    /**
     *
     */
    public function rest_api_endpoint()
    {
        \register_rest_route('rock-convert/v2',
            '/analytics/cta/click/(?P<id>\d+)',
            array(
                'methods'  => 'POST',
                'callback' => array($this, 'track_cta_click'),
                'args'     => array(
                    'id' => array(
                        'validate_callback' => function (
                            $param,
                            $request,
                            $key
                        ) {
                            return is_numeric($param);
                        }
                    ),
                ),
            ));

        \register_rest_route('rock-convert/v2',
            '/analytics/cta/view/(?P<id>\d+)',
            array(
                'methods'  => 'POST',
                'callback' => array($this, 'track_cta_view'),
                'args'     => array(
                    'id' => array(
                        'validate_callback' => function (
                            $param,
                            $request,
                            $key
                        ) {
                            return is_numeric($param);
                        }
                    ),
                ),
            ));
    }

    /**
     * @param \WP_REST_Request $request
     */
    public function track_cta_click(\WP_REST_Request $request)
    {
        $id = sanitize_key($request->get_param('id'));

        $post = get_post($id);
        if ($post->post_type == "cta") {
            $this->increaseClickCount($post->ID);
        }
    }

    /**
     * @param $postID
     */
    public function increaseClickCount($postID)
    {
        $count_key = '_rock_convert_cta_clicks';
        $count     = intval(get_post_meta($postID, $count_key, true));

        if (empty($count)) {
            $count = 1;
        } else {
            $count++;
        }

        update_post_meta($postID, $count_key, $count);
    }


    /**
     * @param \WP_REST_Request $request
     */
    public function track_cta_view(\WP_REST_Request $request)
    {
        $id = sanitize_key($request->get_param('id'));

        $post = get_post($id);
        if ($post->post_type == "cta") {
            $this->increaseViewCount($post->ID);
        }
    }

    /**
     * @param $postID
     */
    public function increaseViewCount($postID)
    {
        $count_key = '_rock_convert_cta_views';
        $count     = intval(get_post_meta($postID, $count_key, true));

        if (empty($count)) {
            $count = 1;
        } else {
            $count++;
        }

        update_post_meta($postID, $count_key, $count);
    }
}
