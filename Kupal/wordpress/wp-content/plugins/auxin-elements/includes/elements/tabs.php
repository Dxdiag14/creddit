<?php
/**
 * Tabs element if site origin bundle plugin is activaited
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_tabs_master_array( $master_array ) {

    $master_array['aux_tabs'] = array( // shortcode info here
        'name'                    => __('Tabs', 'auxin-elements'),
        'auxin_output_callback'   => 'auxin_widget_tabs_callback',
        'base'                    => 'aux_tabs',
        'description'             => __('It adds tabs element.', 'auxin-elements'),
        'class'                   => 'aux-widget-tabs',
        'show_settings_on_create' => true,
        'so_api'                  => true,
        'weight'                  => 1,
        'is_widget'               => false,
        'is_shortcode'            => true,
        'is_so'                   => true,
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
                'heading'          => __('Tab label','auxin-elements'),
                'description'      => __('Enter your tab item label.', 'auxin-elements'),
                'repeater'         => 'tabs',
                'repeater-label'   => __('Tabs', 'auxin-elements'),
                'section-name'     => __('Tabs Section', 'auxin-elements'),
                'param_name'       => 'tab_label',
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
                'heading'          => __('Content', 'auxin-elements'),
                'description'      => __('Enter your tab item content.', 'auxin-elements'),
                'repeater'         => 'tabs',
                'section-name'     => __('Tabs section', 'auxin-elements'),
                'repeater-label'   => __('Tabs', 'auxin-elements'),
                'param_name'       => 'content',
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_tabs_master_array', 10, 1 );

/**
 * Sample element markup for front-end
 * In other words, the front-end output of this element is returned by the following function
 *
 *
 * @param  array  $atts              The array containing the parsed values from shortcode,it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_tabs_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'         => '', // header title
        'style'         => 'bordered', // header title
        'tabs'          => '', // header title
        'type'          => 'horizontal',
        'tab_id_prefix' => '', // Default prefix for tab index and id
        'extra_classes' => '', // custom css class names for this element
        'custom_el_id'  => '', // custom id attribute for this element
        'base_class'    => 'aux-widget-tabs'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    // widget custom output -----------------------
    $extra_classes .= ' aux-' . $type;

    $output  = '<div class="widget-tabs widget-container ' . esc_attr( $style ) . '">';
    $output .= '<div class="widget-inner ' . esc_attr( trim( $extra_classes ) ) . '"> ';
    $tabs_markup  = '<ul class="tabs">';
    $tabs_content = '<ul class="tabs-content">';

    if ( is_array( $tabs ) || is_object( $tabs ) ) {
        foreach ( $tabs as $index => $value ) {

            $id_number = esc_attr( $tab_id_prefix . ( $index + 1 ) );

            // Tab
            $tab_attrs = array(
                'id'            => 'aux-tab-' . $id_number,
                //'class'         => '',
                'href'          => '#',
                'tabindex'      => $id_number,
                'role'          => 'tab',
                'aria-controls' => 'aux-tab-content-' . $id_number,
            );
            $tabs_markup .= sprintf( '<li><a %s>%s</a></li>',
                auxin_make_html_attributes( $tab_attrs ),
                $value['tab_label']
            );

            // Content
            $content_attrs = array(
                'id'             => 'aux-tab-content-' . $id_number,
                'class'          => 'entry-editor',
                'tabindex'       => $id_number,
                'role'           => 'tabpanel',
                'aria-labelledby'=> 'aux-tab-' . $id_number,
            );
            
            if ( ! empty( $value['__dynamic__' ] ) ) {
                $content = $value['content'];
            } else {
                preg_match('/<p>(.*?)<\/p>/', $value['content'] , $matches);
                $content = ! empty( $matches[0] ) ? $matches[0] : $value['content'];
            }

            $tabs_content .= sprintf( '<li><div %s><p>%s</p></div></li>',
                auxin_make_html_attributes( $content_attrs ),
                $content
            );

        }
    }


    $tabs_markup  .= '</ul>';
    $tabs_content .= '</ul>';
    $output = $output . $tabs_markup . $tabs_content . '</div>' . '</div>';

    echo $output;

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
