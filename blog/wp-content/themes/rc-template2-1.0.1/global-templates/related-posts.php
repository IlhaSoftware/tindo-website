<?php $related = rock_related_posts(get_the_ID()); ?>

<?php if ($related->have_posts()) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="section-title">Posts relacionados</div>
        </div>
    </div>

    <div class="row">
        <?php while ($related->have_posts()) : $related->the_post(); ?>

            <div class="col-md-4">
                <?php get_template_part('loop-templates/content', 'related'); ?>
            </div>

        <?php endwhile; ?>
    </div>

<?php endif; ?>

<?php wp_reset_postdata(); ?>