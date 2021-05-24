<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package rockcontent
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$container = get_theme_mod('rock_container_type');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109604992-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-109604992-1');
	</script>


    
    <script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/3b1676c1-315c-4043-a035-c41730abcd1b-loader.js" ></script>


    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
	<meta name="google-site-verification" content="35CFvfIJp42KgNQuig9TB4l00nNjrs8TpmC-aQHrRAc" />

</head>

<body <?php body_class(); ?>>

<div class="site" id="page">

    <?php get_template_part('global-templates/head'); ?>
