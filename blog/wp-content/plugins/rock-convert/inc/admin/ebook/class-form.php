<?php

namespace Rock_Convert\inc\admin\ebook;

use Rock_Convert\Inc\Admin\Subscriber;

/**
 * Class Form
 *
 * @package Rock_Convert\inc\admin\ebook
 */
class Form
{
    /**
     * Callback from download form;
     *
     * Here if the email and post_id are valid it will:
     *  * Store email in database
     *  * Send to RD_Station if is integrated
     *  * Send to Hubspot if is integrated
     *  * Redirect to the PDF generated when post is saved
     *
     * @since 2.0.0
     */
    public function download_form_callback()
    {
        if (isset($_POST['convert_add_meta_nonce'])
            && wp_verify_nonce($_POST['convert_add_meta_nonce'],
                'convert_add_subscriber_form_nonce')
        ) {
            $post      = get_post(sanitize_key($_POST['convert_post_id']));
            $email     = sanitize_email($_POST['convert_email']);
            $permalink = get_the_permalink($post->ID);

            $subscriber = new Subscriber($email, $post->ID, $permalink);

            if ($subscriber->subscribe("rock-convert-pdf")) {

                $attatchment_path = $this->get_attatchment_path($post->ID);

                if ( ! empty($attatchment_path)) {
                    $this->redirect($attatchment_path);
                } else {
                    /**
                     * If something goes wrong, redirect back to the post
                     */
                    $this->redirect($permalink);
                }
            } else {
                /**
                 * Email invalid
                 */
                $error = "error=email-invalid";

                if (strpos($permalink, '?') !== false) {
                    $permalink .= '&' . $error;
                } else {
                    $permalink .= '?' . $error;
                }

                $this->redirect($permalink);
            }
        }
    }

    /**
     * Get post attatchment with PDF
     *
     * @param $post_id
     *
     * @since 2.0.0
     * @return false|string
     */
    public function get_attatchment_path($post_id)
    {
        $attatchment_id = get_post_meta($post_id,
            '_rock_convert_ebook_attatchment_id', true);

        return wp_get_attachment_url($attatchment_id);
    }

    /**
     * Redirect
     *
     * @since    2.0.0
     */
    public function redirect($path)
    {
        wp_safe_redirect(esc_url($path));
    }
}
