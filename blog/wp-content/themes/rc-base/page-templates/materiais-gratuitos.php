<?php
/**
 * Template Name: Materiais Gratuitos
 *
 * Utilizado para mostrar materiais gratuitos
 *
 * @package rock content
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

$container = get_theme_mod('rock_container_type');

$query = rock_materiais_gratuitos_query();
?>

<div class="wrapper" id="index-wrapper">

    <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">

        <div class="row">
            <div class="col-md-12 pt-3 pb-5">
                <?php while (have_posts()): the_post(); ?>
                    <h3 class="pb-4 text-center"><?php echo the_title(); ?></h3>
                    <?php the_content(); ?>

                <?php endwhile; ?>
            </div>

        </div>

        <div class="row">

            <?php if ($query->have_posts()) : ?>

                <?php /* Start the Loop */ ?>

                <?php while ($query->have_posts()) : $query->the_post(); ?>


                    <div class="col-md-3 col-6 mb-5">
                        <?php

                        /*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part('loop-templates/content', 'material');
                        ?>
                    </div>


                <?php endwhile; ?>

            <?php else : ?>

                <?php get_template_part('loop-templates/content', 'none'); ?>

            <?php endif; ?>

            <!-- The pagination component -->
            <?php rock_pagination(); ?>


        </div><!-- .row -->

    </div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
