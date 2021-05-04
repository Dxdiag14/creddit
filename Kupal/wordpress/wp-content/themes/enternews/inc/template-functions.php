<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package EnterNews
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function enternews_body_classes($classes)
{
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    $get_background_image = get_background_image();
    if(!empty($get_background_image)){
        $classes[] = 'aft-has-background-image';
    }

    $sticky_sidebar = enternews_get_option('frontpage_sticky_sidebar');
    if($sticky_sidebar){
        $classes[] =  'aft-sticky-sidebar';
    }


    $global_site_mode_setting = enternews_get_option('global_site_mode_setting');
    $classes[] = $global_site_mode_setting;

    if (is_front_page() || is_home() || is_page_template('tmpl-front-page.php')) {
        $sidebar_section_background = enternews_get_option('select_frontpage_sidebar_section_background');
        $classes[] = $sidebar_section_background;
    }else{
        $sidebar_section_background = enternews_get_option('select_main_sidebar_section_background');
        $classes[] = $sidebar_section_background;
    }

    $select_header_image_mode = enternews_get_option('select_header_image_mode');
    if ($select_header_image_mode == 'full') {
        $classes[] = 'header-image-full';
    } else {
        $classes[] = 'header-image-default';
    }


    $select_main_banner_section_layout = enternews_get_option('select_main_banner_section_layout');

    if ($select_main_banner_section_layout) {
        $classes[] = 'aft-main-banner-'.$select_main_banner_section_layout;
    }

    $single_post_featured_image_view = enternews_get_option('single_post_featured_image_view');
    if ($single_post_featured_image_view == 'full') {
        $classes[] = 'aft-single-full-header';
    }

    if ($single_post_featured_image_view == 'within-content') {
        $classes[] = 'aft-single-within-content-header';
    }

    $enable_container_padding = enternews_get_option('enable_container_padding');
    if ($enable_container_padding == true) {
        $classes[] = 'aft-enable-container-padding';
    }

    $global_hide_comment_count_in_list = enternews_get_option('global_hide_comment_count_in_list');
    if ($global_hide_comment_count_in_list == true) {
        $classes[] = 'aft-hide-comment-count-in-list';
    }

    $global_hide_min_read_in_list = enternews_get_option('global_hide_min_read_in_list');
    if ($global_hide_min_read_in_list == true) {
        $classes[] = 'aft-hide-minutes-read-in-list';
    }


    $global_hide_post_date_author_in_list = enternews_get_option('global_hide_post_date_author_in_list');
    if ($global_hide_post_date_author_in_list == true) {
        $classes[] = 'aft-hide-date-author-in-list';
    }

    $transparent_main_banner_boxes = enternews_get_option('transparent_main_banner_boxes');
    if ($transparent_main_banner_boxes == true) {
        $classes[] = 'aft-transparent-main-banner-box';
    }

    global $post;

    $global_layout = enternews_get_option('global_content_layout');
    if (!empty($global_layout)) {
        $classes[] = $global_layout;
    }


    $global_alignment = enternews_get_option('global_content_alignment');
    $page_layout = $global_alignment;
    $disable_class = '';
    $frontpage_content_status = enternews_get_option('frontpage_content_status');
    if (1 != $frontpage_content_status) {
        $disable_class = 'disable-default-home-content';
    }

    // Check if single.
    if ($post && is_singular()) {
        $post_options = get_post_meta($post->ID, 'enternews-meta-content-alignment', true);
        if (!empty($post_options)) {
            $page_layout = $post_options;
        } else {
            $page_layout = $global_alignment;
        }
    }


    if (is_front_page()) {
        $frontpage_layout = enternews_get_option('frontpage_content_alignment');
        if (!empty($frontpage_layout)) {
            $page_layout = $frontpage_layout;
        }

    }

    if(!is_front_page() && is_home()){
        $page_layout = $global_alignment;
    }

    if ( is_front_page() && !is_home()) {
        if ($page_layout == 'frontpage-layout-1') {
            if (is_active_sidebar('home-sidebar-1-widgets')) {
                $classes[] = 'content-with-single-sidebar align-content-left';
            } else {
                $classes[] = 'full-width-content';
            }

        } elseif ($page_layout == 'frontpage-layout-2') {
            if (is_active_sidebar('home-sidebar-1-widgets')) {
                $classes[] = 'content-with-single-sidebar align-content-right';
            } else {
                $classes[] = 'full-width-content';
            }
        } else {
            $classes[] = 'full-width-content';
        }


    } elseif (is_front_page() && is_home()) {

        if ($page_layout == 'frontpage-layout-1') {
            if (is_active_sidebar('sidebar-1')) {
                $classes[] = 'content-with-single-sidebar align-content-left';
            } else {
                $classes[] = 'full-width-content';
            }

        } elseif ($page_layout == 'frontpage-layout-2') {
            if (is_active_sidebar('sidebar-1')) {
                $classes[] = 'content-with-single-sidebar align-content-right';
            } else {
                $classes[] = 'full-width-content';
            }
        } else {
            $classes[] = 'full-width-content';
        }

    } else {

        if ($page_layout == "align-content-left") {
            if (is_active_sidebar('sidebar-1')) {
                $classes[] = 'content-with-single-sidebar align-content-left';
            } else {
                $classes[] = 'full-width-content';
            }
        } elseif ($page_layout == 'align-content-right') {
            if (is_active_sidebar('sidebar-1')) {
                $classes[] = 'content-with-single-sidebar align-content-right';
            } else {
                $classes[] = 'full-width-content';
            }

        } else {
            $classes[] = 'full-width-content';
        }
    }
//        }

    return $classes;

}

add_filter('body_class', 'enternews_body_classes');

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function enternews_pingback_header()
{
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
    }
}

add_action('wp_head', 'enternews_pingback_header');


/**
 * Returns posts.
 *
 * @since EnterNews 1.0.0
 */
if (!function_exists('enternews_get_posts')):
    function enternews_get_posts($number_of_posts, $tax_id = '0', $filterby = 'cat')
    {

        $ins_args = array(
            'post_type' => 'post',
            'posts_per_page' => absint($number_of_posts),
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'ignore_sticky_posts' => true
        );


        $tax_id = isset($tax_id) ? $tax_id : '0';

        if ((absint($tax_id) > 0) && ($filterby == 'tag')) {
            $ins_args['tag_id'] = absint($tax_id);
            $ins_args['orderby'] = 'date';
        } elseif (($filterby == 'popular')) {
            $ins_args['orderby'] = 'comment_count';
        } elseif ((absint($tax_id) > 0) && ($filterby == 'cat')) {
            $ins_args['cat'] = absint($tax_id);
            $ins_args['orderby'] = 'date';
        } else {
            $ins_args['orderby'] = 'date';
        }

        $all_posts = new WP_Query($ins_args);

        return $all_posts;
    }

endif;


/**
 * Returns no image url.
 *
 * @since  EnterNews 1.0.0
 */
if (!function_exists('enternews_post_format')):
    function enternews_post_format($post_id)
    {
        $post_format = get_post_format($post_id);
        switch ($post_format) {
            case "image":
                $post_format = "<div class='af-post-format em-post-format'><i class='fa fa-camera'></i></div>";
                break;

            case "video":
                $post_format = "<div class='af-post-format em-post-format'><i class='fa fa-video-camera'></i></div>";

                break;
            case "gallery":
                $post_format = "<div class='af-post-format em-post-format'><i class='fa fa-camera'></i></div>";
                break;
            default:
                $post_format = "";
        }

        echo $post_format;
    }

endif;


if (!function_exists('enternews_get_block')) :
    /**
     *
     * @param null
     *
     * @return null
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_get_block($block = 'grid', $section = 'post')
    {

        get_template_part('inc/hooks/blocks/block-' . $section, $block);

    }
endif;

if (!function_exists('enternews_archive_title')) :
    /**
     *
     * @param null
     *
     * @return null
     *
     * @since EnterNews 1.0.0
     *
     */

    function enternews_archive_title($title)
    {
        if (is_category()) {
            $title = single_cat_title('', false);
        } elseif (is_tag()) {
            $title = single_tag_title('', false);
        } elseif (is_author()) {
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title('', false);
        } elseif (is_tax()) {
            $title = single_term_title('', false);
        }

        return $title;
    }

endif;
add_filter('get_the_archive_title', 'enternews_archive_title');

/* Display Breadcrumbs */
if (!function_exists('enternews_get_breadcrumb')) :

    /**
     * Simple breadcrumb.
     *
     * @since 1.0.0
     */
    function enternews_get_breadcrumb()
    {

        $enable_breadcrumbs = enternews_get_option('enable_breadcrumb');

        if (1 != $enable_breadcrumbs) {
            return;
        }
        // Bail if Home Page.
        if (is_front_page() || is_home()) {
            return;
        }

        $select_breadcrumbs = enternews_get_option('select_breadcrumb_mode');

        ?>
        <div class="container-wrapper">
            <div class="af-breadcrumbs font-family-1 color-pad af-container-block-wrapper">

                <?php
                if ((function_exists('yoast_breadcrumb')) && ($select_breadcrumbs == 'yoast')) {
                    yoast_breadcrumb();
                } elseif ((function_exists('rank_math_the_breadcrumbs')) && ($select_breadcrumbs == 'rankmath')) {
                    rank_math_the_breadcrumbs();
                } elseif ((function_exists('bcn_display')) && ($select_breadcrumbs == 'bcn')) {
                    bcn_display();
                } else {
                    enternews_get_breadcrumb_trail();
                }
                ?>

            </div>
        </div>
        <?php


    }

endif;
add_action('enternews_action_get_breadcrumb', 'enternews_get_breadcrumb');

/* Display Breadcrumbs */
if (!function_exists('enternews_get_breadcrumb_trail')) :

    /**
     * Simple excerpt length.
     *
     * @since 1.0.0
     */

    function enternews_get_breadcrumb_trail()
    {

        if (!function_exists('breadcrumb_trail')) {

            /**
             * Load libraries.
             */

            require_once get_template_directory() . '/lib/breadcrumb-trail/breadcrumb-trail.php';
        }

        $breadcrumb_args = array(
            'container' => 'div',
            'show_browse' => false,
        );

        breadcrumb_trail($breadcrumb_args);

    }

endif;
/**
 * Front-page main banner section layout
 */
if (!function_exists('enternews_front_page_main_section')) {

    function enternews_front_page_main_section()
    {

        $hide_on_blog = enternews_get_option('disable_main_banner_on_blog_archive');

        if ($hide_on_blog) {
            if (is_front_page()) {
                do_action('enternews_action_front_page_main_section_1');
            }

        } else {
            if (is_front_page() || is_home()) {
                do_action('enternews_action_front_page_main_section_1');
            }


        }
    }
}
add_action('enternews_action_front_page_main_section', 'enternews_front_page_main_section');


/* Display Breadcrumbs */
if (!function_exists('enternews_excerpt_length')) :

    /**
     * Simple excerpt length.
     *
     * @since 1.0.0
     */

    function enternews_excerpt_length($length)
    {

        if (is_admin()) {
            return $length;
        }

        return 15;
    }

endif;
add_filter('excerpt_length', 'enternews_excerpt_length', 999);


/* Display Breadcrumbs */
if (!function_exists('enternews_excerpt_more')) :

    /**
     * Simple excerpt more.
     *
     * @since 1.0.0
     */
    function enternews_excerpt_more($more)
    {
        return '...';
    }

endif;

add_filter('excerpt_more', 'enternews_excerpt_more');


/* Display Pagination */
if (!function_exists('enternews_numeric_pagination')) :

    /**
     * Simple excerpt more.
     *
     * @since 1.0.0
     */
    function enternews_numeric_pagination()
    {
        the_posts_pagination(array(
            'mid_size' => 3,
            'prev_text' => __('Previous', 'enternews'),
            'next_text' => __('Next', 'enternews'),
        ));

    }

endif;

/* Word read count Pagination */
if (!function_exists('enternews_count_content_words')) :
    /**
     * @param $content
     *
     * @return string
     */
    function enternews_count_content_words($post_id)
    {
        $show_read_mins = enternews_get_option('global_show_min_read');
        if ($show_read_mins == 'yes') {
            $content = apply_filters('the_content', get_post_field('post_content', $post_id));
            $read_words = enternews_get_option('global_show_min_read_number');
            $decode_content = html_entity_decode($content);
            $filter_shortcode = do_shortcode($decode_content);
            $strip_tags = wp_strip_all_tags($filter_shortcode, true);
            $count = str_word_count($strip_tags);
            $word_per_min = (absint($count) / $read_words);
            $word_per_min = ceil($word_per_min);

            if (absint($word_per_min) > 0) {
                $word_count_strings = sprintf(_n('%s min read', '%s min read', number_format_i18n($word_per_min), 'enternews'), number_format_i18n($word_per_min));
                if ('post' == get_post_type($post_id)):
                    echo '<span class="min-read">';
                    echo esc_html($word_count_strings);
                    echo '</span>';
                endif;
            }

        }
    }

endif;


/**
 * Check if given term has child terms
 *
 * @param Integer $term_id
 * @param String $taxonomy
 *
 * @return Boolean
 */
function enternews_list_popular_taxonomies($taxonomy = 'post_tag', $title = "Popular Tags", $number = 5)
{
    $popular_taxonomies = get_terms(array(
        'taxonomy' => $taxonomy,
        'number' => absint($number),
        'orderby' => 'count',
        'order' => 'DESC',
        'hide_empty' => true,
    ));

    if ($popular_taxonomies) {
        return $popular_taxonomies;
    } else {
        return false;
    }


}

function enternews_display_list_popular_taxonomies($popular_taxonomies = '', $title = "Popular Tags")
{
    $html = '';
    if (isset($popular_taxonomies) && !empty($popular_taxonomies)):
        $html .= '<div class="aft-popular-taxonomies-lists clearfix">';
        if (!empty($title)):
            $html .= '<strong>';
            $html .= esc_html($title);
            $html .= '</strong>';
        endif;
        $html .= '<ul>';
        foreach ($popular_taxonomies as $tax_term):
            $html .= '<li>';
            $html .= '<a href="' . esc_url(get_term_link($tax_term)) . '">';
            $html .= $tax_term->name;
            $html .= '<span class="tag-count">' . $tax_term->count . '</span></a>';
            $html .= '</li>';

        endforeach;
        $html .= '</ul>';
        $html .= '</div>';
    endif;

    echo $html;
}


/**
 * @param $post_id
 * @param string $size
 *
 * @return mixed|string
 */
function enternews_get_freatured_image_url($post_id, $size = 'enternews-featured')
{
    if (has_post_thumbnail($post_id)) {
        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
        $url = $thumb['0'];
    } else {
        $url = '';
    }

    return $url;
}


/**
 * @param $post_id
 */
function enternews_get_comments_count($post_id)
{


    $show_comment_count = enternews_get_option('global_show_comment_count');
    if ($show_comment_count == 'yes'):

        $comment_count = get_comments_number($post_id);
        if (absint($comment_count) > 1):
            ?>
            <span class="min-read-post-comment">
        <a class="af-comment-count" href="<?php the_permalink(); ?>">
            <?php echo get_comments_number($post_id); ?>
        </a>
        </span>
        <?php endif;
    endif;

}



//Get attachment alt tag

if (!function_exists('enternews_get_img_alt')):
    function enternews_get_img_alt($attachment_ID)
    {
        // Get ALT
        $thumb_alt = get_post_meta($attachment_ID, '_wp_attachment_image_alt', true);

        // No ALT supplied get attachment info
        if (empty($thumb_alt))
            $attachment = get_post($attachment_ID);

        // Use caption if no ALT supplied
        if (empty($thumb_alt))
            $thumb_alt = $attachment->post_excerpt;

        // Use title if no caption supplied either
        if (empty($thumb_alt))
            $thumb_alt = $attachment->post_title;

        // Return ALT
        return trim(strip_tags($thumb_alt));
    }
endif;


//Jetpack social share

function enternews_jptweak_remove_share()
{
    remove_filter('the_content', 'sharing_display', 19);
    remove_filter('the_excerpt', 'sharing_display', 19);

}

add_action('loop_start', 'enternews_jptweak_remove_share');

function enternews_single_post_social_share_icons()
{
    if (class_exists('Jetpack') && Jetpack::is_module_active('sharedaddy')):


        ?>
        <div class="aft-social-share">
            <?php
            if (function_exists('sharing_display')) {
                sharing_display('', true);
            }
            ?>

        </div>
    <?php

    endif;

}


if (!function_exists('enternews_esc_custom_style(')) {

    function enternews_esc_custom_style($props)  {
        echo wp_kses( $props, array( "\'", '\"' ) );

    }
}