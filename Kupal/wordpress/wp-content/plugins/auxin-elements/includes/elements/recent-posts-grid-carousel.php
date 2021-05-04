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

function auxin_get_recent_posts_master_array( $master_array ) {

    $categories = get_terms( 'category', 'orderby=count&hide_empty=0' );
    $categories_list = array( ' ' => __('All Categories', 'auxin-elements' ) )  ;
    foreach ( $categories as $key => $value ) {
        $categories_list[$value->term_id] = $value->name;
    }

    // $tags = get_terms( 'post_tag', 'orderby=count&hide_empty=0' );
    // $tags_list;
    // foreach ($tags as $key => $value) {
    //     $tags_list["$value->term_id"] = $value->name;
    // }


    $master_array['aux_recent_posts'] = array(
        'name'                          => __('Grid & Carousel Recent Posts', 'auxin-elements' ),
        'auxin_output_callback'         => 'auxin_widget_recent_posts_callback',
        'base'                          => 'aux_recent_posts',
        'description'                   => __('It adds recent posts in grid or carousel mode.', 'auxin-elements' ),
        'class'                         => 'aux-widget-recent-posts',
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
        'icon'                          => 'aux-element aux-pb-icons-grid',
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
                'description'      => __('Recent post title, leave it empty if you don`t need title.', 'auxin-elements'),
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
                'description'      => __('Recent posts subtitle, leave it empty if you don`t need title.', 'auxin-elements'),
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
                'type'              => 'aux_select2_multiple',
                'def_value'         => ' ',
                'holder'            => '',
                'class'             => 'cat',
                'value'             => $categories_list,
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Number of posts to show', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'num',
                'type'              => 'textfield',
                'value'             => '8',
                'holder'            => '',
                'class'             => 'num',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
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
                    '1.33'          => __('Vertical 3:4'   , 'auxin-elements')
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
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude custom post formats','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'exclude_custom_post_formats',
                'type'              => 'aux_switch',
                'value'             => '0',
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
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'exclude_custom_post_formats',
                    'value'         => array('0', 'false')
                ),
                'weight'            => '',
                'group'             => '',
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
                'heading'           => __('Display post media (image, video, etc)', 'auxin-elements' ),
                'param_name'        => 'show_media',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'show_media',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display post title','auxin-elements' ),
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
                'heading'           => __('Display post info','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'show_info',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display post content','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'show_content',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Post info position', 'auxin-elements' ),
                'description'       => '',
                'param_name'        => 'post_info_position',
                'type'              => 'dropdown',
                'def_value'         => 'after-title',
                'holder'            => '',
                'class'             => 'post_info_position',
                'value'             => array (
                    'after-title'   => __('After Title' , 'auxin-elements' ),
                    'before-title'  => __('Before Title', 'auxin-elements' )
                ),
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_info',
                    'value'         => '1'
                ),
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
                'dependency'        => array(
                    'element'       => 'show_info',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display Date','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'show_date',
                'type'              => 'aux_switch',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'show_date',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_info',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display like button','auxin-elements' ),
                'description'       => sprintf(__('Enable it to display %s like button%s on gride template blog. Please note WP Ulike plugin needs to be activaited to use this option.', 'auxin-elements'), '<strong>', '</strong>'),
                'param_name'        => 'display_like',
                'type'              => 'aux_switch',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'display_like',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Load More Type','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'loadmore_type',
                'type'             => 'aux_visual_select',
                'value'            => '',
                'class'            => 'loadmore_type',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => '',
                'choices'          => array(
                    ''             => array(
                        'label' => __('None', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/load-more-none.svg'
                    ),
                    'scroll'       => array(
                        'label' => __('Infinite Scroll', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/load-more-infinite.svg'
                    ),
                    'next'         => array(
                        'label' => __('Next Button', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/load-more-button.svg'
                    ),
                    'next-prev'    => array(
                        'label' => __('Next Prev', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/load-more-next-prev.svg'
                    )
                )
            ),
            array(
                'heading'           => __('Excerpt length','auxin-elements' ),
                'description'       => __('Specify summary content in character.','auxin-elements' ),
                'param_name'        => 'excerpt_len',
                'type'              => 'textfield',
                'value'             => '160',
                'holder'            => '',
                'class'             => 'excerpt_len',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display author or read more', 'auxin-elements'),
                'description'       => __('Specifies whether to show author or read more on each post.', 'auxin-elements'),
                'param_name'        => 'author_or_readmore',
                'type'              => 'dropdown',
                'def_value'         => 'readmore',
                'holder'            => '',
                'class'             => 'author_or_readmore',
                'value'             =>array (
                    'readmore'      => __( 'Read More'  , 'auxin-elements' ),
                    'author'        => __( 'Author Name', 'auxin-elements' ),
                    'none'          => __( 'None'       , 'auxin-elements' )
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Meta Info position', 'auxin-elements' ),
                'description'       => '',
                'param_name'        => 'meta_info_position',
                'type'              => 'dropdown',
                'def_value'         => 'after-title',
                'holder'            => '',
                'class'             => 'meta_info_position',
                'value'             => array (
                    'after-content'   => __('After Content' , 'auxin-elements' ),
                    'before-content'  => __('Before Content', 'auxin-elements' )
                ),
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
                'group'             => __( 'Layout', 'auxin-elements' ),
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
                'group'             => __( 'Layout', 'auxin-elements' ),
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
                'group'             => __( 'Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display items as', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'preview_mode',
                'type'              => 'dropdown',
                'def_value'         => 'grid',
                'holder'            => 'textfield',
                'class'             => 'num',
                'value'             => array(
                    'grid'           => __( 'Grid', 'auxin-elements' ),
                    'grid-table'     => __( 'Grid - Table Style', 'auxin-elements' ),
                    'grid-modern'    => __( 'Grid - Modern Style', 'auxin-elements' ),
                    'carousel-modern'=> __( 'Carousel - Modern Style', 'auxin-elements' ),
                    'carousel'       => __( 'Carousel', 'auxin-elements' )
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Content layout', 'auxin-elements'),
                'description'       => __('Specifies the style of content for each post column.', 'auxin-elements' ),
                'param_name'        => 'content_layout',
                'type'              => 'dropdown',
                'def_value'         => 'default',
                'holder'            => '',
                'class'             => 'content_layout',
                'value'             =>array (
                    'default'       => __('Full Content', 'auxin-elements'),
                    'entry-boxed'   => __('Boxed Content', 'auxin-elements')
                ),
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => array( 'grid', 'grid-table' )
                ),
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Mouse Over Effect', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'grid_table_hover',
                'type'              => 'dropdown',
                'def_value'         => 'bgimage-bgcolor',
                'holder'            => '',
                'class'             => 'num',
                'value'               => array(
                    'bgcolor'         => __( 'Background color', 'auxin-elements' ),
                    'bgimage'         => __( 'Cover image', 'auxin-elements' ),
                    'bgimage-bgcolor' => __( 'Cover image or background color', 'auxin-elements' ),
                    'none'            => __( 'Nothing', 'auxin-elements' )
                ),
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'grid-table'
                ),
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            // Carousel Options
            array(
                'heading'           => __( 'Column space', 'auxin-elements' ),
                'description'       => __( 'Specifies horizontal space between items (pixel).', 'auxin-elements' ),
                'param_name'        => 'carousel_space',
                'type'              => 'textfield',
                'value'             => '30',
                'holder'            => '',
                'class'             => 'excerpt_len',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'grid'
                ),
                'weight'            => '',
                'group'             => '',
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
                    'value'         => array( 'carousel', 'carousel-modern' )
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
                'def_value'         => 'bullets',
                'holder'            => '',
                'class'             => 'num',
                'value'             => array(
                   'arrows'         => __('Arrows', 'auxin-elements'),
                   'bullets'        => __('Bullets', 'auxin-elements'),
                   ''               => __('None', 'auxin-elements'),
                ),
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => array( 'carousel', 'carousel-modern' )
                ),
                'weight'            => '',
                'admin_label'       => false,
                'group'             => __( 'Carousel', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Control Position', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'carousel_nav_control_pos',
                'type'              => 'dropdown',
                'def_value'         => 'center',
                'holder'            => '',
                'value'             => array(
                   'center'         => __('Center', 'auxin-elements'),
                   'side'           => __('Side', 'auxin-elements'),
                ),
                'dependency'        => array(
                    'element'       => 'carousel_navigation_control',
                    'value'         => 'arrows'
                ),
                'weight'            => '',
                'admin_label'       => false,
                'group'             => __( 'Carousel', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Control Skin', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'carousel_nav_control_skin',
                'type'              => 'dropdown',
                'def_value'         => 'boxed',
                'holder'            => '',
                'value'             => array(
                   'boxed'           => __('boxed', 'auxin-elements'),
                   'long'         => __('Long Arrow', 'auxin-elements'),
                ),
                'dependency'        => array(
                    'element'       => 'carousel_navigation_control',
                    'value'         => 'arrows'
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
                    'value'         => array( 'carousel', 'carousel-modern' )
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
                    'value'         => array( 'carousel', 'carousel-modern' )
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
                    'value'         => array( 'carousel', 'carousel-modern' )
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_recent_posts_master_array', 10, 1 );




/**
 * Element without loop and column
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_recent_posts_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'                       => '',    // header title (required)
        'subtitle'                    => '',    // header title (required)
        'cat'                         => ' ',
        'num'                         => '8',   // max generated entry
        'only_posts__in'               => '',   // display only these post IDs. array or string comma separated
        'include'                     => '',    // include these post IDs in result too. array or string comma separated
        'exclude'                     => '',    // exclude these post IDs from result. array or string comma separated
        'offset'                      => '',
        'paged'                       => '',
        'post_type'                   => 'post',
        'taxonomy_name'               => 'category', // the taxonomy that we intent to display in post info
        'order_by'                    => 'date',
        'order'                       => 'DESC',

        'exclude_without_media'       => 0,
        'exclude_custom_post_formats' => 0,
        'exclude_quote_link'          => 0,
        'include_post_formats_in'     => array(), // the list od post formats to exclude
        'exclude_post_formats_in'     => array(), // the list od post formats to exclude

        'size'                        => '',
        'display_title'               => true,
        'words_num'                   => '',
        'show_media'                  => true,
        'display_like'                => true,
        'display_comments'            => true,
        'display_categories'          => true,
        'show_badge'                  => false,
        'content_layout'              => '', // entry-boxed
        'excerpt_len'                 => '160',
        'show_excerpt'                => true,
        'show_content'                => true,
        'show_info'                   => true,
        'show_format_icon'            => false,
        'show_date'                   => true,
        'ignore_formats'              => false,
        'post_info_position'          => 'after-title',
        'author_or_readmore'          => 'readmore', // readmore, author, none
        'image_aspect_ratio'          => 0.75,
        'meta_info_position'          => 'after-content',
        'desktop_cnum'                => 4,
        'tablet_cnum'                 => 'inherit',
        'phone_cnum'                  => '1',
        'preview_mode'                => 'grid',
        'grid_table_hover'            => 'bgimage-bgcolor',
        'tag'                         => '',
        'display_author_footer'       => false,
        'display_author_header'       => true,

        'preloadable'                 => false,
        'preload_preview'             => true,
        'preload_bgcolor'             => '',

        'extra_classes'               => '',
        'extra_column_classes'        => '',
        'custom_el_id'                => '',
        'carousel_space'              => '30',
        'carousel_autoplay'           => false,
        'carousel_autoplay_delay'     => '2',
        'carousel_navigation'         => 'peritem',
        'carousel_navigation_control' => 'arrows',
        'carousel_nav_control_pos'    => 'center',
        'carousel_nav_control_skin'   => 'boxed',
        'carousel_loop'               => 1,
        'request_from'                => '',
        'template_part_file'          => 'theme-parts/entry/post-column',
        'extra_template_path'         => '',

        'universal_id'                => '',
        'use_wp_query'                => false, // true to use the global wp_query, false to use internal custom query
        'reset_query'                 => true,
        'wp_query_args'               => array(), // additional wp_query args
        'custom_wp_query'             => '',
        'loadmore_type'               => '', // 'next' (more button), 'scroll', 'next-prev'
        'loadmore_per_page'           => '',
        'base'                        => 'aux_recent_posts',
        'base_class'                  => 'aux-widget-recent-posts'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    // Validate the boolean variables
    $exclude_without_media = auxin_is_true( $exclude_without_media );
    $display_like          = auxin_is_true( $display_like );
    $display_title         = auxin_is_true( $display_title );
    $show_info             = auxin_is_true( $show_info );
    $display_author_footer = auxin_is_true( $display_author_footer );
    $display_author_header = auxin_is_true( $display_author_header );

    // specify the post formats that should be excluded -------
    $exclude_post_formats_in = (array) $exclude_post_formats_in;

    if( auxin_is_true( $exclude_custom_post_formats ) ){
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
            'posts__in'               => $only_posts__in, // only posts in this list

            'exclude_without_media'   => $exclude_without_media,
            'exclude_post_formats_in' => $exclude_post_formats_in,
            'include_post_formats_in' => $include_post_formats_in
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

    echo $subtitle ? '<h4 class="widget-subtitle">' . esc_html( $subtitle ) . '</h4>' : '';


    $phone_break_point     = 767;
    $tablet_break_point    = 1025;

    $show_comments         = true; // shows comments icon
    $post_counter          = 0;
    $column_class          = '';
    $item_class            = 'aux-col';
    $carousel_attrs        = '';

    $columns_custom_styles = '';

    if( ! empty( $loadmore_type ) ) {
        $item_class        .= ' aux-ajax-item';
    }

    $tablet_cnum = ('inherit' == $tablet_cnum  ) ? $desktop_cnum : $tablet_cnum ;
    $phone_cnum  = ('inherit' == $phone_cnum  )  ? $tablet_cnum : $phone_cnum;

    if ( in_array( $preview_mode, array( 'grid', 'grid-table', 'grid-modern', 'flip' ) ) ) {
        // generate columns class
        $column_class  = 'aux-match-height aux-row aux-de-col' . $desktop_cnum;

        $column_class .=  ' aux-tb-col'.$tablet_cnum . ' aux-mb-col'.$phone_cnum . ' aux-total-'. $wp_query->post_count;

        $column_class .= 'entry-boxed' == $content_layout  ? ' aux-entry-boxed' : '';

        if ( 'flip' == $preview_mode ) {
            $column_class .= ' aux-flip-box';
        }

    } elseif ( in_array( $preview_mode, array('carousel', 'carousel-modern') ) ) {
        $column_class    = 'master-carousel aux-no-js aux-mc-before-init' . ' aux-' . $carousel_nav_control_pos . '-control';
        $item_class      = 'aux-mc-item';

        // genereate the master carousel attributes
        $carousel_attrs  =  'data-columns="' . esc_attr( $desktop_cnum ) . '"';
        $carousel_attrs .= auxin_is_true( $carousel_autoplay ) ? ' data-autoplay="true"' : '';
        $carousel_attrs .= auxin_is_true( $carousel_autoplay ) ? ' data-delay="' . esc_attr( $carousel_autoplay_delay ) . '"' : '';
        $carousel_attrs .= ' data-navigation="' . esc_attr( $carousel_navigation ) . '"';
        $carousel_attrs .= ' data-space="' . esc_attr( $carousel_space ). '"';
        $carousel_attrs .= auxin_is_true( $carousel_loop ) ? ' data-loop="' . esc_attr( $carousel_loop ) . '"' : '';
        $carousel_attrs .= ' data-wrap-controls="true"';
        $carousel_attrs .= ' data-bullets="' . ('bullets' == $carousel_navigation_control ? 'true' : 'false') . '"';
        $carousel_attrs .= ' data-bullet-class="aux-bullets aux-small aux-mask"';
        $carousel_attrs .= ' data-arrows="' . ('arrows' == $carousel_navigation_control ? 'true' : 'false') . '"';
        $carousel_attrs .= ' data-same-height="true"';

        if ( 'inherit' != $tablet_cnum || 'inherit' != $phone_cnum ) {
            $carousel_attrs .= ' data-responsive="'. esc_attr( ( 'inherit' != $tablet_cnum  ? $tablet_break_point . ':' . $tablet_cnum . ',' : '' ).
                                                               ( 'inherit' != $phone_cnum   ? $phone_break_point  . ':' . $phone_cnum : '' ) ) . '"';
        }

    }

    if ( 'grid-table' == $preview_mode ) {
        $column_class  .= ' aux-grid-table-layout aux-border-collapse';
        $column_class  .= 'none' != $grid_table_hover ? ' aux-has-bghover' : '';

        $show_media   = false;
    }

    if ( in_array( $preview_mode, array('grid-modern', 'carousel-modern') ) ) {
        $column_class  .= ' aux-grid-carousel-modern-layout';
    }

    $ignore_formats =  auxin_is_true( $ignore_formats ) ? array( '*' ) : array();

    // Specifies whether the columns have footer meta or not
    $column_class  .= 'none' === $author_or_readmore ? ' aux-no-meta' : '';
    $column_class  .= ' aux-ajax-view  ' . $extra_column_classes;

    // automatically calculate the media size if was empty
    if( empty( $size ) ){
        $column_media_width = auxin_get_content_column_width( $desktop_cnum, 15, $content_width );
        $size = array( 'width' => $column_media_width, 'height' => $column_media_width * $image_aspect_ratio );
    }

    $have_posts = $wp_query->have_posts();

    if( $have_posts ){

        echo ! $skip_wrappers ? sprintf( '<div data-element-id="%s" class="%s" %s>', esc_attr( $universal_id ), esc_attr( $column_class ), $carousel_attrs ) : '';

        while ( $wp_query->have_posts() ) {

            $wp_query->the_post();
            $post = get_post();

            $post_vars = auxin_get_post_type_media_args(
                $post,
                array(
                    'post_type'          => $post_type,
                    'request_from'       => $request_from,
                    'media_width'        => $phone_break_point,
                    'media_size'         => $size,
                    'upscale_image'      => true,
                    'image_from_content' => ! $exclude_without_media, // whether to try to get image from content or not
                    'no_gallery'         => in_array( $preview_mode, array('carousel', 'carousel-modern') ),
                    'ignore_media'       => ! $show_media,
                    'add_image_hw'       => false, // whether add width and height attr or not
                    'preloadable'        => $preloadable,
                    'preload_preview'    => $preload_preview,
                    'preload_bgcolor'    => $preload_bgcolor,
                    'image_sizes'        => 'auto',
                    'srcset_sizes'       => 'auto',
                    'ignore_formats'     => $ignore_formats
                ),
                $content_width
            );

            extract( $post_vars );
            $the_format = get_post_format( $post );

            // add specific class to current classes for each column
            $post_classes  = $has_attach && $show_media ? 'post column-entry' : 'post column-entry no-media';

            // generate custom inline style base on feature colors for each post if the preview mode is table cell
            if ( 'grid-table' == $preview_mode ) {

                $featured_color = '';
                $featured_image = '';

                if( false !== strpos( $grid_table_hover, 'bgcolor' ) ){
                    $featured_color = get_post_meta( $post->ID, 'auxin_featured_color_enabled', true ) ? get_post_meta( $post->ID, 'auxin_featured_color', true ) :
                                      auxin_get_option( 'post_single_featured_color' );
                }
                if( false !== strpos( $grid_table_hover, 'bgimage' ) ){
                    $featured_image = auxin_get_the_attachment_url( $post, array( $column_media_width, $column_media_width ) );
                }
                // if grid table hover effect was only bgcolor
                if( 'bgcolor' == $grid_table_hover ){
                    $columns_custom_styles .= $featured_color ? "\n.$base_class .aux-grid-table-layout > .post-{$post->ID}:hover { background-color:$featured_color; }" : '';

                // if grid table hover effect was only bgimage
                } elseif( 'bgimage' == $grid_table_hover ){
                    $columns_custom_styles .= $featured_image ? "\n.$base_class .aux-grid-table-layout > .post-{$post->ID}:hover { background-image:linear-gradient( rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4) ), url( $featured_image ); }" : '';

                // if grid table hover effect was bgimage with bgcolor fallback
                } elseif( 'bgimage-bgcolor' == $grid_table_hover ){
                    if( $featured_image ){
                        $columns_custom_styles .= "\n.$base_class .aux-grid-table-layout > .post-{$post->ID}:hover { background-image:linear-gradient( rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4) ), url( $featured_image ); }" ;
                    } elseif( $featured_color ){
                        $columns_custom_styles .= "\n.$base_class .aux-grid-table-layout > .post-{$post->ID}:hover { background-color:$featured_color; }";
                    }
                }

            }

            $template_part_file = 'flip' === $preview_mode ? 'theme-parts/entry/post-flip' : $template_part_file;

            // Generate the markup by template parts
            if( has_action( $base_class . '-template-part' ) ){
                do_action(  $base_class . '-template-part', $result, $post_vars, $item_class );

            } else {
                printf( '<div class="%s post-%s">', esc_attr( $item_class ), esc_attr( $post->ID ) );
                include auxin_get_template_file( $template_part_file, '', $extra_template_path );
                echo    '</div>';
            }

        }


        // print the custom inline style if available
        echo $columns_custom_styles ? "<style>$columns_custom_styles</style>" : '';

        if ( in_array( $preview_mode, array('carousel', 'carousel-modern') ) && 'arrows' == $carousel_navigation_control ) {
        if ( 'boxed' === $carousel_nav_control_skin ) :?>
            <div class="aux-carousel-controls">
                <div class="aux-next-arrow aux-arrow-nav aux-outline aux-hover-fill">
                    <span class="aux-svg-arrow aux-small-right"></span>
                    <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-right"></span>
                </div>
                <div class="aux-prev-arrow aux-arrow-nav aux-outline aux-hover-fill">
                    <span class="aux-svg-arrow aux-small-left"></span>
                    <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-left"></span>
                </div>
            </div>
        <?php else : ?>
            <div class="aux-carousel-controls">
                <div class="aux-next-arrow">
                    <span class="aux-svg-arrow aux-l-right"></span>
                </div>
                <div class="aux-prev-arrow">
                    <span class="aux-svg-arrow aux-l-left"></span>

                </div>
            </div>
        <?php  endif;
        }

        if( ! $skip_wrappers ) {
            // End tag for aux-ajax-view wrapper
            echo '</div>';
            // Execute load more functionality
            if( ! in_array( $preview_mode, array('carousel', 'carousel-modern') ) && $wp_query->found_posts > $loadmore_per_page ) {
                echo auxin_get_load_more_controller( $loadmore_type );
            }

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

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
