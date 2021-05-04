<?php
/**
 * Retrieves attachment URL
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

add_shortcode( 'aux_attach_url', 'auxin_shortcode_attach_id' );

function auxin_shortcode_attach_id( $atts, $content = null ) {
    extract( shortcode_atts( array( 'id' => '' ) , $atts ) );
    if( empty( $id ) ){
        return '';
    }
    return wp_get_attachment_url( $id );
}


add_shortcode( 'aux_responsive_image_tag', 'auxin_shortcode_responsive_image_tag' );

function auxin_shortcode_responsive_image_tag( $atts, $content = null ) {

    $attrs = shortcode_atts(
        array(
            'id'              => '',
            'quality'         => 100,
            'preloadable'     => true, // Set it to "true" or "null" in order make the image ready for preloading, "true" will load the best match as well.
            'preload_preview' => true, // (true, false, 'progress') if true, insert a low quality placeholder until lazyloading the main image. If set to progress, display a progress animation as a placeholder.
            'upscale'         => false,
            'size'            => 'large',
            'crop'            => null,
            'add_hw'          => true,
            'add_ratio'       => true,
            'sizes'           => 'auto', // (sizes)
            'srcset'          => 'auto', // (srcset) automatically calculate the image sizes based on the 'size' param, OR 'wp' generates image srcs based on WP default image sizes
            'original_src'    => true,
            'extra_class'     => ''
        ),
    $atts );


    if( empty( $attrs['id'] ) ){
        return '';
    }

    return auxin_get_the_responsive_attachment( $attrs['id'], $attrs );
}
