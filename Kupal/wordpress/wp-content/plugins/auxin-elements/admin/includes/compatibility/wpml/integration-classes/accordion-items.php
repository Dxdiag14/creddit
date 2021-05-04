<?php
if( class_exists('WPML_Elementor_Module_With_Items') ) {
    /**
     * Class WPML_Elementor_Icon_List
     */
    class Auxin_WPML_Elementor_Accordion extends WPML_Elementor_Module_With_Items {
        /**
         * @return string
         */
        public function get_items_field() {
            return 'tab_items';
        }
        /**
         * @return array
         */
        public function get_fields() {
            return array( 'accordion_label', 'content' );
        }
        /**
         * @param string $field
         *
         * @return string
         */
        protected function get_title( $field ) {
            switch( $field ) {
                case 'accordion_label':
                    return esc_html__( 'Accordion: Label', 'auxin-elements' );
                case 'content':
                    return esc_html__( 'Accordion: Content', 'auxin-elements' );
                default:
                    return '';
            }
        }
        /**
         * @param string $field
         *
         * @return string
         */
        protected function get_editor_type( $field ) {
            switch( $field ) {
                case 'accordion_label':
                    return 'LINE';
                case 'content':
                    return 'VISUAL';
                default:
                    return '';
            }
        }
    }
}