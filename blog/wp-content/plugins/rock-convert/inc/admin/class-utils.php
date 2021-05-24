<?php

namespace Rock_Convert\Inc\Admin;

class Utils
{

    /**
     * @param      $post_id
     * @param bool $enabled
     *
     * @return array
     */
    public static function get_post_analytics($post_id, $enabled = true)
    {
        $custom_fields = get_post_custom($post_id);

        if ($enabled) {
            $views  = intval(Utils::getArrayValue($custom_fields, '_rock_convert_cta_views', 0));
            $clicks = intval(Utils::getArrayValue($custom_fields, '_rock_convert_cta_clicks', 0));
            $ctr    = $views == 0 ? 0 : (($clicks / $views) * 100);
        } else {
            $views  = 100000;
            $clicks = 2000;
            $ctr    = 10.20;
        }

        return array(
            'views'  => $views,
            'clicks' => $clicks,
            'ctr'    => $ctr
        );
    }

    /**
     * Safelly get index from array
     *
     * @param $array
     * @param $index
     * @param null $subindex
     *
     * @return null
     */
    public static function getArrayValue($array, $index, $subindex = null, $default = null)
    {
        if (isset($subindex)) {
            return isset($array[$index]) && isset($array[$index][$subindex]) ?
                $array[$index][$subindex] : $default;
        }

        return isset($array[$index]) ?
            $array[$index] : $default;
    }

    /**
     * @param $num
     *
     * @return float|string
     */
    public static function thousandsCurrencyFormat($num)
    {

        if ($num > 1000) {

            $x               = round($num);
            $x_number_format = number_format($x);
            $x_array         = explode(',', $x_number_format);
            $x_parts         = array('k', 'm', 'b', 't');
            $x_count_parts   = count($x_array) - 1;
            $x_display       = $x;
            $x_display       = $x_array[0] . ((int)$x_array[1][0] !== 0 ? '.'
                                                                          . $x_array[1][0]
                    : '');
            $x_display       .= $x_parts[$x_count_parts - 1];

            return $x_display;

        }

        return $num;
    }

    /**
     * Powered by referer link
     *
     * @return string
     */
    public static function powered_by_link($nofollow = false, $campaign = "Powered_By_Link")
    {
        $url    = "https://convert.rockcontent.com/?";
        $domain = home_url();
        $params = array(
            "utm_medium"   => "referral",
            "utm_source"   => $domain,
            "utm_campaign" => $campaign,
        );
        $rel    = ($nofollow ? "rel='nofllow'" : null);

        if (Admin::hide_referral() && self::unlocked()) {
            return null;
        }

        return "<small class='powered-by-rock-convert'>Powered by <a href='"
               . $url . self::build_http_query($params)
               . "' " . $rel . " target='_blank'>Rock Convert</a></small>";
    }

    /**
     * @return bool
     */
    public static function unlocked()
    {
        $licenseKey       = get_option('_rock_convert_license_key');
        $licenseActivated = get_option('_rock_convert_license_activated');

        return ! empty($licenseKey) && boolval($licenseActivated) === true;
    }

    /**
     * Builds an http query string.
     *
     * @param array $query // of key value pairs to be used in the query
     *
     * @return string      // http query string.
     *
     * @since 2.1.2
     **/
    public static function build_http_query($query)
    {
        return http_build_query($query);
    }

    public static function read_backward_line($filename, $lines, $revers = false)
    {
        $offset = -1;
        $c      = '';
        $read   = '';
        $i      = 0;
        $fp     = @fopen($filename, "r");
        while ($lines && fseek($fp, $offset, SEEK_END) >= 0) {
            $c = fgetc($fp);
            if ($c == "\n" || $c == "\r") {
                $lines--;
                if ($revers) {
                    $read[$i] = strrev($read[$i]);
                    $i++;
                }
            }
            if ($revers) {
                $read[$i] .= $c;
            } else {
                $read .= $c;
            }
            $offset--;
        }
        fclose($fp);
        if ($revers) {
            if ($read[$i] == "\n" || $read[$i] == "\r") {
                array_pop($read);
            } else {
                $read[$i] = strrev($read[$i]);
            }

            return implode('', $read);
        }

        return strrev(rtrim($read, "\n\r"));
    }

    /**
     * @param $message
     */
    public static function logError($message)
    {
        $date  = date("Y-m-d h:m:s");
        $file  = plugin_dir_path(__FILE__) . 'logs' . DIRECTORY_SEPARATOR . 'debug.log';
        $level = "warning";

        $message = "[{$date}] [{$level}] " . $message . PHP_EOL;

        error_log($message, 3, $file);
    }
}
