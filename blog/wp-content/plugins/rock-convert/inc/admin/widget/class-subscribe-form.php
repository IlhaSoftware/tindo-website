<?php

namespace Rock_Convert\Inc\Admin\Widget;

use Rock_Convert\Inc\Admin\Subscriber;

class Subscribe_Form
{
    /**
     * Callback from subscribe form;
     *
     * Here if the email and post_id are valid it will:
     *  * Store email in database
     *  * Send to RD_Station if is integrated
     *  * Send to Hubspot if is integrated
     *  * Redirect to the page back
     *
     * @since 2.1.0
     */
    public function subscribe_form_callback()
    {
        if (isset($_POST['rock_convert_subscribe_nonce'])
            && wp_verify_nonce($_POST['rock_convert_subscribe_nonce'],
                'rock_convert_subscriber_nonce')
        ) {
            $url         = esc_url_raw($_POST['rock_convert_subscribe_page']);
            $email       = sanitize_email($_POST['rock_convert_subscribe_email']);
            $subscriber  = new Subscriber($email, 0, $url);
            $redirect_id = $_POST['rock_convert_subscribe_redirect_page'];

            $status = array("success" => "rc-subscribed#rock-convert-alert-box");

            if ( ! $subscriber->subscribe("rock-convert-newsletter")) {
                $status = array("error" => "rc-subscribe-email-invalid#rock-convert-alert-box");
            }

            if ( ! ! intval($redirect_id)) {
                $redirect_url = get_permalink(get_post($redirect_id));
                $this->redirect($redirect_url);
                exit;
            }

            $this->redirect(esc_url_raw(add_query_arg($status, $url)));
        }
    }

    /**
     * Redirect
     *
     * @since    2.0.0
     */
    public function redirect($path)
    {
        wp_safe_redirect(esc_url_raw($path));
    }
}
