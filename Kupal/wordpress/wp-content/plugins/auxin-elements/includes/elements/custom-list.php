<?php
/**
 * List element if site origin bundle plugin is activaited
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function auxin_get_list_master_array( $master_array ) {

     $master_array['aux_icon_list'] = array(
        'name'                    => __('List', 'auxin-elements'),
        'auxin_output_callback'   => 'auxin_widget_list_callback',
        'base'                    => 'aux_icon_list',
        'description'             => __('Icon List', 'auxin-elements'),
        'class'                   => 'aux-widget-icon-list',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => false,
        'is_shortcode'            => true,
        'is_so'                   => true,
        'is_vc'                   => false,
        'category'                => THEME_NAME,
        'group'                   => '',
        'so_api'                  => true,
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-list',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'          => __('Title','auxin-elements'),
                'description'      => __('leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'       => 'title',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'title',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Text','auxin-elements'),
                'repeater'         => 'list',
                'repeater-label'   => __('List', 'auxin-elements'),
                'section-name'     => __('List Item', 'auxin-elements'),
                'param_name'       => 'text_primary',
                'type'             => 'textfield',
                'value'            => __('List Item', 'auxin-elements' ),
                'holder'           => 'textfield',
                'class'            => 'text_primary',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Icon','auxin-elements'),
                'repeater'         => 'list',
                'repeater-label'   => __('List', 'auxin-elements'),
                'section-name'     => __('List Item', 'auxin-elements'),
                'param_name'       => 'icon',
                'type'             => 'aux_iconpicker',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'item_icon',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Text Tag','auxin-elements'),
                'repeater'         => 'list',
                'repeater-label'   => __('List', 'auxin-elements'),
                'section-name'     => __('List Item', 'auxin-elements'),
                'param_name'       => 'text_tag',
                'type'             => 'textfield',
                'value'            => __('List Item', 'auxin-elements' ),
                'holder'           => 'textfield',
                'class'            => 'text_tag',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Link','auxin-elements'),
                'repeater'         => 'list',
                'repeater-label'   => __('List', 'auxin-elements'),
                'section-name'     => __('List Item', 'auxin-elements'),
                'param_name'       => 'link',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'link',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Text Seconday','auxin-elements'),
                'repeater'         => 'list',
                'repeater-label'   => __('List', 'auxin-elements'),
                'section-name'     => __('List Item', 'auxin-elements'),
                'param_name'       => 'text_secondary',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'text_secondary',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Description','auxin-elements'),
                'repeater'         => 'list',
                'repeater-label'   => __('List', 'auxin-elements'),
                'section-name'     => __('List Item', 'auxin-elements'),
                'param_name'       => 'description',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'description',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'           => __('Display Connector','auxin-elements' ),
                'description'       => __('Whether to display a line that connects two texts in each list item.', 'auxin-elements'),
                'param_name'        => 'connector',
                'type'              => 'dropdown',
                'def_value'         => 'no',
                'value'             => array(
                    'yes'  => __('Yes', 'auxin-elements' ),
                    'no'   => __('No' , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display Divider','auxin-elements' ),
                'description'       => __('Whether to display a divider between list items or not.', 'auxin-elements'),
                'param_name'        => 'divider',
                'type'              => 'dropdown',
                'def_value'         => 'no',
                'value'             => array(
                    'yes'  => __('Yes', 'auxin-elements' ),
                    'no'   => __('No' , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Extra class name','auxin-elements'),
                'description'      => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements'),
                'param_name'       => 'extra_classes',
                'type'             => 'textfield',
                'value'            => '',
                'def_value'        => '',
                'holder'           => 'textfield',
                'class'            => 'extra_classes',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_list_master_array', 10, 1 );


/**
 * List Element
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_list_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'             => '', // header title
        'list'              => '', // repeater items
        'direction'         => 'default',
        'connector'         => 'no',
        'divider'           => 'no',
        'title_tag'         => 'h6',
        'item_class_prefix' => '', // Default class prefix for each repeater item
        'extra_classes'     => '', // custom css class names for this element
        'custom_el_id'      => '', // custom id attribute for this element
        'base_class'        => 'aux-widget-icon-list'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    // widget header ------------------------------
    $output  = $result['widget_header'];
    $output .= $result['widget_title'];

    $output .= '<div class="widget-inner">';
    $output .= '<div class="aux-widget-icon-list-inner">';

    // Attrs for list wrapper
    $list_attrs = array(
        'class' => array( 'aux-icon-list-items', 'aux-direction-' . esc_attr( $direction ) )
    );
    if( auxin_is_true( $divider ) ){
        $list_attrs['class'][] = 'aux-icon-list-divider';
    }
    $output .= '<ul '. auxin_make_html_attributes( $list_attrs ) .'>';

    // widget custom output -----------------------
    if ( is_array( $list ) || is_object( $list ) ) {
        foreach ( $list as $index => $list_item ) {

            // Class for each repeater item
            $item_classes = array( 'aux-icon-list-item' );

            if( auxin_is_true( $connector ) ){
                $item_classes[] = 'aux-list-item-has-connector';
            }

            if( ! empty( $list_item['custom_class_name'] ) ) {
                $item_classes[] = $list_item['custom_class_name'];
            }

            if( $item_unique_id = ! empty( $list_item['_id'] ) ? $list_item['_id'] : '' ){
                $item_classes[] = 'aux-list-item-has-icon';
                $item_classes[] = 'aux-icon-list-item-'. $item_unique_id;
                $item_classes[] = 'elementor-repeater-item-'. $item_unique_id;
            }

            $item_text_tag = $list_item['text_tag'];

            $output .= '<li '. auxin_make_html_attributes( array( 'class' => $item_classes ) ) .'>';

            // Generate link for list item
            if( ! empty( $list_item['link']['url'] ) ){
                $link_attrs = array(
                    'class' =>  array( 'aux-icon-list-link' ),
                    'href'  => esc_url( $list_item['link']['url'] )
                );
                if( ! empty( $list_item['link']['is_external'] ) ){
                    $link_attrs['target'] = '_blank';
                }
                if( ! empty( $list_item['link']['nofollow'] ) ){
                    $link_attrs['rel'] = 'nofollow';
                }
                $output .= '<a '. auxin_make_html_attributes( $link_attrs ) .'>';
            }

            // a fix to prevent unwanted default value 'check-1' which is set in elementor
            if( ! empty( $list_item['icon'] ) && 'check-1' != $list_item['icon'] ){
                $output .= '<span '. auxin_make_html_attributes( array( 'class' => array( 'aux-icon-list-icon', $list_item['icon'] ) ) ) .'></span>';
            }
            if( ! empty( $list_item['text_primary'] ) ){
                $output .= "<$item_text_tag ". auxin_make_html_attributes( array( 'class' => array( 'aux-icon-list-text' ) ) ) .'>' . $list_item['text_primary'] . "</$item_text_tag>";
            }
            if( auxin_is_true( $connector ) ){
                $output .= '<span '. auxin_make_html_attributes( array( 'class' => array( 'aux-list-connector' ) ) ) .'></span>';
            }
            if( ! empty( $list_item['text_secondary'] ) ){
                $output .= '<span '. auxin_make_html_attributes( array( 'class' => array( 'aux-icon-list-text2' ) ) ) .'>' . $list_item['text_secondary'] . '</span>';
            }
            if( ! empty( $list_item['description'] ) ){
                $output .= '<p '. auxin_make_html_attributes( array( 'class' => array( 'aux-icon-list-description' ) ) ) .'>' . $list_item['description'] . '</p>';
            }

            if( ! empty( $list_item['link']['url'] ) ){
                $output .= '</a>';
            }

            $output .= '</li>';
        }
    }

    $output .= '</ul></div></div>'; // End od widget-toggle
    $output .= $result['widget_footer'];

    // widget footer ------------------------------

    return $output;
}
