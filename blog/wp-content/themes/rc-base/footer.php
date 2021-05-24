<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package rockcontent
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$container = get_theme_mod('rock_container_type');
?>

<?php get_template_part('global-templates/footer', 'widgets'); ?>

<div class="wrapper" id="copyright">

    <div class="<?php echo esc_attr($container); ?>">

        <div class="row">

            <div class="col-md-12">

                <footer class="site-footer" id="colophon">
                   <?php get_template_part('global-templates/copyright'); ?>
                </footer><!-- #colophon -->

            </div><!--col end -->

        </div><!-- row end -->

    </div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

