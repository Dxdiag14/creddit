<?php
if (!function_exists('enternews_front_page_main_section_1')) :
    /**
     * Banner Slider
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_front_page_main_section_1()
    {
        $enternews_enable_main_slider = enternews_get_option('show_main_news_section');

        ?>


        <?php if ($enternews_enable_main_slider): ?>

        <?php


        $dir = 'ltr';
        if (is_rtl()) {
            $dir = 'rtl';
        }
        $enternews_slider_mode = enternews_get_option('select_main_banner_section_mode');
        $enternews_class = $enternews_slider_mode;

        $enternews_banner_layout_mode = enternews_get_option('select_banner_layout_mode');


        $enternews_main_banner_background = enternews_get_option('main_banner_background_section');

        $enternews_main_banner_url = '';
        if(!empty($enternews_main_banner_background)){

        $enternews_main_banner_background = absint($enternews_main_banner_background);
        $enternews_main_banner_url = wp_get_attachment_url($enternews_main_banner_background);

        $enternews_class .= ' data-bg';

        }


        if ($enternews_banner_layout_mode == 'boxed') {

            $enternews_class .= ' af-main-banner-boxed';

        }
        ?>
        <section class="aft-blocks aft-main-banner-section banner-carousel-1-wrap bg-fixed <?php echo $enternews_class; ?>"
                dir="<?php echo esc_attr($dir); ?>" data-background="<?php echo esc_attr($enternews_main_banner_url); ?>">
            <?php if(is_home() || is_front_page()): ?>
                <div class="exclusive-news">
                    <?php do_action('enternews_action_banner_exclusive_posts'); ?>
                </div>
            <?php endif; ?>


            <?php do_action('enternews_action_banner_featured_section'); ?>



            <?php

            if ($enternews_slider_mode == 'default') {
                enternews_get_block('layout-1', 'main-banner');
            } else {
                enternews_get_block($enternews_slider_mode, 'main-banner');
            }


            ?>



        </section>
    <?php endif; ?>

        <!-- end slider-section -->
        <?php
    }
endif;
add_action('enternews_action_front_page_main_section_1', 'enternews_front_page_main_section_1', 40);