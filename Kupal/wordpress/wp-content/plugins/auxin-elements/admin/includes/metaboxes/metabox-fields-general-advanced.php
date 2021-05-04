<?php
 /**
 * Add custom code meta box Model
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


function auxin_metabox_fields_general_advanced(){

    $model         = new Auxin_Metabox_Model();
    $model->id     = 'general-advanced';
    $model->title  = __('Advanced Setting', 'auxin-elements');
    $model->fields = array(

        array(
            'title'         => __('Custom CSS class name for body', 'auxin-elements'),
            'description'   => __('You can define custom CSS class name for this page. It helpful for targeting this page by custom CSS code.', 'auxin-elements'),
            'id'            => 'aux_custom_body_class',
            'type'          => 'textbox',
            'default'       => '' // default value
        ),

        array(
            'title'         => __('Custom CSS Code', 'auxin-elements'),
            'description'   => __('Attention: The following custom CSS code will be applied ONLY to this page.', 'auxin-elements').'<br />'.
                           __('For defining global CSS roles, please use custom CSS field on option panel.', 'auxin-elements'),
            'id'            => 'aux_page_custom_css',
            'type'          => 'code',
            'mode'          => 'css',
            'default'       => ''
        ),

        array(
            'title'         => __('Custom JavaScript Code', 'auxin-elements'),
            'description'   => __('Attention: The following custom JavaScript code will be applied ONLY to this page.', 'auxin-elements').'<br />'.
                               __('For defining global JavaScript roles, please use custom javaScript field on option panel.', 'auxin-elements' ),
            'id'            => 'aux_page_custom_js',
            'type'          => 'code',
            'mode'          => 'javascript',
            'default'       => ''
        ),

        array(
            'title'         => __( 'Extra Google Font1', 'auxin-elements' ),
            'description'   => __( 'Load an extra Google font for this page.', 'auxin-elements' ),
            'id'            => 'aux_page_custom_font1',
            'type'          => 'googlefont',
            'default'       => ''
        ),

        array(
            'title'         => __( 'Extra Google Font2', 'auxin-elements' ),
            'description'   => __( 'Load an extra Google font for this page.', 'auxin-elements' ),
            'id'            => 'aux_page_custom_font2',
            'type'          => 'googlefont',
            'default'       => ''
        )

    );

    return $model;
}
