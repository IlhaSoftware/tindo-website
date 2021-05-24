<?php
/**
 * The right sidebar containing the main widget area.
 *
 * @package rock content
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! is_active_sidebar( 'right-sidebar' ) ) {
	return;
}

// when both sidebars turned on reduce col size to 3 from 4.
$sidebar_pos = get_theme_mod( 'rock_sidebar_position' );
?>

<?php if ( 'both' === $sidebar_pos ) { ?>
<div class="col-md-3 widget-area" id="right-sidebar" role="complementary">
<?php } elseif ( 'ads' === $sidebar_pos ) { ?>
<div class="col-md-3 widget-area" id="right-sidebar" role="complementary">
	<?php } else { ?>
<div class="col widget-area" id="right-sidebar" role="complementary">
	<?php } ?>
<?php dynamic_sidebar( 'right-sidebar' ); ?>

</div><!-- #right-sidebar -->
