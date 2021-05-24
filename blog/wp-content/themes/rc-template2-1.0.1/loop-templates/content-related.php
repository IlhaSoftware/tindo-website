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

<article <?php post_class('post-entry post-entry--related'); ?> id="post-<?php the_ID(); ?>">
    <div class="post-entry__thumbnail">
        <a href="<?php echo get_permalink(); ?>">
            <?php echo get_the_post_thumbnail($post->ID, 'entry'); ?>
        </a>
    </div>
    <div class="post-entry__content">
        <?php the_title(sprintf('<h2 class="post-entry__content__title"><a href="%s" rel="bookmark">',
            esc_url(get_permalink())),
            '</a></h2>'); ?>

        <div class="post-entry__content__date mb-3">
            <?php rock_posted_on(); ?>
        </div>
    </div>
</article><!-- #post-## -->
