<?php
/**
 * Add page template setting meta box Model
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

function auxin_metabox_fields_page_template(){

    $model         = new Auxin_Metabox_Model();
    $model->id     = 'page-template';
    $model->title  = __('Page Template Setting', 'auxin-elements');
    $model->fields = array(

        array(
            'title'         => __('Page content location', 'auxin-elements'),
            'description'   => __('Specifies where the dynamic page content (page entry) should appear in comparison with the template content. Note: A page template from "Page Attributes > Template" should be selected for this page in order to use this option.', 'auxin-elements'),
            'id'            => 'aux_page_template_content_location',
            'type'          => 'select',
            'default'       => 'above-in-frame',
            'choices' => array(
                'above-in-frame' => __( 'Before Content', 'auxin-elements' ),                       // Page builder content right before template content
                'above-full'     => __( 'Above Content, FullWidth Layout', 'auxin-elements' ),      // Page builder content on above of template with full width
                'above-boxed'    => __( 'Above Content, Boxed Layout', 'auxin-elements' ),          // Page builder content on above of template with boxed width
                'below-in-frame' => __( 'After Content', 'auxin-elements' ),                        // Page builder content right after template content
                'below-boxed'    => __( 'Below Content, Boxed Layout', 'auxin-elements' ),          // Page builder content below template content with boxed width
                'below-full'     => __( 'Below Content, FullWidth Layout', 'auxin-elements' ),       // Page builder content below template content with full width
                'none'           => __( 'Hide it' )                                              // Skip Page builder content
            )
        )

    );

    return $model;
}
