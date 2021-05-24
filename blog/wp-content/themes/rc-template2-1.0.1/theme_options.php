<?php

function rock_theme_get_customizer_css() {
  ob_start();

  $header_background = get_theme_mod('header_background', '');
  if(!empty($header_background)) {
      $header_background = sanitize_hex_color($header_background);
      ?>
      .site-header {
        background-color: <?php echo $header_background; ?>;
      }
      <?php
  }

  $primary_color = get_theme_mod( 'primary_color', '' );
  if ( ! empty( $primary_color ) ) {
      $primary_color = sanitize_hex_color($primary_color);
      ?>
      .bg-primary {
          background-color: <?php echo $primary_color; ?> !important;
      }
      .widget-title {
        border-bottom: 1px solid <?php echo $primary_color; ?> !important;
      }
      <?php
  }

    $secondary_color = get_theme_mod( 'secondary_color', '' );
    if ( ! empty( $secondary_color ) ) {
        $secondary_color = sanitize_hex_color($secondary_color);
        ?>

        .btn-primary {
            border-color: <?php echo $secondary_color; ?>;
        }
        .post-entry__content__category {
            color: <?php echo $secondary_color; ?>;
        }

        .highlights__content__category,
        .single-header__content__category,
        .btn-primary,
        #comments .btn-secondary,
        .rock-convert-subscribe-form input[type="submit"],
        .rock-convert-download-container input[type="submit"]{
            background-color: <?php echo $secondary_color; ?> !important;
        }

        .btn-primary:hover,
        #comments .btn-secondary {
            background-color: <?php echo $secondary_color; ?> !important;
            border-color: <?php echo $secondary_color; ?> !important;
        }

        .page-item.active .page-link {
            background-color: <?php echo $secondary_color; ?> !important;
            border-color: <?php echo $secondary_color; ?> !important;
        }
        <?php
    }

  $accent_color = get_theme_mod( 'accent_color', '' );
  if ( ! empty( $accent_color ) ) {
      $accent_color = sanitize_hex_color($accent_color);
      ?>
      #footer, #copyright::before {
        background-color: <?php echo $accent_color; ?>;
      }
      <?php
  }

    $link_color = get_theme_mod( 'link_color', '' );
    if ( ! empty( $link_color ) ) {
        $link_color = sanitize_hex_color($link_color);
        ?>
        .post_single__content a,
        .comment-content a,
        .powered-by-rock-convert a,
        .post-entry__content__category a{
            color: <?php echo $link_color; ?> !important;
        }
        .rock-widget-cta {
            background-color: <?php echo $link_color; ?>;
        }

        <?php
    }

  $css = ob_get_clean();
  return $css;
}
