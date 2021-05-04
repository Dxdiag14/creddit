<?php
/**
 * Quote element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_quote_master_array( $master_array ) {

    $master_array['aux_quote'] = array(
        'name'                    => __("Quote", 'auxin-elements'  ),
        'auxin_output_callback'   => 'auxin_widget_quote_callback',
        'base'                    => 'aux_quote',
        'description'             => __('Blockquote and introduction paragraph', 'auxin-elements' ),
        'class'                   => 'aux-widget-quote',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'category'                => THEME_NAME,
        'is_so'                   => true,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-quote',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Quote text','auxin-elements' ),
                'description'       => __('Enter a text as a quote.','auxin-elements' ),
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
                'heading'               => __('Blockqoute style','auxin-elements' ),
                'description'           => '',
                'param_name'            => 'type',
                'type'                  => 'aux_visual_select',
                'def_value'             => 'blockquote-normal',
                'choices'               => array(
                    'quote-normal'      => array(
                        'label'         => __('Quote Normal', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/blockquote-normal-1.svg'
                    ),
                    'blockquote-normal' => array(
                        'label'         => __('Blockquote Normal', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/blockquote-normal.svg'
                    ),
                    'blockquote-bordered' => array(
                        'label'         => __('Blockquote Bordered', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/blockquote-bordered.svg'
                    ),
                    'intro-normal'      => array(
                        'label'         => __('Intro', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/quote-intro-normal.svg'
                    ),
                    'intro-hero'        => array(
                        'label'         => __('Intro Hero', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/quote-intro-hero.svg'
                    ),
                    'intro-splitter'    => array(
                        'label'         => __('Intro with Splitter', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/quote-intro-splitter.svg'
                    ),
                     'pullquote-normal' => array(
                        'label'         => __('Pullquote Normal', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/pullquote-normal.svg'
                    ),
                    'pullquote-colorized' => array(
                        'label'         => __('Pullquote Colorized', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/pullquote-colorized.svg'
                    )
                ),
                'holder'                => '',
                'class'                 => 'type',
                'admin_label'           => true,
                'dependency'            => '',
                'weight'                => '',
                'group'                 => '' ,
                'edit_field_class'      => ''
            ),
            array(
                'heading'           => __('Text alignment','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'text_align',
                'type'              => 'dropdown',
                'def_value'         => 'none',
                'value'             => array(
                     'none'         => __('Default', 'auxin-elements' ),
                     'left'         => __('Left'   , 'auxin-elements' ),
                     'right'        => __('Right'  , 'auxin-elements' ),
                     'center'       => __('Center' , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'type',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            // array(
            //     'param_name'        => 'float',
            //     'type'              => 'dropdown',
            //     'def_value'         => 'none',
            //     'value'             => array(
            //         'none'      => __('Default', 'auxin-elements' ),
            //         'left'          =>__('Left'   , 'auxin-elements' ),
            //         'right' => __('Right'  , 'auxin-elements' )
            //     ),
            //     'holder'            => 'dropdown',
            //     'class'             => 'type',
            //     'heading'           => __('Block alignment','auxin-elements' ),
            //     'description'       => '',
            //     'admin_label'       => true,
            //     'dependency'        => '',
            //     'weight'            => '',
            //     'group'             => '' ,
            //     'edit_field_class'  => ''
            // ),
            array(
                'heading'           => __('Insert quote symbol', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'quote_symbol',
                'type'              => 'aux_switch',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'showheader',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Extra class name','auxin-elements' ),
                'description'      => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements' ),
                'param_name'       => 'extra_classes',
                'type'             => 'textfield',
                'value'            => '',
                'def_value'        => '',
                'holder'           => '',
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_quote_master_array', 10, 1 );

function auxin_widget_quote_callback( $atts, $shortcode_content = null ){

   /**
    * type:
    * intro-hero
    * intro-normal
    * intro-splitter
    *
    * pullquote-normal
    * pullquote-colorized
    *
    * quote-normal
    * quote-big
    *
    * blockquote-normal
    * blockquote-bordered
    *
    * text-align:
    * center
    * right
    * left
    *
    * float:
    * none
    * left
    * right
    */

    // Defining default attributes
    $default_atts = array(
        'content'           => '',

        'indent'            => '', // custom values: yes, no
        'type'              => 'blockquote-normal',
        'text_align'        => '',
        'float'             => '',
        'quote_symbol'      => '1',
        'css'               => '',

        'extra_classes'     => '', // custom css class names for this element
        'custom_el_id'      => '', // custom id attribute for this element
        'base_class'        => 'aux-widget-quote'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];

    // widget custom output -----------------------

    $classes_list = array( 'aux-elem-quote' );

    $classes_list[] = 'aux-' . $type;

    if( ! empty( $text_align ) && 'none'!== $text_align ){
        $classes_list[] = 'aux-text-align-' . $text_align;
    }

    if( ! empty( $float ) ){
        $classes_list[] = 'aux-float-' . $float;
    }

    if( auxin_is_true( $quote_symbol ) ){
        //$classes_list[] = 'aux-quote-letter';
        $classes_list[] = 'aux-quote-symbol';
    }

    if( !empty($extra_classes) ){
        $classes_list[] = $extra_classes;
    }

    $classes = implode( ' ', $classes_list );
    $shortcode_content = auxin_do_cleanup_shortcode( $shortcode_content );
    $shortcode_content = empty( $shortcode_content ) ? auxin_do_cleanup_shortcode( $content ) : $shortcode_content;

    switch ( $type ) {
        case 'intro-hero':
        case 'intro-normal':
        case 'intro-splitter':
            echo sprintf( '<p class="%s">%s</p>', $classes, $shortcode_content );
            break;

        case 'pullquote-normal':
        case 'pullquote-colorized':
            echo sprintf( '<blockquote class="%s"><p>%s</p></blockquote>', $classes, $shortcode_content );
            break;

        case 'quote-normal':
        case 'quote-big':
            echo sprintf( '<blockquote class="%s"><p>%s</p></blockquote>', $classes, $shortcode_content );
            break;

        case 'blockquote-normal':
        case 'blockquote-bordered':
        default:
            echo sprintf( '<blockquote class="%s"><p>%s</p></blockquote>', $classes, $shortcode_content );
            break;
    }

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
