<?php
if (!function_exists('enternews_front_page_widgets_section')) :
    /**
     *
     * @param null
     * @return null
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_front_page_widgets_section()
    {
        ?>
        <!-- Main Content section -->
        <?php
        $frontpage_layout = enternews_get_option('frontpage_content_alignment');


//            if (is_active_sidebar('home-content-widgets') && is_active_sidebar('home-sidebar-1-widgets') ) {
        ?>
        <section class="section-block-upper">
            <div class="af-container-block-wrapper clearfix">

                <div id="primary" class="content-area ">
                    <main id="main" class="site-main">
                        <?php dynamic_sidebar('home-content-widgets'); ?>
                    </main>
                </div>

                <?php
                $frontpage_layout = enternews_get_option('frontpage_content_alignment');

                if ($frontpage_layout != 'frontpage-layout-3'):


                $sticky_sidebar = enternews_get_option('frontpage_sticky_sidebar');


                $sidebar_class = '';
                if ($sticky_sidebar) {
                    $sidebar_class .= ' aft-sticky-sidebar';
                }

                if (is_active_sidebar('home-sidebar-1-widgets')):
                    ?>

                    <?php
                    $sticky_sidebar_class = '';
                    $sticky_sidebar = enternews_get_option('frontpage_sticky_sidebar');
                    if($sticky_sidebar){
                        $sticky_sidebar_class = enternews_get_option('frontpage_sticky_sidebar_position');

                    }
                    ?>
                    <div id="secondary" class="sidebar-area <?php echo esc_attr($sticky_sidebar_class); ?>">
                            <aside class="widget-area color-pad">
                                <?php dynamic_sidebar('home-sidebar-1-widgets'); ?>
                            </aside>
                    </div>
                <?php endif; ?>
                <?php endif; ?>

            </div>
        </section>
        <?php //}


    }
endif;
add_action('enternews_front_page_section', 'enternews_front_page_widgets_section', 50);