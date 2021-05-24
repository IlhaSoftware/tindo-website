<?php

namespace Rock_Convert\Inc\Admin;

use Rock_Convert\inc\libraries\MailChimp;
use Rock_Convert\inc\libraries\Wp_License_Manager;

/**
 * The cta settings page
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      2.0.0
 *
 * @author     Rock Content
 */
class Page_Settings
{
    public $subscriptions_table_name = "rconvert-subscriptions";

    public function register()
    {
        add_submenu_page(
            'edit.php?post_type=cta',
            __('Configurações do Rock Convert', 'rock-convert'),
            __('Configurações', 'rock-convert'),
            'manage_options',
            'rock-convert-settings',
            array(
                $this,
                'display'
            )
        );
        add_filter('admin_footer_text', array($this, 'custom_admin_footer'));
    }

    public function custom_admin_footer()
    {
        $current_page = get_current_screen();

        if ($current_page->id == "cta_page_rock-convert-settings"
            || $current_page->id == "cta"
        ) {
            echo 'Rock Convert by <a href="https://rockcontent.com" target="_blank">Rock Content</a> | <a href="https://convert.rockcontent.com/suporte" target="_blank">Entre em contato com o suporte</a>';
        }
    }

    public function save_settings_callback()
    {
        if (isset($_POST['rock_convert_settings_nonce'])
            && wp_verify_nonce($_POST['rock_convert_settings_nonce'],
                'rock_convert_settings_nonce')
        ) {
            $tab = sanitize_key(Utils::getArrayValue($_POST, 'tab'));

            if ($tab == "general") {
                $this->updateGeneralTab();
            }

            if ($tab == "advanced") {
                $this->updateAdvancedTab();
            }

            if ($tab == "integrations") {
                $this->updateIntegrationsTab();
            }

            if ($tab == "license") {
                $this->updateLicenseTab();
            }

            wp_safe_redirect(
                admin_url('edit.php?post_type=cta&page=rock-convert-settings&tab='
                          . $tab . "&success=true")
            );
        }
    }

    protected function updateGeneralTab()
    {
        $enable_analytics = intval(Utils::getArrayValue($_POST, 'rock_convert_enable_analytics'));
        $hide_referral    = intval(Utils::getArrayValue($_POST, 'rock_convert_remove_label'));

        update_option(
            '_rock_convert_enable_analytics', $enable_analytics
        );

        if (Utils::unlocked()) {
            update_option(
                '_rock_convert_powered_by_hidden', $hide_referral
            );
        }
    }

    protected function updateAdvancedTab()
    {
        $mailchimp_token  = sanitize_key(Utils::getArrayValue($_POST, 'mailchimp_token'));
        $rd_public_token  = sanitize_key(Utils::getArrayValue($_POST, 'rd_station_public_token'));
        $hubspot_form_url = esc_url_raw(Utils::getArrayValue($_POST, 'hubspot_form_url'));

        update_option(
            '_rock_convert_mailchimp_token', $mailchimp_token
        );

        if (isset($_POST['mailchimp_list'])) {
            $mailchimp_list = Utils::getArrayValue($_POST, 'mailchimp_list');

            update_option(
                '_rock_convert_mailchimp_list', $mailchimp_list
            );
        }

        update_option(
            '_rock_convert_rd_public_token', $rd_public_token
        );

        update_option(
            '_rock_convert_hubspot_form_url', $hubspot_form_url
        );
    }

    protected function updateIntegrationsTab()
    {
        $mailchimp_token = Utils::getArrayValue($_POST, 'mailchimp_token');

        update_option(
            '_rock_convert_mailchimp_token', $mailchimp_token
        );

        if (isset($_POST['mailchimp_list'])) {
            $mailchimp_list = Utils::getArrayValue($_POST, 'mailchimp_list');

            update_option(
                '_rock_convert_mailchimp_list', $mailchimp_list
            );
        }
    }

    protected function updateLicenseTab()
    {
        $license_key = Utils::getArrayValue($_POST, 'license_key');
        $action      = Utils::getArrayValue($_POST, 'license_action');

        if ($action == "activate") {
            Wp_License_Manager::activate($license_key);
        } elseif ($action == "deactivate") {
            Wp_License_Manager::deactivate($license_key);
        }
    }

    public function export_csv_callback()
    {
        if (isset($_POST['rock_convert_csv_nonce'])
            && wp_verify_nonce($_POST['rock_convert_csv_nonce'],
                'rock_convert_csv_nonce')
        ) {
            try {
                $exportCSV = new CSV($this->subscriptions_table_name);
            } catch (\Exception $e) {
                //
            }
        }
    }

    public function display()
    {
        $unlocked         = Utils::unlocked();
        $active_tab       = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        $success_saved    = isset($_GET['success']);
        $integrations_tab = $unlocked ? 'advanced' : 'integrations';
        $title            = $unlocked ? "Rock Convert (Premium)" : "Rock Convert";
        ?>
        <div class="wrap">

            <h1 class="wp-heading-inline"><?php echo $title; ?></h1>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo $this->settings_tab_url('general') ?>"
                   class="nav-tab <?php echo $active_tab
                                             == 'general'
                       ? 'nav-tab-active' : ''; ?>"><?php echo __("Início", "rock-convert"); ?></a>
                <a href="<?php echo $this->settings_tab_url($integrations_tab) ?>"
                   class="nav-tab <?php echo in_array($active_tab, array("integrations", "advanced"))
                       ? 'nav-tab-active' : ''; ?>"><?php echo __("Integrações", "rock-convert"); ?></a>
                <a href="<?php echo $this->settings_tab_url('leads') ?>"
                   class="nav-tab <?php echo $active_tab == 'leads'
                       ? 'nav-tab-active' : ''; ?>"><?php echo __("Contatos", "rock-convert"); ?></a>
                <a href="<?php echo $this->settings_tab_url('license') ?>"
                   class="nav-tab <?php echo $active_tab == 'license'
                       ? 'nav-tab-active' : ''; ?>"><?php echo __("Licença", "rock-convert"); ?></a>
            </h2>

            <?php if ($success_saved) { ?>
                <div class="notice notice-success is-dismissible">
                    <p><strong><?php echo __("Atualizações realizadas com sucesso!", "rock-convert"); ?></strong></p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            <?php } ?>

            <div class="rock-convert-settings-wrap">
                <?php if ($active_tab == "general") {
                    $this->general_tab();
                } elseif ($active_tab == "advanced") {
                    $this->advanced_tab();
                } elseif ($active_tab == "integrations") {
                    $this->integrations_tab();
                } elseif ($active_tab == "license") {
                    $this->license_tab();
                } elseif ($active_tab == "logs") {
                    $this->logs_tab();
                } else {
                    $this->leads_tab();
                } ?>
            </div>


        </div>
        <?php
    }

    public function settings_tab_url($tab)
    {
        return admin_url("edit.php?post_type=cta&page=rock-convert-settings&tab="
                         . $tab);
    }

    public function general_tab()
    {
        $settings_nonce    = wp_create_nonce('rock_convert_settings_nonce');
        $analytics_enabled = Admin::analytics_enabled();
        $hide_referral     = Admin::hide_referral();
        ?>

        <div id="welcome-panel" class="welcome-panel">

            <div class="welcome-panel-content">

                <div class="welcome-panel-column-container">
                    <div class="welcome-panel-column rock-convert-admin-subscribe-container--left">
                        <h2><?php echo __("Comece a usar", "rock-convert"); ?></h2>
                        <a class="button button-primary button-hero load-customize hide-if-no-customize"
                           href="<?php echo admin_url('post-new.php?post_type=cta') ?>"><?php echo __("Adicionar um banner",
                                "rock-convert"); ?>
                        </a>
                        <h2 style="margin-top: 30px;margin-bottom: 15px;"><?php echo __("Precisa de ajuda?",
                                "rock-convert"); ?></h2>
                        <ul>
                            <li>
                                <a href="https://rock-content.gitbook.io/rock-convert/"
                                   target="_blank"
                                   class="welcome-icon welcome-widgets-menus"><?php echo __("Dúvidas comuns (FAQ)",
                                        "rock-convert"); ?></a>
                            </li>
                            <li>
                                <a href="https://convert.rockcontent.com/sugerir"
                                   target="_blank"
                                   class="welcome-icon welcome-write-blog"><?php echo __("Sugerir nova funcionalidade",
                                        "rock-convert"); ?></a>
                            </li>
                            <li>
                                <a href="https://convert.rockcontent.com/suporte"
                                   target="_blank"
                                   class="welcome-icon welcome-comments"><?php echo __("Relatar um problema",
                                        "rock-convert"); ?></a>
                            </li>
                            <?php if ( ! Utils::unlocked()) { ?>
                                <li>
                                    <a href="https://convert.rockcontent.com/premium"
                                       target="_blank"
                                       class="welcome-icon dashicons-star-filled"><?php echo __("Adquirir versão premium",
                                            "rock-convert"); ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="welcome-panel-column rock-convert-admin-subscribe-container">
                        <h2 style="margin-top: 0;margin-bottom: 15px;"><?php echo __("Próximos passos",
                                "rock-convert"); ?></h2>
                        <ul>
                            <li>
                                <a href="https://marketingdeconteudo.com/o-que-e-cta/"
                                   target="_blank"
                                   class="welcome-icon welcome-learn-more"><?php echo __("O que é CTA: Tudo que você precisa saber",
                                        "rock-convert"); ?></a>
                            </li>
                            <li>
                                <a href="https://marketingdeconteudo.com/parametros-utm-do-google-analytics/"
                                   class="welcome-icon welcome-learn-more"
                                   target="_blank"><?php echo __("Como usar os parâmetros de UTM",
                                        "rock-convert"); ?></a></li>
                        </ul>

                        <?php $this->newsletter_subscribe_form(); ?>
                    </div>
                </div>
            </div>
        </div>

        <h1 class="wp-heading-inline"><?php echo __("Recursos disponíveis", "rock-convert"); ?></h1>
        <br><br>

        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
              method="post">
            <input type="hidden" name="action"
                   value="rock_convert_settings_form">
            <input type="hidden"
                   name="rock_convert_settings_nonce"
                   value="<?php echo $settings_nonce ?>"/>
            <input type="hidden" name="tab" value="general"/>

            <label for="rock_convert_enable_analytics" style="display: block">
                <input type="checkbox" name="rock_convert_enable_analytics"
                       id="rock_convert_enable_analytics"
                       value="1" <?php echo $analytics_enabled ? "checked"
                    : null ?>/>
                <strong><?php echo __("Salvar visualizações e cliques", "rock-convert"); ?><span
                            class="rock-convert-label-new"><?php echo __("Novo!", "rock-convert"); ?></span></strong>

                <div style="padding-top: 5px;padding-bottom: 25px;padding-left: 25px;">
                    <small>
                        <i><?php echo __("Ative esta opção para coletar os dados de visualizações e clicks nos banners do Rock Convert.",
                                "rock-convert"); ?></i></small>
                </div>

            </label>

            <?php if ( ! Utils::unlocked()) { ?>
                <label for="rock_convert_remove_label">
                    <input type="checkbox" disabled
                           id="rock_convert_remove_label"
                           value="true"/>
                    <strong style="color: #9c9c9c;"><?php echo __("Remover \"Powered by Rock Convert\" abaixo dos anúncios",
                            "rock-convert"); ?>
                        <span class="rock-convert-label-new" style="background: #969595;"><a
                                    href="https://convert.rockcontent.com/premium"
                                    target="_blank"
                                    style="color: #FFF; text-decoration: none;">Premium</a></span></strong>

                    <div style="padding-left: 25px; padding-top: 5px;">
                        <small><a href="https://convert.rockcontent.com/premium"
                                  target="_blank"><?php echo __("Adquirir versão premium", "rock-convert"); ?></a>
                        </small>
                    </div>

                </label>
                <br><br>
            <?php } else { ?>
                <label for="rock_convert_remove_label" style="display: block;padding: 10px 0 30px;">
                    <input type="checkbox" name="rock_convert_remove_label"
                           id="rock_convert_remove_label"
                           value="1" <?php echo $hide_referral ? "checked" : null ?>/>
                    <strong><?php echo __("Remover \"Powered by Rock Convert\" abaixo dos anúncios",
                            "rock-convert"); ?></strong>
                </label>
            <?php } ?>

            <button type="submit" class="button button-large button-primary"><?php echo __("Salvar configurações",
                    "rock-convert"); ?></button>
        </form>

        <?php
    }

    public function newsletter_subscribe_form()
    {
        ?>
        <form action="https://www.rdstation.com.br/api/1.2/conversions" method="POST">
            <input type="hidden" name="token_rdstation" value="e58085419f764dbdaf17ac942334b0fc"/>
            <input type="hidden" name="identificador" value="rock-convert-wp-admin"/>
            <input type="hidden" name="website" value="<?php echo get_bloginfo('url'); ?>">
            <input type="hidden" name="redirect_to"
                   value="<?php echo esc_url(admin_url('edit.php?post_type=cta&page=rock-convert-settings&success=newsletter')); ?>"/>
            <input type="hidden" name="c_utmz" id="c_utmz" value=""/>
            <script type="text/javascript">
                function read_cookie(a) {
                    var b = a + "=";
                    var c = document.cookie.split(";");
                    for (var d = 0; d < c.length; d++) {
                        var e = c[d];
                        while (e.charAt(0) == " ") e = e.substring(1, e.length);
                        if (e.indexOf(b) == 0) {
                            return e.substring(b.length, e.length)
                        }
                    }
                    return null
                }

                try {
                    document.getElementById("c_utmz").value = read_cookie("__utmz")
                } catch (err) {
                }
            </script>
            <div class="rock-convert-newsletter-form">
                <h2><?php echo __("Atualizações", "rock-convert"); ?></h2>
                <p class="about-description"><?php echo __("Cadastre seu e-mail abaixo para receber novidades do Rock Convert!",
                        "rock-convert"); ?></p>
                <div class="welcome-panel-column-container">
                    <input required name="email" type="email"
                           class="rock-convert-newsletter-form__input"
                           placeholder="<?php echo __("Digite seu e-mail", "rock-convert"); ?>">
                    <button type="submit" class="button button-primary button-hero rock-convert-newsletter-form__btn">
                        <?php echo __("Cadastrar", "rock-convert"); ?>
                    </button>
                    <br><br>
                </div>
            </div>
        </form>

        <?php
    }

    public function advanced_tab()
    {
        $settings_nonce = wp_create_nonce('rock_convert_settings_nonce');

        if ( ! Utils::unlocked()) {
            return;
        }
        ?>

        <h1 class="wp-heading-inline"><?php echo __("Ferramentas de automação", "rock-convert"); ?></h1>

        <p style="max-width: 580px">
            <?php echo __("Selecione abaixo uma ferramenta de automação e envie os leads gerados pelos formulários do Rock Convert.",
                "rock-convert"); ?>
        </p>

        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
              method="post">
            <input type="hidden" name="tab" value="advanced"/>
            <input type="hidden" name="action"
                   value="rock_convert_settings_form">
            <input type="hidden" name="rock_convert_settings_nonce"
                   value="<?php echo $settings_nonce ?>"/>
            <div class="rock-convert-how-it-works">
                <?php $this->mailchimp_form(); ?>
                <hr>
                <br>
                <?php $this->rd_station_form(); ?>
                <hr>
                <br>
                <?php $this->hubspot_form(); ?>
            </div>

            <button type="submit" class="button button-large button-primary"><?php echo __("Salvar integrações",
                    "rock-convert"); ?></button>
        </form>
        <?php
    }

    public function mailchimp_form()
    {
        $mailchimp_token = get_option('_rock_convert_mailchimp_token');
        $mailchimp_list  = get_option('_rock_convert_mailchimp_list');

        $lists = $this->get_mailchimp_lists($mailchimp_token);

        ?>

        <h3 style="margin-bottom: 0;">MailChimp</h3>

        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="mailchimp_token">
                        <?php echo __("Chave de API do MailChimp", "rock-convert"); ?>
                    </label>
                </th>
                <td>
                    <input name="mailchimp_token"
                           id="mailchimp_token"
                           type="text"
                           placeholder="Ex: abc123abc123abc123abc123abc123-us"
                           class="regular-text code"
                           value="<?php echo $mailchimp_token ?>">
                    <br>
                    <small><?php echo __("Precisa de ajuda?", "rock-convert"); ?> <a
                                href="https://mailchimp.com/help/about-api-keys/"
                                target="_blank"><?php echo __("Veja como criar uma chave de API para o MailChimp",
                                "rock-convert"); ?></a>
                    </small>
                </td>
            </tr>
            <?php if ( ! empty($mailchimp_token) && empty($lists)) { ?>
                <tr>
                    <th>
                    </th>
                    <td>
                        <span style="color: orangered;font-weight: bold"><?php echo __("Atenção: nenhuma lista encontrada.",
                                "rock-convert"); ?></span>
                        <br/>
                        <small>
                            <?php
                            $url  = 'https://rockcontent.com/blog/mailchimp/#listas';
                            $link = sprintf(wp_kses(__('Confira se a chave de API está correta e se esta conta já possui uma lista criada. Caso ainda não tenha nenhuma lista, saiba como criar <a href="%s">clicando aqui</a>.',
                                'rock-convert'),
                                array('a' => array('href' => array()))), esc_url($url));
                            echo $link;
                            ?>
                        </small>
                    </td>
                </tr>
            <?php } ?>
            <?php if ( ! empty($mailchimp_token) && ! empty($lists)) { ?>
                <tr>
                    <th>
                        <label for="mailchimp_list">
                            <?php echo __("Selecione uma lista", "rock-convert"); ?>
                        </label>
                    </th>
                    <td>
                        <select name="mailchimp_list" id="mailchimp_list" class="regular-text code">
                            <option>-- <?php echo __("Selecione uma lista", "rock-convert"); ?> --</option>
                            <?php foreach ($lists as $list) { ?>
                                <option value="<?php echo $list['id']; ?>" <?php echo $mailchimp_list == $list['id'] ? "selected" : null ?>>
                                    <?php echo $list['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <br>
                        <small><?php echo __("Escolha uma lista para enviar os contatos coletados pelo Rock Convert.",
                                "rock-convert"); ?></small>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * @param $token
     *
     * @return array|bool
     */
    public function get_mailchimp_lists($token)
    {
        if (empty($token)) {
            return array();
        }

        try {
            $MailChimp = new MailChimp($token);

            return $MailChimp->getLists();
        } catch (\Exception $e) {
            Utils::logError($e);

            return array();
        }
    }

    public function rd_station_form()
    {
        $rd_public_token = get_option('_rock_convert_rd_public_token');

        ?>
        <h3 style="margin-bottom: 0;">RD Station</h3>

        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="rd_station_public_token">
                        <?php echo __("Token público da RD Station", "rock-convert"); ?>
                    </label>
                </th>
                <td>
                    <input name="rd_station_public_token"
                           id="rd_station_public_token"
                           type="text" placeholder="Ex: e580854190764dbdaf19ac942334b0fc"
                           class="regular-text code"
                           value="<?php echo $rd_public_token ?>">

                    <br>
                    <small><?php echo __("Para encontrar o token público da RD Station acesse:", "rock-convert"); ?>
                        <a href="https://app.rdstation.com.br/integracoes/tokens" target="_blank">https://app.rdstation.com.br/integracoes/tokens</a>
                    </small>
                </td>
            </tr>
            </tbody>
        </table>
        </p>
        <?php
    }

    public function hubspot_form()
    {
        $hubspot_form_url = get_option('_rock_convert_hubspot_form_url');
        ?>

        <h3 style="margin-bottom: 0;">HubSpot</h3>

        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="hubspot_form_url">
                        <?php echo __("URL do form da HubSpot", "rock-convert"); ?>
                    </label>
                </th>
                <td>
                    <input name="hubspot_form_url"
                           id="hubspot_form_url"
                           type="text"
                           placeholder="Ex: https://forms.hubspot.com/uploads/form/v2/:portal_id/:form_guid"
                           class="regular-text code"
                           value="<?php echo $hubspot_form_url ?>">
                    <br>
                    <small><?php echo __("Precisa de ajuda?", "rock-convert"); ?> <a
                                href="https://developers.hubspot.com/docs/methods/forms/submit_form"
                                target="_blank"><?php echo __("Acesse a central de ajuda da HubSpot",
                                "rock-convert"); ?></a></small>
                    <br>
                    <br>
                    <small><strong><?php echo __("Formato da URL:", "rock-convert"); ?> </strong>https://forms.hubspot.com/uploads/form/v2/<strong>PORTAL_ID</strong>/<strong>FORM_GUID</strong>
                    </small>
                    <br><br>
                    <small><?php echo __("Onde: <strong>PORTAL_ID</strong> é o id da conta e <strong>FORM_GUID</strong> é o ID do
                        formulário.", "rock-convert"); ?></small>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }

    public function integrations_tab()
    {
        $settings_nonce = wp_create_nonce('rock_convert_settings_nonce');
        ?>

        <h1 class="wp-heading-inline"><?php echo __("Ferramentas de automação", "rock-convert"); ?></h1>

        <p style="max-width: 580px">
            <?php echo __("Selecione abaixo uma ferramenta de automação e envie os leads gerados pelos formulários do Rock Convert.",
                "rock-convert"); ?>
        </p>
        <br>

        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
              method="post">
            <input type="hidden" name="tab" value="integrations"/>
            <input type="hidden" name="action"
                   value="rock_convert_settings_form">
            <input type="hidden" name="rock_convert_settings_nonce"
                   value="<?php echo $settings_nonce ?>"/>
            <div class="rock-convert-how-it-works">
                <?php $this->mailchimp_form(); ?>
                <br>
                <?php $this->premium_form("RD Station"); ?>
                <br>
                <?php $this->premium_form("HubSpot"); ?>
                <br>
            </div>

            <button type="submit" class="button button-large button-primary"><?php echo __("Salvar integrações",
                    "rock-convert"); ?></button>
        </form>
        <?php
    }

    public function premium_form($title)
    {
        ?>
        <h3 style="margin-bottom: 0;"><?php echo $title; ?> <span class="dashicons dashicons-lock"
                                                                  style="color: #0073aa;"></span></h3>

        <div class="rock-convert-admin-premium-form">
            <a href="https://convert.rockcontent.com/premium/" target="_blank"
               class="rock-convert-admin-premium-form-link"><?php echo __("Disponível na versão Premium",
                    "rock-convert"); ?></a>
            <table class="form-table rock-convert-admin-premium-form-container">
                <tbody>
                <tr>
                    <th>
                        <label for="rd_station_public_token">
                            Lorem ipsum dolor sit amet
                        </label>
                        <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</small>
                    </th>
                    <td>
                        <input type="text" placeholder=""
                               class="regular-text code"
                               value="">
                        <br>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        </p>
        <?php
    }

    public function license_tab()
    {
        $license_key    = get_option('_rock_convert_license_key');
        $activated      = Utils::unlocked();
        $license_error  = get_option('_rock_convert_license_error');
        $settings_nonce = wp_create_nonce('rock_convert_settings_nonce');
        $license_action = ! $activated ? "activate" : "deactivate";

        if ( ! empty($license_error)) {
            ?>
            <div class="notice notice-error">
                <p><?php echo $license_error ?></p>
            </div>
            <?php
            delete_option('_rock_convert_license_error');
        }
        ?>

        <h3 style="margin-bottom: 0;">Licença para versão premium</h3>

        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
              method="post">
            <input type="hidden" name="tab" value="license"/>
            <input type="hidden" name="action"
                   value="rock_convert_settings_form">
            <input type="hidden" name="license_action"
                   value="<?php echo $license_action; ?>">
            <input type="hidden" name="rock_convert_settings_nonce"
                   value="<?php echo $settings_nonce ?>"/>
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <label for="license_key">
                            <?php echo __("Chave da licença", "rock-convert"); ?>
                        </label>
                    </th>
                    <td>
                        <input name="license_key"
                               id="license_key"
                               type="text"
                            <?php echo $activated ? "disabled='disabled'" : null; ?>
                               placeholder=""
                               class="regular-text code"
                               value="<?php echo $license_key ?>">
                        <br>
                        <small><?php echo __("Precisa de ajuda?", "rock-convert"); ?> <a
                                    href="https://mailchimp.com/help/about-api-keys/"
                                    target="_blank"><?php echo __("Veja como criar uma chave de API para o MailChimp",
                                    "rock-convert"); ?></a>
                        </small>
                    </td>
                </tr>

                </tbody>
            </table>

            <?php if ( ! $activated) { ?>
                <button type="submit" class="button button-large button-primary"><?php echo __("Salvar licença",
                        "rock-convert"); ?></button>
            <?php } ?>

            <?php if ($activated) { ?>
                <input type="hidden" name="license_key" value="<?php echo $license_key; ?>">
                <button type="submit"
                        style="background: none;border: none;color: #a00;text-decoration: underline;cursor: pointer;"><?php echo __("Remover licença",
                        "rock-convert"); ?></button>
            <?php } ?>
        </form>
        <?php
    }

    public function logs_tab()
    {
        $file    = plugin_dir_path(__FILE__) . "logs/debug.log";
        $content = Utils::read_backward_line($file, 300);
        ?>
        <h2>Log</h2>
        <div style="height: 100%; overflow-x: scroll">
            <pre><?php echo $content; ?></pre>
        </div>
        <?php
    }

    public function leads_tab()
    {
        $csv_nonce = wp_create_nonce('rock_convert_csv_nonce');
        ?>
        <h1 class="wp-heading-inline"><?php echo __("Exportar", "rock-convert"); ?></h1>
        <p>
            <?php echo __("Para fazer o download dos contatos capturados pelo formulário de download no formato <strong>CSV</strong>, clique abaixo.",
                "rock-convert"); ?>
        </p>
        <p>
            <strong><?php echo $this->get_leads_count(); ?></strong>
            <?php echo __("contatos salvos.", "rock-convert"); ?>
        </p>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
              method="post" target="_blank">
            <input type="hidden" name="action"
                   value="rock_convert_export_csv">
            <input type="hidden" name="rock_convert_csv_nonce"
                   value="<?php echo $csv_nonce ?>"/>
            <button type="submit" class="button button-primary button-hero">
                <?php echo __("Exportar no formato CSV", "rock-convert"); ?>
            </button>
        </form>

        <?php
    }

    /**
     * Get number of subscribers saved in $this->subscriptions_table_name table
     *
     * @return int
     */
    public function get_leads_count()
    {
        global $wpdb;
        $table   = $wpdb->prefix . $this->subscriptions_table_name;
        $query   = "SELECT COUNT(*) as count FROM `" . $table . "`;";
        $results = $wpdb->get_results($query);

        if (count($results)) {
            return $results[0]->count;
        } else {
            return 0;
        }
    }

    /**
     * Add plugin action links.
     *
     * Add a link to the settings page on the plugins.php page.
     *
     * @since 2.0.0
     *
     * @param  array $links List of existing plugin action links.
     *
     * @return array         List of modified plugin action links.
     */
    public function action_links($links)
    {
        $integrations_tab = Utils::unlocked() ? 'advanced' : 'integrations';

        $links = array_merge(array(
            '<a href="' . $this->settings_tab_url('general') . '">'
            . __('Configurações', 'rock-convert') . '</a>',
            '<a href="' . $this->settings_tab_url($integrations_tab) . '">'
            . __('Integrações', 'rock-convert') . '</a>'
        ), $links);

        return $links;
    }

}
