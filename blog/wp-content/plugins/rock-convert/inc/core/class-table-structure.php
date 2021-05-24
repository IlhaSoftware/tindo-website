<?php

namespace Rock_Convert\inc\core;


class Table_Structure
{
    /**
     * Current database structure version
     *
     * @var string
     */
    public $db_version = "1.1";

    /**
     * @var
     */
    public $current_version;

    /**
     * Table name on DB
     *
     * @var string
     */
    public $table_name;

    /**
     * @var
     */
    public $db;

    /**
     * Table_Structure constructor.
     */
    public function __construct()
    {
        global $wpdb;

        $this->current_version = get_option('rock_convert_db_version');
        $this->db              = $wpdb;
        $this->table_name      = $wpdb->prefix . "rconvert-subscriptions";
    }

    /**
     * Check if table is already created
     *
     * @return bool
     */
    public function isInstalled()
    {
        return $this->current_version;
    }

    /**
     * @return bool
     */
    public function isOutdated()
    {
        return $this->current_version != $this->db_version;
    }

    /**
     *
     */
    public function install()
    {
        $charset_collate = $this->db->get_charset_collate();

        $sql
            = "CREATE TABLE IF NOT EXISTS `$this->table_name` (
                    id bigint(9) NOT NULL AUTO_INCREMENT,
                    email varchar(100) DEFAULT '' NOT NULL,
                    post_id bigint(20) unsigned NOT NULL DEFAULT '0',
                    url varchar(255) NULL DEFAULT NULL,
                    created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		            PRIMARY KEY  (id)
		        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option("rock_convert_db_version", $this->db_version);
    }

    /**
     * Update DB Schema based on current version difference
     */
    public function migrate()
    {
        switch ($this->current_version) {
            case "1.0";
                $sql = $this->sql_update_1_0();
                break;
        }

        if (isset($sql)) {
            global $wpdb;
            $wpdb->query($sql);

            update_option("rock_convert_db_version", $this->db_version);
        } else {
            // TODO: Something is definitely wrong
        }
    }

    /**
     * SQL Update statement for version 1.0
     *
     * @return string
     */
    public function sql_update_1_0()
    {
        return "ALTER TABLE `$this->table_name` ADD `url` VARCHAR(255) NULL DEFAULT NULL AFTER `post_id`;";
    }

    /**
     * Insert data in subscriptions table
     *
     * @param $data
     */
    public function insert($data)
    {
        $this->db->insert($this->table_name, $data);
    }
}