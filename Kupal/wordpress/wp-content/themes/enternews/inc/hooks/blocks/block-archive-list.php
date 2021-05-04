<?php
/**
 * List block part for displaying page content in page.php
 *
 * @package EnterNews
 */

$excerpt_length = 20;
global $post;
$url = enternews_get_freatured_image_url($post->ID, 'enternews-medium');
$thumbnail_size = 'enternews-medium';
$show_excerpt = 'true';
$col_class = 'col-ten';
?>
<div class="archive-list-post list-style">
    <div class="read-single color-pad">

        <div class="read-img pos-rel col-2 float-l read-bg-img af-sec-list-img">
            <?php if (!empty($url)): ?>
                <?php the_post_thumbnail($thumbnail_size); ?>
            <?php endif; ?>
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


        <div class="read-details col-2 float-l pad af-sec-list-txt color-tp-pad">

            <div class="read-title">
                <h4>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h4>
            </div>
            <div class="entry-meta">
                <?php enternews_post_item_meta(); ?>
            </div>
                <div class="read-descprition full-item-discription">
                    <div class="post-description">
                        <?php echo wp_kses_post(enternews_get_excerpt(15, null, $post->ID)); ?>
                    </div>
                </div>
        </div>
    </div>
    <?php
    wp_link_pages(array(
        'before' => '<div class="page-links">' . esc_html__('Pages:', 'enternews'),
        'after' => '</div>',
    ));
    ?>
</div>









