<?php

namespace Rock_Convert\inc\admin;

/**
 * Class CSV
 *
 * Export any wordpress table in CSV format
 *
 * @since   2.0.0
 * @example $csv = new CSV('posts'); // Will export wp-posts table
 *
 * @package Rock_Convert\inc\admin
 */
class CSV
{
    /**
     * @var \wpdb
     */
    private $db;

    /**
     * @var
     */
    private $separator;

    /**
     * @var string
     */
    private $table;

    /**
     * CSV constructor.
     *
     * @param null   $table_name Table name without prefix
     * @param string $sep        CSV separator
     * @param string $filename   Name of the csv file
     *
     * @throws \Exception
     */
    public function __construct(
        $table_name = null,
        $sep = ",",
        $filename = "rconvert-contacts"
    ) {
        if ( ! isset($table_name)) {
            throw new \Exception("Table name argument is required");
        }

        global $wpdb;
        $this->db        = $wpdb;
        $this->separator = $sep;
        $this->table     = $this->db->prefix . $table_name;

        $generatedDate = date('d-m-Y His');

        $csvFile = $this->generate_csv();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . $filename . "-"
               . $generatedDate . ".csv\";");
        header("Content-Transfer-Encoding: binary");

        echo $csvFile;
        exit;
    }

    /**
     * Fetch results from table and return a string containing all the data
     *
     * @return string
     */
    public function generate_csv()
    {
        $csv_output = '';
        $csv_output .= $this->get_columns();
        $csv_output .= "\n";
        $csv_output .= $this->get_data();

        return $csv_output;
    }

    /**
     * Get table columns as a string separated by $this->separator;
     *
     * @return string
     */
    public function get_columns()
    {
        $output = "";
        $query  = "SHOW COLUMNS FROM `" . $this->table . "`";
        $result = $this->db->get_results($query);

        if (count($result) > 0) {
            foreach ($result as $row) {
                $output = $output . $row->Field . $this->separator;
            }
            $output = substr($output, 0, -1);
        }

        return $output;
    }

    /**
     * Get table data as a string separated by $this->separator
     *
     * @return string
     */
    public function get_data()
    {
        $output = "";
        $query  = "SELECT * FROM `" . $this->table . "`";
        $values = $this->db->get_results($query);

        foreach ($values as $rowr) {
            $fields = array_values((array)$rowr);
            $output .= implode($this->separator, $fields);
            $output .= "\n";
        }

        return $output;
    }
}
