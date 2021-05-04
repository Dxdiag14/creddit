<?php
    /**
     * List block part for displaying latest posts in footer.php
     *
     * @package EnterNews
     */
    
    $enternews_latest_posts_title = enternews_get_option('frontpage_latest_posts_section_title');
    $enternews_latest_posts_subtitle = enternews_get_option('frontpage_latest_posts_section_subtitle');
    $number_of_posts = enternews_get_option('number_of_frontpage_latest_posts');
    
    $all_posts = enternews_get_posts($number_of_posts);


?>
<div class="af-main-banner-latest-posts grid-layout">
    <div class="container-wrapper">
    <div class="af-container-block-wrapper pad-20">
            <div class="widget-title-section">
                <?php if (!empty($enternews_latest_posts_title)): ?>
                    <h4 class="widget-title header-after1">
                            <span class="header-after">
                                <?php echo esc_html($enternews_latest_posts_title); ?>
                            </span>
                    </h4>
                <?php endif; ?>

            </div>
            <div class="af-container-row clearfix">
                <?php
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post;
                            $url = enternews_get_freatured_image_url($post->ID, 'medium');
                            $thumbnail_size = 'medium';
                            
                            ?>
                            <div class="col-4 pad float-l" data-mh="you-may-have-missed">
                                <div class="read-single color-pad">
                                    <div class="read-img pos-rel read-bg-img">
                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                        <div class="min-read-post-format">
                                            <?php echo enternews_post_format($post->ID); ?>
                                            <span class="min-read-item">
                                <?php enternews_count_content_words($post->ID); ?>
                            </span>
                                        </div>
                                        <a href="<?php the_permalink(); ?>"></a>
                                        <div class="read-categories af-category-inside-img">

                                            <?php enternews_post_categories(); ?>
                                        </div>
                                    </div>
                                    <div class="read-details color-tp-pad">


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
                        endwhile; ?>
                    <?php
                    endif;
                    wp_reset_postdata();
                ?>
            </div>
    </div>
    </div>
</div>
