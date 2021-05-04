<?php
 /**
 * Adds fields for header templates metabox
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


function auxin_metabox_fields_header_templates(){

    $model         = new Auxin_Metabox_Model();
    $model->id     = 'header-templates';
    $model->title  = __('Header Templates', 'auxin-elements');
    $model->fields = [];

    if ( auxin_is_plugin_active( 'elementor/elementor.php' ) ) {

        $model->fields[] = array(
            'title'       => __( 'Current Header', THEME_DOMAIN ),
            'id'          => 'page_elementor_header_edit_template',
            'type'        => 'edit_template',
            'template'    => 'header',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_use_legacy',
                    'value'   => array( 'no' ),
                    'operator'=> '!='
                ),
            ),
        );


        $templates_list = auxin_get_elementor_templates_list('header');
        $templates_list[ auxin_get_option( 'site_elementor_header_template' ) . '-def'] = __( 'Theme Default', THEME_DOMAIN );

        $model->fields[] = array(
            'title'            => __( 'Your Headers', THEME_DOMAIN ),
            'id'               => 'page_elementor_header_template',
            'type'             => 'selective_list',
            'choices'          => $templates_list,
            'dependency'  => array(
                array(
                    'id'      => 'page_header_use_legacy',
                    'value'   => array( 'no' ),
                    'operator'=> '!='
                ),
            ),
            'related_controls' => ['page_elementor_header_edit_template']
        );

    } else {
        $model->fields[] = array(
            'title'       => __( 'Header Builder', THEME_DOMAIN ),
            'description' => __( 'Get header builder and templates by installing Elementor plugin.', THEME_DOMAIN ),
            'id'          => 'page_header_install_elementor',
            'section'     => 'header-section-builder',
            'type'        => 'install_elementor_plugin',
        );
    }

    $model->fields[] = array(
        'title'            => __( 'Use Legacy Header', THEME_DOMAIN ),
        'description'      => __( 'Disable it to replace header section with an Elementor template', THEME_DOMAIN ),
        'id'               => 'page_header_use_legacy',
        'type'             => 'select',
        'transport'        => 'postMessage',
        'default'          => 'default',
        'related_controls' => ['page_top_header_section_use_legacy', 'page_header_section_use_legacy'],
        'choices'       => array(
            'default' => __( 'Theme Default', 'auxin-elements' ),
            'yes'     => __( 'Yes', 'auxin-elements' ),
            'no'      => __( 'No', 'auxin-elements' ),
        ),
    );

    return $model;
}
