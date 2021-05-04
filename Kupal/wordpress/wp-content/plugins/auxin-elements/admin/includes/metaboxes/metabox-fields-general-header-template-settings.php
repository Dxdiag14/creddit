<?php
 /**
 * Adds fields for header templates settings metabox
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


function auxin_metabox_fields_header_templates_settings(){

    $model         = new Auxin_Metabox_Model();
    $model->id     = 'header-templates-settings';
    $model->title  = __('Header Templates Settings', 'auxin-elements');
    $model->fields = [
        array(
            'title'       => __( 'Enable Overlay Header', 'auxin-elements' ),
            'description' => __( 'Whether to set a overlay header for this page.', 'auxin-elements' ),
            'id'          => 'page_overlay_header',
            'type'        => 'select',
            'default'     => 'default',
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        ),

        array(
            'title'       => __( 'Enable Sticky Header', 'auxin-elements' ),
            'description' => __( 'Whether to pin the header menu on top.', 'auxin-elements' ),
            'id'          => 'page_header_top_sticky',
            'type'        => 'select',
            'choices'     => array (
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'   => __( 'Yes', 'auxin-elements' ),
                'no'    => __( 'No', 'auxin-elements' ),
            ),
        ),

        array(
            'title'       => __( 'Sticky Header Height', 'auxin-elements' ),
            'description' => __( 'Specifies the sticky header height for this page. Leave it blank to use the theme default value for this option.', 'auxin-elements' ),
            'id'          => 'page_header_container_scaled_height',
            'type'        => 'text',
            'style_callback' => function( $value = null ){
                $selector  = ".aux-top-sticky .site-header-section.aux-sticky .aux-fill .aux-menu-depth-0 > .aux-item-content, ".
                             ".aux-top-sticky .site-header-section.aux-sticky .aux-header-elements { height:%spx; }";

                return $value ? sprintf( $selector , $value ) : '';
            },
            'dependency'  => array(
                array(
                    'id'      => 'page_header_top_sticky',
                    'value'   => array('yes'),
                    'operator'=> '=='
                ),
            ),
            'default'   => '',
        ),
    ];

    return $model;
}
