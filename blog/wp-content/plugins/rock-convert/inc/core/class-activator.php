<?php

namespace Rock_Convert\Inc\Core;

use Rock_Convert\Inc\Admin\Utils;
use Rock_Convert\inc\libraries\Wp_License_Manager;

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */
class Activator
{
    /**
     * Short Description.
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {

        $min_php = '5.6.0';

        // Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
        if (version_compare(PHP_VERSION, $min_php, '<')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die('This plugin requires a minmum PHP Version of ' . $min_php);
        }

        self::build_table_structure();

        add_option('rock_convert_getting_started', true);

        if ( ! wp_next_scheduled('rock_convert_license_check_event')) {
            wp_schedule_event(time(), 'daily', 'rock_convert_license_check_event');
        }
    }

    /**
     * Call table structure script to check if is necessary to
     * create the plugin tables
     *
     * @since 2.0.0
     */
    public static function build_table_structure()
    {
        $structure = new Table_Structure();

        if ( ! $structure->isInstalled()) {
            $structure->install();
        } else {
            if ($structure->isOutdated()) {
                $structure->migrate();
            }
        }
    }

    public static function check_license()
    {
        if (Utils::unlocked()) {
            $licenseKey = get_option('_rock_convert_license_key');
            Wp_License_Manager::check_license($licenseKey);
        }
    }

    public function table_structure_db_check()
    {
        self::build_table_structure();
    }

}
