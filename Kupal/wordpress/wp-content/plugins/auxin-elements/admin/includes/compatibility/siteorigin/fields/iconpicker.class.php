<?php

/**
 * Class Auxin_SiteOrigin_Field_Iconpicker
 */
class Auxin_SiteOrigin_Field_Iconpicker extends SiteOrigin_Widget_Field_Base {

    protected function render_field( $value, $instance ) {

        $font_icons = Auxin()->Font_Icons->get_icons_list('fontastic');
        $output = '<div class="aux-element-field aux-iconpicker">';
        $output .= sprintf( '<select name="%1$s" id="%1$s" class="aux-fonticonpicker aux-select" >', $this->element_name );
        $output .= '<option value="">' . __('Choose ..', 'auxin-elements') . '</option>';

        if( is_array( $font_icons ) ){
            foreach ( $font_icons as $icon ) {
                $icon_id = trim( $icon->classname, '.' );
                $output .= '<option value="'. $icon_id .'" '. selected( $value, $icon_id, false ) .' >'. $icon->name . '</option>';
            }
        }

        $output .= '</select>';
        $output .= '</div>';

        echo $output;
    }

    protected function sanitize_field_input( $value, $instance ) {
        return $value;
    }
}
