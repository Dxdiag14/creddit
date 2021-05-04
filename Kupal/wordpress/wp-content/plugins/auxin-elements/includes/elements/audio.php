<?php
/**
 * Audio player element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_audio_master_array( $master_array ) {

    $master_array['aux_audio'] = array(
        'name'                    => __("Audio", 'auxin-elements'  ),
        'auxin_output_callback'   => 'auxin_widget_audio_callback',
        'base'                    => 'aux_audio',
        'description'             => __('Audio player', 'auxin-elements' ),
        'description'             => __('It adds an audio player element.', 'auxin-elements'),
        'class'                   => 'aux-widget-audio',
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
        'icon'                    => 'aux-element aux-pb-icons-sound-cloud',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Title','auxin-elements' ),
                'description'       => __('Audio title, leave it empty if you don`t need title.', 'auxin-elements'),
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
                'heading'           => __('Audio file(MP3 or ogg)','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'src',
                'type'              => 'aux_select_audio',
                'value'             => '',
                'holder'            => '',
                'class'             => 'audio_src',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Repeat the audio','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'loop',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('AutoPlay','auxin-elements' ),
                'description'       => __('Play the audio file automatically.','auxin-elements' ),
                'param_name'        => 'autoplay',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Skin','auxin-elements' ),
                'description'      => __('The skin of audio element.','auxin-elements' ),
                'param_name'       => 'skin',
                'type'             => 'aux_visual_select',
                'def_value'        => 'dark',
                'choices'          => array(
                    'default'      => array(
                        'label' => __('Theme Default', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/default2.svg'
                    ),
                    'dark'      => array(
                        'label' => __('Dark', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/audio-player-dark.svg'
                    ),
                    'light'     => array(
                        'label' => __('Light', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/audio-player-light.svg'
                    )
                ),
                'holder'           => '',
                'class'            => 'skin',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __('Appearance', 'auxin-elements'),
                'edit_field_class' => ''
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
                'group'            => __('Appearance', 'auxin-elements'),
                'edit_field_class' => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_audio_master_array', 10, 1 );


/**
 * This is the widget callback in fact the front end out put of this widget comes from this function
 */
function auxin_widget_audio_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'         => '',    // section title
        'src'           => '',
        'loop'          => '1',
        'autoplay'      => '0',
        'preload'       => '',
        'skin'          => '', // dark or light

        'extra_classes' => '', // custom css class names for this element
        'custom_el_id'  => '', // custom id attribute for this element
        'base_class'    => 'aux-widget-audio'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    $skin = 'default' === $skin ? auxin_get_option( 'global_audio_player_skin', 'dark' ) : $skin;

    $class = 'wp-audio-shortcode aux-player-' . esc_attr( $skin );

    // convert attachment id to url
    if( is_numeric( $src ) ){
        $src = wp_get_attachment_url( $src );
    }

    if( $provider = auxin_extract_embed_provider_name( $src ) ){
        $class .= ' aux-provider-'. $provider;
    }

    $autoplay = auxin_is_true( $autoplay ) ? "1": "0";
    $loop     = auxin_is_true( $loop     ) ? "1": "0";

    echo wp_audio_shortcode( 
        array(
            'src'      => $src,
            'loop'     => $loop,
            'autoplay' => $autoplay,
            'preload'  => $preload,
            'class'    => $class
        ) 
    );    

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
