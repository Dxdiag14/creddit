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

function auxin_get_code_master_array( $master_array ) {

    $master_array['aux_code'] = array(
        'name'                          => __('Code', 'auxin-elements' ),
        'auxin_output_callback'         => 'auxin_widget_code_callback',
        'base'                          => 'aux_code',
        'description'                   => __('It adds a code element.', 'auxin-elements' ),
        'class'                         => 'aux-widget-code',
        'show_settings_on_create'       => true,
        'weight'                        => 1,
        'is_widget'                     => true,
        'is_shortcode'                  => true,
        'is_so'                         => true,
        'is_vc'                         => true,
        'category'                      => THEME_NAME,
        'group'                         => '',
        'admin_enqueue_js'              => '',
        'admin_enqueue_css'             => '',
        'front_enqueue_js'              => '',
        'front_enqueue_css'             => '',
        'icon'                          => 'aux-element aux-pb-icons-code',
        'custom_markup'                 => '',
        'js_view'                       => '',
        'html_template'                 => '',
        'deprecated'                    => '',
        'content_element'               => '',
        'as_parent'                     => '',
        'as_child'                      => '',
        'params' => array(
            array(
                'heading'           => __('Title','auxin-elements' ),
                'description'       => __('Code title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'std'               => '',
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
                'heading'           => __('Code','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'content',
                'type'              => 'textarea_raw_html',
                'value'             => '',
                'def_value'         => '',
                'holder'            => '',
                'class'             => 'content',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Language','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'language',
                'type'              => 'dropdown',
                'def_value'         => 'javascript ',
                'value'             => array(
                    'javascript '   => __('JavaScript'  , 'auxin-elements' ) ,
                    'html'          => __('HTML'        , 'auxin-elements' ),
                    'xml'           => __('XML'         , 'auxin-elements' ),
                    'php'           => __('PHP'         , 'auxin-elements' )
                ),
                'holder'            => 'textfield',
                'class'             => 'language',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Style', 'auxin-elements' ),
                'description'       => __('Specifies the theme for code element.','auxin-elements' ),
                'param_name'        => 'theme',
                'type'              => 'dropdown',
                'def_value'         => 'tomorrow',
                'value'             => array(
                    'default'           => __('Default'        , 'auxin-elements' ),
                    'androidstudio'     => __('AndroidStudio'  , 'auxin-elements' ),
                    'atom-one-dark'     => __('Atom Dark'      , 'auxin-elements' ),
                    'atom-one-light'    => __('Atom Light'     , 'auxin-elements' ),
                    'github'            => __('Github'         , 'auxin-elements' ),
                    'googlecode'        => __('Google Code'    , 'auxin-elements' ),
                    'railscasts'        => __('RailsCasts'     , 'auxin-elements' ),
                    'solarized-light'   => __('Solarized Light' , 'auxin-elements' ),
                    'tomorrow-night'    => __('Tomorrow Night'  , 'auxin-elements' ),
                    'tomorrow'          => __('Tomorrow'       , 'auxin-elements' ),
                    'vs'                => __('VisualStudio'   , 'auxin-elements' ),
                    'zenburn'           => __('ZenBurn'        , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'theme',
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_code_master_array', 10, 1 );




/**
 * Element without loop and column
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_code_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'         => '', // header title
        'content'       => '', // custom css class names for this element
        'language'      => 'javascript',
        'theme'         => 'tomorrow',

        'extra_classes' => '',
        'custom_el_id'  => '',
        'base_class'    => 'aux-widget-code'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    $themes = array(
        'androidstudio',
        'atom-one-dark',
        'atom-one-light',
        'default',
        'github',
        'googlecode',
        'railscasts',
        'solarized-light',
        'tomorrow-night',
        'tomorrow',
        'vs',
        'zenburn'
    );

    if( ! in_array( $theme, $themes ) ){
        $theme = 'default';
    }

    if ( empty( $content ) ) {
        $content = auxin_get_gmap_style();
    } elseif ( base64_decode( $content, true ) === false ) {

    } else {
        $content = rawurldecode( base64_decode( strip_tags( $content ) ) );
    }

    echo '<div class="hljs-'. $theme .'">' .
         '<pre class="aux-widget-code ' .  $extra_classes . '">' .
                '<code class="' . $language . '">' . do_shortcode( $content )  .
         '</code></pre></div>';

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
