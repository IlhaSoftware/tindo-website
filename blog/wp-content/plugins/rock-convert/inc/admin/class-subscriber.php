<?php

namespace Rock_Convert\Inc\Admin;

use Rock_Convert\inc\core\Table_Structure;
use Rock_Convert\inc\libraries\Hubspot;
use Rock_Convert\inc\libraries\MailChimp;
use Rock_Convert\inc\libraries\RD_Station;

class Subscriber
{
    /**
     * @var null
     */
    public $email = null;
    /**
     * @var null
     */
    public $post_id = null;

    /**
     * @var null
     */
    public $url = null;

    /**
     * Subscriber constructor.
     */
    public function __construct($email, $post_id = 0, $url = null)
    {
        $this->email   = $email;
        $this->post_id = $post_id;
        $this->url     = $url;
    }

    /**
     * @return bool
     */
    public function subscribe($source = null)
    {
        if (is_email($this->email)) {
            $this->store_email($this->post_id, $this->email);
            $this->send_to_hubspot($this->email, $this->url);
            $this->send_to_rd_station($this->email, $source);
            $this->send_to_mailchimp($this->email);

            return true;
        } else {
            /**
             * Email is invalid
             */
            return false;
        }
    }

    /**
     * Store subscriber on rock convert subscriber table
     *
     * @param $post_id
     * @param $email
     *
     * @since 2.0.0
     */
    private function store_email($post_id, $email)
    {
        $subscriptions = new Table_Structure();

        if ( ! isset($this->url)) {
            $this->url = get_permalink($post_id);
        }

        $data = array(
            'post_id'    => $post_id,
            'email'      => $email,
            'url'        => $this->url,
            'created_at' => date('Y-m-d H:i:s')
        );

        $subscriptions->insert($data);
    }

    /**
     * Send a lead to Hubspot
     *
     * @param $email
     *
     * @example $this->send_to_hubspot('foo@example.com');
     */
    private function send_to_hubspot($email, $url)
    {
        $form = get_option('_rock_convert_hubspot_form_url');

        if ( ! empty($form) && Utils::unlocked()) {
            try {
                $hubspot = new Hubspot($form, $url);
                $result  = $hubspot->newLead($email);

                if ($result["response"]["code"] != 200) {
                    Utils::logError("[Hubspot] - Form: " . $form . " | " . $result["response"]["message"]);
                }
            } catch (\Exception $e) {
                Utils::logError("[Hubspot] - Form: " . $form . " | " . $e);
            }
        }
    }

    /**
     * Send lead to RD Station
     *
     * For this to work, option _rock_convert_rd_public_token should be present
     *
     * @param $email
     * @param $identifier
     *
     * @see   https://github.com/agendor/rdstation-php-client/blob/master/RDStationAPI.class.php
     * @since 2.0.0
     */
    private function send_to_rd_station($email, $identifier)
    {
        $token = get_option('_rock_convert_rd_public_token');

        if ( ! empty($token) && Utils::unlocked()) {
            try {

                $rd_station_api = new RD_Station($token);
                $result         = $rd_station_api->newLead($email, array("identificador" => $identifier));

                if ($result["response"]["code"] != 200) {
                    Utils::logError("[RD Station] - Token: " . $token . " | Message: " . $result["response"]["message"]);
                }
            } catch (\Exception $e) {
                Utils::logError("[RD Station] - Token: " . $token . " | Message: " . $e);
            }
        }
    }

    /**
     * Send lead to Mailchimp
     *
     * For this to work, option _rock_convert_mailchimp_token and _rock_convert_mailchimp_list should be present
     *
     * @param $email
     *
     * @see https://github.com/drewm/mailchimp-api
     * @since 2.2.0
     */
    private function send_to_mailchimp($email)
    {
        $token = get_option('_rock_convert_mailchimp_token');
        $list  = get_option('_rock_convert_mailchimp_list');

        if ( ! empty($token) && ! empty($list)) {

            try {
                $mailchimp = new MailChimp($token);
                $result    = $mailchimp->newLead($email, $list);

                if ($result["status"] != "subscribed") {
                    Utils::logError("[Mailchimp] - Token:  " . $token . " | List: " . $list . " | Message: " . $result["detail"]);
                }
            } catch (\Exception $e) {
                Utils::logError($e);
            }
        }
    }
}
