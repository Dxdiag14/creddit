<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
 * <?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package EnterNews
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses enternews_header_style()
 */
function enternews_custom_header_setup()
{
    add_theme_support('custom-header', apply_filters('enternews_custom_header_args', array(
        'default-image' => '',
        'default-text-color' => '000000',
        'width' => 1440,
        'height' => 400,
        'flex-height' => true,
        'wp-head-callback' => 'enternews_header_style',
    )));
}

add_action('after_setup_theme', 'enternews_custom_header_setup');

if (!function_exists('enternews_header_style')) :
    /**
     * Styles the header image and text displayed on the blog.
     *
     * @see enternews_custom_header_setup().
     */
    function enternews_header_style()
    {
        $header_image_tint_overlay = enternews_get_option('disable_header_image_tint_overlay');
        $site_title_font_size = enternews_get_option('site_title_font_size');
        $header_text_color = get_header_textcolor();
        $main_banner_section_background_image = enternews_get_option('main_banner_section_background_image');

        // If we get this far, we have custom styles. Let's do this.
        ?>
        <style type="text/css">
            <?php

            if($header_image_tint_overlay):
                ?>
            body .header-style1 .top-header.data-bg:before,
            body .header-style1 .main-header.data-bg:before {
                background: rgba(0, 0, 0, 0);
            }

            <?php
            endif;
            // Has the text been hidden?
            if ( ! display_header_text() ) :
            ?>
            .site-title,
            .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
                display: none;
            }

            <?php
                // If the user has set a custom color for the text use that.
                else :
            ?>
            body .site-title a,
            .site-header .site-branding .site-title a:visited,
            .site-header .site-branding .site-title a:hover,
            .site-description {
                color: #<?php echo esc_attr( $header_text_color ); ?>;
            }

            .header-layout-3 .site-header .site-branding .site-title,
            .site-branding .site-title {
                font-size: <?php echo esc_attr( $site_title_font_size ); ?>px;
            }

            @media only screen and (max-width: 640px) {
                .site-branding .site-title {
                    font-size: 40px;

                }
            }

            @media only screen and (max-width: 375px) {
                .site-branding .site-title {
                    font-size: 32px;

                }
            }

            <?php endif; ?>

            <?php

    if (!empty($main_banner_section_background_image)):
            $main_banner_section_background_image = absint($main_banner_section_background_image);
            $main_banner_section_background_image_url = wp_get_attachment_image_src($main_banner_section_background_image, 'full');


        ?>
            body.aft-light-mode .aft-blocks.banner-carousel-1-wrap ,
            body.aft-dark-mode .aft-blocks.banner-carousel-1-wrap ,
            body.aft-default-mode .aft-blocks.banner-carousel-1-wrap {
                background-image: url( <?php echo $main_banner_section_background_image_url[0]; ?>);

            }

            <?php endif; ?>


        </style>
        <?php


    }
endif;