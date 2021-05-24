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

<article <?php post_class('post-entry'); ?> id="post-<?php the_ID(); ?>">
    <div class="row">
        <div class="col-md-5">
            <div class="post-entry__thumbnail">
                <a href="<?php echo get_permalink(); ?>">
                    <?php echo get_the_post_thumbnail($post->ID, 'entry'); ?>
                </a>
            </div>
        </div>
        <div class="col-md-7">
            <div class="post-entry__content">
                <div class="post-entry__content__category mt-3 mt-md-0">
                    <?php rock_post_category(); ?>
                </div>
                <?php the_title(sprintf('<h2 class="post-entry__content__title"><a href="%s" rel="bookmark">',
                    esc_url(get_permalink())),
                    '</a></h2>'); ?>

                <div class="post-entry__content__date mb-3">
                    <?php rock_posted_on(); ?>
                </div>
                <div class="post-entry__content__excerpt"><?php the_excerpt(); ?></div>
            </div>
        </div>
    </div>
</article><!-- #post-## -->
