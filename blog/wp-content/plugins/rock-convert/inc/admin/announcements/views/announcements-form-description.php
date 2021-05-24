<div class="postbox">
    <h3 class="hndle" style="padding-left: 20px;padding-bottom: 10px;">
        <span><?php echo __("Configurações", "rock-convert"); ?></span>
    </h3>
    <div class="inside">
        <label for="activate_announcement" class="announcement">
            <input type="checkbox"
                   name="rconvert_activate_announcement" <?php echo $activated == "1" ? "checked" : null; ?>
                   value="1" id="activate_announcement">
            <?php echo __("Ativar barra de anúncios", "rock-convert"); ?>
        </label><br/><br/>

        <label for="announcement_text">
            <?php echo __("Texto do anúncio", "rock-convert"); ?><br/>
            <input type="text" name="rconvert_announcement_text" size="80" value="<?php echo $text; ?>"
                   id="announcement_text">
            <div>
                <small><?php echo __("É recomendado que o texto tenha no máximo <strong>70 caracteres</strong>",
                        "rock-convert"); ?></small>
            </div>
        </label><br/>

        <label for="announcement_btn">
            <?php echo __("Texto do botão", "rock-convert"); ?><br/>
            <input type="text" name="rconvert_announcement_btn" size="50" value="<?php echo $btn; ?>"
                   id="announcement_btn">

            <div>
                <small><strong><?php echo __("Dica:",
                            "rock-convert"); ?></strong> <?php echo __("Para deixar a barra de anúncios sem botão, basta deixar o campo acima vazio.",
                        "rock-convert"); ?></small>
            </div>
        </label><br/>

        <label for="announcement_link">
            <?php echo __("Link do botão", "rock-convert"); ?><br/>
            <input type="text" name="rconvert_announcement_link" size="50" value="<?php echo $link; ?>"
                   id="announcement_link">
        </label><br><br>

        <?php echo __("Posição onde a barra deve aparecer", "rock-convert"); ?><br/><br>

        <div class="rconvert_announcement_position_preview">
            <img src="<?php echo plugin_dir_url(__FILE__); ?>../../img/preview-top.png"
                 class="rconvert_announcement-preview-img"/>
            <label for="announcement_position_top" style="padding-right: 20px;">
                <input type="radio" name="rconvert_announcement_position" id="announcement_position_top" value="top"
                    <?php echo $position == "top" ? "checked" : null; ?>> <?php echo __("Fixa no topo do site", "rock-convert"); ?>
            </label>
        </div>

        <div class="rconvert_announcement_position_preview">
            <img src="<?php echo plugin_dir_url(__FILE__); ?>../../img/preview-bottom.png"
                 class="rconvert_announcement-preview-img"/>
            <label for="announcement_position_bottom">
                <input type="radio" name="rconvert_announcement_position" id="announcement_position_bottom"
                       value="bottom" <?php echo $position == "bottom" ? "checked" : null; ?>>
                <?php echo __("Fixa no fundo do site", "rock-convert"); ?>
            </label>
        </div>

        <br>

        <div class="clearfix" style="display: block;clear: both;"></div>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php echo __("Salvar todas as configurações", "rock-convert"); ?>">
        </p>
    </div>

</div>
