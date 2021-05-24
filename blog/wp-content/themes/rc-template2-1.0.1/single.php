<?php
/**
 * The template for displaying all single posts.
 *
 * @package rockcontent
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
$container = get_theme_mod('rock_container_type');
?>

<div id="single">

    <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">
        <?php while (have_posts()) : the_post(); ?>

            <div class="row">
                <div class="col-md-12">
                    <section class="single-header">
                        <?php echo get_the_post_thumbnail($post->ID, 'post-single',
                            array('class' => 'd-none d-md-block')); ?>

                        <?php echo get_the_post_thumbnail($post->ID,
                            'highlight-mobile',
                            array('class' => 'd-block d-sm-none')); ?>

                        <div class="single-header__content">
                            <span class="single-header__content__category">
                                <?php rock_post_category(); ?>
                            </span>
                            <h1 class="single-header__content__title">
                                <?php the_title(); ?>
                            </h1>
                            <div class="single-header__content__meta">
                                Por <?php the_author(); ?> - <?php the_date(); ?>
                            </div>

                        </div>
                    </section>
                </div>
            </div>

            <div class="row">

                <!-- Do the left sidebar check -->
                <?php get_template_part('global-templates/left-sidebar-check'); ?>

                <main class="site-main" id="main">
                    <?php get_template_part('loop-templates/content', 'single'); ?>

                    <?php get_template_part('global-templates/related', 'posts'); ?>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </main><!-- #main -->

                <?php get_template_part('global-templates/right-sidebar-check'); ?>

            </div><!-- .row -->

        <?php endwhile; // end of the loop. ?>

    </div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
