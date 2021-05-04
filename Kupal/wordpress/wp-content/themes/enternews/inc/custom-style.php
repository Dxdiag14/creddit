<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Customizer
 *
 * @class   enternews
 */

if (!function_exists('enternews_custom_style')) {

    function enternews_custom_style()
    {

        global $enternews_google_fonts;
        $background_color = get_background_color();
        $background_color = '#' . $background_color;

        $secondary_color = enternews_get_option('secondary_color');
        $primary_font = $enternews_google_fonts[enternews_get_option('primary_font')];
        $secondary_font = $enternews_google_fonts[enternews_get_option('secondary_font')];
        $tertiary_font = $enternews_google_fonts[enternews_get_option('tertiary_font')];

        ob_start();
        ?>

        <?php if (!empty($primary_font)): ?>

        body,
        button,
        input,
        select,
        optgroup,
        textarea,
        p,
        .min-read,
        .enternews-widget.widget ul.cat-links li a
        {
        font-family: <?php enternews_esc_custom_style($primary_font); ?>;
        }
    <?php endif; ?>

        <?php if (!empty($secondary_font)): ?>
        .enternews-widget.widget ul.nav-tabs li a,
        .nav-tabs>li,
        .main-navigation ul li a,
        body .post-excerpt,
        .sidebar-area .social-widget-menu ul li a .screen-reader-text,
        .site-title, h1, h2, h3, h4, h5, h6 {
        font-family: <?php enternews_esc_custom_style($secondary_font); ?>;
        }
    <?php endif; ?>

        <?php if (!empty($tertiary_font)): ?>
        .enternews-widget.widget .widget-title + ul li a,
        h4.af-author-display-name,
        .exclusive-posts .marquee a .circle-title-texts h4,
        .read-title h4 {
        font-family: <?php enternews_esc_custom_style($tertiary_font); ?>;
        }

    <?php endif; ?>


        <?php if (!empty($background_color)): ?>
        #af-preloader{
        background-color: <?php enternews_esc_custom_style($background_color) ?>
        }
    <?php endif; ?>

        <?php if (!empty($secondary_color)): ?>

        body.aft-default-mode .aft-widget-background-secondary-background.widget.enternews_youtube_video_slider_widget .widget-block .af-widget-body,
        body .post-excerpt::before,
        .enternews-widget .woocommerce-product-search button[type="submit"],
        body .enternews_author_info_widget.aft-widget-background-secondary-background.widget .widget-block,
        body .aft-widget-background-secondary-background.widget .widget-block .read-single,
        .secondary-sidebar-background #secondary,
        body .enternews_tabbed_posts_widget .nav-tabs > li > a.active,
        body .enternews_tabbed_posts_widget .nav-tabs > li > a.active:hover,
        body.aft-default-mode .enternews_posts_slider_widget.aft-widget-background-secondary-background .widget-block,
        body.aft-dark-mode .enternews_posts_slider_widget.aft-widget-background-secondary-background .widget-block,

        .aft-widget-background-secondary-background .social-widget-menu ul li a,
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="facebook.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="dribble.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="vk.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="twitter.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="linkedin.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="instagram.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="youtube.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="vimeo.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="pinterest.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="tumblr.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="wordpress.org"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="whatsapp.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="reddit.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="t.me"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="ok.ru"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="wechat.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="weibo.com"],
        .aft-widget-background-secondary-background .social-widget-menu ul li a[href*="github.com"],

        body.aft-default-mode .aft-main-banner-section .af-banner-carousel-1.secondary-background,
        body.aft-dark-mode .aft-main-banner-section .af-banner-carousel-1.secondary-background,

        body.aft-default-mode .aft-main-banner-section .af-editors-pick .secondary-background .af-sec-post .read-single,
        body.aft-dark-mode .aft-main-banner-section .af-editors-pick .secondary-background .af-sec-post .read-single,

        body.aft-default-mode .aft-main-banner-section .af-trending-news-part .trending-posts.secondary-background .read-single,
        body.aft-dark-mode .aft-main-banner-section .af-trending-news-part .trending-posts.secondary-background .read-single,

        body.aft-dark-mode.alternative-sidebar-background #secondary .widget-title .header-after::before,
        body .widget-title .header-after:before,
        body .widget-title .category-color-1.header-after::before,
        body .header-style1 .header-right-part .popular-tag-custom-link > div.custom-menu-link a > span,
        body .aft-home-icon,
        body.aft-dark-mode input[type="submit"],
        body .entry-header-details .af-post-format i:after,
        body.aft-default-mode .enternews-pagination .nav-links .page-numbers.current,
        body #scroll-up,
        body input[type="reset"],
        body input[type="submit"],
        body input[type="button"],
        body .inner-suscribe input[type=submit],
        body .widget-title .header-after:after,
        body .widget-title .category-color-1.header-after:after,
        body.aft-default-mode .inner-suscribe input[type=submit],
        body.aft-default-mode .enternews_tabbed_posts_widget .nav-tabs > li > a.active:hover,
        body.aft-default-mode .enternews_tabbed_posts_widget .nav-tabs > li > a.active,
        body .aft-main-banner-section .aft-trending-latest-popular .nav-tabs>li.active,
        body .header-style1 .header-right-part > div.custom-menu-link > a,
        body .aft-popular-taxonomies-lists ul li a span.tag-count,
        body .aft-widget-background-secondary-background.widget .widget-block .read-single
        {
        background-color: <?php enternews_esc_custom_style($secondary_color); ?>;
        }
        body.aft-dark-mode:not(.alternative-sidebar-background) #secondary .color-pad .wp-calendar-nav span a:not(.enternews-categories),
        body .enternews-pagination .nav-links .page-numbers:not(.current),
        body.aft-dark-mode .site-footer #wp-calendar tfoot tr td a,
        body.aft-dark-mode #wp-calendar tfoot td a,
        body.aft-default-mode .site-footer #wp-calendar tfoot tr td a,
        body.aft-default-mode #wp-calendar tfoot td a,
        body.aft-dark-mode #wp-calendar tfoot td a,
        body.aft-default-mode .wp-calendar-nav span a,
        body.aft-default-mode .wp-calendar-nav span a:visited,
        body.aft-dark-mode .wp-calendar-nav span a,
        body.aft-dark-mode #wp-calendar tbody td a,
        body.aft-dark-mode #wp-calendar tbody td#today,
        body.aft-default-mode #wp-calendar tbody td#today,
        body.aft-default-mode #wp-calendar tbody td a,
        body.aft-default-mode  .sticky .read-title h4 a:before {
        color: <?php enternews_esc_custom_style($secondary_color); ?>;
        }

        body .post-excerpt {
        border-left-color: <?php enternews_esc_custom_style($secondary_color); ?>;
        }

        body.aft-dark-mode .read-img .min-read-post-comment:after,
        body.aft-default-mode .read-img .min-read-post-comment:after{
        border-top-color: <?php enternews_esc_custom_style($secondary_color); ?>;
        }

        body .af-fancy-spinner .af-ring:nth-child(1){
        border-right-color: <?php enternews_esc_custom_style($secondary_color); ?>;
        }
        body.aft-dark-mode .enternews-pagination .nav-links .page-numbers.current {
        background-color: <?php enternews_esc_custom_style($secondary_color); ?>;
        }
        body.aft-dark-mode .enternews-pagination .nav-links .page-numbers,
        body.aft-default-mode .enternews-pagination .nav-links .page-numbers,
        body .af-sp-wave:after,
        body .bottom-bar{
        border-color: <?php enternews_esc_custom_style($secondary_color); ?>;
        }

    <?php endif; ?>

        }
        <?php
        return ob_get_clean();
    }
}