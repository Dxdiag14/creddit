<?php


// // Extend visual composer
if ( defined( 'WPB_VC_VERSION' ) ) {

    // add extra classes to post_class to determine whether the content is created by pagebuilder or not
    function auxin_vc_modify_post_classess( $classes, $class, $post_id ){
        $post = get_post( $post_id );
        if ( $post && preg_match( '/vc_row/', $post->post_content ) ) {
            $classes[] = 'aux-has-pb';
            $classes[] = 'aux-has-vc';
        }
        return $classes;
    }
    add_filter( 'post_class', 'auxin_vc_modify_post_classess', 10, 3 );

    // wrap the page builder content with an specific wrapper
    function auxin_on_vc_changes( $content ){
        if( strpos(  $content, '[vc_row' ) !== false )
            return '<div class="aux-vc-wrapper" >' . $content . '</div> ';
        return $content;
    }
    add_filter( 'the_content', 'auxin_on_vc_changes', 10 );


    /**
     * Add special attributes of visual composer to all theme specific shortcodes
     *
     * @param  array $element_config
     */
    function auxin_add_vc_special_attributes( $element_config, $atts, $default_atts ){

        // check and inject 'vc_css' shortcode attribute to all auxin shortcodes
        if( empty( $element_config['parsed_atts']['vc_css'] ) ){
            if( isset( $atts['vc_css'] ) ){
                $element_config['parsed_atts']['vc_css'] = $atts['vc_css'];
            } else {
                return $element_config;
            }
        }

        // pass the generate css by "design options" of visual composer to appropriate hooks to retrieve the custom css class name
        if( ! empty( $element_config['parsed_atts']['vc_css'] ) ){
            $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $element_config['parsed_atts']['vc_css'], ' ' ), $element_config['parsed_atts'] );
            // add the class name to 'extra classes' param
            $element_config['parsed_atts']['extra_classes'] = ' ' . $css_class;
        }

        return $element_config;
    }
    add_filter( 'auxin_pre_widget_scafold_params', 'auxin_add_vc_special_attributes', 10, 3 );
}


