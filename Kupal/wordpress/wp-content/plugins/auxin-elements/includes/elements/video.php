<?php
/**
 * Video player element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function  auxin_get_video_master_array( $master_array ) {

    $master_array['aux_video'] = array(
        'name'                    => __('Video', 'auxin-elements'),
        'auxin_output_callback'   => 'auxin_widget_video_callback',
        'base'                    => 'aux_video',
        'description'             => __('It adds a video player element.', 'auxin-elements'),
        'class'                   => 'aux-widget-video',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-video',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'    => array(
            array(
                'heading'          => __('Title','auxin-elements'),
                'description'      => __('Video title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'       => 'title',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'title',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
             array(
                'heading'           => __('Autoplay','auxin-elements'),
                'description'       => __('Whether to start the video automatically or not.','auxin-elements'),
                'param_name'        => 'autoplay',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => 'autoplay',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Repeat video','auxin-elements'),
                'description'      => __('Play video again when it ends.','auxin-elements'),
                'param_name'       => 'loop',
                'type'             => 'aux_switch',
                'value'            => '0',
                'class'            => 'loop',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Width','auxin-elements'),
                'description'      => __('Width size of video in pixel.','auxin-elements'),
                'param_name'       => 'width',
                'type'             => 'textfield',
                'value'            => '1200',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Style', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Height','auxin-elements'),
                'description'      => __('Height size of video in pixel.','auxin-elements'),
                'param_name'       => 'height',
                'type'             => 'textfield',
                'value'            => '675',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Style', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Video file','auxin-elements'),
                'description'      => __('Please upload the video file.','auxin-elements'),
                'param_name'       => 'src',
                'type'             => 'aux_select_video',
                'value'            => '',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Video link','auxin-elements'),
                'description'      => __('Youtube, Vimeo or any video embed link.','auxin-elements'),
                'param_name'       => 'url',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Video poster','auxin-elements'),
                'description'      => __('An image that represents the video content.','auxin-elements'),
                'param_name'       => 'poster',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Player skin','auxin-elements'),
                'description'      => __('Specifies skin for the player.','auxin-elements'),
                'param_name'       => 'skin',
                'type'             => 'aux_visual_select',
                'def_value'        => 'dark',
                'choices'          => array(
                    'dark'         => array(
                        'label'    => __('Dark', 'auxin-elements'),
                        'image'    => AUXIN_URL . 'images/visual-select/audio-player-dark.svg'
                    ),
                    'light'        => array(
                        'label'    => __('Light', 'auxin-elements'),
                        'image'    => AUXIN_URL . 'images/visual-select/audio-player-light.svg'
                    )
                ),
                'holder'           => '',
                'class'            => 'skin',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Style', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Extra class name','auxin-elements'),
                'description'      => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements'),
                'param_name'       => 'extra_classes',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => '',
                'class'            => 'extra_classes',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            )

        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_video_master_array', 10, 1 );




function auxin_widget_video_callback( $atts, $shortcode_content = null ){
    global $content_width;

    // Defining default attributes
    $default_atts = array(
        'title'         => '', // header title

        'autoplay'      => '', // play video automatically
        'loop'          => '', // loop video
        'preload'       => 'metadata',
        'width'         => '1200', // default video size
        'height'        => '675', // default video height
        'url'           => '', // embed video link
        'src'           => '', // quick set for mp4 file
        'poster'        => '',
        'skin'          => 'dark', // dark or light

        'extra_classes' => '', // custom css class names for this element
        'custom_el_id'  => '', // custom id attribute for this element
        'base_class'    => 'aux-widget-video'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    if( empty( $skin ) ){
        $skin = 'light';
    }

    if( empty( $width ) ) {
        $width = $content_width;
    }

    if( empty( $height ) ) {
        $height = round( ( 360 * $content_width ) / 640 );
    }

    $class = 'wp-video-shortcode aux-player-' . esc_attr( $skin );

    // convert attachment id to url
    if( is_numeric( $src ) ){
        $src = wp_get_attachment_url( $src );
    }

    // convert attachment id to url
    if( is_numeric( $poster ) ){
        $poster = wp_get_attachment_url( $poster );
    }

    if( $provider = auxin_extract_embed_provider_name( $src ) ){
        $class .= ' aux-provider-'. $provider;
    }

    if( ! empty( $src ) ){

        $poster = auxin_aq_resize( $poster, $width, $height, true, 100, true, false );
        
        echo wp_video_shortcode(
                array(
                    'src'      => $src,
                    'class'    => $class,
                    'width'    => $width,
                    'height'   => $height,
                    'poster'   => $poster,
                    'loop'     => auxin_is_true( $loop ),
                    'autoplay' => auxin_is_true( $autoplay ),
                    'preload'  => $preload,
                )
            );

    } elseif( ! empty( $url ) ){

        echo wp_oembed_get( $url, array(
            'width'  => $width,
            'height' => $height
        ));
    }

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
