<?php
if( class_exists('WPML_Elementor_Module_With_Items') ) {
    /**
     * Class WPML_Elementor_Icon_List
     */
    class Auxin_WPML_Elementor_Icon_List extends WPML_Elementor_Module_With_Items {
        /**
         * @return string
         */
        public function get_items_field() {
            return 'list';
        }
        /**
         * @return array
         */
        public function get_fields() {
            return array( 'text_primary', 'text_secondary', 'link' => array( 'url' ) );
        }
        /**
         * @param string $field
         *
         * @return string
         */
        protected function get_title( $field ) {
            switch( $field ) {
                case 'text_primary':
                    return esc_html__( 'Flexible List: Text', 'auxin-elements' );
                case 'text_secondary':
                    return esc_html__( 'Flexible List: Secondary Text', 'auxin-elements' );
                case 'link':
                    return esc_html__( 'Flexible List: Link', 'auxin-elements' );
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
                case 'text_primary':
                case 'text_secondary':
                    return 'LINE';
                case 'url':
                    return 'LINK';
                default:
                    return '';
            }
        }
    }
}