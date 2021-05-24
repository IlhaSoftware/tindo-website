<?php
/**
 * Add Chorus analytics.
 *
 * This class defines all code necessary to insert chorus analytics code into pages.
 *
 * @link       https://rockcontent.com/
 * @since      2.4.0
 * @package    Rcp_Wp_plugin
 * @subpackage Rcp_Wp_plugin/includes
 * @author     Rock Content <plugin@rockcontent.com>
 */
class Rcp_Wp_plugin_Chorus_Analytics {

  /**
   * @since 2.4.0
   */
  public static function get_analytics_tpl() {
    if ( ! get_option( "rcp_chorus_analytics_enabled", false ) ) {
      return;
    }

    $writeKey = get_option( "rcp_chorus_analytics_write_key" );
    $analyticsDomain = get_option( "rcp_chorus_analytics_domain" );
    $chorusBlogId = get_option( "rcp_chorus_blog_id" );
    $chorusUserId = get_option( "rcp_chorus_user_id" );

    // nanoid was uglified from https://github.com/ai/nanoid/blob/22bc8fff7c6eb86426bcfc57c18c035884df023b/index.browser.js
    // Rakam loader script was inserted from https://rakam.io/integrate?type=javascript
    echo "
      <script type='text/javascript'>
        (function () {
            var nanoid = (function(){var r=self.crypto||self.msCrypto;return function(n){n=n||21;for(var o=\"\",t=r.getRandomValues(new Uint8Array(n));0<n--;)o+=\"Uint8ArdomValuesObj012345679BCDEFGHIJKLMNPQRSTWXYZ_cfghkpqvwxyz-\"[63&t[n]];return o}})();

            var getUserId = function () {
                if (!localStorage.getItem('stageUserId')) {
                    localStorage.setItem('stageUserId', nanoid());
                }

                return localStorage.getItem('stageUserId') || '';
            };

            var logPageview = function () {
                var e = document.documentElement, g = document.getElementsByTagName('body')[0],
                    x = window.innerWidth || e.clientWidth || g.clientWidth,
                    y = window.innerHeight|| e.clientHeight|| g.clientHeight;

                rakam.logEvent('chorus_pageview', {
                    url: window.location.toString(),
                    hostname: window.location.hostname,
                    blog_id: '$chorusBlogId',
                    blog_owner_id: '$chorusUserId',
                    blog_kind: 'wordpress',
                    time_on_page: rakam.getTimeOnPreviousPage(),
                    returning_session: rakam.isReturningUser(),
                    color_depth: window.screen.colorDepth,
                    viewport: x + ' × ' + y,
                    title: document.title
                });
            }

            if (window.chorusAnalytics_rakamInitialized) {
                logPageview();
            } else {
                (function(e,t){var n=e.rakam||{};var r=t.createElement('script');r.type='text/javascript';
                r.async=true;r.src='https://d2f7xo8n6nlhxf.cloudfront.net/rakam.min.js';r.onload=function(){
                e.rakam.runQueuedFunctions()};var o=t.getElementsByTagName('script')[0];o.parentNode.insertBefore(r,o);
                function a(e,t){e[t]=function(){this._q.push([t].concat(Array.prototype.slice.call(arguments,0)));
                return this}}var s=function(){this._q=[];return this};var i=['set','setOnce','increment','unset'];
                for(var c=0;c<i.length;c++){a(s.prototype,i[c])}n.User=s;n._q=[];var u=['init','logEvent','logInlinedEvent','setUserId','getUserId','getDeviceId','setSuperProperties','setOptOut','setVersionName','setDomain','setUserProperties','setDeviceId','onload','onEvent','startTimer'];
                for(var l=0;l<u.length;l++){a(n,u[l])}var m=['getTimeOnPreviousPage','getTimeOnPage','isReturningUser'];
                var v=(e.console?e.console.error||e.console.log:null)||function(){};var d=function(e){
                return function(){v('The method rakam.'+e+'() must be called inside rakam.init callback function!');
                }};for(l=0;l<m.length;l++){n[m[l]]=d(m[l])}e.rakam=n})(window,document);

                rakam.init('$writeKey', getUserId(), {
                    apiEndpoint: '$analyticsDomain',
                    includeUtm: true,
                    trackClicks: true,
                    trackForms: true,
                    includeReferrer: true
                }, () => {
                    window.chorusAnalytics_rakamInitialized = true;
                    logPageview();
                });
            }

            // We need to set at least one property, or Rakam UI (app.rakam.com)
            // won’t show the user in the People list
            rakam.setUserProperties({
                resolution: `\${window.screen.width} × \${window.screen.height}`,
            });
            rakam.startTimer(true);
            rakam.setSuperProperties({
                _ip: true,
                _user_agent: true,
                _referrer: document.referrer,
                resolution: window.screen.width + ' × ' + window.screen.height
            }, true);
        })();
    </script>
    ";
  }

}
