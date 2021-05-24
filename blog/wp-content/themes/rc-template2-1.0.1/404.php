<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package rockcontent
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

$container = get_theme_mod('rock_container_type');
?>

<div class="wrapper" id="error-404-wrapper">

    <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">

        <div class="row">

            <div class="col-md-12 content-area" id="primary">

                <main class="site-main" id="main">

                    <section class="error-404 not-found">

                        <header class="page-header">

                            <h1 class="page-title text-center"><?php esc_html_e('Oops! That page can&rsquo;t be found.',
                                    'rockcontent'); ?></h1>

                        </header><!-- .page-header -->

                        <div class="page-content text-center">

                            <p>Parece que a página que você procura não existe ou foi removida.</p>

                            <a href="<?php echo get_bloginfo('url') ?>">Voltar para a página inicial</a>

                        </div><!-- .page-content -->

                    </section><!-- .error-404 -->

                </main><!-- #main -->

            </div><!-- #primary -->

        </div><!-- .row -->

    </div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
