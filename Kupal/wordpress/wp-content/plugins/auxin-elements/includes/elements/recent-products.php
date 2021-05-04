<?php
/**
 * Woocommerce Recent Products Element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_the_recent_products_master_array( $master_array ) {

    $master_array['aux_recent_product'] = array(
        'name'                    => __("Recent Products", 'auxin-elements'  ),
        'auxin_output_callback'   => 'auxin_widget_the_recent_products_callback',
        'base'                    => 'aux_recent_product',
        'description'             => __("a Widget for Display Your Store's Products", 'auxin-elements' ),
        'class'                   => 'aux-widget-recent-products',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'category'                => THEME_NAME,
        'is_so'                   => true,
        'is_vc'                   => true,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-grid',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'          => __('Title','auxin-elements' ),
                'description'      => __('Recent products title, leave it empty if you don`t need title.', 'auxin-elements'),
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
                'heading'          => __('Subtitle','auxin-elements' ),
                'description'      => __('Recent products subtitle, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'       => 'subtitle',
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
                'taxonomy'          => 'product_cat',
                'def_value'         => ' ',
                'holder'            => '',
                'class'             => 'cat',
                'value'             => ' ', // should use the taxonomy name
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Image aspect ratio', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'image_aspect_ratio',
                'type'              => 'dropdown',
                'def_value'         => '0.75',
                'holder'            => '',
                'class'             => 'order',
                'value'             =>array (
                    '0.75'          => __('Horizontal 4:3' , 'auxin-elements'),
                    '0.56'          => __('Horizontal 16:9', 'auxin-elements'),
                    '1.00'          => __('Square 1:1'     , 'auxin-elements'),
                    '1.33'          => __('Vertical 3:4'   , 'auxin-elements'),
                    '1.5'           => __('Vertical 10:15'   , 'auxin-elements'),

                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude posts without media','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'exclude_without_media',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Order by', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'order_by',
                'type'              => 'dropdown',
                'def_value'         => 'date',
                'holder'            => '',
                'class'             => 'order_by',
                'value'             => array (
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
                'group'             => 'Query',
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
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Only products','auxin-elements' ),
                'description'       => __('If you intend to display ONLY specific products, you should specify the products here. You have to insert the Products IDs that are separated by comma (eg. 53,34,87,25).', 'auxin-elements' ),
                'param_name'        => 'only_products__in',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Include products','auxin-elements' ),
                'description'       => __('If you intend to include additional products, you should specify the products here. You have to insert the Products IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'param_name'        => 'include',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude products','auxin-elements' ),
                'description'       => __('If you intend to exclude specific products from result, you should specify the products here. You have to insert the Products IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'param_name'        => 'exclude',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Start offset','auxin-elements' ),
                'description'       => __('Number of products to displace or pass over.', 'auxin-elements' ),
                'param_name'        => 'offset',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display products price', 'auxin-elements' ),
                'param_name'        => 'display_price',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'display_price',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display sale badge', 'auxin-elements' ),
                'param_name'        => 'display_sale_badge',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'display_sale_badge',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display product title','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'display_title',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => 'display_title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display Categories','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'display_categories',
                'type'              => 'aux_switch',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'display_categories',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Number of columns', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'desktop_cnum',
                'type'              => 'dropdown',
                'def_value'         => '4',
                'holder'            => '',
                'class'             => 'num',
                'value'             => array(
                    '1'  => '1', '2' => '2', '3' => '3',
                    '4'  => '4', '5' => '5', '6' => '6'
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Layout',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Number of columns in tablet size', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'tablet_cnum',
                'type'              => 'dropdown',
                'def_value'         => 'inherit',
                'holder'            => '',
                'class'             => 'num',
                'value'             => array(
                    'inherit' => 'Inherited from larger',
                    '1'  => '1', '2' => '2', '3' => '3',
                    '4'  => '4', '5' => '5', '6' => '6'
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Layout',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Number of columns in phone size', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'phone_cnum',
                'type'              => 'dropdown',
                'def_value'         => '1',
                'holder'            => '',
                'class'             => 'num',
                'value'             => array(
                    '1' => '1', '2' => '2', '3' => '3'
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Layout',
                'edit_field_class'  => ''
            ),
            // array(
            //     'heading'           => __('Content layout', 'auxin-elements'),
            //     'description'       => __('Specifies the style of content for each post column.', 'auxin-elements' ),
            //     'param_name'        => 'content_layout',
            //     'type'              => 'dropdown',
            //     'def_value'         => 'default',
            //     'holder'            => '',
            //     'class'             => 'content_layout',
            //     'value'             =>array (
            //         'default'       => __('Full Content', 'auxin-elements'),
            //         'entry-boxed'   => __('Boxed Content', 'auxin-elements')
            //     ),
            //     'admin_label'       => false,
            //     'dependency'        => '',
            //     'weight'            => '',
            //     'group'             => '',
            //     'edit_field_class'  => ''
            // ),
            array(
                'heading'           => __('Extra class name','auxin-elements' ),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements' ),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_the_recent_products_master_array', 10, 1 );

function auxin_widget_the_recent_products_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'                 => '',    // header title (required)
        'subtitle'              => '',    // header subtitle
        'cat'                   => ' ',
        'num'                   => '8',   // max generated entry
        'only_products__in'     => '',   // display only these post IDs. array or string comma separated
        'include'               => '',    // include these post IDs in result too. array or string comma separated
        'exclude'               => '',    // exclude these post IDs from result. array or string comma separated
        'offset'                => '',
        'paged'                 => '',
        'post_type'             => 'product',
        'taxonomy_name'         => 'product_cat', // the taxonomy that we intent to display in post info
        'tax_args'              => '',
        'order_by'              => 'date',
        'order'                 => 'DESC',
        'exclude_without_media' => 0,
        'display_title'         => true,
        'show_media'            => true,
        'display_categories'    => true,
        'display_button'        => true,
        'preloadable'           => false,
        'preload_preview'       => true,
        'preload_bgcolor'       => '',
        // 'content_layout'     => '', // entry-boxed
        'image_aspect_ratio'    => 0.75,
        'desktop_cnum'          => 4,
        'tablet_cnum'           => 'inherit',
        'phone_cnum'            => '1',
        'display_price'         => true,
        'display_sale_badge'    => true,
        'extra_classes'         => '',
        'template_part_file'    => 'recent-products',
        'extra_template_path'   =>  AUXELS_PUB_DIR . '/templates/woocommerce',
        'universal_id'          => '',
        'use_wp_query'          => false, // true to use the global wp_query, false to use internal custom query
        'reset_query'           => true,
        'wp_query_args'         => array(), // additional wp_query args
        'custom_wp_query'       => '',
        'base'                  => 'aux_recent_products',
        'base_class'            => 'aux-widget-recent-products'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    // get content width
    global $aux_content_width;

    ob_start();

    if( empty( $cat ) || $cat == " " || ( is_array( $cat ) && in_array( " ", $cat ) ) ) {
        $tax_args = array();
    } else {
        $tax_args = array(
            array(
                'taxonomy' => $taxonomy_name,
                'field'    => 'term_id',
                'terms'    => ! is_array( $cat ) ? explode( ",", $cat ) : $cat
            )
        );
    }

    if( $custom_wp_query ){
        $wp_query = $custom_wp_query;

    } elseif( ! $use_wp_query ){

        // create wp_query to get latest items ---------------------------------
        $args = array(
            'post_type'               => $post_type,
            'orderby'                 => $order_by,
            'order'                   => $order,
            'offset'                  => $offset,
            'paged'                   => $paged,
            'tax_query'               => $tax_args,
            'post_status'             => 'publish',
            'posts_per_page'          => $num,
            'ignore_sticky_posts'     => 1,

            'include_posts__in'       => $include, // include posts in this list
            'posts__not_in'           => $exclude, // exclude posts in this list
            'posts__in'               => $only_products__in, // only posts in this list

            'exclude_without_media'   => $exclude_without_media,
        );

        // ---------------------------------------------------------------------

        // add the additional query args if available
        if( $wp_query_args ){
            $args = wp_parse_args( $wp_query_args, $args );
        }

        // pass the args through the auxin query parser
        $wp_query = new WP_Query( auxin_parse_query_args( $args ) );
    } else {

        global $wp_query;
    }

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];
    echo '<h4 class="aux-h4 widget-subtitle">' . esc_html( $subtitle ) . '</h4>';

    $phone_break_point     = 767;
    $tablet_break_point    = 1025;

    $show_comments         = true; // shows comments icon
    $post_counter          = 0;
    $column_class          = '';
    $item_class            = '';

    $columns_custom_styles = '';

    if( ! empty( $loadmore_type ) ) {
        $item_class        .= ' aux-ajax-item';
    }

    $column_class    .= '';
    $item_class      .= ' aux-recent-product-item aux-col';

    // Specifies whether the columns have footer meta or not
    $column_class  .= ' aux-recent-products-wrapper aux-row aux-ajax-view  aux-de-col' . $desktop_cnum;

    $tablet_cnum = ('inherit' == $tablet_cnum  ) ? $desktop_cnum : $tablet_cnum ;
    $column_class .=  ' aux-tb-col'.$tablet_cnum . ' aux-mb-col'.$phone_cnum;


    // automatically calculate the media size if was empty
    if( empty( $size ) ){
        $column_media_width = auxin_get_content_column_width( $desktop_cnum, 15, $content_width );
        $size = array( 'width' => $column_media_width, 'height' => $column_media_width * $image_aspect_ratio );
    }
    echo '<div class="'. $column_class .'">';

    $have_posts = $wp_query->have_posts();

    if( $have_posts ){

        while ( $wp_query->have_posts() ) {

            $wp_query->the_post();
            $post = get_post();

            $post_vars = auxin_get_post_type_media_args(
                $post,
                array(
                    'post_type'          => $post_type,
                    'request_from'       => 'element',
                    'media_width'        => $phone_break_point,
                    'media_size'         => $size,
                    'upscale_image'      => true,
                    'image_from_content' => ! $exclude_without_media, // whether to try to get image from content or not
                    'ignore_media'       => ! $show_media,
                    'add_image_hw'       => false, // whether add width and height attr or not
                    'preloadable'        => $preloadable,
                    'preload_preview'    => $preload_preview,
                    'preload_bgcolor'    => $preload_bgcolor,
                    'image_sizes'        => 'auto',
                    'srcset_sizes'       => 'auto'
                )
            );

            extract( $post_vars );
            $the_format = get_post_format( $post );

            // add specific class to current classes for each column
            $post_classes  = $has_attach && $show_media ? 'post column-entry' : 'post column-entry no-media';

            // Generate the markup by template parts
            if( has_action( $base_class . '-template-part' ) ){
                do_action(  $base_class . '-template-part', $result, $post_vars, $item_class );

            } else {
                printf( '<div class="%s post-%s">', esc_attr( $item_class ), esc_attr( $post->ID ) );
                include auxin_get_template_file( $template_part_file, '', $extra_template_path );
                echo    '</div>';
            }

        }

        if( ! $skip_wrappers ) {
            // End tag for aux-ajax-view wrapper
            echo '</div>';
        } else {
            // Get post counter in the query
            echo '<span class="aux-post-count hidden">'.$wp_query->post_count.'</span>';
        }

    }

    if( $reset_query ){
        wp_reset_postdata();
    }

    // return false if no result found
    if( ! $have_posts ){
        ob_get_clean();
        return false;
    }

    // widget custom output -----------------------


    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
