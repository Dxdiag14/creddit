<?php
/**
 * Hightlight text
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_highlight_master_array( $master_array ) {

    $master_array['aux_highlight'] = array(
        'name'                    => __("Highlight", 'auxin-elements'  ),
        'auxin_output_callback'   => 'auxin_widget_highlight_callback',
        'base'                    => 'aux_highlight',
        'description'             => __('Highlighted Text', 'auxin-elements' ),
        'class'                   => 'auxin-widget-highlight',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => false,
        'is_shortcode'            => true,
        'is_so'                   => false,
        'is_vc'                   => false,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => '',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Divider style','auxin-elements' ),
                'description'       => __('The style of divider.','auxin-elements' ),
                'param_name'        => 'style',
                'type'              => 'aux_visual_select',
                'std'               => "aux-highlight-red",
                'holder'            => 'dropdown',
                'class'             => 'style',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => '',
                'choices' => array(
                    'aux-highlight-red' => array(
                        'label'     => __('Red', 'auxin-elements' ),
                        'css_class' => 'axiAdminIcon-none',
                        'image'     => ''
                    ),
                    'aux-highlight-blue' => array(
                        'label'     => __('Blue', 'auxin-elements' ),
                        'css_class' => 'axiAdminIcon-repeat-y',
                        'image'     => ''
                    ),
                    'aux-highlight-yellow' => array(
                        'label'     => __('Yellow', 'auxin-elements' ),
                        'css_class' => 'axiAdminIcon-repeat-x',
                        'image'     => ''
                    ),
                    'aux-highlight-green' => array(
                        'label'     => __('Green', 'auxin-elements' ),
                        'css_class' => 'axiAdminIcon-repeat-y',
                        'image'     => ''
                    )
                )
            ),
            array(
                'heading'           => __('Content','auxin-elements' ),
                'description'       => __('Enter the text to be highlighted.', 'auxin-elements' ),
                'param_name'        => 'content',
                'type'              => 'textarea_html',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'div',
                'class'             => 'content',
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_highlight_master_array', 10, 1 );



/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_highlight_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'         => '', // header title
        'content'       => '', // header title
        'style'         => 'aux-highlight-red',

        'extra_classes' => '', // custom css class names for this element
        'custom_el_id'  => '', // custom id attribute for this element
        'base_class'    => 'auxin-widget-highlight'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    if( ! empty( $extra_classes ) ) {
        $style .= " $extra_classes";
    }

    echo '<span class="aux-highlight ' . $style . '">' . $content . '</span>';

    return ob_get_clean();
}
