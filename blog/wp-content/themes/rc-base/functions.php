<?php
/**
 * Rock Content functions and definitions
 *
 * @package rockcontent
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$rock_includes = array(
    '/theme-settings.php',                  // Initialize theme default settings.
    '/dependencies.php',
    '/setup.php',                           // Theme setup and custom theme supports.
    '/widgets.php',                         // Register widget area.
    '/enqueue.php',                         // Enqueue scripts and styles.
    '/template-tags.php',                   // Custom template tags for this theme.
    '/pagination.php',                      // Custom pagination for this theme.
    '/hooks.php',                           // Custom hooks.
    '/extras.php',                          // Custom functions that act independently of the theme templates.
    '/customizer.php',                      // Customizer additions.
    '/custom-comments.php',                 // Custom Comments file.
    '/related-posts.php',                   // Related posts functions
    '/popular-posts.php',                   // Popular posts functions
    '/admin/custom-post-types/class-materiais.php',
    '/cta-widget.php',                      // CTA Widget
    '/jetpack.php',                         // Load Jetpack compatibility file.
    '/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker.
    '/editor.php',                          // Load Editor functions.
    '/performance.php',                     // Load performance and security functions
);

foreach ($rock_includes as $file) {
    $filepath = locate_template('inc' . $file);
    if ( ! $filepath) {
        trigger_error(sprintf('Error locating /inc%s for inclusion', $file), E_USER_ERROR);
    }
    require_once $filepath;
}
