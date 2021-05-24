<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

    <a class="skip-link sr-only sr-only-focusable" href="#content">
        <?php esc_html_e('Skip to content', 'rockcontent'); ?>
    </a>

    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false"
                aria-label="<?php esc_attr_e('Toggle navigation', 'rockcontent'); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-block d-sm-none pull-right text-right search-form-mobile">
            <?php get_search_form(); ?>
        </div>

        <div class="container-fluid">
            <div class="row w-100 ml-0 justify-content-center">
                <div class="col-md-2 d-none d-md-block">
                    <?php if (rock_logo_navbar()) {
                        the_custom_logo();
                    } else {
                        get_template_part('global-templates/social', 'networks');
                    } ?>
                </div>
                <div class="col-md-8 text-center">
                    <?php wp_nav_menu(
                        array(
                            'theme_location'  => 'primary',
                            'container_class' => 'collapse navbar-collapse',
                            'container_id'    => 'navbarNavDropdown',
                            'menu_class'      => 'navbar-nav w-100',
                            'fallback_cb'     => '',
                            'menu_id'         => 'main-menu',
                            'depth'           => 2,
                            'walker'          => new rock_WP_Bootstrap_Navwalker(),
                        )
                    ); ?>
                </div>
                <div class="col-md-2 d-none d-md-block">
                    <?php get_search_form(); ?>
                </div>
            </div>


        </div>
    </nav><!-- .site-navigation -->
    <?php if ( ! has_custom_logo()) { ?>
        <header class="site-header">
            <?php if (is_front_page() && is_home()) { ?>

                <h1 class="navbar-brand mb-0">
                    <a rel="home" href="<?php echo esc_url(home_url('/')); ?>"
                       title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                       itemprop="url"><?php bloginfo('name'); ?></a>
                </h1>

            <?php } else { ?>

                <a class="navbar-brand" rel="home" href="<?php echo esc_url(home_url('/')); ?>"
                   title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                   itemprop="url"><?php bloginfo('name'); ?></a>

            <?php } ?>
        </header>
    <?php } elseif (rock_logo_head()) { ?>
        <header class="site-header site-header--custom-logo">
            <div class="header__logo">
                <?php the_custom_logo(); ?>
            </div>
        </header>
    <?php } ?>
    </header>

</div>

<?php if (is_front_page()) { ?>
    <?php get_template_part('global-templates/highlights'); ?>
<?php } ?>
