<?php

namespace Rock_Convert\inc\frontend;

use Rock_Convert\Inc\Admin\Utils;

class Download
{
    /**
     * The post ID
     *
     * @var
     */
    private $post_id;

    /**
     * The post attatchment ID
     *
     * @var
     */
    private $attatchment_id;

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->register();
    }

    public function register()
    {
        add_shortcode('rock-convert-pdf',
            array($this, 'render_from_shortcode'));
    }

    /**
     * @param $atts
     *
     * @return string
     */
    public function render_from_shortcode($atts)
    {
        $this->post_id = $atts["id"];

        if ($this->download_enabled()) {
            return $this->the_form();
        }
    }

    /**
     * Check if download is enabled by:
     *
     * Havin _rock_convert_enable_ebook with true
     * and by having an attachment_id in _rock_convert_ebook_attatchment_id meta
     *
     * @return bool
     */
    public function download_enabled()
    {
        $enable_download = get_post_meta($this->post_id,
            '_rock_convert_enable_ebook',
            true);


        if ( ! filter_var($enable_download, FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }

        $this->attatchment_id = get_post_meta($this->post_id,
            '_rock_convert_ebook_attatchment_id', true);

        return ! empty($this->attatchment_id);
    }

    /**
     * Render the download form
     */
    public function the_form()
    {
        // Generate a custom nonce value.
        $convert_add_meta_nonce
            = wp_create_nonce('convert_add_subscriber_form_nonce');

        $html
            = '
        <div class="rock-convert-download">
        
        <div class="rock-convert-download-container">
            <p class="rock-convert-download-container-title">'
              . $this->form_title() . '</p>
            <form target="_blank" action="' . $this->form_action() . '" method="post" class="rock-convert-download-container-form">
                <input type="hidden" name="action" value="rock_convert_download_form">
                <input type="hidden" name="convert_post_id" value="'
              . $this->post_id . '">
                <input type="hidden" name="convert_add_meta_nonce" value="'
              . $convert_add_meta_nonce . '" />
                <input type="email" required name="convert_email" placeholder="'
              . $this->email_placeholder() . '"
                       class="rock-convert-download-container-form-email">
                <input type="submit" value="' . $this->button_text() . '"
                       class="rock-convert-download-container-form-btn" />
                <span class="rock-convert-download-container-form-help">'
              . $this->help_text() . '</span>
            </form>
            
        </div> 
        ' . Utils::powered_by_link() . '   
        </div>';

        return $html;
    }

    public function form_title()
    {
        return __("Faça o download deste post inserindo seu e-mail abaixo", "rock-convert");
    }

    public function form_action()
    {
        return esc_url(admin_url('admin-post.php'));
    }

    public function email_placeholder()
    {
        return __("Informe seu e-mail aqui", "rock-convert");
    }

    public function button_text()
    {
        return __("Fazer Download", "rock-convert");
    }

    public function help_text()
    {
        return __("Não se preocupe, não fazemos spam.", "rock-convert");
    }
}
