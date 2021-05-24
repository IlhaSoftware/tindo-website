<?php $social = rock_get_social_networks(); ?>

<ul class="social-icons">
    <?php foreach ($social as $network) { ?>
        <li>
            <a href="<?php echo $network['url']; ?>" target="_blank" title="<?php echo $network['slug']; ?>">
                <i class="fa <?php echo rock_map_social_icon($network['slug']); ?>"></i>
            </a>
        </li>
    <?php } ?>
</ul>