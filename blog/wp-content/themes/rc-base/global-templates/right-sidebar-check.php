<?php
/**
 * Right sidebar check.
 *
 * @package rock content
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

</div><!-- #closing the primary container from /global-templates/left-sidebar-check.php -->

<?php $sidebar_pos = get_theme_mod('rock_sidebar_position'); ?>

<?php if ('right' === $sidebar_pos || 'both' === $sidebar_pos) : ?>

    <?php get_template_part('sidebar-templates/sidebar', 'right'); ?>

<?php endif; ?>
