<?php
if (!function_exists('enternews_single_header')) :
    /**
     * Banner Slider
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_single_header()
    {
        $single_post_featured_image_view = enternews_get_option('single_post_featured_image_view');
        $show_featured_image = enternews_get_option('single_show_featured_image');

        global $post;
        $post_id = $post->ID;

        $wrapper_class = '';
        if (($show_featured_image == false) || (has_post_thumbnail($post_id) == false)) {
            $wrapper_class = 'aft-no-featured-image';

        }


        ?>

        <header class="entry-header pos-rel <?php echo esc_attr($wrapper_class); ?>">
            <div class="container-wrapper ">
                <div class="read-details af-container-block-wrapper">

                    <?php
                    $enternews_has_featured_image = false;
                    $col_class = 'col-1';

                    if ($single_post_featured_image_view == 'within-content' || $single_post_featured_image_view == 'full') {
                        $col_class = 'col-1';
                    } else {
                        if (has_post_thumbnail($post_id)) {
                            $enternews_has_featured_image = true;
                            $col_class = 'col-2';
                        }
                    }

                    ?>



                    <?php

                    if ($enternews_has_featured_image):
                        $single_post_featured_image_view = enternews_get_option('single_post_featured_image_view');
                        if ($single_post_featured_image_view == 'default'):
                            ?>
                            <div class="enternews-entry-featured-image-wrap float-l <?php echo esc_attr($col_class); ?>">
                                <?php do_action('enternews_action_single_featured_image'); ?>
                            </div>
                        <?php endif;
                    endif; ?>

                    <div class="enternews-entry-header-details-wrap <?php echo esc_attr($col_class); ?>">
                        <?php do_action('enternews_action_single_entry_details'); ?>
                    </div>


                </div>

            </div>


            <?php

            if ($single_post_featured_image_view == 'full') {
                do_action('enternews_action_single_featured_image');


            }
            ?>

        </header><!-- .entry-header -->

        <!-- end slider-section -->
        <?php
    }
endif;
add_action('enternews_action_single_header', 'enternews_single_header', 40);

add_action('enternews_action_single_entry_details', 'enternews_single_entry_details', 40);

function enternews_single_entry_details()
{
    global $post;
    $yt_url = 'https://www.youtube.com/watch?v=fQshXWKNOYU';
    //$yt_url = '';
    $single_post_featured_image_view = enternews_get_option('single_post_featured_image_view');
    $col_class = '';
    if ($single_post_featured_image_view == 'full') {
        $col_class = 'af-category-inside-img';
    }

    if (!empty($yt_url)) {
        $col_class .= ' af-have-yt-link';
    }

    ?>
    <div class="entry-header-details <?php echo esc_attr($col_class); ?>">
        <?php if ('post' === get_post_type()) : ?>
            <div class="read-categories af-category-inside-img">
                <?php enternews_post_categories(); ?>

            </div>
        <?php endif; ?>

        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        <div class="post-meta-share-wrapper">
            <div class="post-meta-detail">
                                    <span class="min-read-post-format">
                                        <?php echo enternews_post_format($post->ID); ?>
                                    </span>
                <span class="entry-meta">
                                        <?php enternews_post_item_publish_author(); ?>
                                    </span>
                <?php enternews_post_item_publish_date(); ?>
                <?php enternews_count_content_words($post->ID); ?>
            </div>
            <?php
            enternews_single_post_social_share_icons($post->ID);
            ?>
        </div>


    </div>
    <?php

}


add_action('enternews_action_single_featured_image', 'enternews_single_featured_image', 40);

function enternews_single_featured_image()
{
    global $post;
    $post_id = $post->ID;
    $show_featured_image = enternews_get_option('single_show_featured_image');


    if ($show_featured_image):
        ?>
        <div class="read-img pos-rel">
            <?php enternews_post_thumbnail(); ?>
            <span class="aft-image-caption-wrap">

                        <?php
                        if (has_post_thumbnail($post_id)):
                            if ($aft_image_caption = get_post(get_post_thumbnail_id())->post_excerpt): ?>
                                <span class="aft-image-caption">
                                    <p>
                                        <?php echo esc_html($aft_image_caption); ?>
                                    </p>
                                </span>
                            <?php
                            endif;
                        endif;
                        ?>
                    </span>

        </div>
    <?php endif;

}