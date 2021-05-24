<?php

$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'order'          => 'DESC',
    'orderby'        => 'date'
);

$the_query = new WP_Query($args); ?>

<?php if ($the_query->have_posts()) : ?>

    <div class="site-highlights">
        <div class="container">
            <div class="row">
                <?php $idx = 0; ?>
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                    <?php
                    $square = $idx == 0;
                    switch ($idx) {
                        case 0:
                            $padding = "pr-md-0";
                            break;
                        case 1:
                            $padding = "pr-md-0 pl-md-0";
                            break;
                        default:
                            $padding = "pl-md-0";
                    }
                    ?>
                    <div class="col-md-<?php echo $square ? 6 : 3 ?> <?php echo $padding; ?> mb-3 mb-md-0">
                        <section class="highlights">
                            <a href="<?php echo get_the_permalink(); ?>">
                                <?php echo get_the_post_thumbnail($post->ID,
                                    'highlight-' . ($square ? 'square' : 'vertical'),
                                    array('class' => 'd-none d-md-block')); ?>
                            </a>

                            <a href="<?php echo get_the_permalink(); ?>">
                                <?php echo get_the_post_thumbnail($post->ID,
                                    'highlight-mobile',
                                    array('class' => 'd-block d-sm-none')); ?>
                            </a>

                            <div class="highlights__content pr-3">
                                <span class="highlights__content__category">
                                    <?php rock_post_category(); ?>
                                </span>
                                <h2 class="highlights__content__title"><a
                                            href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            </div>
                        </section>
                    </div>
                    <?php $idx++; ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php wp_reset_postdata(); ?>

<?php endif; ?>