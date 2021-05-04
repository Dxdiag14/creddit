<?php
/**
 * A class for creating SiteOrigin widgets from the master widgets list
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;

/*--------------------------------*/

if( ! class_exists( 'Auxin_SiteOrigin_Widget' ) && class_exists( 'SiteOrigin_Widget') ) :

class Auxin_SiteOrigin_Widget extends SiteOrigin_Widget {


    private $widget_info = array();
    private $widget_fields = array();
    public  $widget_fun_name;

    /**
     * Setups new SiteOrigin Widget
     * @param Array $widget_info
     */
    function __construct( $widget_info ) {

        $this->widget_info = $widget_info;
        $this->widget_fields = $widget_info['params'];
        $this->widget_fun_name = $widget_info['auxin_output_callback'];

        parent::__construct(
            // The unique id for your widget.
            $widget_info['base'],

            // The name of the widget for display purposes.
            $widget_info['name'],

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => $widget_info['description'],
                'has_preview' => false
                //'help'        => 'http://example.com/hello-world-widget-docs',
            ),

            //The $control_options array, which is passed through to WP_Widget
            array(
            ),

            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            false,

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );

        // we don't want to use template files for site origin widgets
        add_filter( 'siteorigin_widgets_template_file_' . $widget_info['base'] , array( $this, 'get_widget_template_file' ) );
        // override the widget template html
        add_filter( 'siteorigin_widgets_template_html_' . $widget_info['base'] , array( $this, 'get_widget_html' ), 10, 2 );


    }

    function initialize_form(){

        $so_fields = array();
        $so_fields_sections = array();

        foreach ( $this->widget_fields as $field ) {

            $so_field = array(
                'label'       => $field['heading'],
                'default'     => ! empty( $field['value'] ) ? $field['value'] : '',
                'description' => ! empty( $field['description'] ) ? $field['description'] : ''
            );

            switch ( $field['type'] ) {
                case 'iconpicker':
                case 'aux_iconpicker':
                    $so_field['type'] = 'iconpicker';
                break;

                case 'textarea_html':
                    $so_field['type'] = 'tinymce';
                    $so_field['rows'] = 20;
                break;

                case 'textbox':
                case 'textfield':
                    $so_field['type'] = 'text';
                break;

                case 'dropdown':
                case 'select':
                    $so_field['type']    = 'select';
                    $so_field['options'] = $field['value'];

                    if ( !empty( $field['def_value'] ) ) {
                        $so_field['default'] = $field['def_value'];
                    }
                break;

                case 'aux_select2_multiple' :
                    $so_field['type']     = 'select2_multiple';
                    $so_field['options']  = $field['value'];
                    $so_field['multiple'] = true;

                    if ( !empty( $field['def_value'] ) ) {
                        $so_field['default'] = $field['def_value'];
                    }

                break;

                case 'aux_visual_select':
                    $so_field['type'] = 'visualselect';
                    $so_field['options'] = $field['choices'];
                break;

                case 'checkbox':
                case 'aux_switch':
                    // TODO: add switch box to so
                    $so_field['type'] = 'checkbox';
                break;

                case 'color':
                case 'colorpicker':
                    // TODO: add color picker to so
                    $so_field['type'] = 'color';
                break;

                case 'aux_select_image':
                case 'attach_image':
                    $so_field['type'] = 'media';
                    $so_field['library'] = 'image';
                break;

                case 'aux_select_images':
                case 'attach_images':
                    $so_field['type'] = 'media';
                    $so_field['library'] = 'image';
                break;

                case 'aux_select_video':
                case 'attach_video':
                    $so_field['type'] = 'media';
                    $so_field['library'] = 'video';
                break;

                case 'aux_select_audio':
                case 'attach_audio':
                    $so_field['type'] = 'media';
                    $so_field['library'] = 'audio';
                break;

                default:
                    continue;
                break;
            }


            if ( ! empty( $field['repeater'] )  ) {
                $repeater_name = $this->sanitize_field_name( $field['repeater'] );

                if ( ! isset( $so_fields[ $repeater_name ] ) ) {
                    $so_fields[ $repeater_name ] = array(
                        'type'        => 'repeater',
                        'label'       => (isset($field['repeater-label']))? $field['repeater-label'] : $field['repeater'],
                        'item_name'   => (isset($field['section-name']))? $field['section-name'] : "Item",
                        'hide'        => true,
                        'fields'      => array()
                    );
                }

                $so_fields[ $repeater_name ]['fields'][ $field['param_name'] ] = $so_field;

            } elseif ( ! empty( $field['group'] )  ) {
                $section_name = $this->sanitize_field_name( $field['group'] );

                if ( ! isset( $so_fields[ $section_name ] ) ) {
                    $so_fields[ $section_name ] = array(
                        'type'        => 'section',
                        'label'       => $field['group'],
                        'hide'        => true,
                        'fields'      => array()
                    );
                }

                $so_fields[ $section_name ]['fields'][ $field['param_name'] ] = $so_field;
            } else {
                $so_fields[$field['param_name']] = $so_field;
            }

        }

        return $so_fields;
    }

    private function sanitize_field_name( $field_label ) {
        return str_replace( ' ', '_', strtolower( $field_label ) );
    }

    /**
     * get the widget output
     */
    function get_widget_html( $template_html, $instance ) {

        $args = $this->widget_fields;
        // make sure to pass same class name for wrapper to widget too
        if( isset( $this->widget_info['base_class'] ) ){
            $args['base_class'] = $this->widget_info['base_class'];
        }

        $instance['widget_info'] = $args;

        if( function_exists( $this->widget_fun_name ) ){
            return call_user_func( $this->widget_fun_name, $instance );
        } else {
            auxin_error( __('The callback for widget does not exists.', 'auxin-elements') );
        }
    }

    /**
     * Auxin elements doesn't support template files
     */
    function get_widget_template_file( $template_path ) {
        return '';
    }

    function get_template_name( $instance ) {
        return '';
    }

    function get_template_dir( $instance ) {
        return '';
    }

}

endif;
