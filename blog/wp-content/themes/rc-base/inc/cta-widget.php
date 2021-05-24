<?php

function rock_load_cta_widget()
{
    register_widget('rock_cta_widget');
}

add_action('widgets_init', 'rock_load_cta_widget');

// Creating the widget
class rock_cta_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(

        // Base ID of your widget
            'rock_cta_widget',

            // Widget name will appear in UI
            __('Call to Action | Rock Content', 'rockcontent'),

            // Widget description
            array('description' => __('Adiciona um CTA na sidebar', 'rockcontent'),)
        );
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        $link  = esc_url_raw($instance['link']);

        echo $args['before_widget'];
        ?>
        <div class="rock-widget-cta">
            <a href="<?php echo $link; ?>">
                <?php echo $title; ?>
            </a>
        </div>
        <?php
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = 'Conheça nossos produtos';
        }

        if (isset($instance['link'])) {
            $link = $instance['link'];
        } else {
            $link = '';
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Titulo:',
                    'rockcontent'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php echo __('Link do CTA:',
                    'rockcontent'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>"
                   name="<?php echo $this->get_field_name('link'); ?>"
                   type="text" value="<?php echo esc_attr($link); ?>"/>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance          = array();
        $instance['link']  = ( ! empty($new_instance['link'])) ? strip_tags($new_instance['link']) : '';
        $instance['title'] = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }
}
