<?php
/**
 * Full block part for displaying page content in page.php
 *
 * @package EnterNews
 */
?>

<?php

$enternews_slider_category = enternews_get_option('select_slider_news_category');
$enternews_number_of_slides = enternews_get_option('number_of_slides');
$enternews_slider_mode = enternews_get_option('select_main_banner_section_mode');
$enternews_main_banner_section_background = enternews_get_option('select_main_banner_section_background');

$enternews_cat_class = 'af-category-inside-img';




$enternews_column = enternews_get_option('select_default_carousel_column');

if ($enternews_slider_mode == 'layout-8') {
    $enternews_slidesToShow = 2;
    $enternews_slide_to_scroll = 2;
    $enternews_centerMode = false;
    $enternews_break_point_1_slidesToShow = 2;
    $enternews_break_point_1_slidesToScroll = 2;
    $enternews_break_point_2_slidesToShow = 1;
    $enternews_break_point_2_slidesToScroll = 1;
    $enternews_break_point_3_slidesToShow = 1;
    $enternews_break_point_3_slidesToScroll = 1;
} elseif ($enternews_slider_mode == 'layout-9') {
    $enternews_slidesToShow = 3;
    $enternews_slide_to_scroll = 3;
    $enternews_centerMode = false;
    $enternews_break_point_1_slidesToShow = 3;
    $enternews_break_point_1_slidesToScroll = 3;
    $enternews_break_point_2_slidesToShow = 1;
    $enternews_break_point_2_slidesToScroll = 1;
    $enternews_break_point_3_slidesToShow = 1;
    $enternews_break_point_3_slidesToScroll = 1;
} else {
    $enternews_slidesToShow = 1;
    $enternews_slide_to_scroll = 1;
    $enternews_centerMode = false;
    $enternews_break_point_1_slidesToShow = 1;
    $enternews_break_point_1_slidesToScroll = 1;
    $enternews_break_point_2_slidesToShow = 1;
    $enternews_break_point_2_slidesToScroll = 1;
    $enternews_break_point_3_slidesToShow = 1;
    $enternews_break_point_3_slidesToScroll = 1;
}


$enternews_carousel_args = array(
    'slidesToShow' => $enternews_slidesToShow,
    'autoplaySpeed' => 8000,
    'slidesToScroll' => $enternews_slide_to_scroll,
    'centerMode' => $enternews_centerMode,
    'responsive' => array(
        array(
            'breakpoint' => 1024,
            'settings' => array(
                'slidesToShow' => $enternews_break_point_2_slidesToShow,
                'slidesToScroll' => $enternews_break_point_3_slidesToScroll,
                'infinite' => true
            ),
        ),
        array(
            'breakpoint' => 769,
            'settings' => array(
                'slidesToShow' => $enternews_break_point_2_slidesToShow,
                'slidesToScroll' => $enternews_break_point_2_slidesToScroll,
                'infinite' => true,
            ),
        ),
        array(
            'breakpoint' => 480,
            'settings' => array(
                'slidesToShow' => $enternews_break_point_3_slidesToShow,
                'slidesToScroll' => $enternews_break_point_3_slidesToScroll,
                'infinite' => true
            ),
        ),
    ),
);

$enternews_carousel_args_encoded = wp_json_encode($enternews_carousel_args);


?>

<?php
$color_class = 'category-color-1';
if (absint($enternews_slider_category) > 0) {
    $color_id = "category_color_" . $enternews_slider_category;
    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option($color_id);
    $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : 'category-color-1';
}
$section_title = enternews_get_option('main_banner_section_label');
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

<div class="af-banner-carousel-1 af-widget-carousel slick-wrapper banner-carousel-slider title-over-image <?php echo esc_attr($enternews_main_banner_section_background); ?>"
     data-slick='<?php echo wp_kses_post($enternews_carousel_args_encoded); ?>'>
    <?php
    $slider_posts = enternews_get_posts($enternews_number_of_slides, $enternews_slider_category);
    if ($slider_posts->have_posts()) :
        while ($slider_posts->have_posts()) : $slider_posts->the_post();

            global $post;
            $url = enternews_get_freatured_image_url($post->ID, 'enternews-medium');
            $thumbnail_size = 'enternews-medium';


            ?>
            <div class="slick-item">
                <div class="read-single color-pad pos-rel">
                    <div class="read-img pos-rel read-img read-bg-img">
                        <a class="aft-slide-items" href="<?php the_permalink(); ?>"></a>
                        <?php if (!empty($url)): ?>
                            <?php the_post_thumbnail($thumbnail_size); ?>
                        <?php endif; ?>

                        <div class="min-read-post-format">
                            <?php echo enternews_post_format($post->ID); ?>
                            <span class="min-read-item">
                                <?php enternews_count_content_words($post->ID); ?>
                            </span>
                        </div>
                    </div>
                    <div class="read-details color-tp-pad">
                        <div class="read-categories <?php echo esc_attr($enternews_cat_class); ?>">
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
        endwhile;
    endif;
    wp_reset_postdata();
    ?>
</div>