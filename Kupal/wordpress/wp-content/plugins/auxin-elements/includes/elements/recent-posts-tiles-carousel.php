<?php
/**
 * Recent Posts Tiles in Carousel Mode
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function auxin_get_recent_posts_tiles_carousel_master_array( $master_array ) {

    $master_array['aux_recent_posts_tiles_carousel'] = array(
        'name'                          => __('Recent Posts Tiles Carousel', 'auxin-elements' ),
        'auxin_output_callback'         => 'auxin_widget_recent_posts_tiles_carousel_callback',
        'base'                          => 'aux_recent_posts_tiles_carousel',
        'description'                   => __('It adds recent posts in tiles carousel mode.', 'auxin-elements' ),
        'class'                         => 'aux-widget-recent-posts-tiles aux-carousel',
        'show_settings_on_create'       => true,
        'weight'                        => 1,
        'is_widget'                     => false,
        'is_shortcode'                  => true,
        'is_so'                         => true,
        'is_vc'                         => true,
        'category'                      => THEME_NAME,
        'group'                         => '',
        'admin_enqueue_js'              => '',
        'admin_enqueue_css'             => '',
        'front_enqueue_js'              => '',
        'front_enqueue_css'             => '',
        'icon'                          => 'aux-element aux-pb-icons-tile',
        'custom_markup'                 => '',
        'js_view'                       => '',
        'html_template'                 => '',
        'deprecated'                    => '',
        'content_element'               => '',
        'as_parent'                     => '',
        'as_child'                      => '',
        'params' => array(
            array(
                'heading'          => __('Title','auxin-elements' ),
                'description'       => __('Recent post title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'       => 'title',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'title',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'           => __('Categories', 'auxin-elements'),
                'description'       => __('Specifies a category that you want to show posts from it.', 'auxin-elements' ),
                'param_name'        => 'cat',
                'type'              => 'aux_taxonomy',
                'taxonomy'          => 'category',
                'def_value'         => ' ',
                'holder'            => '',
                'class'             => 'cat',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Number of posts to show in per page', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'num',
                'type'              => 'textfield',
                'value'             => '5',
                'holder'            => '',
                'class'             => 'num',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Number of Pages', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'page',
                'type'              => 'textfield',
                'value'             => '2',
                'holder'            => '',
                'class'             => 'num',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude posts without media','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'exclude_without_media',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude custom post formats','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'exclude_custom_post_formats',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude quote and link post formats','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'exclude_quote_link',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'exclude_custom_post_formats',
                    'value'         => '0'
                ),
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'            => __('Order by', 'auxin-elements'),
                'description'        => '',
                'param_name'         => 'order_by',
                'type'               => 'dropdown',
                'def_value'          => 'date',
                'holder'             => '',
                'class'              => 'order_by',
                'value'              => array (
                    'date'            => __('Date', 'auxin-elements'),
                    'menu_order date' => __('Menu Order', 'auxin-elements'),
                    'title'           => __('Title', 'auxin-elements'),
                    'ID'              => __('ID', 'auxin-elements'),
                    'rand'            => __('Random', 'auxin-elements'),
                    'comment_count'   => __('Comments', 'auxin-elements'),
                    'modified'        => __('Date Modified', 'auxin-elements'),
                    'author'          => __('Author', 'auxin-elements'),
                    'post__in'        => __('Inserted Post IDs', 'auxin-elements')
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Order', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'order',
                'type'              => 'dropdown',
                'def_value'         => 'DESC',
                'holder'            => '',
                'class'             => 'order',
                'value'             =>array (
                    'DESC'          => __('Descending', 'auxin-elements'),
                    'ASC'           => __('Ascending', 'auxin-elements'),
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Only posts','auxin-elements' ),
                'description'       => __('If you intend to display ONLY specific posts, you should specify the posts here. You have to insert the post IDs that are separated by comma (eg. 53,34,87,25).', 'auxin-elements' ),
                'param_name'        => 'only_posts__in',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Include posts','auxin-elements' ),
                'description'       => __('If you intend to include additional posts, you should specify the posts here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'param_name'        => 'include',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude posts','auxin-elements' ),
                'description'       => __('If you intend to exclude specific posts from result, you should specify the posts here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'param_name'        => 'exclude',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'            => __('Order by', 'auxin-elements'),
                'description'        => '',
                'param_name'         => 'order_by',
                'type'               => 'dropdown',
                'def_value'          => 'date',
                'holder'             => '',
                'class'              => 'order_by',
                'value'              => array (
                    'date'            => __('Date', 'auxin-elements'),
                    'menu_order date' => __('Menu Order', 'auxin-elements'),
                    'title'           => __('Title', 'auxin-elements'),
                    'ID'              => __('ID', 'auxin-elements'),
                    'rand'            => __('Random', 'auxin-elements'),
                    'comment_count'   => __('Comments', 'auxin-elements'),
                    'modified'        => __('Date Modified', 'auxin-elements'),
                    'author'          => __('Author', 'auxin-elements'),
                    'post__in'        => __('Inserted Post IDs', 'auxin-elements')
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Order', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'order',
                'type'              => 'dropdown',
                'def_value'         => 'DESC',
                'holder'            => '',
                'class'             => 'order',
                'value'             =>array (
                    'DESC'          => __('Descending', 'auxin-elements'),
                    'ASC'           => __('Ascending', 'auxin-elements'),
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Start offset','auxin-elements' ),
                'description'       => __('Number of post to displace or pass over.', 'auxin-elements' ),
                'param_name'        => 'offset',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __( 'Post Tile styles','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'tile_style_pattern',
                'type'             => 'aux_visual_select',
                'def_value'        => 'default',
                'holder'           => '',
                'class'            => 'tile_style_pattern',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Style', 'auxin-elements' ),
                'edit_field_class' => '',
                'choices'          => array(
                    'default'    => array(
                        'label'    => __( 'Default', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-5.svg'
                    ),
                    'pattern-1'  => array(
                        'label'    => __( 'Pattern 1', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-3.svg'
                    ),
                    'pattern-2'  => array(
                        'label'    => __( 'Pattern 2', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-6.svg'
                    ),
                    'pattern-3'  => array(
                        'label'    => __( 'Pattern 3', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-7.svg'
                    ),
                    'pattern-4'  => array(
                        'label'    => __( 'Pattern 4', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-8.svg'
                    ),
                    'pattern-5'  => array(
                        'label'    => __( 'Pattern 5', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-4.svg'
                    ),
                    'pattern-6'  => array(
                        'label'    => __('Pattern 6', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-1.svg'
                    ),
                    'pattern-7'  => array(
                        'label'    => __('Pattern 7', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-2.svg'
                    ),
                )
            ),
            array(
                'heading'          => __('Post tile style','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'tile_style',
                'type'             => 'dropdown',
                'def_value'        => '',
                'holder'           => '',
                'class'            => 'tile_style',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Style', 'auxin-elements' ),
                'edit_field_class' => '',
                'value'             => array(
                   'dark'          => __('Dark', 'auxin-elements'),
                   'light'         => __('Light', 'auxin-elements'),
                   'light-overlay' => __('Light Overlay', 'auxin-elements'),
                ),
            ),
            array(
                'heading'          => __('Button Navigation Style','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'button_style',
                'type'             => 'aux_visual_select',
                'def_value'        => '',
                'holder'           => '',
                'class'            => 'button_style',
                'admin_label'      => false,
                'dependency'        => array(
                    'element'       => 'carousel_navigation_control',
                    'value'         => 'arrows'
                ),
                'weight'           => '',
                'group'            => __( 'Style', 'auxin-elements' ),
                'edit_field_class' => '',
                'choices'          => array(
                    'pattern-1'             => array(
                        'label'    => __('Pattern 1', 'auxin-elements' ),
                        'image'    => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    'pattern-2'  => array(
                        'label'    => __('Pattern 2', 'auxin-elements' ),
                        'image'    => AUXIN_URL . 'images/visual-select/button-curved.svg'
                    ),
                ),
            ),
            array(
                'heading'           => __('Insert post title','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'display_title',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => 'display_title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Insert post meta','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'show_info',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Navigation type', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'carousel_navigation',
                'type'              => 'dropdown',
                'def_value'         => 'peritem',
                'holder'            => '',
                'class'             => 'num',
                'value'             => array(
                   'peritem'        => __('Move per column', 'auxin-elements'),
                   'perpage'        => __('Move per page', 'auxin-elements'),
                   'scroll'         => __('Smooth scroll', 'auxin-elements'),
                ),
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'group'             => __( 'Carousel', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Navigation control', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'carousel_navigation_control',
                'type'              => 'dropdown',
                'def_value'         => 'arrows',
                'holder'            => '',
                'class'             => 'num',
                'value'             => array(
                   'arrows'         => __('Arrows', 'auxin-elements'),
                   'bullets'        => __('Bullets', 'auxin-elements'),
                   ''               => __('None', 'auxin-elements'),
                ),
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'admin_label'       => false,
                'group'             => __( 'Carousel', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Loop navigation','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'carousel_loop',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'group'             => __( 'Carousel', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Autoplay carousel','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'carousel_autoplay',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'group'             => __( 'Carousel', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Autoplay delay','auxin-elements' ),
                'description'       => __('Specifies the delay between auto-forwarding in seconds.', 'auxin-elements' ),
                'param_name'        => 'carousel_autoplay_delay',
                'type'              => 'textfield',
                'value'             => '2',
                'holder'            => '',
                'class'             => 'excerpt_len',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'group'             => __( 'Carousel', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements' ),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements' ),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'extra_classes',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_recent_posts_tiles_carousel_master_array', 10, 1 );




/**
 * Element without loop and column
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_recent_posts_tiles_carousel_callback( $atts, $shortcode_content = null ){

    global $aux_content_width;

    // Defining default attributes
    $default_atts = array(
        'title'                       => '', // header title
        'cat'                         => ' ',
        'num'                         => '5', // max generated entry
        'only_posts__in'              => '', // display only these post IDs. array or string comma separated
        'include'                     => '',  // include these post IDs in result too. array or string comma separated
        'exclude'                     => '',  // exclude these post IDs from result. array or string comma separated
        'posts_per_page'              => -1,
        'offset'                      => '',
        'paged'                       => '',
        'order_by'                    => 'date',
        'order'                       => 'DESC',
        'excerpt_len'                 => '160',
        'exclude_without_media'       => true,
        'exclude_custom_post_formats' => true,
        'page'                        => '2',
        'exclude_quote_link'          => true,
        'exclude_post_formats_in'     => array(), // the list od post formats to exclude
        'tile_style'                  => 'light',
        'tile_style_pattern'          => 'default',
        'button_style'                => 'pattern-1',
        'display_title'               => true,
        'show_info'                   => true,
        'show_date'                   => true,
        'display_categories'          => true,
        // 'preloadable'                 => false,
        // 'preload_preview'             => true,
        // 'preload_bgcolor'             => '',
        'extra_classes'               => '',
        'extra_column_classes'        => '',
        'custom_el_id'                => '',
        'taxonomy_name'               => 'category',
        'template_part_file'          => 'theme-parts/entry/post-tile',
        'extra_template_path'         => '',
        'universal_id'                => '',
        'reset_query'                 => true,
        'carousel_autoplay'           => false,
        'carousel_navigation'         => 'peritem',
        'carousel_navigation_control' => 'arrows',
        'carousel_autoplay_delay'     => '2',
        'carousel_loop'               => 1,
        'use_wp_query'                => false, // true to use the global wp_query, false to use internal custom query
        'wp_query_args'               => array(), // additional wp_query args
        'loadmore_type'               => '', // 'next' (more button), 'scroll', 'next-prev'
        'loadmore_per_page'           => '',
        'base'                        => 'aux_recent_posts_tiles_carousel',
        'base_class'                  => 'aux-widget-recent-posts-tiles aux-carousel'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    // specify the post formats that should be excluded -------
    $exclude_post_formats_in = (array) $exclude_post_formats_in;

    if( $exclude_custom_post_formats ){
        $exclude_post_formats_in = array_merge( $exclude_post_formats_in, array( 'aside', 'gallery', 'image', 'link', 'quote', 'video', 'audio' ) );
    }
    if( $exclude_quote_link ){
        $exclude_post_formats_in[] = 'quote';
        $exclude_post_formats_in[] = 'link';
    }
    $exclude_post_formats_in = array_unique( $exclude_post_formats_in );

    // --------------

    ob_start();

    $tax_args = array();
    if( ! empty( $cat ) && $cat != " " && ( ! is_array( $cat ) || ! in_array( " ", $cat ) ) ) {
        $tax_args = array(
            array(
                'taxonomy' => $taxonomy_name,
                'field'    => 'term_id',
                'terms'    => ! is_array( $cat ) ? explode( ",", $cat ) : $cat
            )
        );
    }

    global $wp_query;

    if( ! $use_wp_query ){

        // create wp_query to get latest items -----------
        $args = array(
            'post_type'               => 'post',
            'orderby'                 => $order_by,
            'order'                   => $order,
            'offset'                  => $offset,
            'tax_query'               => $tax_args,
            'post_status'             => 'publish',
            'posts_per_page'          => $num * $page,
            'ignore_sticky_posts'     => 1,
            'include_posts__in'       => $include, // include posts in this liat
            'posts__not_in'           => $exclude, // exclude posts in this list
            'posts__in'               => $only_posts__in, // only posts in this list

            'exclude_without_media'   => $exclude_without_media,
            'exclude_post_formats_in' => $exclude_post_formats_in
        );

        // ---------------------------------------------------------------------

        // add the additional query args if available
        if( $wp_query_args ){
            $args = wp_parse_args( $args, $wp_query_args );
        }

        // pass the args through the auxin query parser
        $wp_query = new WP_Query( auxin_parse_query_args( $args ) );
    }

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    $tile_style         = 'aux-tile-' . esc_attr( $tile_style ) . ' ';
    $phone_break_point  = 767;
    $tablet_break_point = 1025;

    $show_comments      = true; // shows comments icon
    $post_counter       = 0;
    $item_class         = 'aux-post-tile aux-image-box';

    if( ! empty( $loadmore_type ) ) {
        $item_class        .= ' aux-ajax-item';
    }

    $container_class    = 'master-carousel aux-no-js aux-mc-before-init ' . $tile_style;
    // genereate the master carousel attributes
    $carousel_attrs  =  'data-columns="1"';
    $carousel_attrs .= ' data-autoplay="'. esc_attr( $carousel_autoplay ) .'"';
    $carousel_attrs .= ' data-navigation="' . esc_attr( $carousel_navigation ) . '"';
    $carousel_attrs .= ' data-loop="' . esc_attr( $carousel_loop ) . '"';
    $carousel_attrs .= ' data-delay="' . esc_attr( $carousel_autoplay_delay ) . '"';
    $carousel_attrs .= ' data-wrap-controls="true"';
    $carousel_attrs .= ' data-bullets="' . ('bullets' == $carousel_navigation_control ? 'true' : 'false') . '"';
    $carousel_attrs .= ' data-bullet-class="aux-bullets aux-small aux-mask"';
    $carousel_attrs .= ' data-arrows="' . ('arrows' == $carousel_navigation_control ? 'true' : 'false') . '"';
    $carousel_attrs .= ' data-same-height="false"';

    $have_posts = $wp_query->have_posts();

    if( $have_posts ){


        echo ! $skip_wrappers ? sprintf( '<div data-lazyload="true" data-element-id="%s" class="%s" %s>', esc_attr( $universal_id ), esc_attr( $container_class ), $carousel_attrs ) : '';

        while ( $wp_query->have_posts() ) {
            $item_pattern_info = auxin_get_tile_pattern( $tile_style_pattern , $post_counter, $aux_content_width );

            $post_counter++;

            if ( ( $post_counter  %  $num ) == 1 ){
                echo '<div class="aux-mc-item aux-tiles-layout">';
            }


            $wp_query->the_post();
            $post = $wp_query->post;


            $post_vars = auxin_get_post_format_media(
                $post,
                array(
                    'request_from'       => 'archive',
                    'media_width'        => $phone_break_point,
                    'media_size'         => $item_pattern_info['size'],
                    'upscale_image'      => true,
                    'image_from_content' => ! $exclude_without_media,
                    'ignore_formats'     => array( '*' ),
                    'preloadable'        => false,
                    'image_sizes'        => 'auto',
                    'srcset_sizes'       => 'auto'
                )
            );

            extract( $post_vars );

            $post_classes = $item_class .' post '. $item_pattern_info['classname'];

            $the_format = get_post_format( $post );

            include auxin_get_template_file( $template_part_file, '', $extra_template_path );

            if ( ( $post_counter   %  $num ) == 0 ){
            $post_counter = 0;
            echo '</div>';
            }

        }
        if ( $page != 1 && 'bullets' != $carousel_navigation_control ) {
        ?>
            <div class="aux-carousel-controls">
                <?php if ( $button_style === 'pattern-1' ) { ?>
                <div class="aux-next-arrow aux-arrow-nav aux-outline aux-hover-fill">
                    <span class="aux-svg-arrow aux-small-right"></span>
                    <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-right"></span>
                </div>
                <div class="aux-prev-arrow aux-arrow-nav aux-outline aux-hover-fill">
                    <span class="aux-svg-arrow aux-small-left"></span>
                    <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-left"></span>
                </div>
                <?php } else { ?>
                <div class="aux-next-arrow aux-arrow-nav aux-hover-slide aux-round aux-outline aux-medium">
                    <span class="aux-overlay"></span>
                    <span class="aux-svg-arrow aux-medium-right"></span>
                    <span class="aux-hover-arrow aux-svg-arrow aux-medium-right aux-white"></span>
                </div>
                <div class="aux-prev-arrow aux-arrow-nav aux-hover-slide aux-round aux-outline aux-medium">
                    <span class="aux-overlay"></span>
                    <span class="aux-svg-arrow aux-medium-left"></span>
                    <span class="aux-hover-arrow aux-svg-arrow aux-medium-left aux-white"></span>
                </div>
                <?php } ?>
            </div>

        <?php
        }

        if( ! $skip_wrappers ) {
            // End tag for aux-ajax-view wrapper
            echo '</div>';
            // Execute load more functionality
            if( $wp_query->found_posts > $loadmore_per_page ) {
                echo auxin_get_load_more_controller( $loadmore_type );
            }

        } else {
            // Get post counter in the query
            echo '<span class="aux-post-count hidden">'.$wp_query->post_count.'</span>';
        }
    }

    if( $reset_query ){
        wp_reset_query();
    }

    // return false if no result found
    if( ! $have_posts ){
        ob_get_clean();
        return false;
    }

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}


