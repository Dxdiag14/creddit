<?php
if (!function_exists('enternews_header_section')) :
    /**
     * Banner Slider
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_header_section()
    {

        $header_layout = enternews_get_option('header_layout');
        ?>

        <header id="masthead" class="header-style1 <?php echo esc_attr($header_layout); ?>">

            <?php

            enternews_get_block('layout-1', 'header');


            ?>


            <div class="header-menu-part">
                <div id="main-navigation-bar" class="bottom-bar">
                    <div class="navigation-section-wrapper">
                        <div class="container-wrapper">
                            <div class="header-middle-part">
                                <div class="navigation-container">
                                    <nav class="main-navigation clearfix">
                                        <?php
                                        $global_show_home_menu = enternews_get_option('global_show_home_menu');
                                        $global_home_menu_icon = enternews_get_option('global_home_menu_icon');
                                        if ($global_show_home_menu == 'yes'):
                                            ?>
                                            <span class="aft-home-icon">
                                        <?php $home_url = home_url(); ?>
                                                <a href="<?php echo esc_url($home_url); ?>">
                                            <i class="<?php echo esc_attr($global_home_menu_icon); ?>"
                                               aria-hidden="true"></i>
                                        </a>
                                    </span>
                                        <?php endif; ?>
                                        <div class="main-navigation-container-items-wrapper">
                                        <span class="toggle-menu" aria-controls="primary-menu"
                                              aria-expanded="false">
                                                <a href="javascript:void(0)" class="aft-void-menu">
                                        <span class="screen-reader-text">
                                            <?php esc_html_e('Primary Menu', 'enternews'); ?>
                                        </span>
                                        <i class="ham"></i>
                                                </a>
                                    </span>
                                        <?php
                                        $global_show_home_menu = enternews_get_option('global_show_home_menu_border');

                                        wp_nav_menu(array(
                                            'theme_location' => 'aft-primary-nav',
                                            'menu_id' => 'primary-menu',
                                            'container' => 'div',
                                            'container_class' => 'menu main-menu menu-desktop ' . $global_show_home_menu,
                                        ));
                                        ?>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                            <div class="header-right-part">
                                <div class="af-search-wrap">
                                    <div class="search-overlay">
                                        <a href="#" title="Search" class="search-icon">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        <div class="af-search-form">
                                            <?php get_search_form(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="popular-tag-custom-link">
                                    <?php
                                    $aft_enable_custom_link = enternews_get_option('show_watch_online_section');
                                    $watch_online_icon = enternews_get_option('watch_online_icon');
                                    if ($aft_enable_custom_link):
                                        $aft_custom_link = enternews_get_option('aft_custom_link');
                                        $aft_custom_title = enternews_get_option('aft_custom_title');

                                        ?>
                                        <div class="custom-menu-link">

                                            <a href="<?php echo esc_url($aft_custom_link); ?>">
                                                <i class="<?php echo esc_attr($watch_online_icon); ?>"
                                                   aria-hidden="true"></i>
                                                <span><?php echo esc_html($aft_custom_title); ?></span>
                                            </a>
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </header>

        <!-- end slider-section -->
        <?php
    }
endif;
add_action('enternews_action_header_section', 'enternews_header_section', 40);