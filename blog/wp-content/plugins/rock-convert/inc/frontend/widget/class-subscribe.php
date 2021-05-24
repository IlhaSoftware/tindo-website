<?php

namespace Rock_Convert\Inc\Frontend\Widget;

use Rock_Convert\Inc\Admin\Utils;

class Subscribe extends \WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'rock_convert_subscribe_widget',
            __('Caixa de captura | Rock Convert', 'rock-convert'),
            array('description' => __('Colete e-mails dos leitores no site', 'rock-convert'))
        );
    }

    public function widget($args, $instance)
    {
        global $wp;

        $title         = apply_filters('widget_title', $instance['title']);
        $hint          = apply_filters('widget_title', $instance['hint']);
        $submit        = apply_filters('widget_title', $instance['submit']);
        $redirect_page = apply_filters('widget_title', $instance['redirect_page']);

        $rock_convert_subscribe_once
            = wp_create_nonce('rock_convert_subscriber_nonce');

        $current_url = home_url(add_query_arg(array($_GET), $wp->request));

        echo $args['before_widget'];
        ?>

        <?php if ($this->isError()) { ?>
        <div class="rock-convert-alert-error" id="rock-convert-alert-box" role="alert">
            <?php echo __("<strong>Ops!</strong><br/> Favor informar um e-mail válido!", "rock-convert"); ?>
        </div>
    <?php } ?>

        <?php if ($this->isSuccess()) { ?>
        <div class="rock-convert-alert-success" id="rock-convert-alert-box" role="alert">
            <?php echo __("<strong>Pronto!</strong><br/> E-mail cadastrado com sucesso.", "rock-convert"); ?>
        </div>
    <?php } ?>

        <div class="rock-convert-subscribe-form">
            <h5 class="rock-convert-subscribe-form-title"><?php echo $title ?></h5>
            <br>

            <form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="post">
                <input type="hidden" name="rock_convert_subscribe_nonce"
                       value="<?php echo $rock_convert_subscribe_once ?>"/>
                <input type="hidden" name="action" value="rock_convert_subscribe_form">
                <input type="hidden" name="rock_convert_subscribe_page" value="<?php echo $current_url ?>">
                <input type="hidden" name="rock_convert_subscribe_redirect_page" value="<?php echo $redirect_page ?>">
                <input type="email" name="rock_convert_subscribe_email" required
                       class="rock-convert-subscribe-form-email"
                       placeholder="E-mail">
                <input type="submit" class="rock-convert-subscribe-form-btn"
                       value="<?php echo $submit; ?>">
                <span class="rock-convert-subscribe-form-hint"><?php echo $hint ?></span>
            </form>
        </div>
        <?php echo Utils::powered_by_link(true, "Widget_Powered_by_link"); ?>


        <?php
        echo $args['after_widget'];
    }

    public function isError()
    {
        return isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] == "error=rc-subscribe-email-invalid";
    }

    public function isSuccess()
    {
        return isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] == "success=rc-subscribed";
    }

    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __("Receba nossos conteúdos gratuitamente!", "rock-convert");
        }
        if (isset($instance['submit'])) {
            $submit = $instance['submit'];
        } else {
            $submit = __("Receber conteúdo", "rock-convert");
        }
        if (isset($instance['hint'])) {
            $hint = $instance['hint'];
        } else {
            $hint = __("Não te mandaremos spam!", "rock-convert");
        }

        if (isset($instance['redirect_page'])) {
            $redirect_page = $instance['redirect_page'];
        } else {
            $redirect_page = "rc-no-redirect";
        }

        $site_pages = get_pages();

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Título',
                    'rock-convert'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('hint'); ?>"><?php echo __("Texto de ajuda",
                    "rock-convert"); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('hint'); ?>"
                   name="<?php echo $this->get_field_name('hint'); ?>"
                   type="text" value="<?php echo esc_attr($hint); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('submit'); ?>"><?php echo __("Texto do botão",
                    "rock-convert"); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('submit'); ?>"
                   name="<?php echo $this->get_field_name('submit'); ?>"
                   type="text" value="<?php echo esc_attr($submit); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('redirect_page'); ?>"><?php echo __("Redirecionar para",
                    "rock-convert"); ?></label>
            <select name="<?php echo $this->get_field_name('redirect_page'); ?>"
                    id="<?php echo $this->get_field_id('redirect_page'); ?>"
                    class="widefat">
                <option value="rc-no-redirect" <?php echo $redirect_page == "rc-no-redirect" ? "selected" : null; ?>>
                    -- <?php echo __("Continuar na mesma página", "rock-convert"); ?> --
                </option>

                <?php foreach ($site_pages as $obj) { ?>
                    <option value="<?php echo $obj->ID; ?>" <?php echo $redirect_page == $obj->ID ? "selected" : null; ?>>
                        <?php echo $obj->post_title; ?>
                    </option>
                <?php } ?>
            </select>
            <small><?php __("Selecione para onde o usuário será redirecionado ao cadastrar o email.",
                    "rock-convert"); ?></small>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance                  = array();
        $instance['title']         = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['hint']          = ( ! empty($new_instance['hint'])) ? strip_tags($new_instance['hint']) : '';
        $instance['submit']        = ( ! empty($new_instance['submit'])) ? strip_tags($new_instance['submit']) : '';
        $instance['redirect_page'] = ( ! empty($new_instance['redirect_page'])) ? strip_tags($new_instance['redirect_page']) : '';

        return $instance;
    }
}
