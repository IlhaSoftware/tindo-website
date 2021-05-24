<?php

namespace Rock_Convert\inc\libraries;

class MailChimp extends MailChimp_Core
{
    public function __construct($api_key)
    {
        parent::__construct($api_key);
    }

    /**
     * Get lists from account
     *
     * @return array
     */
    public function getLists()
    {
        if ( ! empty($this->api_key)) {
            $result = $this->get('lists');

            return $result["lists"];
        }

        return array();
    }


    /**
     * Subscribe a user to a list
     *
     * @param $email
     * @param $list
     *
     * @return array|false
     */
    public function newLead($email, $list)
    {
        return $this->post("lists/$list/members", array(
            "email_address" => $email,
            "status"        => "subscribed"
        ));
    }
}
