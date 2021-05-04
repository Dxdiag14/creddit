<?php

/**
 * Class Auxin_SiteOrigin_Field_Select2
 */
class Auxin_SiteOrigin_Field_Select2_Multiple extends SiteOrigin_Widget_Field_Base {

    protected $options;

    protected function render_field( $value, $instance ) {

        if( gettype( $value ) ==="string" ) {
            $value = explode( ',', $value );
        }

        $output = '<div class="aux-element-field aux-multiple-selector ">';
        $output .= '<select multiple="multiple" name="'.$this->element_name.'" id="'.$this->element_id.'"  style="width:100%" '  . ' class="wpb-multiselect wpb_vc_param_value aux-select2-multiple">';

        foreach ( $this->options as $id => $option_info ) {
            $active_attr = in_array( $id, $value ) ? 'selected="selected"' : '';
            $output     .= sprintf( '<option value="%s" %s >%s</option>', $id, $active_attr, $option_info );
        }

        $output .= '</select></div>';

        echo $output;

    }

    protected function sanitize_field_input( $value, $instance ) {
        return $value;
    }
}
