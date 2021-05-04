<?php
// Include integration classes for repetition values
include_once( 'integration-classes/flexible-list.php' );
include_once( 'integration-classes/accordion-items.php' );
include_once( 'integration-classes/tabs.php' );

/**
 * Make our widgets compatible with WPML elementor list
 *
 * @param array $widgets
 * @return array
 */
function auxin_wpml_widgets_to_translate_list( $widgets ) {

   $widgets[ 'aux_modern_heading' ] = array(
      'conditions' => array( 'widgetType' => 'aux_modern_heading' ),
      'fields'     => array(
         array(
            'field'       => 'title',
            'type'        => __( 'Modern Heading: Title', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'description',
            'type'        => __( 'Modern Heading: Description', 'auxin-elements' ),
            'editor_type' => 'VISUAL'
         ),
         array(
            'field'       => 'title_secondary_before',
            'type'        => __( 'Modern Heading: Before Text', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'title_secondary_highlight',
            'type'        => __( 'Modern Heading: Highlighted Text', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'title_secondary_after',
            'type'        => __( 'Modern Heading: After Text', 'auxin-elements' ),
            'editor_type' => 'LINE'
         )
      ),
   );

   $widgets[ 'aux_icon_list' ] = array(
      'conditions'        => array( 'widgetType' => 'aux_icon_list' ),
      'fields'            => array(),
      'integration-class' => 'Auxin_WPML_Elementor_Icon_List',
   );

   $widgets[ 'aux_accordion' ] = array(
      'conditions'        => array( 'widgetType' => 'aux_accordion' ),
      'fields'            => array(),
      'integration-class' => 'Auxin_WPML_Elementor_Accordion',
   );

   $widgets[ 'aux_tabs' ] = array(
      'conditions'        => array( 'widgetType' => 'aux_tabs' ),
      'fields'            => array(),
      'integration-class' => 'Auxin_WPML_Elementor_Tabs',
   );

   $widgets[ 'aux_button' ] = array(
      'conditions' => array( 'widgetType' => 'aux_button' ),
      'fields'     => array(
         array(
            'field'       => 'label',
            'type'        => __( 'Button: Label', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
      ),
   );

   $widgets[ 'aux_contact_form' ] = array(
      'conditions' => array( 'widgetType' => 'aux_contact_form' ),
      'fields'     => array(
         array(
            'field'       => 'label',
            'type'        => __( 'Contact Form 7: Shortcode', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
      ),
   );

   $widgets[ 'aux_blockquote' ] = array(
      'conditions' => array( 'widgetType' => 'aux_blockquote' ),
      'fields'     => array(
         array(
            'field'       => 'content',
            'type'        => __( 'Blockquote: Content', 'auxin-elements' ),
            'editor_type' => 'VISUAL'
         ),
      ),
   );

   $widgets[ 'aux_staff' ] = array(
      'conditions' => array( 'widgetType' => 'aux_staff' ),
      'fields'     => array(
         array(
            'field'       => 'title',
            'type'        => __( 'Staff: Name', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'subtitle',
            'type'        => __( 'Staff: Occupation', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'content',
            'type'        => __( 'Staff: Content', 'auxin-elements' ),
            'editor_type' => 'VISUAL'
         )
      ),
   );

   $widgets[ 'aux_testimonial' ] = array(
      'conditions' => array( 'widgetType' => 'aux_testimonial' ),
      'fields'     => array(
         array(
            'field'       => 'title',
            'type'        => __( 'Testimonial: Name', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'subtitle',
            'type'        => __( 'Testimonial: Occupation', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'content',
            'type'        => __( 'Testimonial: Content', 'auxin-elements' ),
            'editor_type' => 'VISUAL'
         )
      ),
   );

   $widgets[ 'aux_text' ] = array(
      'conditions' => array( 'widgetType' => 'aux_text' ),
      'fields'     => array(
         array(
            'field'       => 'title',
            'type'        => __( 'Info Box: Name', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'subtitle',
            'type'        => __( 'Info Box: Occupation', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'content',
            'type'        => __( 'Info Box: Content', 'auxin-elements' ),
            'editor_type' => 'VISUAL'
         ),
         array(
            'field'       => 'btn_label',
            'type'        => __( 'Info Box: Button label', 'auxin-elements' ),
            'editor_type' => 'LINE'
         )
      ),
   );

   $widgets[ 'aux_gmap' ] = array(
      'conditions' => array( 'widgetType' => 'aux_gmap' ),
      'fields'     => array(
         array(
            'field'       => 'latitude',
            'type'        => __( 'MAP: Latitude', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'longitude',
            'type'        => __( 'MAP: Longitude', 'auxin-elements' ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'marker_info',
            'type'        => __( 'MAP: Marker info', 'auxin-elements' ),
            'editor_type' => 'LINE'
         )
      ),
   );


    return $widgets;
}

/**
 * Add filter on wpml elementor widgets node when init action.
 *
 * @return void
 */
function auxin_wpml_widgets_to_translate_filter(){
    add_filter( 'wpml_elementor_widgets_to_translate', 'auxin_wpml_widgets_to_translate_list' );
}
add_action( 'init', 'auxin_wpml_widgets_to_translate_filter' );

