<?php
/**
 * Dropcap element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function  auxin_get_dropcap_master_array( $master_array ) {

    $master_array['aux_dropcap']  = array(
        'name'                    => __("Dropcap", 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_widget_dropcap_callback',
        'base'                    => 'aux_dropcap',
        'description'             => __('Big styled character at the beginning of paragraph', 'auxin-elements' ),
        'class'                   => 'aux-widget-dropcap',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => false,
        'is_shortcode'            => true,
        'is_so'                   => true,
        'is_vc'                   => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-dropcap',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Dropcap style','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'style',
                'type'              => 'aux_visual_select',
                'def_value'         => 'classic',
                'holder'            => '',
                'class'             => 'style',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => '',
                'choices'           => array(
                    'classic'           => array(
                        'label'         => __('Classic', 'auxin-elements' ),
                        'image'         => AUXIN_URL . 'images/visual-select/dropcap-classic.svg'
                     ),
                    'square'            => array(
                        'label'         => __('Square', 'auxin-elements' ),
                        'image'         => AUXIN_URL . 'images/visual-select/dropcap-square.svg'
                    ),
                    'square-outline'    => array(
                        'label'         => __('Outline Square', 'auxin-elements' ),
                        'image'         => AUXIN_URL . 'images/visual-select/dropcap-square-outline.svg'
                    ),
                    'square-round'      => array(
                        'label'         => __('Round Square', 'auxin-elements' ),
                        'image'         => AUXIN_URL . 'images/visual-select/dropcap-square-round.svg'
                    ),
                    'circle'            => array(
                        'label'         => __('Circle', 'auxin-elements' ),
                        'image'         => AUXIN_URL . 'images/visual-select/dropcap-circle.svg'
                    ),
                     'circle-outline'   => array(
                        'label'         => __('Outline Circle', 'auxin-elements' ),
                        'image'         => AUXIN_URL . 'images/visual-select/dropcap-circle-outline.svg'
                    )
                )
            ),
            array(
                'heading'           => __('Content','auxin-elements' ),
                'description'       => __('Enter a text to show as dropcap text.', 'auxin-elements' ),
                'param_name'        => 'content',
                'type'              => 'textarea_html',
                'value'             => '',
                'holder'            => '',
                'class'             => 'content',
                'admin_label'       => false,
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_dropcap_master_array', 10, 1 );

/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_dropcap_callback( $atts, $shortcode_content = null ) {

    // Defining default attributes
    $default_atts = array(
        'title'         => '', // header title
        'style'         => 'classic',
        'content'       => '', // custom css class names for this element

        'extra_classes' => '',
        'custom_el_id'  => '',
        'base_class'    => 'aux-widget-dropcap'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    //  The output for element here
    $class_names = "";

    switch ($style) {
        case 'classic':
            $class_names = "dropcap";
        break;
        case 'square':
            $class_names = "dropcap square";

        break;
        case 'square-outline':
            $class_names = "dropcap square outline";

        break;
        case 'square-round':
            $class_names = "dropcap square round";

        break;
        case 'square-round-outline':
            $class_names = "dropcap square round outline";

        break;
        case 'circle':
            $class_names = "dropcap circle";

        break;
        case 'circle-outline':
            $class_names = "dropcap circle outline";

        break;
    }

    $first_letter = substr( $content, 0, 1);  // get the first letter

    if ( $first_letter === "<") {
        $tag = auxin_get_string_between($content, '<', '>');
        $content = auxin_str_replace_first( "<" .$tag. ">" ,"" , $content );
        $content = auxin_str_replace_first( "</".$tag. ">" ,"" , $content );
    }

    $first_letter = mb_substr( $content, 0, 1,'utf-8' ); // get the first letter
    $rest_of_text = mb_substr( $content, 1, null, 'utf-8' ); // get the rest of text

    echo '<p class="'. $extra_classes .'">' .
         '<span class="'. $class_names .'">'. $first_letter .'</span>'. $rest_of_text .
    '</p>';

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}


if( ! function_exists( 'auxin_str_replace_first' ) ) {

    function auxin_str_replace_first($from, $to, $subject) {
        $from = '/'.preg_quote($from, '/').'/';
        return preg_replace($from, $to, $subject, 1);
    }
}

