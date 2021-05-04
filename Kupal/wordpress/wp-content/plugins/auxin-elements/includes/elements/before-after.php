<?php
/**
 * Before after slider element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_before_after_master_array( $master_array ) {

    $master_array['aux_before_after'] = array(
         'name'                    => __("Before After Slider", 'auxin-elements' ),
         'auxin_output_callback'   => 'auxin_widget_before_after_callback',
         'base'                    => 'aux_before_after',
         'description'             => __('Before after comparison slider.', 'auxin-elements' ),
         'class'                   => 'aux-widget-before-after',
         'show_settings_on_create' => true,
         'weight'                  => 1,
         'is_widget'               => true,
         'is_shortcode'            => true,
         'is_so'                   => true,
         'is_vc'                   => true,
         'category'                => THEME_NAME,
         'group'                   => '',
         'admin_enqueue_js'        => '',
         'admin_enqueue_css'       => '',
         'front_enqueue_js'        => '',
         'front_enqueue_css'       => '',
         'icon'                    => 'aux-element aux-pb-icons-image',
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
                'description'       => __('Element title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'id',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Before image','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'before_attach_id',
                'type'              => 'attach_image',
                'value'             => '',
                'class'             => 'before_attach_id',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('After image','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'after_attach_id',
                'type'              => 'attach_image',
                'value'             => '',
                'def_value'         => '',
                'class'             => 'after_attach_id',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __( 'Start offset','auxin-elements' ),
                'description'       => __( 'How much of the before image is visible when the page loads, between 0 to 1.', 'auxin-elements' ),
                'param_name'        => 'default_offset',
                'type'              => 'textfield',
                'value'             => '0.5',
                'def_value'         => '',
                'class'             => 'default_offset',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Image size','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'size',
                'type'              => 'dropdown',
                'def_value'         => 'large',
                'value'             => array(
                    'thumbnail' => __('Thumbnail' , 'auxin-elements' ),
                    'medium'    => __('Medium' , 'auxin-elements' ),
                    'large'     => __('Large' , 'auxin-elements' ),
                    'full'      => __('Full' , 'auxin-elements' )
                ),
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Width','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'width',
                'type'              => 'textfield',
                'value'             => '',
                'class'             => 'width',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Height','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'height',
                'type'              => 'textfield',
                'value'             => '',
                'class'             => 'height',
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
                'class'             => 'extra_classes',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_before_after_master_array', 10, 1 );

// This is the widget call back in fact the front end out put of this widget comes from this function
function auxin_widget_before_after_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'             => '', // header title
        'before_attach_id'  => '', // attachment id of before image
        'after_attach_id'   => '', // attachment id of after image
        'width'             => '', // final width of image
        'height'            => '', // final height of image
        'size'              => 'large',
        'crop'              => true,
        'default_offset'    => '0.5',

        'extra_classes'     => '', // custom css class names for this element
        'custom_el_id'      => '', // custom id attribute for this element
        'base_class'        => 'aux-widget-before-after'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    $before_image = '';
    $after_image  = '';
    $style_attr   = '';

    if ( ! empty( $width ) && is_numeric( $width ) ) {
        $style_attr .= 'max-width:' . $width . 'px;';
    }

    if ( ! empty( $height ) && is_numeric( $height ) ) {
        $style_attr .= 'max-height:' . $height . 'px ';
    }

    if( ! empty( $before_attach_id ) && is_numeric( $before_attach_id ) ) {
        $before_image = wp_get_attachment_image( $before_attach_id, $size );
    }

    if( ! empty( $after_attach_id ) && is_numeric( $after_attach_id ) ) {
        $after_image = wp_get_attachment_image( $after_attach_id, $size );
    }

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];


    // widget output -----------------------
    if ( !empty( $after_image ) ) {
    ?>
        <div class="aux-before-after" style="<?php echo $style_attr ?>" data-offset="<?php echo $default_offset; ?>">
            <?php echo $before_image ?>
            <?php echo $after_image; ?>
        </div>
    <?php } else { ?>
        <div class="aux-before-after" style="<?php echo $style_attr ?>" data-offset="<?php echo $default_offset; ?>">
            <?php echo $before_image ?>
        </div>
    <?php
    }

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
