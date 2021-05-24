<?php
/**
 * Single post partial template.
 *
 * @package rock content
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<article <?php post_class('post_single'); ?> id="post-<?php the_ID(); ?>">

    <div class="post_single__content">
        <div class="post_single__content__reading-time">
            <?php echo rock_reading_time(get_the_content()); ?>
        </div>

        <?php the_content(); ?>

    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <!-- Rate content -->
    </footer><!-- .entry-footer -->

</article><!-- #post-## -->
