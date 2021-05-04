<?php

$enternews_slider_mode = enternews_get_option('select_main_banner_section_mode');
$enternews_default_carousel_layout = enternews_get_option('select_default_carousel_layout');
if ($enternews_slider_mode != 'none'):
    $wrapper_class = 'aft-main-banner';
    $enternews_show_trending_carousel_section = enternews_get_option('show_trending_carousel_section');

    $enternews_select_trending_carousel_section_mode = enternews_get_option('select_trending_carousel_section_mode');


    if ($enternews_show_trending_carousel_section) {
        if ($enternews_slider_mode == 'default') {
            if (($enternews_select_trending_carousel_section_mode == 'left')) {
                $wrapper_class .= '-trending-' . $enternews_select_trending_carousel_section_mode;
            } elseif (($enternews_select_trending_carousel_section_mode == 'right')) {
                $wrapper_class .= '-trending-' . $enternews_select_trending_carousel_section_mode;
            }
        }
    }


    ?>

    <!-- <div class="banner-carousel-1 af-widget-carousel owl-carousel owl-theme"> -->
    <div class="aft-main-banner-wrapper clearfix aft-add-gaps-between">
        <div class="aft-banner-box-wrapper af-container-row clearfix <?php echo esc_attr($wrapper_class); ?>">
            <?php

            if($enternews_default_carousel_layout == 'title-over-image'){
                $col_class_75 = 'col-2 pad';
                $col_class_25 = 'col-4 pad';
            }else{
                $col_class_75 = 'col-45 pad';
                $col_class_30 = 'col-30 pad';
                $col_class_25 = 'col-25 pad';
            }



            ?>

            <?php

            $enternews_top_left = false;
            $enternews_bottom_right = false;

            if (($enternews_select_trending_carousel_section_mode == 'left')) {
                $enternews_top_left = true;
                $col_class_25 .= ' ' . $enternews_select_trending_carousel_section_mode;
            } elseif (($enternews_select_trending_carousel_section_mode == 'right')) {
                $enternews_bottom_right = true;
                $col_class_25 .= ' ' . $enternews_select_trending_carousel_section_mode;
            }
            ?>



            <div class="af-trending-news-part float-l <?php echo esc_attr($col_class_25); ?> ">
                <?php do_action('enternews_action_banner_trending_posts'); ?>
            </div>

            <div class="aft-carousel-part float-l <?php echo esc_attr($col_class_75); ?>">
                <?php enternews_get_block('carousel', 'banner'); ?>
            </div>
            <div class="float-l af-editors-pick <?php echo esc_attr($col_class_30); ?> ">
                <?php do_action('enternews_action_banner_editors_pick'); ?>
            </div>


        </div>
    </div>
<?php endif; ?>