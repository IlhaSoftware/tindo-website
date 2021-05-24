<?php

class Material
{
    public $labels;
    public $args;
    public $custom_post_type;
    public $custom_post_type_rewrite;

    public function __construct($custom_post_type)
    {
        $this->custom_post_type         = $custom_post_type;
        $this->custom_post_type_rewrite = $custom_post_type;

        $this->set_labels();
        $this->set_args();
        $this->register();
    }

    public function set_labels()
    {
        $this->labels = array(
            'name'               => 'Materiais gratuitos',
            'singular_name'      => 'Material gratuito',
            'menu_name'          => 'Materiais',
            'parent_item_colon'  => '',
            'all_items'          => 'Todos os materiais',
            'view_item'          => 'Ver material',
            'add_new_item'       => 'Novo material gratuito',
            'add_new'            => 'Novo',
            'edit_item'          => 'Alterar material',
            'update_item'        => 'Atualizar material',
            'search_items'       => 'Procurar material',
            'not_found'          => __('Not found', 'rockcontent'),
            'not_found_in_trash' => __('Not found in trash', 'rockcontent'),
        );
    }

    public function set_args()
    {
        $this->args = array(
            'label'               => "Materiais gratuitos",
            'description'         => "Arquivo de materiais gratuitos",
            'labels'              => $this->labels,
            'supports'            => array('title', 'thumbnail'),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'taxonomies'          => array('category'),
            'rewrite'             => array('with_front' => false, 'slug' => $this->custom_post_type_rewrite),
            'menu_icon'           => 'dashicons-book-alt',
        );
    }

    public function register()
    {
        register_post_type($this->custom_post_type, $this->args);
    }
}

class Material_Meta_Box
{

    public $custom_post_type;

    public function __construct($custom_post_type)
    {
        $this->custom_post_type = $custom_post_type;
        if (is_admin()) {
            add_action('load-post.php', array($this, 'init_metabox'));
            add_action('load-post-new.php', array($this, 'init_metabox'));
        }
    }

    public function init_metabox()
    {
        add_action('add_meta_boxes', array($this, 'add_metabox'));
        add_action('save_post', array($this, 'save_metabox'), 10, 2);
        add_action('add_meta_boxes', array($this, 'remove_yoast'), 100);
    }

    public function remove_yoast()
    {
        remove_meta_box('wpseo_meta', 'material', 'normal');
    }

    public function add_metabox()
    {
        add_meta_box(
            'rock-material-meta',
            'Configurações',
            array($this, 'render_metabox'),
            $this->custom_post_type,
            'normal',
            'high'
        );
    }

    public function render_metabox($post)
    {
        // Add an nonce field so we can check for it later.
        wp_nonce_field('rock_inner_custom_box', 'rock_inner_custom_box_nonce');

        // Use get_post_meta to retrieve an existing value from the database.
        $link = get_post_meta($post->ID, '_rock_material_link', true);
        ?>
        <p>
            <label for="rock_material_link">
                <strong>Link do material</strong>
            </label>
            <input type="text" id="rock_material_link" name="rock_material_link"
                   value="<?php echo esc_attr($link); ?>" size="55" style="width: 100%"/>
            <em>Ex: https://meu-blog.com.br/ebook-sobre-algum-assunto</em>
        </p>

        <?php
    }

    public function save_metabox($post_id, $post)
    {
        // Check if nonce is set.
        $nonce = ! empty($_POST) ? $_POST['rock_inner_custom_box_nonce'] : null;

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce($nonce, 'rock_inner_custom_box')) {
            return $post_id;
        }

        // Check if user has permissions to save data.
        if ( ! current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check if not an autosave.
        if (wp_is_post_autosave($post_id)) {
            return;
        }

        // Check if not a revision.
        if (wp_is_post_revision($post_id)) {
            return;
        }

        // Sanitize the user input.
        $link = sanitize_text_field($_POST['rock_material_link']);

        // Update the meta field.
        update_post_meta($post_id, '_rock_material_link', $link);
    }
}


function rock_materiais_gratuitos_query($limit = 12)
{
    $custom_post_type_name = "material-gratuito";
    $paged                 = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args                  = array(
        'post_type'      => $custom_post_type_name,
        'posts_per_page' => $limit,
        'order'          => 'DESC',
        'orderby'        => 'date',
        'post_status'    => 'publish',
        'paged'          => $paged
    );

    $query = new WP_Query($args);

    return $query;
}


$custom_post_type_name = "material-gratuito";

new Material($custom_post_type_name);
new Material_Meta_Box($custom_post_type_name);