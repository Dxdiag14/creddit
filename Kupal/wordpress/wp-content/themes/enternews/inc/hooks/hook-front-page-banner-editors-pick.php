<?php
if (!function_exists('enternews_banner_editors_pick')):
    /**
     * Ticker Slider
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_banner_editors_pick()
    {

        $color_class = 'category-color-1';
        ?>
        <?php


        $enternews_editors_pick_category = enternews_get_option('select_editors_pick_category');
        $enternews_ediotors_picks_section_background = enternews_get_option('select_editors_picks_section_background');

        $enternews_slider_mode = enternews_get_option('select_main_banner_section_mode');
        $enternews_slider_layout = enternews_get_option('select_main_banner_section_layout');
        $dir = 'ltr';
        if (is_rtl()) {
            $dir = 'rtl';
        }
        ?>

        <?php
        $color_class = 'category-color-1';
        if (absint($enternews_editors_pick_category) > 0) {
            $color_id = "category_color_" . $enternews_editors_pick_category;
            // retrieve the existing value(s) for this meta field. This returns an array
            $term_meta = get_option($color_id);
            $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : 'category-color-1';
        }
        $section_title = enternews_get_option('editors_pick_section_title');
        ?>

        <?php if (!empty($section_title)): ?>
        <div class="em-title-subtitle-wrap">
            <?php if (!empty($section_title)): ?>
                <h4 class="widget-title header-after1">
                        <span class="header-after <?php echo esc_attr($color_class); ?>">
                            <?php echo esc_html($section_title);  ?>
                        </span>
                </h4>
            <?php endif; ?>
        </div>
    <?php endif; ?>

        <div class="af-main-banner-featured-posts featured-posts <?php echo esc_attr($enternews_ediotors_picks_section_background); ?>" dir="<?php echo esc_attr($dir); ?>">

            <div class="section-wrapper">
                <div class="small-gird-style af-container-row">
                    <?php

                    //$enternews_number_of_editors_pick_news = enternews_get_option('number_of_editors_pick_news');
                    $enternews_number_of_editors_pick_news = 2;

                    if (($enternews_slider_mode == 'layout-6')) {
                        $col_class = 'col-2 pad';
                        if($enternews_slider_layout == 'wide') {
                            $enternews_number_of_editors_pick_news = 6;
                        }else{
                            $enternews_number_of_editors_pick_news = 4;
                        }

                    } else {
                        $col_class = 'col-1 pad';
                        if($enternews_slider_layout == 'wide'){

                            if($enternews_slider_mode == 'default' || $enternews_slider_mode == 'layout-2' || $enternews_slider_mode == 'layout-3'){
                                $enternews_number_of_editors_pick_news = 3;
                            }


                            if($enternews_slider_mode == 'layout-7'){
                                $enternews_number_of_editors_pick_news = 4;
                            }

                        }
                    }

                    $featured_posts = enternews_get_posts($enternews_number_of_editors_pick_news, $enternews_editors_pick_category);
                    if ($featured_posts->have_posts()) :
                        $enternews_count = 1;
                        while ($featured_posts->have_posts()) :
                            $featured_posts->the_post();
                            global $post;
                            $url = enternews_get_freatured_image_url($post->ID, 'enternews-thumbnail');
                            $thumbnail_size = 'enternews-thumbnail';
                            if($enternews_slider_mode == 'default' || $enternews_slider_mode == 'layout-2' || $enternews_slider_mode == 'layout-3'){
                                if($enternews_count > 1){
                                    $url = enternews_get_freatured_image_url($post->ID, 'medium');
                                    $thumbnail_size = 'medium';
                                }
                            }
                            ?>
                            <div class="float-l big-grid af-sec-post <?php echo esc_attr($col_class); ?>">
                                <div class="read-single pos-rel">

                                    <div class="read-img pos-rel read-bg-img">
                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                        <a class="aft-slide-items" href="<?php the_permalink(); ?>"></a>

                                        <div class="min-read-post-format">
                                            <?php echo enternews_post_format($post->ID); ?>
                                            <span class="min-read-item">
                                                <?php enternews_count_content_words($post->ID); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="read-details">

                                        <div class="read-categories af-category-inside-img">

                                            <?php enternews_post_categories(); ?>
                                        </div>

                                        <div class="read-title">
                                            <h4>
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h4>
                                        </div>


                                        <div class="entry-meta">
                                            <?php enternews_post_item_meta(); ?>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <?php
                            $enternews_count++;
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
        <!-- Editors Pick line END -->
        <?php

    }
endif;

add_action('enternews_action_banner_editors_pick', 'enternews_banner_editors_pick', 10);

if (!function_exists('enternews_banner_editors_pick_extended')):
    /**
     * Ticker Slider
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_banner_editors_pick_extended()
    {

        $color_class = 'category-color-1';
        ?>
        <?php


        $enternews_editors_pick_category = enternews_get_option('select_editors_pick_category');
        $enternews_ediotors_picks_section_background = enternews_get_option('select_editors_picks_section_background');

        $enternews_slider_mode = enternews_get_option('select_main_banner_section_mode');
        $dir = 'ltr';
        if (is_rtl()) {
            $dir = 'rtl';
        }
        ?>

        <?php
        $color_class = 'category-color-1';
        if (absint($enternews_editors_pick_category) > 0) {
            $color_id = "category_color_" . $enternews_editors_pick_category;
            // retrieve the existing value(s) for this meta field. This returns an array
            $term_meta = get_option($color_id);
            $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : 'category-color-1';
        }
        $section_title = enternews_get_option('editors_pick_section_title');
        ?>

        <?php if (!empty($section_title)): ?>
        <div class="em-title-subtitle-wrap">
            <?php if (!empty($section_title)): ?>
                <h4 class="widget-title header-after1">
                        <span class="header-after <?php echo esc_attr($color_class); ?>">
                            <?php echo esc_html($section_title);  ?>
                        </span>
                </h4>
            <?php endif; ?>
        </div>
    <?php endif; ?>

        <div class="af-main-banner-featured-posts featured-posts <?php echo esc_attr($enternews_ediotors_picks_section_background); ?>" dir="<?php echo esc_attr($dir); ?>">

            <div class="section-wrapper">
                <div class="small-gird-style af-container-row">
                    <?php

                    $col_class = 'col-1 pad';
                    $enternews_number_of_editors_pick_news = 1;


                    $featured_posts = enternews_get_posts($enternews_number_of_editors_pick_news, $enternews_editors_pick_category);
                    if ($featured_posts->have_posts()) :
                        $enternews_count = 1;

                        while ($featured_posts->have_posts()) :
                            $featured_posts->the_post();
                            global $post;
                            $rel_class = '';
                            if ($enternews_count == 1){
                                $rel_class = 'pos-rel';

                            }
                            $url = enternews_get_freatured_image_url($post->ID, 'enternews-medium');
                            $thumbnail_size = 'enternews-medium';
                            ?>
                            <div class="float-l big-grid af-sec-post title-under-image <?php echo esc_attr($col_class); ?>">
                                <div class="read-single <?php echo esc_attr($rel_class); ?>">

                                    <div class="read-img pos-rel read-bg-img">
                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                        <a class="aft-slide-items" href="<?php the_permalink(); ?>"></a>
                                        <div class="min-read-post-format">
                                            <?php echo enternews_post_format($post->ID); ?>
                                            <span class="min-read-item">
                                                <?php enternews_count_content_words($post->ID); ?>
                                            </span>
                                        </div>
                                        <div class="read-categories af-category-inside-img">

                                            <?php enternews_post_categories(); ?>
                                        </div>
                                    </div>

                                    <div class="read-details">

                                        <div class="read-title">
                                            <h4>
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h4>
                                        </div>

                                        <div class="entry-meta">
                                            <?php enternews_post_item_meta(); ?>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <?php
                            $enternews_count++;
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
        <!-- Editors Pick line END -->
        <?php

    }
endif;

add_action('enternews_action_banner_editors_pick_extended', 'enternews_banner_editors_pick_extended', 10);