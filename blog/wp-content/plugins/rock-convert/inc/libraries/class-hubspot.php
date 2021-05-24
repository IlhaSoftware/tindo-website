<?php

namespace Rock_Convert\inc\libraries;

/**
 * Class Hubspot
 *
 * This class sends a lead to Hubspot through a form
 *
 * @see     https://knowledge.hubspot.com/forms-user-guide-v2/how-to-create-a-form
 * @since   2.0.0
 * @package Rock_Convert\inc\libraries
 */
class Hubspot
{
    /**
     * Hubspot form url
     *
     * @since 2.0.0
     *
     * @var null
     */
    public $form_url;

    /**
     * @var
     */
    public $page_url;

    /**
     * Hubspot constructor.
     *
     * @param string $form_url
     */
    public function __construct($form_url, $page_url = null)
    {
        $this->form_url = $form_url;
        $this->page_url = $page_url;
    }

    /**
     * @param string $email
     * @param array $custom_context
     * @param string $life_cycle
     *
     * @return array|\WP_Error
     */
    public function newLead(
        $email,
        $custom_context = array(),
        $life_cycle = "subscriber"
    ) {

        $context = $this->build_context($custom_context);

        return wp_remote_post($this->form_url, array(
            'headers' => array('Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'),
            'body'    => $this->get_post_body($email, $life_cycle, $context),
            'method'  => 'POST'
        ));
    }

    /**
     * @param array $custom_context
     *
     * @return string
     */
    private function build_context($custom_context = array())
    {
        /**
         * Visitors cookie
         */
        $hubspotutk = $_COOKIE['hubspotutk'];

        /**
         * Current visitor IP address
         */
        $ip_addr = $_SERVER['REMOTE_ADDR'];

        /**
         * Merge custom context with both cookie and IP Address
         */
        $hs_context = array(
            'hutk'      => $hubspotutk,
            'ipAddress' => $ip_addr,
            "pageUrl"   => $this->page_url
        );

        $context = array_merge($hs_context, $custom_context);

        return json_encode($context);
    }

    /**
     * @param string $email
     * @param string $life_cycle
     * @param string $context
     *
     * @return string
     */
    private function get_post_body($email, $life_cycle, $context)
    {
        return "email=" . urlencode($email)
               . "&lifecyclestage=" . $life_cycle
               . "&hs_context=" . urlencode($context);
    }
}
