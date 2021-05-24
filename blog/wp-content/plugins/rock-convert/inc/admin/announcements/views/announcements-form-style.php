<div class="postbox">
    <h3 class="hndle" style="padding-left: 20px;padding-bottom: 10px;">
        <span><?php echo __("Estilo da barra de anúncios", "rock-convert"); ?></span>
    </h3>
    <div class="inside">

        <div style="float: left;width: 300px;">
            <p>
                <label for="rconvert_announcement_bg_color"><?php _e('Cor do fundo', 'rock-convert'); ?></label><br>
                <input type="text" name="rconvert_announcement_bg_color" class="color-picker"
                       id="rconvert_announcement_bg_color" value="<?php echo $bg_color; ?>"
                       data-default-color="<?php echo $default['bg_color'] ?>"/>
            </p>

            <p>
                <label for="rconvert_announcement_text_color"><?php _e('Cor do texto', 'rock-convert'); ?></label><br>
                <input type="text" name="rconvert_announcement_text_color" class="color-picker"
                       id="rconvert_announcement_text_color" value="<?php echo $text_color; ?>"
                       data-default-color="<?php echo $default['text_color'] ?>"/>
            </p>

            <p>
                <label for="rconvert_announcement_btn_color"><?php _e('Cor do botão', 'rock-convert'); ?></label><br>
                <input type="text" name="rconvert_announcement_btn_color" class="color-picker"
                       id="rconvert_announcement_btn_color" value="<?php echo $btn_color; ?>"
                       data-default-color="<?php echo $default['btn_color'] ?>"/>
            </p>

            <p>
                <label for="rconvert_announcement_btn_text_color"><?php _e('Cor do texto do botão', 'rock-convert'); ?></label><br>
                <input type="text" name="rconvert_announcement_btn_text_color" class="color-picker"
                       id="rconvert_announcement_btn_text_color" value="<?php echo $btn_text_color; ?>"
                       data-default-color="<?php echo $default['btn_text_color'] ?>"/>
            </p>
        </div>

        <div style="float: left;">
            <span><strong><?php echo __("Pre-visualização", "rock-convert"); ?></strong></span><br>
            <div class="ann_preview rconvert_announcement_bg_color"
                 style="padding: 20px; background-color: <?php echo $bg_color; ?>">
                <span style="color: <?php echo $text_color; ?>"
                      class="ann_preview_text rconvert_announcement_text_color"><?php echo $text; ?></span>
                <?php if ( ! empty($btn) && ! empty($link)) { ?>
                    <a href="<?php echo $link; ?>"
                       class="ann_preview_btn rconvert_announcement_btn_color rconvert_announcement_btn_text_color"
                       style="background: <?php echo $btn_color; ?>;color: <?php echo $btn_text_color; ?>"><?php echo $btn; ?></a>
                <?php } ?>
            </div>

        </div>
        <br>

        <div class="clearfix" style="display: block;clear: both;"></div>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php echo __("Salvar todas as configurações", "rock-convert"); ?>">
        </p>
    </div>

</div>
