<?php




/**
 * Returns all categories.
 *
 * @since EnterNews 1.0.0
 */
if (!function_exists('enternews_get_terms')):
function enternews_get_terms( $category_id = 0, $taxonomy='category', $default='' ){
    $taxonomy = !empty($taxonomy) ? $taxonomy : 'category';

    if ( $category_id > 0 ) {
            $term = get_term_by('id', absint($category_id), $taxonomy );
            if($term)
                return esc_html($term->name);


    } else {
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true,
        ));


        if (isset($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                if( $default != 'first' ){
                    $array['0'] = __('Select Category', 'enternews');
                }
                $array[$term->term_id] = esc_html($term->name);
            }

            return $array;
        }
    }
}
endif;

/**
 * Returns all categories.
 *
 * @since EnterNews 1.0.0
 */
if (!function_exists('enternews_get_terms_link')):
function enternews_get_terms_link( $category_id = 0 ){

    if (absint($category_id) > 0) {
        return get_term_link(absint($category_id), 'category');
    } else {
        return get_post_type_archive_link('post');
    }
}
endif;

/**
 * Returns word count of the sentences.
 *
 * @since EnterNews 1.0.0
 */
if (!function_exists('enternews_get_excerpt')):
    function enternews_get_excerpt($length = 25, $enternews_content = null, $post_id = 1) {
        $widget_excerpt   = enternews_get_option('global_widget_excerpt_setting');
        if($widget_excerpt == 'default-excerpt'){
            return get_the_excerpt();
        }

        if(empty($post_id))
            return;

        $kreeti_default_excerpt = get_the_excerpt($post_id);


        $kreeti_global_excerpt_length = $length;
        $excerpt = explode(' ', $kreeti_default_excerpt, $kreeti_global_excerpt_length);
        if (count($excerpt)>=$kreeti_global_excerpt_length) {
            array_pop($excerpt);
            $excerpt = implode(" ",$excerpt).'...';
        } else {
            $excerpt = implode(" ",$excerpt);
        }
        $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
        //$excerpt = $excerpt.$kreeti_read_more;
        return $excerpt;
    }
endif;

/**
 * Returns no image url.
 *
 * @since EnterNews 1.0.0
 */
if(!function_exists('enternews_no_image_url')):
    function enternews_no_image_url(){
        $url = get_template_directory_uri().'/assets/images/no-image.png';
        return $url;
    }

endif;





/**
 * Outputs the tab posts
 *
 * @since 1.0.0
 *
 * @param array $args  Post Arguments.
 */
if (!function_exists('enternews_render_posts')):
  function enternews_render_posts( $type, $show_excerpt, $excerpt_length, $number_of_posts, $category = '0' ){

    $args = array();
   
    switch ($type) {
        case 'popular':
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => absint($number_of_posts),
                'orderby' => 'comment_count',
                'ignore_sticky_posts' => true
            );
            break;

        case 'recent':
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => absint($number_of_posts),
                'orderby' => 'date',
                'ignore_sticky_posts' => true
            );
            break;

        case 'categorised':
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => absint($number_of_posts),
                'ignore_sticky_posts' => true
            );
            $category = isset($category) ? $category : '0';
            if (absint($category) > 0) {
                $args['cat'] = absint($category);
            }
            break;


        default:
            break;
    }

    if( !empty($args) && is_array($args) ){
        $all_posts = new WP_Query($args);
        if($all_posts->have_posts()):
            echo '<ul class="article-item article-list-item article-tabbed-list article-item-left">';
            while($all_posts->have_posts()): $all_posts->the_post();

                ?>
                <li class="af-double-column list-style">
                    <div class="read-single color-pad">
                        <?php
                        if(has_post_thumbnail()){
                            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()));
                            $url = $thumb['0'];
                            $col_class = 'col-sm-8';
                        }else {
                            $url = '';
                            $col_class = 'col-sm-12';
                        }
                        global $post;
                        ?>
                        <?php if (!empty($url)): ?>
                            <div class="read-img pos-rel col-4 float-l read-bg-img">
                                <?php if (!empty($url)): ?>
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>"></a>
                                <div class="min-read-post-format">
                                    <?php echo enternews_post_format($post->ID); ?>
                                    <span class="min-read-item">
                                                <?php enternews_count_content_words($post->ID); ?>
                                            </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="read-details col-75 float-l pad color-tp-pad">
                            <div class="full-item-metadata primary-font">
                                <div class="read-categories">
                                    <?php enternews_post_categories(); ?>
                                </div>
                            </div>
                            <div class="full-item-content">
                                <div class="read-title">
                                    <h4>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div class="entry-meta">
                                    <?php enternews_get_comments_count($post->ID); ?>
                                    <?php enternews_post_item_meta(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php
            endwhile;wp_reset_postdata();
            echo '</ul>';
        endif;
    }
}
endif;



if(!function_exists('enternews_update_widget_before')):
    
    function enternews_update_widget_before($args='',$background='',$str=''){
        $newclass = 'aft-widget-background-'.esc_attr($background);
        $args['before_widget'] = str_replace( $str, $newclass, $args['before_widget'] );
        return $args['before_widget'];
    }
    endif;
