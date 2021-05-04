<?php
 /**
  * Adds fields for footer templates settings metabox
  *
  * undefined
  */

 // no direct access allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function auxin_metabox_fields_footer_templates_settings() {

	$model         = new Auxin_Metabox_Model();
	$model->id     = 'footer-templates-settings';
	$model->title  = __( 'Footer Templates Settings', 'auxin-elements' );
	$model->fields = array(

		array(
			'title'       => __( 'Footer Copyright Text', 'auxin-elements' ),
			'description' => __( 'Enter your copyright text to display on footer.', 'auxin-elements' ),
			'id'          => 'page_footer_copyright',
			'type'        => 'textarea',
		),

		array(
			'title'       => __( 'Show Phlox attribution', 'auxin-elements' ),
			'description' => __( 'Show the "Powered By Phlox" text with link to Phlox homepage in footer.', 'auxin-elements' ),
			'id'          => 'page_footer_attribution',
			'type'        => 'select',
			'default'     => 'default',
			'choices'     => array(
				'default' => __( 'Theme Default', 'auxin-elements' ),
				'yes'     => __( 'Yes', 'auxin-elements' ),
				'no'      => __( 'No', 'auxin-elements' ),
			),
		),

		array(
			'title'       => __( 'Enbale Sticky Footer', 'auxin-elements' ),
			'description' => __( 'Enable this option to pin the footer and subfooter to bottom of the website.', 'auxin-elements' ),
			'id'          => 'page_footer_is_sticky',
			'type'        => 'select',
			'default'     => 'default',
			'choices'     => array(
				'default' => __( 'Theme Default', 'auxin-elements' ),
				'yes'     => __( 'Yes', 'auxin-elements' ),
				'no'      => __( 'No', 'auxin-elements' ),
			),
		),

	);

	return $model;
}
