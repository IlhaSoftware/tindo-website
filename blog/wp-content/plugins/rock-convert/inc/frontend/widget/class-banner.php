<?php

namespace Rock_Convert\Inc\Frontend\Widget;

class Banner extends \WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'rock_cta_widget',
            __('Banner customizável | Rock Convert', 'rock-convert'),
            array('description' => __('Adiciona um CTA na sidebar', 'rock-convert'),)
        );
    }

    public function widget($args, $instance)
    {
        $title      = apply_filters('widget_title', $instance['title']);
        $link       = esc_url_raw($instance['link']);
        $bg_color   = ( ! empty($instance['bg_color'])) ? $instance['bg_color'] : '#333333';
        $link_color = ( ! empty($instance['link_color'])) ? $instance['link_color'] : '#ffffff';
        echo $args['before_widget'];
        ?>
        <style>
            .rock-convert-widget-cta a span::before, .rock-convert-widget-cta a span::after {
                background: <?php echo $link_color; ?>;
            }
        </style>
        <div class="rock-convert-widget-cta" style="background-color: <?php echo $bg_color; ?>">
            <a href="<?php echo $link; ?>" style="color: <?php echo $link_color; ?>">
                <span style="color: <?php echo $link_color; ?>"><?php echo $title; ?></span>
            </a>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $instance = wp_parse_args(
            $instance,
            array(
                'title'      => __('Conheça nossos produtos', 'rock-convert'),
                'link'       => '',
                'bg_color'   => '#333333',
                'link_color' => '#ffffff',
            )
        );

        $title      = esc_attr($instance['title']);
        $link       = esc_attr($instance['link']);
        $bg_color   = esc_attr($instance['bg_color']);
        $link_color = esc_attr($instance['link_color']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Título',
                    'rock-convert'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php echo __('Link do CTA',
                    'rock-convert'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>"
                   name="<?php echo $this->get_field_name('link'); ?>"
                   type="text" value="<?php echo esc_attr($link); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('bg_color'); ?>"><?php _e('Cor do fundo',
                    'rock-convert'); ?></label><br>
            <input type="text" name="<?php echo $this->get_field_name('bg_color'); ?>" class="color-picker"
                   id="<?php echo $this->get_field_id('bg_color'); ?>" value="<?php echo $bg_color; ?>"
                   data-default-color="#333333"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('link_color'); ?>"><?php _e('Cor do texto',
                    'rock-convert'); ?></label><br>
            <input type="text" name="<?php echo $this->get_field_name('link_color'); ?>" class="color-picker"
                   id="<?php echo $this->get_field_id('link_color'); ?>" value="<?php echo $link_color; ?>"
                   data-default-color="#fff"/>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance               = array();
        $instance['link']       = ( ! empty($new_instance['link'])) ? strip_tags($new_instance['link']) : '';
        $instance['title']      = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['bg_color']   = ( ! empty($new_instance['bg_color'])) ? strip_tags($new_instance['bg_color']) : '';
        $instance['link_color'] = ( ! empty($new_instance['link_color'])) ? strip_tags($new_instance['link_color']) : '';

        return $instance;
    }
}
