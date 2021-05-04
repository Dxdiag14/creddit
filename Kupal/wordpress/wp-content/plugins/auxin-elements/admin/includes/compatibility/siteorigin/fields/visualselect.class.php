<?php

/**
 * Class Auxin_SiteOrigin_Field_Visualselect
 */
class Auxin_SiteOrigin_Field_Visualselect extends SiteOrigin_Widget_Field_Base {

    protected $options;

    protected function render_field( $value, $instance ) {

        $output = '<div class="aux-element-field aux-visual-selector">';
        $output .= '<select class="meta-select visual-select-wrapper" name="' . $this->element_name . '" id="' . $this->element_id . '" value="' . $value . '" >';

        foreach ( $this->options as $id => $option_info ) {
           $active_attr = ( $value == $id ) ? ' selected ' : "";
           $data_class  = isset( $option_info['css_class'] ) && !empty( $option_info['css_class'] ) ? 'data-class="'. $option_info['css_class'].'"' : '';
           $data_symbol = empty( $data_class ) && isset( $option_info['image'] ) && ! empty( $option_info['image'] ) ? 'data-symbol="'. $option_info['image'].'"' : '';
           $data_video  = ! empty( $option_info['video_src'] ) ? 'data-video-src="'. esc_attr( $option_info['video_src'] ).'"' : '';
           $output     .= sprintf( '<option value="%s" %s %s %s %s>%s</option>', $id, $active_attr, $data_symbol, $data_video, $data_class, $option_info['label'] );
        }

        $output .= '</select></div>';

        echo $output;
    }

    protected function sanitize_field_input( $value, $instance ) {
        return $value;
    }
}
