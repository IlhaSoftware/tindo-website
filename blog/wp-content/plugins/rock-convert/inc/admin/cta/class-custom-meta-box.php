<?php

namespace Rock_Convert\Inc\Admin\CTA;

use Rock_Convert\Inc\Admin\Admin;
use Rock_Convert\inc\admin\Utils;

/**
 * Class Custom_Meta_Box
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      1.0.0
 * @since      2.0.0
 *
 * @author     Rock Content
 */
class Custom_Meta_Box
{

    public function __construct()
    {
        if (is_admin()) {
            add_action('load-post.php', array($this, 'init_metabox'));
            add_action('load-post-new.php', array($this, 'init_metabox'));
        }
    }

    public function init_metabox()
    {
        add_action('add_meta_boxes', array($this, 'add_upload_metabox'));
        add_action('do_meta_boxes', array($this, 'move_categories'));
        add_action('add_meta_boxes', array($this, 'add_metabox'));
        add_action('add_meta_boxes', array($this, 'add_shortcode_box'));
        add_action('add_meta_boxes', array($this, 'add_visibility_box'));
        add_action('add_meta_boxes', array($this, 'add_analytics_box'));

        add_action('save_post', array($this, 'save_metabox'), 10, 2);
    }

    public function move_categories()
    {
        remove_meta_box('categorydiv', 'cta', 'side');
        add_meta_box('categorydiv',
            __('Categorias de exibição', 'rock-convert'),
            'post_categories_meta_box', 'cta', 'side', 'high');
    }

    public function add_visibility_box()
    {
        add_meta_box(
            'rock-convert-visibility',
            __('Visibilidade', 'rock-convert'),
            array($this, 'render_visibility_box'),
            'cta',
            'normal',
            'low'
        );
    }

    public function add_metabox()
    {
        add_meta_box(
            'rock-convert-meta',
            __('Configurações do Banner', 'rock-convert'),
            array($this, 'render_metabox'),
            'cta',
            'normal',
            'low'
        );
    }

    public function add_shortcode_box()
    {
        global $post;

        if ( ! isset($post)) {
            return;
        }

        if ($post->post_status != "publish") {
            return;
        }

        add_meta_box(
            'rock-convert-shortcode',
            __('Utilização em qualquer lugar', 'rock-convert'),
            array($this, 'render_shortcode_box'),
            'cta',
            'side',
            'high'
        );
    }

    public function add_analytics_box()
    {
        global $post;

        if ( ! isset($post)) {
            return;
        }

        if ($post->post_status != "publish") {
            return;
        }

        add_meta_box(
            'rock-convert-analytics',
            __('Estatísticas', 'rock-convert'),
            array($this, 'render_analytics_box'),
            'cta',
            'side',
            'high'
        );
    }

    public function render_analytics_box($post)
    {
        $analytics_enabled = Admin::analytics_enabled();
        $analytics         = Utils::get_post_analytics($post->ID, $analytics_enabled);
        ?>


        <div style="text-align: center;<?php echo $analytics_enabled
            ? null : "filter: blur(6px)" ?>">
            <span class="rconvert-analytics-numbers">
                <?php echo number_format($analytics["views"], 0, ',', '.'); ?>
            </span>
            <div class="rconvert-analytics-label">
                <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/views.svg" class="rconvert-analytics-icon"/>
                <?php echo __("Visualizações", "rock-convert"); ?>
            </div>

            <hr class="rconvert-analytics-divider">

            <span class="rconvert-analytics-numbers">
                <?php echo number_format($analytics["clicks"], 0, ',', '.'); ?>
            </span>
            <div class="rconvert-analytics-label">
                <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/click.svg" class="rconvert-analytics-icon"/>
                <?php echo __("Clicks", "rock-convert"); ?>
            </div>

            <hr class="rconvert-analytics-divider">

            <span class="rconvert-analytics-numbers">
                <?php echo round($analytics["ctr"], 2); ?>
                %
            </span>
            <div class="rconvert-analytics-label">
                <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/ctr.svg" class="rconvert-analytics-icon"/>
                <?php echo __("Taxa de conversão", "rock-convert"); ?>
            </div>
        </div>
        <?php if ( ! $analytics_enabled) { ?>
        <div class="analytics-warning" style="margin-top: 50px;">
            <p><strong style="color: red"><?php echo __('Atenção:', 'rock-convert'); ?></strong> <?php echo __('A funcionalidade analytics não está habilitada.', 'rock-convert'); ?> <br/><br><?php echo __('Para ver as estatísticas deste CTA', 'rock-convert'); ?>
                <a href="<?php echo admin_url("edit.php?post_type=cta&page=rock-convert-settings&tab=general") ?>"><?php echo __('habilite a funcionalidade Analytics', 'rock-convert'); ?></a></p>
        </div>
    <?php } ?>
        <?php
    }


    public function add_upload_metabox()
    {
        add_meta_box(
            'rock_convert_banner',
            __('Selecione a imagem do banner', 'rock-convert'),
            array($this, 'rock_convert_media_upload'),
            'cta',
            'normal',
            'high');
    }

    public function render_visibility_box($post)
    {
        $visibility = get_post_meta($post->ID, '_rock_convert_visibility',
            true);

        $urls = get_post_meta($post->ID, '_rock_convert_excluded_urls', true);

        if (empty($visibility)) {
            $visibility = "all";
        }

        ?>
        <p></p>
        <p>
        <div>
            <input type="radio" class="rock-convert-visibility-control"
                   id="rock_convert_visibility_all"
                   name="rock_convert_visibility" <?php echo esc_attr($visibility)
                                                             == "all"
                ? "checked"
                : ""; ?> value="all"/>
            <label for="rock_convert_visibility_all"><?php echo __("Exibir em todas as páginas das categorias selecionadas", "rock-convert"); ?><br/>
                <small style="padding-left: 24px;"><?php echo __("O banner será exibido em todos os posts que estejam nas categorias selecionadas ao lado.", "rock-convert"); ?>
                </small>
            </label>

        </div>
        <br/>
        <div>
            <input type="radio" class="rock-convert-visibility-control"
                   id="rock_convert_visibility_exclude"
                   name="rock_convert_visibility" <?php echo esc_attr($visibility)
                                                             == "exclude"
                ? "checked" : ""; ?> value="exclude"/>
            <label for="rock_convert_visibility_exclude"><?php echo __("Exibir em todas exceto:", "rock-convert"); ?> <br/>
                <small style="padding-left: 24px;"><?php echo __("Exibe em todos os posts que estejam nas categorias selecionadas ao lado, com exceção das páginas cadastradas abaixo.", "rock-convert"); ?></small>
            </label>
        </div>
        <div class="rock-convert-exclude-control" style="<?php if ($visibility
                                                                   == "all"
        ) {
            echo "display: none";
        } ?>">
            <div style="padding-top: 20px; clear: both;"
                 class="rock-convert-exclude-pages">
                <?php if ( ! empty($urls)) { ?>
                    <?php foreach ($urls as $url) { ?>
                        <div style="display: flex;"
                             class="rock-convert-exclude-pages-link">
                            <input type="text"
                                   name="rock_convert_exclude_pages[]"
                                   style="width: 65%;margin-right: 10px;"
                                   value="<?php echo $url ?>"
                                   placeholder="<?php echo __("Exemplo", "rock-convert"); ?>: <?php echo get_bloginfo('url') ?>/meu-post">
                            <input type="button"
                                   class="preview button rock-convert-exclude-pages-remove"
                                   value="x">
                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <div style="display: flex;"
                         class="rock-convert-exclude-pages-link">
                        <input type="text" name="rock_convert_exclude_pages[]"
                               style="width: 95%;margin-right: 10px;"
                               placeholder="<?php echo __("Exemplo", "rock-convert"); ?>: <?php echo get_bloginfo('url') ?>/meu-post">
                        <input type="button"
                               class="preview button rock-convert-exclude-pages-remove"
                               value="x">
                    </div>
                <?php } ?>
            </div>
            <input type="button"
                   class="preview button rock-convert-exclude-pages-add"
                   style="float: left;margin-top: 10px;"
                   value="+ <?php echo __("Adicionar página", "rock-convert"); ?>">

            <div class="clear"></div>
            <br><br>
        </div>

        </p>
        <?
    }

    public function render_shortcode_box($post)
    {
        ?>
        <p>
            <?php echo __('Copie e cole o código abaixo para exibir o banner em qualquer lugar do conteúdo.', 'rock-convert'); ?>
        </p>
        <p>
            <label for="shortcode">
                <strong><?php echo __("Shortcode", "rock-convert"); ?></strong> <br>
                <input type="text" id="shortcode" readonly
                       value='[rock-convert-cta id="<?php echo $post->ID; ?>"]'
                       style="background-color: #EEE;margin-top: 3px; margin-bottom: 5px;"
                       size="30">
                <br>
            </label>

        </p>
        <?php
    }

    public function render_metabox($post)
    {
        // Add an nonce field so we can check for it later.
        wp_nonce_field('rock_convert_inner_custom_box',
            'rock_convert_inner_custom_box_nonce');

        $custom_fields = get_post_custom($post->ID);

        $title    = Utils::getArrayValue($custom_fields, '_rock_convert_title', 0);
        $source   = Utils::getArrayValue($custom_fields, '_rock_convert_utm_source', 0);
        $position = Utils::getArrayValue($custom_fields, '_rock_convert_position', 0);

        /**
         * Support for version 1.0 that medium field was editable
         */
        $campaign = Utils::getArrayValue($custom_fields, '_rock_convert_utm_campaign', 0);
        $medium   = Utils::getArrayValue($custom_fields, '_rock_convert_utm_medium', 0);

        if (empty($campaign) && ! empty($medium)) {
            $campaign = $medium;
        }

        if (empty($position)) {
            $position = "bottom";
        }

        // Display the form, using the current value.
        ?>
        <p>
            <label for="rock_convert_title">
                <strong><?php _e('Link', 'rock-convert'); ?></strong>
            </label><br>
            <input type="text" id="rock_convert_title" name="rock_convert_title"
                   value="<?php echo esc_attr($title); ?>" size="56"
                   style=""/>
            <br>
            <em><strong>Dica:</strong> <?php echo __("Não utilize parâmetros do tipo UTM neste campo.", "rock-convert"); ?></em>
        </p>

        <div class="rconvert_announcement_position_preview">

            <p>
                <label for="rock_convert_utm_source">
                    <strong><?php _e('UTM Source', 'rock-convert'); ?></strong>
                </label><br>
                <input type="text" id="rock_convert_utm_source"
                       name="rock_convert_utm_source"
                       value="<?php echo esc_attr($source); ?>" size="25"
                       style=""/><br>
                <em><?php echo __('Ex: google, newsletter', 'rock-convert'); ?></em>
            </p>
        </div>

        <div class="rconvert_announcement_position_preview">
            <p>
                <label for="rock_convert_utm_medium">
                    <strong><?php _e('UTM Campaign', 'rock-convert'); ?></strong>
                </label>
                <br>
                <input type="text" id="rock_convert_utm_campaign"
                       name="rock_convert_utm_campaign"
                       value="<?php echo esc_attr($campaign); ?>" size="25"
                       style=""/><br>
                <em><?php echo __('Ex: ebook_de_natal', 'rock-convert'); ?></em>
            </p>
        </div>
        <div class="clearfix" style="display: block;clear: both;"></div>

        <p>
            <label>
                <strong><?php echo __("Posição do CTA", "rock-convert"); ?></strong>
            </label>
        </p>

        <p>

        <div class="rconvert_announcement_position_preview">
            <label for="rock_convert_position_top">
                <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/banner-top.png"
                     class="rconvert_announcement-preview-img"/>
                <input type="radio" id="rock_convert_position_top"
                       name="rock_convert_position" <?php echo esc_attr($position)
                                                               == "top" ? "checked"
                    : ""; ?> value="top"/>
                <?php echo __("Acima do conteúdo", "rock-convert"); ?></label>
        </div>

        <div class="rconvert_announcement_position_preview">

            <label for="rock_convert_position_middle">
                <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/banner-middle.png"
                     class="rconvert_announcement-preview-img"/>
                <input type="radio" id="rock_convert_position_middle"
                       name="rock_convert_position" <?php echo esc_attr($position)
                                                               == "middle"
                    ? "checked" : ""; ?> value="middle"/>
                <?php echo __("No meio do conteúdo", "rock-convert"); ?></label>
        </div>

        <div class="rconvert_announcement_position_preview">

            <label for="rock_convert_position_bottom">
                <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/banner-bottom.png"
                     class="rconvert_announcement-preview-img"/>
                <input type="radio" id="rock_convert_position_bottom"
                       name="rock_convert_position" <?php echo esc_attr($position)
                                                               == "bottom"
                    ? "checked" : ""; ?> value="bottom"/>
                <?php echo __("Abaixo do conteúdo", "rock-convert"); ?></label>
        </div>

        <br>

        <div class="clearfix" style="display: block;clear: both;"></div>

        </p>

        <?php //
    }

    public function save_metabox($post_id, $post)
    {
        // Check if nonce is set.
        $nonce = Utils::getArrayValue($_POST, 'rock_convert_inner_custom_box_nonce');

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce($nonce, 'rock_convert_inner_custom_box')) {
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
        $title      = sanitize_text_field(Utils::getArrayValue($_POST, 'rock_convert_title'));
        $source     = sanitize_text_field(Utils::getArrayValue($_POST, 'rock_convert_utm_source'));
        $campaign   = sanitize_text_field(Utils::getArrayValue($_POST, 'rock_convert_utm_campaign'));
        $position   = sanitize_text_field(Utils::getArrayValue($_POST, 'rock_convert_position'));
        $visibility = sanitize_text_field(Utils::getArrayValue($_POST, 'rock_convert_visibility'));

        $urls = $_POST['rock_convert_exclude_pages'];

        // Update the meta field.
        update_post_meta($post_id, '_rock_convert_title', $title);
        update_post_meta($post_id, '_rock_convert_utm_source', $source);
        update_post_meta($post_id, '_rock_convert_utm_campaign', $campaign);
        update_post_meta($post_id, '_rock_convert_position', $position);
        update_post_meta($post_id, '_rock_convert_visibility', $visibility);

        if ( ! empty($urls)) {
            update_post_meta($post_id, '_rock_convert_excluded_urls', $urls);
        }

        // Update image field
        $image = array_map('intval', Utils::getArrayValue($_POST, 'rock-convert-media')); //sanitize
        foreach ($image as $k => $v) {
            update_post_meta($post_id, $k, $v); //save
        }
    }

    public function rock_convert_media_upload()
    {
        wp_enqueue_media();
        wp_enqueue_script('meta-box-media',
            plugins_url('../js/media.js', __FILE__), array('jquery'));
        wp_nonce_field('nonce_action', 'nonce_name');
        // one or more
        $field_names = array('_rock_convert_image_media');
        foreach ($field_names as $name) {
            $value = $rawvalue = get_post_meta(get_the_id(), $name, true);
            $name  = esc_attr($name);
            $value = esc_attr($value);
            echo "<input type='hidden' id='$name-value'  class='small-text'       name='rock-convert-media[$name]' value='$value' />";
            echo "<input type='button' id='$name'        class='button button-primary rock-convert-upload-button' value='Selecionar imagem' />";
            echo "<input type='button' id='$name-remove' class='button rock-convert-upload-button-remove' value='Remover' />";
            $image = ! $rawvalue
                ? ''
                : wp_get_attachment_image($rawvalue, 'full', false,
                    array('style' => 'max-width:100%;height:auto;'));
            echo "<div class='rock-convert-image-preview'>$image</div>";
            echo '<br />';
        }
    }
}
