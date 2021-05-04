<?php
/**
 * Tabs Element just For Widgets
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_tabs_widget_master_array( $master_array ) {

    $master_array['aux_tabs_widget'] = array( // shortcode info here
        'name'                    => __('Tabs', 'auxin-elements'),
        'auxin_output_callback'   => 'auxin_get_tabs_widget_callback',
        'base'                    => 'aux_tabs_widget',
        'description'             => __('It adds tabs element.', 'auxin-elements'),
        'class'                   => 'aux-widget-tabs',
        'show_settings_on_create' => true,
        'so_api'                  => true,
        'weight'                  => 1,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'is_so'                   => false,
        'is_vc'                   => false,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-tab',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Title','auxin-elements'),
                'description'       => __('Tabs title, leave it empty if you don`t need title.', 'auxin-elements'),
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
                'heading'           => __('Style', 'auxin-elements'),
                'description'       => __('Choose between bordered style and no border.', 'auxin-elements'),
                'param_name'        => 'style',
                'type'              => 'dropdown',
                'def_value'         => 'bordered',
                'holder'            => '',
                'class'             => 'style',
                'value'             =>array (
                    'bordered'          => __('Bordered', 'auxin-elements'),
                    'aux-stripe'        => __('No border', 'auxin-elements'),
                ),
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('First Tab Label','auxin-elements'),
                'description'      => __('Enter your tab item label.', 'auxin-elements'),
                'param_name'       => 'first_tab_label',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'tab_label',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('First Tab Content', 'auxin-elements'),
                'description'      => __('Enter your tab item content.', 'auxin-elements'),
                'param_name'       => 'first_content',
                'type'             => 'textarea_html',
                'value'            => '',
                'def_value'        => '',
                'holder'           => 'div',
                'class'            => 'content',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Second Tab Label','auxin-elements'),
                'description'      => __('Enter your tab item label.', 'auxin-elements'),
                'param_name'       => 'second_tab_label',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'tab_label',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Second Tab Content', 'auxin-elements'),
                'description'      => __('Enter your tab item content.', 'auxin-elements'),
                'param_name'       => 'second_content',
                'type'             => 'textarea_html',
                'value'            => '',
                'def_value'        => '',
                'holder'           => 'div',
                'class'            => 'content',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Third Tab Label','auxin-elements'),
                'description'      => __('Enter your tab item label.', 'auxin-elements'),
                'param_name'       => 'third_tab_label',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'tab_label',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Third Tab Content', 'auxin-elements'),
                'description'      => __('Enter your tab item content.', 'auxin-elements'),
                'param_name'       => 'third_content',
                'type'             => 'textarea_html',
                'value'            => '',
                'def_value'        => '',
                'holder'           => 'div',
                'class'            => 'content',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements'),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements'),
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_tabs_widget_master_array', 10, 1 );

/**
 * Sample element markup for front-end
 * In other words, the front-end output of this element is returned by the following function
 *
 *
 * @param  array  $atts              The array containing the parsed values from shortcode,it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_get_tabs_widget_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'            => '', // header title
        'style'            => 'bordered', // header title
        'first_tab_label'  => '', // first label title
        'first_content'    => '', // first content
        'second_tab_label' => '', // second title
        'second_content'   => '', // second content
        'third_tab_label'  => '', // third title
        'third_content'    => '', // third content
        'extra_classes'    => '', // custom css class names for this element
        'custom_el_id'     => '', // custom id attribute for this element
        'base_class'       => 'aux-widget-tabs'  // base class name for container
    );

    $result              = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    $output              = '';
    $tabs_count           = 0;
    $tabs_info           = array () ;
    $tabs_markup         = '';
    $tabs_content_markup = '';

    extract( $result['parsed_atts'] );

    $tabs_info = array (
        array( 'label' => $first_tab_label  , 'content' => $first_content  ),
        array( 'label' => $second_tab_label , 'content' => $second_content ),
        array( 'label' => $third_tab_label  , 'content' => $third_content  )
    );

    foreach ( $tabs_info as $tab ) {

        if ( !empty ( $tab['label'] ) ) {
            $tabs_markup         .= '<li><a href="#">' . esc_html ( $tab['label'] ) . '</a></li>';
            $tabs_content_markup .= '<li>' . $tab['content'] . '</li>';
            $tabs_count++;
        }


    }

    $style        = 'bordered' === $style ? '': 'aux-stripe aux-fill ';
    $widget_class = 'widget-tabs ' . esc_attr( $style ) . esc_attr( $extra_classes ) ;

    // widget header ------------------------------
    $output .= $result['widget_header'];
    $output .= $result['widget_title'];

    // widget custom output -----------------------
    $output .= '<div class="'. esc_attr( $widget_class ) .'">';
        $output .= '<div class="widget-inner">';

            if( $tabs_count > 1 ){
                $output .= '<ul class="tabs">';
                    $output .= $tabs_markup ;
                $output .= '</ul>';
            }

            $output .= '<ul class="tabs-content">';
                $output .= $tabs_content_markup ;
            $output .= '</ul>';

        $output .= '</div>';
    $output .= '</div>' ;


    // widget footer ------------------------------
    $output .= $result['widget_footer'];

    return $output;
}
