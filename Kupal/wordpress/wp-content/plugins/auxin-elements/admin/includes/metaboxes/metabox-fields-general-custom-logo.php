<?php
 /**
 * Adds fields for custom logo metabox
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

 // no direct access allowed
if ( ! defined('ABSPATH') )  exit;


function auxin_metabox_fields_general_custom_logo(){

    $model         = new Auxin_Metabox_Model();
    $model->id     = 'custom-logo';
    $model->title  = __('Custom Logo', 'auxin-elements');
    $model->fields = array(
        array(
            'title'          => __( 'Page Logo', 'auxin-elements' ),
            'description'    => __( 'The main logo which appears only on this page. If you do not specify an image, the default logo will be used.', 'auxin-elements' ),
            'id'             => 'aux_custom_logo',
            'type'           => 'image',
            'default'        => ''
        ),

        array(
            'title'          => __( 'Page Secondary Logo', 'auxin-elements' ),
            'description'    => __( 'The secondary logo which appears when the header becomes sticky. If you do not specify an image, the default secondary logo will be used.', 'auxin-elements' ),
            'id'             => 'aux_custom_logo2',
            'type'           => 'image',
            'default'        => ''
        ),
    
        array(
            'title'          => __( 'Page Logo Width', 'auxin-elements' ),
            'description'    => __( 'Specifies the max width of logo image in pixels. Leave it blank to use the theme default value for this option.', 'auxin-elements' ),
            'id'             => 'aux_custom_logo_width',
            'type'           => 'text',
            'default'        => '',
            'style_callback' => function( $value = null ){
                // Get the dependency value while saving the metafield
                $enabled = isset( $_POST['aux_use_custom_logo'] ) ? $_POST['aux_use_custom_logo'] : 0;
                if( ! auxin_is_true( $enabled ) ){
                    return '';
                }
                $value = trim( $value, 'px');
                return $value ? ".aux-logo-header .aux-logo-anchor{ max-width:{$value}px; }" : '';
            }
        ),

    );

    return $model;
}
