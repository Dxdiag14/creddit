<?php
/**
 * Code highlighter element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function auxin_get_recent_post_widget_master_array( $master_array ) {

    $categories = get_terms( 'category', 'orderby=count&hide_empty=0' );
    $categories_list = array( '' => __('All Categories', 'auxin-elements' ) );
    foreach ( $categories as $key => $value ) {
        $categories_list[$value->term_id] = $value->name;
    }

    // $tags = get_terms( 'post_tag', 'orderby=count&hide_empty=0' );
    // $tags_list;
    // foreach ($tags as $key => $value) {
    //     $tags_list["$value->term_id"] = $value->name;
    // }


    $master_array['aux_recent_post_widget'] = array(
        'name'                          => __('Recent Posts Widget', 'auxin-elements' ),
        'auxin_output_callback'         => 'auxin_widget_recent_post_widget_callback',
        'base'                          => 'aux_recent_post_widget',
        'description'                   => __('Shows recent posts with thumbnail.', 'auxin-elements' ),
        'class'                         => 'aux-widget-recent-post-widget',
        'show_settings_on_create'       => true,
        'weight'                        => 1,
        'is_widget'                     => true,
        'is_shortcode'                  => false,
        'is_so'                         => false,
        'is_vc'                         => false,
        'category'                      => THEME_NAME,
        'group'                         => '',
        'admin_enqueue_js'              => '',
        'admin_enqueue_css'             => '',
        'front_enqueue_js'              => '',
        'front_enqueue_css'             => '',
        'icon'                          => 'aux-element aux-pb-icons-code',
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
                'description'      => '',
                'param_name'       => 'title',
                'type'             => 'textfield',
                'std'              => '',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'title',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'     => __('Number of posts to show', 'auxin-elements'),
                'description' => '',
                'param_name'  => 'num',
                'type'        => 'dropdown',
                'def_value'   => '4',
                'holder'      => '',
                'class'       => 'num',
                'value'       => array(
                    '1'  => '1' , '2' => '2'  , '3' => '3' ,
                    '4'  => '4' , '5' => '5'  , '6' => '6',
                    '7'  => '7' , '8' => '8'  , '9' => '9' ,
                    '10' => '10','11' => '11' ,'12' => '12'
                ),
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display image', 'auxin-elements' ),
                'description'       => __('Enable it to display images as well.','auxin-elements' ),
                'param_name'        => 'show_media',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'show_media',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display excerpt','auxin-elements' ),
                'description'       => __('Enable it to display post summary instead of full content.','auxin-elements' ),
                'param_name'        => 'show_excerpt',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'show_excerpt',
                'admin_label'       => 1,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display post date','auxin-elements' ),
                'param_name'        => 'show_date',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'show_date',
                'admin_label'       => 1,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Excerpt length','auxin-elements' ),
                'description'       => __('Specify summary content in character','auxin-elements' ),
                'param_name'        => 'excerpt_len',
                'type'              => 'textfield',
                'value'             => '60',
                'holder'            => 'textfield',
                'class'             => 'excerpt_len',
                'admin_label'       => 1,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'     => __('Order by', 'auxin-elements'),
                'description' => '',
                'param_name'  => 'order_by',
                'type'        => 'dropdown',
                'def_value'   => 'date',
                'holder'      => 'dropdown',
                'class'       => 'order_by',
                'value'       => array (
                    'date'            => __('Date', 'auxin-elements'),
                    'menu_order date' => __('Menu Order', 'auxin-elements'),
                    'title'           => __('Title', 'auxin-elements'),
                    'ID'              => __('ID', 'auxin-elements'),
                    'rand'            => __('Random', 'auxin-elements'),
                    'comment_count'   => __('Comments', 'auxin-elements'),
                    'modified'        => __('Date Modified', 'auxin-elements'),
                    'author'          => __('Author', 'auxin-elements'),
                ),
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
             array(
                'heading'     => __('Order', 'auxin-elements'),
                'description' => '',
                'param_name'  => 'order',
                'type'        => 'dropdown',
                'def_value'   => 'DESC',
                'holder'      => 'dropdown',
                'class'       => 'order',
                'value'       =>array (
                    'DESC'  => __('Descending', 'auxin-elements'),
                    'ASC'   => __('Ascending', 'auxin-elements'),
                ),
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Categories', 'auxin-elements'),
                'description'       => __('Specifies a category that you want to show posts from it.', 'auxin-elements' ),
                'param_name'       => 'cat',
                'type'             => 'dropdown',
                'def_value'        => 'default',
                'holder'           => 'dropdown',
                'class'            => 'cat',
                'value'            => $categories_list,
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            // array(
            //     'param_name'        => 'tag',
            //     'type'              => 'dropdown',
            //     'def_value'             => '',
            //     'holder'            => '',
            //     'class'             => 'tag',
            //     'heading'           => __('Tags', 'auxin-elements'),
            //     'description'       => __('specify a tags that you want.', 'auxin-elements' ),
            //     'value'             => $tags_list,
            //     'admin_label'       => true,
            //     'dependency'        => '',
            //     'weight'            => '',
            //     'group'             => '' ,
            //     'edit_field_class'  => ''
            // ),
             array(
                'heading'           => __('Extra class name','auxin-elements' ),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements' ),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'extra_classes',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_recent_post_widget_master_array', 10, 1 );




/**
 * Element without loop and column
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_recent_post_widget_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'         => '', // header title
        'num'           => '4',
        'show_media'    => 1,
        'show_excerpt'  => 1,
        'show_date'     => 1,
        'excerpt_len'   => '60',
        'order'         => 'desc',
        'order_by'      => 'date',
        'cat'           => '',
        'tag'           => '',
        'extra_classes' => '',
        'custom_el_id'  => '',
        'base_class'    => 'aux-widget-recent-post-widget'
    );

    // the parsed widget params
    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    $output = '';

    // create wp_query to get latest items -----------
    $recent_args = array(
        'post_type'           => 'post',
        'orderby'             => $result['parsed_atts']['order_by'],
        'order'               => $result['parsed_atts']['order'],
        'post_status'         => 'publish',
        'posts_per_page'      => $result['parsed_atts']['num'],
        'cat'                 => $result['parsed_atts']['cat'],
        // 'tag_id'              => $tag,
        'ignore_sticky_posts' => 1
    );

    // @TODO
    $result['parsed_atts']['show_format'] = false;

    // widget header ------------------------------

    $output .= $result['widget_header'];
    $output .= $result['widget_title'];

    $output .= auxin_get_post_type_markup( $recent_args, 'templates/theme-parts/entry/widget-recent-post.php', $result['parsed_atts'] );

    // widget footer ------------------------------
    $output .= $result['widget_footer'];

    return $output;
}
