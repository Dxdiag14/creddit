<?php
/**
 * Instagram feed
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_instagram_master_array( $master_array ) {

   $master_array['aux_instagram_feed'] = array( // shortcode info here
        'name'                    => __("instagram feed", 'auxin-elements'  ),
        'auxin_output_callback'   => 'auxin_widget_instagram_feed_callback',
        'base'                    => 'aux_instagram_feed',
        'description'             => __('It adds an instagram feed element.', 'auxin-elements' ),
        'class'                   => 'aux-widget-instagram',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'is_so'                   => true,
        'is_vc'                   => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-image',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
              array(
                'heading'           => __('Title','auxin-elements' ),
                'description'       => __('A title for instagram element', 'auxin-elements' ),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Instagram user ID','auxin-elements' ),
                'description'       => sprintf(
                    __('Instagram User ID. Separate multiple IDs by commas. You can find the User ID with %sthis tool%s.', 'auxin-elements' ),
                    '<a href="https://smashballoon.com/instagram-feed/find-instagram-user-id/" target="_blank">',
                    '</a>'
                ),
                'param_name'        => 'id',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'user_id',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Number of photo','auxin-elements' ),
                'description'       => __('Number of photos to display initially. Maximum is 33.','auxin-elements' ),
                'param_name'        => 'num',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'num',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
             array(
                'heading'           => __('Resolution/size of the photos. ','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'imageres',
                'type'              => 'dropdown',
                'def_value'         => 'medium',
                'value'             => array(
                    'auto'       => __('Auto', 'auxin-elements' ),
                    'thumb'      => __('Thumb', 'auxin-elements' ),
                    'medium'     => __('Medium', 'auxin-elements' ),
                    'full'       => __('Full', 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'border',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
             array(
                'heading'           => __('Columns','auxin-elements' ),
                'description'       => __('Number of columns in the feed. 1 - 10.','auxin-elements' ),
                'param_name'        => 'cols',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'columns',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
             array(
                'heading'           => __('Spacing around the photos','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'imagepadding',
                'type'              => 'textfield',
                'value'             => '0',
                'def_value'         => '0',
                'holder'            => 'textfield',
                'class'             => 'height',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Unit of the spacing.','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'imagepaddingunit',
                'type'              => 'dropdown',
                'def_value'         => 'px',
                'value'             => array(
                    '%'     => __('Percentage (%)', 'auxin-elements' ),
                    'px'    => __('Pixels (px)', 'auxin-elements' ),
                ),
                'holder'            => '',
                'class'             => 'border',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            // array(
            //     'param_name'    => 'width',
            //     'type'          => 'textfield',
            //     'value'         => '100',
            //     'def_value'     => '100',
            //     'holder'        => 'textfield',
            //     'class'         => 'width',
            //     'heading'       => __('width','auxin-elements' ),
            //     'description'   => '',
            //     'admin_label'   => true,
            //     'dependency'    => '',
            //     'weight'    => '',
            //     'group' => '' ,
            //     'edit_field_class'  => ''
            // ),
            // array(
            //     'param_name'        => 'widthunit',
            //     'type'              => 'dropdown',
            //     'def_value'         => '%',
            //     'value'             => array(
            //         '%'     => __('Percentage (%)', 'auxin-elements' ),
            //         'px'    => __('Pixels (px)', 'auxin-elements' ),
            //     ),
            //     'holder'            => '',
            //     'class'             => 'border',
            //     'heading'           =>  __(' The unit of the width.','auxin-elements' ),
            //     'description'       => '',
            //     'admin_label'       => true,
            //     'dependency'        => '',
            //     'weight'            => '',
            //     'group'             => '' ,
            //     'edit_field_class'  => ''
            // ),
            array(
                'heading'           => __('Height','auxin-elements' ),
                'description'       => __('The height of the feed','auxin-elements' ),
                'param_name'        => 'height',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'height',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Unit of the height.','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'heightunit',
                'type'              => 'dropdown',
                'def_value'         => 'px',
                'value'             => array(
                    '%'     => __('Percentage (%)', 'auxin-elements' ),
                    'px'    => __('Pixels (px)', 'auxin-elements' ),
                ),
                'holder'            => '',
                'class'             => 'border',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
             array(
                'heading'           => __('Background color','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'background',
                'type'              => 'color',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'background-color',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
             array(
                'heading'           => __('Show header','auxin-elements' ),
                'description'       => __('Whether to show the feed header.', 'auxin-elements' ),
                'param_name'        => 'showheader',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '0',
                'holder'            => '',
                'class'             => 'showheader',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Show follow button','auxin-elements' ),
                'description'       => __('Whether to show the Follow on Instagram button.', 'auxin-elements' ),
                'param_name'        => 'showfollow',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '0',
                'holder'            => '',
                'class'             => 'showfollow',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Show load more button','auxin-elements' ),
                'description'       => __(' Whether to show the load more button.', 'auxin-elements' ),
                'param_name'        => 'showbutton',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '0',
                'holder'            => '',
                'class'             => 'showbutton',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
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
                'group'             => '' ,
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_instagram_master_array', 10, 1 );

// This is the widget call back in fact the front end out put of this widget comes from this function
function auxin_widget_instagram_feed_callback( $atts, $shortcode_content = null ) {

    $default_atts = array(
        'title'            => '',
        'id'               => '',
        'width'            => '100',
        'widthunit'        => '%',
        'height'           => '',
        'heightunit'       => 'px',
        'background'       => '',
        'imagepadding'     => '0',
        'imagepaddingunit' => 'px',
        'cols'             => '',
        'num'              => '',
        'showheader'       => '0',
        'imageres'         => 'medium',
        'showfollow'       => '0',
        'showbutton'       => '0',

        'extra_classes'    => '', // custom css class names for this element
        'custom_el_id'     => '', // custom id attribute for this element
        'base_class'       => 'aux-widget-instagram'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];


    // widget custom output -----------------------
    if( ! empty( $id ) ) {
        $id =" id=$id";
    }

    // if( ! empty( $width ) ) {
    //     $width =" width=$width";
    // }

    if( ! empty( $height ) ) {
        $height =" height=$height";
    }

     if( ! empty( $background ) ) {
        $background =" background=\"$background\"";
    }

    if( ! empty( $cols ) ) {
        $cols =" cols=$cols";
    }

    if( ! empty( $num ) ) {
        $num =" num=$num";
    }

    if( ! empty( $extra_classes ) ) {
        $extra_classes =" class=\"$extra_classes\"";
    }

    $instagram_feed_shortcode = "[instagram-feed width=100 widthunit=\"%\"".
        $id.
        " showbutton=$showbutton".
        " showfollow=$showfollow".
        " showheader=$showheader".
        " imageres=\"$imageres\"".
        // $width.
        //" widthunit=\"$widthunit\"".
        $height.
        " heightunit=\"$heightunit\"".
        " imagepaddingunit=\"$imagepaddingunit\"".
        " imagepadding=$imagepadding".
        $background.
        $cols.
        $num.
        $extra_classes.
    "]";

    echo do_shortcode( $instagram_feed_shortcode );
    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
