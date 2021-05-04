<?php

/**
 * Related Posts for any post type
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */

function auxin_get_the_related_posts( $args = array() ){

    global $post;

    // the recent posts args
    $defaults = array(
        'title'                       => '',
        'post_type'                   => $post->post_type,
        'taxonomy_name'               => '', // the taxonomy that we intent to display in post info
        'taxonomy'                    => array(),
        'orderbr'                     => 'menu_order date',
        'num'                         => 3,
        'desktop_cnum'                => 3,
        'tablet_cnum'                 => 2,
        'phone_cnum'                  => 1,

        'display_title'               => true,
        'show_info'                   => true,
        'show_date'                   => false,
        'author_or_readmore'          => '', // readmore, author, none
        'display_categories'          => true,
        'max_taxonomy_num'            => 3,
        'wp_query_args'               => array(),

        'size'                        => '',
        'show_media'                  => true,
        'content_layout'              => 'entry-boxed', // entry-boxed
        'show_excerpt'                => false,
        'excerpt_len'                 => '0',
        'post_info_position'          => 'after-title',
        'image_aspect_ratio'          => 0.75,
        'preview_mode'                => 'grid',
        'grid_table_hover'            => 'bgimage-bgcolor',

        'ignore_media'                => false, // whether to ignore media for this
        'exclude'                     => '',
        'include'                     => '',
        'order_by'                    => 'menu_order date',
        'order'                       => 'desc',
        'exclude_without_media'       => 0,
        'exclude_custom_post_formats' => 0,
        'exclude_quote_link'          => 0,
        'exclude_post_formats_in'     => array(), // the list od post formats to exclude
        'display_like'                => true,

        'extra_classes'               => '',
        'extra_column_classes'        => '',
        'custom_el_id'                => '',
        'carousel_space'              => '30',
        'carousel_autoplay'           => false,
        'carousel_autoplay_delay'     => '2',
        'carousel_navigation'         => 'peritem',
        'carousel_navigation_control' => 'bullets',
        'carousel_loop'               => 1,

        'reset_query'                 => true,
        'use_wp_query'                => false, // true to use the global wp_query, false to use internal custom query
        'custom_wp_query'             => '',
        'base_class'                  => 'aux-widget-recent-posts aux-widget-related-posts',

        'text_alignment'              => '',
        'container_start_tag'         => '<div class="aux-container aux-fold">',
        'container_end_tag'           => '</div>'
    );


    // update the setting base on preview mode
    if( ! isset( $args['num'] ) && isset( $args['desktop_cnum'] ) ){
        $args['num'] =  'carousel' == $args['preview_mode'] ? $args['desktop_cnum'] * 2 : $args['desktop_cnum'];
    }

    $args = wp_parse_args( $args, $defaults );

    // query the related posts base on taxonimy --------------------------------

    // the taxonomies
    $taxonomy = $args['taxonomy'];

    // the taxonomy that we intend to display its corresponding terms (required in post-column.php)
    $taxonomy_name = isset( $taxonomy[0] ) ? $taxonomy[0] : $taxonomy;

    // query the post with the same cat or categories
    if( $taxonomy ){

        $args['wp_query_args']['tax_query'] = array();

        foreach ( $taxonomy as $tax ) {
            $tax_terms = get_the_terms( $post->ID, $tax );
            $tax_slugs = array();

            if( $tax_terms && is_array( $tax_terms ) ) {
                // loop through current item terms and get all tax slugs
                foreach ( $tax_terms  as $tax_term ) {
                    $tax_slugs[] = $tax_term->slug;
                }
                $tax_slugs = array_unique( $tax_slugs );

                if( count( $args['wp_query_args']['tax_query'] ) ){
                    $args['wp_query_args']['tax_query']['relation'] = 'OR';
                }

                $args['wp_query_args']['tax_query'][] = array(
                    'taxonomy' => $tax,
                    'field'    => 'slug',
                    'terms'    => $tax_slugs,
                    'operator' => 'IN'
                );
            }

        }

    }

    // query the related posts base on taxonimy --------------------------------

    $args = apply_filters( 'auxin_get_the_related_posts_args', $args );

    return $args['container_start_tag'] . auxin_widget_recent_posts_callback( $args ) . $args['container_end_tag'];
}

