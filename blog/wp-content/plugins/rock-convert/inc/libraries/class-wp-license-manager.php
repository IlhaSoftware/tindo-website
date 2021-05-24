<?php

namespace Rock_Convert\inc\libraries;

use Rock_Convert\Inc\Admin\Utils;

if ( ! class_exists('Wp_License_Manager')) {

    /**
     * Class Wp_License_Manager
     * @package Rock_Convert\inc\libraries
     */
    class Wp_License_Manager
    {

        public static function activate($license_key)
        {
            // API query parameters
            $api_params = array(
                'slm_action'        => 'slm_activate',
                'secret_key'        => ROCK_CONVERT_SECRET_KEY,
                'license_key'       => $license_key,
                'registered_domain' => $_SERVER['SERVER_NAME'],
                'item_reference'    => urlencode(ROCK_CONVERT_ITEM_REFERENCE),
            );

            // Send query to the license manager server
            $query    = esc_url_raw(add_query_arg($api_params, ROCK_CONVERT_LICENSE_SERVER_URL));
            $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

            // Check for error in the response
            if (is_wp_error($response)) {
                echo "Unexpected Error! The query returned with an error.";
            }
            //var_dump($response);//uncomment it if you want to look at the full response

            // License data.
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if ($license_data->result == 'success') {//Success was returned for the license activation
                update_option('_rock_convert_license_activated', true);
                update_option('_rock_convert_license_key', $license_key);
            } else {
                update_option('_rock_convert_license_activated', false);
                update_option('_rock_convert_license_error', __('Licença inválida', 'rock-convert'));
            }
        }

        public static function deactivate($license_key)
        {
            // API query parameters
            $api_params = array(
                'slm_action'        => 'slm_deactivate',
                'secret_key'        => ROCK_CONVERT_SECRET_KEY,
                'license_key'       => $license_key,
                'registered_domain' => $_SERVER['SERVER_NAME'],
                'item_reference'    => urlencode(ROCK_CONVERT_ITEM_REFERENCE),
            );

            // Send query to the license manager server
            $query    = esc_url_raw(add_query_arg($api_params, ROCK_CONVERT_LICENSE_SERVER_URL));
            $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

            // Check for error in the response
            if (is_wp_error($response)) {
                echo "Unexpected Error! The query returned with an error.";
            }

            //var_dump($response);//uncomment it if you want to look at the full response

            // License data.
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if ($license_data->result == 'success') {//Success was returned for the license activation
                update_option('_rock_convert_license_activated', false);
                update_option('_rock_convert_license_key', null);
            }
        }

        public static function check_license($license_key)
        {
            $status = array(
                "error" => null
            );

            if ( ! empty($license_key)) {

                $api_params = array(
                    'slm_action'  => 'slm_check',
                    'secret_key'  => ROCK_CONVERT_SECRET_KEY,
                    'license_key' => $license_key,
                );

                $response = wp_remote_get(add_query_arg($api_params, ROCK_CONVERT_LICENSE_SERVER_URL),
                    array('timeout' => 20, 'sslverify' => false));

                $result = json_decode($response['body']);

                if ($result->result == "success") {
                    if ($result->status == "blocked") {
                        update_option('_rock_convert_license_activated', false);
                        update_option('_rock_convert_license_error', __("Licença bloqueada", "rock-convert"));
                    }

                    if ($result->status == "expired") {
                        update_option('_rock_convert_license_activated', false);
                        update_option('_rock_convert_license_error', __("Licença expirada", "rock-convert"));
                    }

                    if ($result->status == "active" && ! Utils::unlocked()) {
                        update_option('_rock_convert_license_activated', true);
                        update_option('_rock_convert_license_key', $license_key);
                    }
                } elseif ($result->result == "error") {
                    update_option('_rock_convert_license_activated', false);
                    delete_option('_rock_convert_license_key');
                    update_option('_rock_convert_license_error', __($result->message, "rock-convert"));
                }
            }

        }
    }

}
