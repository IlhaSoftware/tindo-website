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
<?php $link = esc_url(get_post_meta($post->ID, '_rock_material_link', true)); ?>

<article <?php post_class('post-material'); ?> id="post-<?php the_ID(); ?>">

    <header class="post-material__header">
        <a href="<?php echo $link; ?>">
            <?php echo get_the_post_thumbnail($post->ID, 'material-gratuito'); ?>
        </a>
    </header>


    <div class="post-material__content">
        <div class="post-material__content__meta">
            <?php rock_posted_on(); ?>
        </div>

        <?php the_title(sprintf('<h2 class="post-material__content__title"><a href="%s" rel="bookmark">', $link),
            '</a></h2>'); ?>
    </div>

</article><!-- #post-## -->
