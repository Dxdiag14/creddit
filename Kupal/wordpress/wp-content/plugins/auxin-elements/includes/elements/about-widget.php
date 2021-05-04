<?php
/**
 * About element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function  get_auxin_about_widget( $master_array ) {

     $master_array['aux_about_widget'] = array(
        'name'                    => __("About Author", 'auxin-elements'  ),
        'auxin_output_callback'   => 'auxin_widget_about_callback',
        'base'                    => 'aux_about_widget',
        'description'             => __('It adds an about author element.', 'auxin-elements' ),
        'class'                   => 'aux-widget-about',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'is_so'                   => false,
        'is_vc'                   => false,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-about',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Title','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'id',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Name','auxin-elements' ),
                'description'       => __('The name which appears in about widget.', 'auxin-elements' ),
                'param_name'        => 'name',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Skills','auxin-elements' ),
                'description'       => __('It appears below the name.', 'auxin-elements' ),
                'param_name'        => 'skills',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'skills',
                'admin_label'       => true,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Info','auxin-elements' ),
                'description'       => __('Biographical info or any other content.', 'auxin-elements' ),
                'param_name'        => 'info',
                'type'              => 'textarea_html',
                'def_value'         => '',
                'value'             => '',
                'holder'            => 'div',
                'class'             => 'info',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Image','auxin-elements' ),
                'description'       => __('It appears above the name.', 'auxin-elements' ),
                'param_name'        => 'about_image',
                'type'              => 'attach_image',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'about_image',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Image style','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'image_style',
                'type'              => 'dropdown',
                'def_value'         => 'circle',
                'value'             => array(
                    'circle'     => __('Circle', 'auxin-elements' ),
                    'square'     => __('Square', 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'image_style',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Align center texts', 'auxin-elements' ),
                'description'       => '',
                'param_name'        => 'align_center',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'align_center',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Show socials below the info','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'show_socials',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '0',
                'holder'            => '',
                'class'             => 'show_socials',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements' ),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements' ),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'extra_classes',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            )

        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'get_auxin_about_widget', 10, 1 );

/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_about_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'         => '',
        'about_image'   => '',
        'show_socials'  => '0',
        'image_style'   => 'square',
        'align_center'  => '1',
        'info'          => '',
        'name'          => '',
        'skills'        => '',

        'extra_classes' => '',
        'custom_el_id'  => '', // custom id attribute for this element
        'base_class'    => 'aux-widget-about'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];
    ?>


       <div class="<?php echo ($align_center ? 'aux-text-center' : ''); ?>">

        <!---  The output for element here -->
        <?php

            if ( !empty( $about_image ) && is_numeric( $about_image ) ) {
                echo '<div class="aux-about-image aux-style-' . $image_style . '">' . auxin_get_the_resized_attachment( $about_image, 240, ( $image_style == 'square' ? 400 : 240 ), true ) . '</div>';
            }

            echo '<dl>';

            if ( !empty( $name ) ) {
                echo '<dt class="aux-about-name">' . $name . '</dt>';
            }

            if ( !empty( $skills ) ) {
                echo '<dd class="aux-about-skills">' . $skills . '</dd>';
            }

            if ( !empty( $info ) ) {
                echo '<dd class="aux-about-content">'.$info.'</dd>';
            }

            echo '</dl>';

            if( $show_socials ) {
                echo auxin_the_socials();
            }
        ?>

        </div><!-- widget-inner -->


    <?php
    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
