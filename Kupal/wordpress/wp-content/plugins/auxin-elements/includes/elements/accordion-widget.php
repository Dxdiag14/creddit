<?php
/**
 * Accordion Elements Just for Widgets
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function  auxin_get_accordion_widget_master_array( $master_array ) {

     $master_array['aux_accordion_widget'] = array(
        'name'                    => __(' Accordion', 'auxin-elements'),
        'auxin_output_callback'   => 'auxin_get_accordion_widget_callback',
        'base'                    => 'aux_accordion_widget',
        'description'             => __('Collapsible content', 'auxin-elements'),
        'class'                   => 'aux-widget-accordion widget-toggle',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'is_so'                   => false,
        'is_vc'                   => false,
        'category'                => THEME_NAME,
        'group'                   => '',
        'so_api'                  => true,
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-accordion',
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
                'description'      => __('Accordion title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'       => 'title',
                'type'             => 'textfield',
                'value'            => __( 'Accordion Title', 'auxin-elements' ),
                'holder'           => 'textfield',
                'class'            => 'title',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Type','auxin-elements'),
                'description'      => __('Whether to show only 1 element opens at a time or multiple.','auxin-elements'),
                'param_name'       => 'type',
                'type'             => 'dropdown',
                'def_value'        => 'true',
                'value'            => array(
                    'false'     => __('Toggle', 'auxin-elements'),
                    'true'      => __('Accordion', 'auxin-elements'),
                ),
                'holder'           => '',
                'class'            => 'type',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
             array(
                'heading'          => __('Content', 'auxin-elements'),
                'description'      => __('Enter your accordion item content.', 'auxin-elements'),
                'param_name'       => 'content',
                'type'             => 'textarea_html',
                'value'            => '',
                'def_value'        => '[aux_accordion_tab label="Your Label"]
Your Content
[/aux_accordion_tab]',
                'holder'           => 'div',
                'class'            => 'content',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
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
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_accordion_widget_master_array', 10, 1 );


/**
 * Element without loop and column
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_get_accordion_widget_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'           => '', // header title
        'type'            => 'true', // accordion/toggle
        'content'         => '', // Custom Shortcodes for content
        'extra_classes'   => '', // custom css class names for this element
        'custom_el_id'    => '', // custom id attribute for this element
        'base_class'      => 'aux-widget-accordion widget-toggle'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );

    extract( $result['parsed_atts'] );

    // widget header ------------------------------
    $output  = $result['widget_header'];
    $output .= $result['widget_title'];
    $output .= '<div class="widget-inner ' .$extra_classes . '" data-toggle="' . $type . '">';

    // widget custom output -----------------------

    $output .= do_shortcode( $content ) ;

    $output .= '</div>'; // End od widget-toggle
    $output .= $result['widget_footer'];

    // widget footer ------------------------------

    return $output;

}

function auxin_accordion_tab_shortcode( $atts, $content = null ) {

    $output = '';

    extract( shortcode_atts(
        array(
            "label" => 'Label'
        ), $atts )
    );
    $output .= '<section>';
    $output .= '<h6 class="toggle-header">' . $label . '</h6>';
    $output .= '<div class="toggle-content"><p>' . $content . '</p></div>';
    $output .= '</section>';

    return $output;
}

add_shortcode('aux_accordion_tab', 'auxin_accordion_tab_shortcode');
