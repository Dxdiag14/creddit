<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package EnterNews
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if (function_exists('wp_body_open')) {
    wp_body_open();
} else {
    do_action('wp_body_open');
} ?>
<?php
$enable_preloader = enternews_get_option('enable_site_preloader');
if (1 == $enable_preloader):
    ?>
    <div id="af-preloader">
        <div class="spinner">
            <div class="af-preloader-bar"></div>
        </div>
    </div>
<?php endif; ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'enternews'); ?></a>


    <?php

    do_action('enternews_action_header_section');

    ?>

    <?php

    do_action('enternews_action_front_page_main_section');

    ?>

    <?php

    if (1 == enternews_get_option('show_featured_news_section')) {
        if(is_front_page() || is_home()){
            enternews_get_block('featured');
        }

    }

    ?>
       <?php do_action('enternews_action_get_breadcrumb'); ?>



    <div id="content" class="container-wrapper ">
