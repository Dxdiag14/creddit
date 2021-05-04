<?php
/**
 * Search element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_search_master_array( $master_array ) {
    $master_array['aux_search']  = array(
        'name'                    => __("Search", 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_widget_search_field_callcack',
        'base'                    => 'aux_search',
        'description'             => __('Fancy search field', 'auxin-elements'),
        'class'                   => 'aux-widget-search',
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
        'icon'                    => 'aux-element aux-pb-icons-search',
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
                'description'       => __('Search title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements'),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements'),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_search_master_array', 10, 1 );

/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_search_field_callcack( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'             => '', // header title
        'has_toggle_icon'   => false,
        'has_field'         => true,
        'has_submit'        => false,
        'has_form'          => true,
        'has_submit_icon'   => true, // this option added for changing submit type
        'toggle_icon_class' => '',

        'extra_classes'     => '', // custom css class names for this element
        'custom_el_id'      => '', // custom id attribute for this element
        'base_class'        => 'aux-widget-search'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();
    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    //  The output for element here
    echo auxin_get_search_box(array(
        'has_toggle_icon'   => $has_toggle_icon,
        'has_field'         => $has_field,
        'has_submit'        => $has_submit,
        'has_form'          => $has_form,
        'has_submit_icon'   => $has_submit_icon, // this option added for changing submit type
        'css_class'         => $extra_classes,
        'toggle_icon_class' => $toggle_icon_class
    ));

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
