<?php

namespace Rock_Convert\inc\libraries;

/**
 * Class RD_Station
 *
 * @package Rock_Convert\inc\libraries
 * @since   2.0.0
 */
class RD_Station
{
    /**
     * Public API token for RD Station
     *
     * @since 2.0.0
     *
     * @var null
     */
    public $token;

    /**
     * Base API URL
     *
     * @since 2.0.0
     *
     * @var string
     */
    public $baseURL = "https://www.rdstation.com.br/api/";

    /**
     * Default identifier for a new lead
     *
     * @since 2.0.0
     *
     * @var string
     */
    public $defaultIdentifier = "rock-convert";

    /**
     * RD_Station constructor.
     *
     * @since 2.0.0
     *
     * @param $token
     */
    public function __construct($token = null)
    {
        $this->token = $token;
    }

    /**
     * Send a new lead to RD Station
     *
     * @param string $email
     * @param array  $data
     *
     * @since 2.0.0
     *
     * @return array|\WP_Error
     * @throws \Exception
     */
    public function newLead($email, $data = array())
    {
        if (empty($this->token)) {
            throw new \Exception("Token is required to connect with RD Station");
        }

        if (empty($email)) {
            throw new \Exception("Email is required to send a lead to RD Station");
        }

        if (empty($data['identificador'])) {
            $data['identificador'] = $this->defaultIdentifier;
        }
        if (empty($data["client_id"]) && ! empty($_COOKIE["rdtrk"])) {
            $data["client_id"] = json_decode($_COOKIE["rdtrk"])->{'id'};
        }
        if (empty($data["traffic_source"]) && ! empty($_COOKIE["__trf_src"])) {
            $data["traffic_source"] = $_COOKIE["__trf_src"];
        }

        $data['email'] = $email;

        return $this->request($this->getURL(), $data);
    }

    /**
     * Make a POST application/json request to an endpoint
     *
     * @param string $url
     * @param array  $data
     *
     * @return array|\WP_Error
     */
    private function request($url, $data = array())
    {
        $data['token_rdstation'] = $this->token;

        return wp_remote_post($url, array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'    => json_encode($data),
            'method'  => 'POST'
        ));
    }

    /**
     * Get URL for registering a new lead on RD Station
     *
     * @param string $apiVersion
     *
     * @since 2.0.0
     * @see   https://www.rdstation.com.br/api/1.3/conversions
     * @return string
     */
    protected function getURL($apiVersion = '1.3')
    {
        return $this->baseURL . $apiVersion . "/conversions";
    }
}
