<?php
if (!function_exists('enternews_banner_trending_posts')):
    /**
     * Ticker Slider
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_banner_trending_posts()
    {
        $color_class = 'category-color-1';
        ?>
        <?php


        $enternews_number_of_trending_slides = enternews_get_option('number_of_trending_slides');
        $enternews_trending_section_background = enternews_get_option('select_trending_section_background');



        $enternews_select_trending_post_filterby = enternews_get_option('select_trending_post_filterby');
        $enternews_trending_category = 0;
        if($enternews_select_trending_post_filterby == 'tag'){
            $enternews_trending_category = enternews_get_option('select_trending_post_tag');
        }elseif ($enternews_select_trending_post_filterby == 'cat'){
            $enternews_trending_category = enternews_get_option('select_trending_carousel_category');
        }

        $carousel_class = 'af-main-banner-trending-posts-vertical-carousel aft-slick-vertical-carousel';


        $dir = 'ltr';
        if (is_rtl()) {
            $dir = 'rtl';
        }
        ?>

        <?php
        $color_class = 'category-color-1';
        if(absint($enternews_trending_category) > 0){
            $color_id = "category_color_" . $enternews_trending_category;
            // retrieve the existing value(s) for this meta field. This returns an array
            $term_meta = get_option($color_id);
            $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : 'category-color-1';
        }
        $section_title = enternews_get_option('trending_slider_title');
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

        <div class="af-main-banner-trending-posts trending-posts <?php echo esc_attr($enternews_trending_section_background); ?>" dir="<?php echo esc_attr($dir); ?>">
            <div class="section-wrapper">
                <div class="af-double-column list-style clearfix <?php echo esc_attr($carousel_class); ?>">
                    <?php

                    $count = 1;
                    $trending_posts = enternews_get_posts($enternews_number_of_trending_slides, $enternews_trending_category, $enternews_select_trending_post_filterby);
                    if ($trending_posts->have_posts()) :
                        while ($trending_posts->have_posts()) :
                            $trending_posts->the_post();
                            global $post;
                            $url = enternews_get_freatured_image_url($post->ID, 'thumbnail');
                            $thumbnail_size = 'thumbnail';
                            ?>

                            <div class="col-1" data-mh="af-feat-list">
                                <div class="read-single color-pad">
                                    <div class="read-img pos-rel col-40 float-l read-bg-img">
                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                        <a href="<?php the_permalink(); ?>"></a>
                                        <div class="trending-post-items pos-rel col-40 float-l show-inside-image">
                                            <span class="trending-no">
                                                <?php echo sprintf('%s', esc_html($count)); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="read-details col-60 float-l pad color-tp-pad">
                                        <div class="read-categories">
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
                            $count++;
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>

        <!-- Trending line END -->
        <?php

    }
endif;

add_action('enternews_action_banner_trending_posts', 'enternews_banner_trending_posts', 10);