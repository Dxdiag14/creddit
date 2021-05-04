<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package EnterNews
 */

?>


<?php if (is_singular()) : ?>
        <div class="entry-content read-details">
            <?php
            the_content(sprintf(
                wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'enternews'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            )); ?>
            <?PHP if (is_single()): ?>
                <div class="post-item-metadata entry-meta">
                    <?php enternews_post_item_tag(); ?>
                </div>
            <?php endif; ?>
            <?php
            the_post_navigation(array(
                'prev_text' => __('<span class="em-post-navigation">Previous</span> %title', 'enternews'),
                'next_text' => __('<span class="em-post-navigation">Next</span> %title', 'enternews'),
                'in_same_term' => true,
                'taxonomy' => __('category', 'enternews'),
                'screen_reader_text' => __('Continue Reading', 'enternews'),
            ));
            ?>
            <?php wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'enternews'),
                'after' => '</div>',
            ));
            ?>
        </div><!-- .entry-content -->
<?php else:

 do_action('enternews_action_archive_layout');

endif;
