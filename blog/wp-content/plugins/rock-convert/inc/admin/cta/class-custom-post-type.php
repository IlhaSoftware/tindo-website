<?php

namespace Rock_Convert\Inc\Admin\CTA;

use Rock_Convert\inc\admin\Utils;

/**
 * Class Custom_Post
 *
 * @package    Rock_Convert\Inc\CTA
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */
class Custom_Post_Type
{

    public $labels;

    public $args;

    public $custom_post_type = "cta";

    public function __construct()
    {
        $this->set_labels();
        $this->set_args();
        $this->register();
    }

    public function set_labels()
    {
        $this->labels = array(
            'name'               => _x('Banners', 'Post Type General Name',
                'rock-convert'),
            'singular_name'      => _x('Banner', 'Post Type Singular Name',
                'rock-convert'),
            'menu_name'          => __('Rock Convert', 'rock-convert'),
            'parent_item_colon'  => __('Parent CTA', 'rock-convert'),
            'all_items'          => __('Todos os banners', 'rock-convert'),
            'view_item'          => __('Visualizar banner', 'rock-convert'),
            'add_new_item'       => __('Novo banner', 'rock-convert'),
            'add_new'            => __('Novo banner', 'rock-convert'),
            'edit_item'          => __('Alterar banner', 'rock-convert'),
            'update_item'        => __('Atualizar banner', 'rock-convert'),
            'search_items'       => __('Buscar banner', 'rock-convert'),
            'not_found'          => __('Não encontrato', 'rock-convert'),
            'not_found_in_trash' => __('Não encontrado na lixeira',
                'rock-convert'),
        );
    }

    public function set_args()
    {
        $this->args = array(
            'label'               => __('cta', 'rock-convert'),
            'description'         => __('Banners de CTA', 'rock-convert'),
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
            'menu_icon'           => plugin_dir_url(__FILE__)
                                     . '../img/rockcontent.png',
        );
    }

    public function register()
    {
        register_post_type($this->custom_post_type, $this->args);
        add_filter('manage_cta_posts_columns',
            array($this, 'set_custom_edit_book_columns'));
        add_action('manage_cta_posts_custom_column',
            array($this, 'custom_book_column'), 10, 2);
        add_filter('manage_edit-cta_sortable_columns',
            array($this, 'views_sortable_column'));
        add_action('pre_get_posts', array($this, 'analytics_orderby'));
    }

    public function set_custom_edit_book_columns($columns)
    {
        unset($columns['categories']);
        unset($columns['date']);
        $columns['views']      = __('Visualizações', 'rock-convert');
        $columns['clicks']     = __('Clicks', 'rock-convert');
        $columns['ctr']        = __('CTR', 'rock-convert');
        $columns['categories'] = __('Categorias', 'rock-convert');
        $columns['date']       = __('Data', 'rock-convert');

        return $columns;
    }

    /**
     * @param $column
     * @param $post_id
     */
    public function custom_book_column($column, $post_id)
    {
        $analytics = Utils::get_post_analytics($post_id);

        switch ($column) {
            case 'views' :
                echo Utils::thousandsCurrencyFormat($analytics["views"]);
                break;
            case 'clicks' :
                echo Utils::thousandsCurrencyFormat($analytics["clicks"]);
                break;
            case 'ctr':
                echo round($analytics["ctr"], 2) . '%';
                break;
        }
    }

    /**
     * @param $columns
     *
     * @return mixed
     */
    public function views_sortable_column($columns)
    {
        $columns['views']  = 'views';
        $columns['clicks'] = 'clicks';

        return $columns;
    }

    /**
     * @param $query
     */
    public function analytics_orderby($query)
    {
        if ( ! is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('views' == $orderby) {
            $query->set('meta_key', '_rock_convert_cta_views');
            $query->set('orderby', 'meta_value_num');
        }

        if ('clicks' == $orderby) {
            $query->set('meta_key', '_rock_convert_cta_clicks');
            $query->set('orderby', 'meta_value_num');
        }
    }
}
