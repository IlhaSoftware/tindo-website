<?php
/**
 * The template for displaying search forms in Underscores.me
 *
 * @package rockcontent
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
    <label class="sr-only" for="s"><?php esc_html_e( 'Search', 'rockcontent' ); ?></label>
    <div class="input-group">
        <i class="fa fa-search"></i>
        <input class="field form-control" id="s" name="s" type="text"
               placeholder="<?php esc_attr_e( 'Search &hellip;', 'rockcontent' ); ?>" value="<?php the_search_query(); ?>">
    </div>
</form>
