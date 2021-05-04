<?php

/**
 * Class Auxin_SiteOrigin_Field_Colorpicker
 */
class Auxin_SiteOrigin_Field_Colorpicker extends SiteOrigin_Widget_Field_Base {

    protected function render_field( $value, $instance ) {

        $output = '<div class="aux-so-colorpicker aux-element-colorpicker mini-color-wrapper">';

        $output .= sprintf( '<input type="text" class="aux-colorpicker-field" name="%s" id="%s" value="%2$s" />', $this->element_name, $this->element_id, $field['value'] ).

        $output .= '</div>';

        echo $output;
    }

    protected function sanitize_field_input( $value, $instance ) {
        return $value;
    }
}
