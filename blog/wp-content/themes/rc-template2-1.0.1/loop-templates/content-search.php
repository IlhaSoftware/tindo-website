<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package rock content
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<?php get_template_part('loop-templates/content'); ?>
