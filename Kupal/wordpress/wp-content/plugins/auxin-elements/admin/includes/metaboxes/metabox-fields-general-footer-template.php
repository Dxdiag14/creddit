<?php
 /**
 * Adds fields for footer templates metabox
 *
 * undefined
*/

 // no direct access allowed
if ( ! defined('ABSPATH') )  exit;


function auxin_metabox_fields_footer_templates(){

    $model         = new Auxin_Metabox_Model();
    $model->id     = 'footer-templates';
    $model->title  = __('Footer Templates', 'auxin-elements');
    $model->fields = [];

    if ( auxin_is_plugin_active( 'elementor/elementor.php' ) ) {

        $model->fields[] = array(
            'title'       => __( 'Current Footer', THEME_DOMAIN ),
            'id'          => 'page_elementor_footer_edit_template',
            'type'        => 'edit_template',
            'template'    => 'footer',
            'dependency'  => array(
                array(
                    'id'      => 'page_footer_use_legacy',
                    'value'   => array( 'no' ),
                    'operator'=> '!='
                ),
            ),
        );


        $templates_list = auxin_get_elementor_templates_list('footer');
        $templates_list[ auxin_get_option( 'site_elementor_footer_template' ) . '-def'] = __( 'Theme Default', THEME_DOMAIN );

        $model->fields[] = array(
            'title'            => __( 'Your Footers', THEME_DOMAIN ),
            'id'               => 'page_elementor_footer_template',
            'type'             => 'selective_list',
            'choices'          => $templates_list,
            'dependency'  => array(
                array(
                    'id'      => 'page_footer_use_legacy',
                    'value'   => array( 'no' ),
                    'operator'=> '!='
                ),
            ),
            'related_controls' => ['page_elementor_footer_edit_template']
        );

    } else {
        $model->fields[] = array(
            'title'       => __( 'Footer Builder', THEME_DOMAIN ),
            'description' => __( 'Get footer builder and templates by installing Elementor plugin.', THEME_DOMAIN ),
            'id'          => 'page_footer_install_elementor',
            'section'     => 'footer-section-builder',
            'type'        => 'install_elementor_plugin',
        );
    }

    $model->fields[] = array(
        'title'            => __( 'Use Legacy Footer', THEME_DOMAIN ),
        'description'      => __( 'Disable it to replace footer section with an Elementor template', THEME_DOMAIN ),
        'id'               => 'page_footer_use_legacy',
        'type'             => 'select',
        'transport'        => 'postMessage',
        'default'          => 'default',
        'related_controls' => ['page_top_footer_section_use_legacy', 'page_footer_section_use_legacy'],
        'choices'       => array(
            'default' => __( 'Theme Default', 'auxin-elements' ),
            'yes'     => __( 'Yes', 'auxin-elements' ),
            'no'      => __( 'No', 'auxin-elements' ),
        ),
    );

    return $model;
}
