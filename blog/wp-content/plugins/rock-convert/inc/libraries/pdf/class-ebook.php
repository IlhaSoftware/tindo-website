<?php

namespace Rock_Convert\Inc\Libraries\PDF;

use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Class PDF
 *
 * @package Rock_Convert\Inc|Libraries\PDF
 */
class Ebook
{
    /**
     * @var
     */
    public $attatchment_id;
    /**
     * @var
     */
    private $post_id;
    /**
     * @var
     */
    private $title;
    /**
     * @var
     */
    private $content;
    /**
     * @var
     */
    private $featured_image_url;
    /**
     * @var PDF_HTML
     */
    private $pdf;
    /**
     * @var
     */
    private $buffer;
    /**
     * @var
     */
    private $upload;

    /**
     * PDF constructor.
     */
    public function __construct(
        $post_id,
        $title,
        $content,
        $featured_image_url = null
    ) {
        $this->post_id            = $post_id;
        $this->title              = $title;
        $this->content            = $content;
        $this->featured_image_url = $featured_image_url;
        $this->pdf                = new Html2Pdf('P', 'A4', 'pt', true, 'UTF-8',
            array(0, 0, 0, 0));
    }

    public function generate()
    {
        $this->setup();
        $this->output();
        $this->create_file();
        $this->create_attatchment();
        $this->attach_ebook();
    }

    private function setup()
    {
        $this->pdf->pdf->SetTitle($this->title);
        $this->pdf->writeHTML($this->html_content());
    }

    /**
     * Build page structure in HTML
     *
     * @return string
     */
    private function html_content()
    {
        $html = "";
        $html .= '
        <style type="text/Css">
        <!--
        p
        {
         margin-bottom: 0px;
        }
        -->
        </style>
        
        ';
        $html .= "<page backtop=\"4mm\" backbottom=\"7mm\" backleft=\"6mm\" backright=\"6mm\" style=\"font-size: 12pt;line-height: 15pt;\">";
        $html .= "<div class='page-title'>";
        $html .= "<h2>" . $this->title . "</h2>";
        $html .= "</div>";
        $html .=   $this->content;
        $html .= "</page>";

        return $html;
    }

    private function output()
    {
        try {
            $this->buffer = $this->pdf->output('my-doc.pdf', 'S');
        } catch (Html2PdfException $e) {
            // TODO: handle this
        }
    }

    private function create_file()
    {
        $filename     = sanitize_title($this->title);
        $this->upload = wp_upload_bits($filename . '.pdf', null,
            $this->buffer);
    }

    private function create_attatchment()
    {
        $file_path        = $this->upload['file'];
        $file_name        = basename($file_path);
        $file_type        = wp_check_filetype($file_name, null);
        $attachment_title = sanitize_file_name(pathinfo($file_name,
            PATHINFO_FILENAME));
        $wp_upload_dir    = wp_upload_dir();

        $post_info = array(
            'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $file_type['type'],
            'post_title'     => $attachment_title,
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // Create the attachment
        $this->attatchment_id = wp_insert_attachment($post_info, $file_path,
            $this->post_id);

        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata($this->attatchment_id,
            $file_path);

        // Assign metadata to attachment
        wp_update_attachment_metadata($this->attatchment_id, $attach_data);
    }

    private function attach_ebook()
    {
        update_post_meta($this->post_id, '_rock_convert_ebook_attatchment_id',
            $this->attatchment_id);
    }

    private function featured_image_tag()
    {
        return '<img src="' . $this->featured_image_url
               . '" width="680">';

    }
}