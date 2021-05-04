<?php
/**
 * List block part for displaying page content in page.php
 *
 * @package EnterNews
 */

$excerpt_length = 20;
global $post;
$url = enternews_get_freatured_image_url($post->ID, 'medium');
$thumbnail_size = 'medium';
$show_excerpt = 'true';

$class = '';
$background = '';
if ($url != '') {
    $class = 'data-bg read-img read-bg-img data-bg-categorised';
    $background = $url;
}
?>

<div class="archive-grid-post">
    <div class="read-single color-pad">
        <div class="read-img pos-rel read-bg-img">
            <?php the_post_thumbnail($thumbnail_size); ?>


            <a href="<?php the_permalink(); ?>"></a>
            <div class="min-read-post-format">
                <?php enternews_post_format($post->ID); ?>
                <span class="min-read-item">
                  <?php enternews_count_content_words($post->ID); ?>
                </span>
            </div>
            <div class="read-categories af-category-inside-img">

                <?php enternews_post_categories(); ?>
            </div>
        </div>
        <div class="read-details color-tp-pad no-color-pad">


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

    <?php
    wp_link_pages(array(
        'before' => '<div class="page-links">' . esc_html__('Pages:', 'enternews'),
        'after' => '</div>',
    ));
    ?>
</div>








