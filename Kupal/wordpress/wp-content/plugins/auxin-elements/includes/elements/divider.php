<?php
/**
 * Divider element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function auxin_get_divider_master_array( $master_array ) {

    $master_array['aux_divider']  = array(
        'name'                    => __('Divider', 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_widget_divider_callback',
        'base'                    => 'aux_divider',
        'description'             => __('Horizontal separator', 'auxin-elements'),
        'class'                   => 'aux-widget-divider',
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
        'icon'                    => 'aux-element aux-pb-icons-divider',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'            => __('Divider style','auxin-elements'),
                'description'        => '',
                'param_name'         => 'style',
                'type'               => 'aux_visual_select',
                'def_value'          => "solid",
                'holder'             => '',
                'class'              => 'style',
                'admin_label'        => true,
                'dependency'         => '',
                'weight'             => '',
                'group'              => '' ,
                'edit_field_class'   => '',
                'choices'            => array(
                    'white-space'    => array(
                        'label'      => __('White Space', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-white-space.svg'
                    ),
                    'solid'          => array(
                        'label'      => __('Solid', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-solid.svg'
                    ),
                    'dashed'         => array(
                        'label'      => __('Dashed', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-dashed.svg'
                    ),
                    'circle-symbol'  => array(
                        'label'      => __('Circle', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-circle.svg'
                    ),
                    'diamond-symbol' => array(
                        'label'      => __('Diamond', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-diamond.svg'
                    ),
                    'vertical' => array(
                        'label'      => __('Vertical Line', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-diamond.svg'
                    )
                )
            ),
            array(
                'heading'           => __('Divider size','auxin-elements'),
                'description'       => __('Specifies the size of divider.', 'auxin-elements'),
                'param_name'        => 'width',
                'type'              => 'dropdown',
                'value'             => array(
                    'large'         => __('Large', 'auxin-elements'),
                    'medium'        => __('Medium', 'auxin-elements'),
                    'small'         => __('Small', 'auxin-elements'),
                    'tiny'          => __('Tiny', 'auxin-elements')
                ),
                'def_value'         => 'medium',
                'holder'            => '',
                'class'             => 'width',
                'admin_label'       => true,
                'dependency'        => array(
                    'element'       => 'style',
                    'value'         => array('solid', 'dashed', 'circle-symbol', 'diamond-symbol', 'vertical')
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Divider color', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'divider_color',
                'type'              => 'colorpicker',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'divider-color',
                'dependency'        => array(
                    'element'       => 'style',
                    'value'         => array('solid', 'dashed', 'circle-symbol', 'diamond-symbol', 'vertical')
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Margin top (px)','auxin-elements'),
                'description'       => __('Adds space above  the divider in pixels.', 'auxin-elements'),
                'param_name'        => 'margin_top',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'margin_top',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Margin bottom (px)','auxin-elements'),
                'description'       => __('Adds space below the divider in pixels.', 'auxin-elements'),
                'param_name'        => 'divider_margin_bottom',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'divider_margin_bottom',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements'),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file. you can add multiple CSS class by separating them with space.', 'auxin-elements'),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => '',
                'class'             => 'extra_classes',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_divider_master_array', 10, 1 );



// This is the widget call back in fact the front end out put of this widget comes from this function
function auxin_widget_divider_callback( $atts, $shortcode_content = null ){

    $default_atts = array(
        'title'                 => '',
        'size'                  => '',
        'style'                 => 'solid',
        'width'                 => 'medium',
        'divider_color'         => '',
        'base_class'            => '',
        'divider_margin_bottom' => '',
        'margin_top'            => '',
        'alignment'             => 'center', // divider alignment
        'symbol_alignment'      => 'center', // symbol alignment

        'extra_classes'         => '',
        'custom_el_id'          => '',
        'base_class'            => 'aux-widget-divider'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    $class_names        = "";
    $symbol_class_names = "";
    $inline_styles      = "";

    // Divider style
    switch ( $style ) {
        case 'solid':
            $class_names = "aux-divider-center";
        break;
        case 'dashed':
            $class_names = "aux-divider-dashed";
        break;
        case 'circle-symbol':
            $class_names = "aux-divider-symbolic-circle";
        break;
        case 'diamond-symbol':
            $class_names = "aux-divider-symbolic-square";
        break;
        case 'white-space':
            $class_names = "aux-divider-space";
        break;
        case 'vertical':
            $class_names = "aux-divider-vertical";
    }

    // Divider width
    switch ( $width ) {
        case 'large':
            $class_names .= "";
        break;
        case 'medium':
            $class_names .= " aux-divider-medium";
        break;
        case 'small':
            $class_names .= " aux-divider-small";
        break;
        case 'tiny':
            $class_names .= " aux-divider-tiny";
        break;
    }

    // Divider alignment
    if( ! empty( $alignment ) ){
        $class_names .= " aux-divider-align-" . $alignment;
    }

    // Symbol alignment
    if( ! empty( $symbol_alignment ) ){
        $class_names .= " aux-symbol-align-" . $symbol_alignment;
    }

    if( ! empty( $extra_classes ) ) {
        $class_names .= " $extra_classes";
    }

    if( ! empty( $margin_top ) ) {
        $margin_top = (int) $margin_top;
        $inline_styles .= "margin-top: $margin_top" . "px;";
    }

    if( ! empty( $divider_margin_bottom ) ) {
        $divider_margin_bottom = (int) $divider_margin_bottom;
        $inline_styles .= "margin-bottom:$divider_margin_bottom" . "px;";
    }

    if( ! empty( $divider_color ) ) {
        $inline_styles .= "border-color:$divider_color" . ";";
    }

    if( ! empty( $inline_styles ) ) {
        $inline_styles = ' style="' . $inline_styles . '" ';
    }

    return '<hr class="'. esc_attr( $class_names ) . '"' . $inline_styles . ' >';
}

