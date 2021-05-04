<?php
/**
 * Text element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function  auxin_get_text_master_array( $master_array ) {

    $master_array['aux_text'] = array(
        'name'                          => __('Info Box', 'auxin-elements'),
        'auxin_output_callback'         => 'auxin_widget_column_callback',
        'base'                          => 'aux_text',
        'description'                   => __('Iconic text block.', 'auxin-elements'),
        'class'                         => 'aux-widget-text',
        'show_settings_on_create'       => true,
        'weight'                        => 1,
        'is_widget'                     => false,
        'is_shortcode'                  => true,
        'is_so'                         => true,
        'is_vc'                         => true,
        'so_api'                        => false,
        'category'                      => THEME_NAME,
        'group'                         => '',
        'admin_enqueue_js'              => '',
        'admin_enqueue_css'             => '',
        'front_enqueue_js'              => '',
        'front_enqueue_css'             => '',
        'icon'                          => 'aux-element aux-pb-icons-text',
        'custom_markup'                 => '',
        'js_view'                       => '',
        'html_template'                 => '',
        'deprecated'                    => '',
        'content_element'               => '',
        'as_parent'                     => '',
        'as_child'                      => '',
        'params' => array(
            array(
                'heading'           => __('Title','auxin-elements'),
                'description'       => __('Text title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'description'       => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Subtitle','auxin-elements'),
                'description'       => '',
                'param_name'        => 'subtitle',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'subtitle',
                'description'       => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Title Link','auxin-elements'),
                'description'       => '',
                'param_name'        => 'title_link',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'title_link',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Wrapper Style','auxin-elements'),
                'description'       => '',
                'param_name'        => 'wrapper_style',
                'type'              => 'aux_visual_select',
                'def_value'         => 'simple',
                'choices'           => array(
                    'simple'          => array(
                        'label'     => __('Simple', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/text-normal.svg'
                    ),
                    'outline'      => array(
                        'label'     => __('Outlined', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/text-outline.svg'
                    ),
                    'box'    => array(
                        'label'     => __('Boxed', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/text-boxed.svg'
                    )
                ),
                'holder'            => '',
                'class'             => 'wrapper_style',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Text Align','auxin-elements'),
                'description'       => '',
                'param_name'        => 'text_align',
                'type'              => 'aux_visual_select',
                'def_value'         => '',
                'choices'           => array(
                    'left'      => array(
                        'label'     => __('Left', 'auxin-elements'),
                        'css_class' => 'axiAdminIcon-text-align-left',
                    ),
                    'center'    => array(
                        'label'     => __('Center', 'auxin-elements'),
                        'css_class' => 'axiAdminIcon-text-align-center'
                    ),
                    'right'     => array(
                        'label'     => __('Right', 'auxin-elements'),
                        'css_class' => 'axiAdminIcon-text-align-right'
                    )
                ),
                'holder'            => '',
                'class'             => 'text_align',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Text Align on Small Screens','auxin-elements'),
                'description'       => '',
                'param_name'        => 'text_align_resp',
                'type'              => 'aux_visual_select',
                'def_value'         => '',
                'choices'           => array(
                    'left'      => array(
                        'label'     => __('Left', 'auxin-elements'),
                        'css_class' => 'axiAdminIcon-text-align-left',
                    ),
                    'center'    => array(
                        'label'     => __('Center', 'auxin-elements'),
                        'css_class' => 'axiAdminIcon-text-align-center'
                    ),
                    'right'     => array(
                        'label'     => __('Right', 'auxin-elements'),
                        'css_class' => 'axiAdminIcon-text-align-right'
                    )
                ),
                'holder'            => '',
                'class'             => 'text_align_resp',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Text Color Scheme','auxin-elements'),
                'description'       => '',
                'param_name'        => 'text_color_mode',
                'type'              => 'dropdown',
                'def_value'         => '',
                'value'             => array(
                    ''              => __( 'Default'  , 'auxin-elements' ),
                    'dark'          => __( 'Dark'     , 'auxin-elements' ),
                    'light'         => __( 'Light'    , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'text_color_mode',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),

            array(
                'heading'           => __('Wrapper Background Type', 'auxin-elements' ),
                'description'       => '',
                'param_name'        => 'wrapper_type',
                'type'              => 'dropdown',
                'def_value'         => '',
                'value'             => array(
                    'color'         => __( 'Single Color'  , 'auxin-elements' ),
                    'image'         => __( 'Background Image' , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'wrapper_type',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),

            array(
                'heading'           => __('Wrapper Background Color', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'wrapper_bg_color',
                'type'              => 'colorpicker',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'wrapper_bg_color',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'wrapper_type',
                    'value'         => array('color')
                ),
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Wrapper Background image', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'wrapper_bg_image',
                'type'              => 'attach_image',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'wrapper_bg_image',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'wrapper_type',
                    'value'         => array('image')
                ),
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Wrapper Background Image Display', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'background_display',
                'type'              => 'dropdown',
                'value'             => array(
                    'cover'             => __( 'Cover', 'auxin-elements' ),
                    'tile'              => __( 'Tiled Image', 'auxin-elements' ),
                    'center'            => __( 'Centered, with original size', 'auxin-elements' ),
                    'fixed'             => __( 'Fixed', 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'background_display',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'wrapper_type',
                    'value'         => array('image')
                ),
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Wrapper Background Overlay', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'overlay_color',
                'type'              => 'colorpicker',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'overlay_color',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'wrapper_type',
                    'value'         => array('image')
                ),
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Header Background Type', 'auxin-elements' ),
                'description'       => '',
                'param_name'        => 'header_type',
                'type'              => 'dropdown',
                'def_value'         => '',
                'value'             => array(
                    'color'         => __( 'Single Color'  , 'auxin-elements' ),
                    'image'         => __( 'Background Image' , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'header_type',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __( 'Header Background color', 'auxin-elements' ),
                'description'      => '',
                'param_name'       => 'header_bg_color',
                'type'             => 'colorpicker',
                'def_value'        => '',
                'value'            => '',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => '',
                'dependency'        => array(
                    'element'       => 'header_type',
                    'value'         => array('color')
                ),
                'weight'           => '',
                'group'            => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class' => ''

            ),
            array(
                'heading'           => __('Header Background image', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'header_bg_img',
                'type'              => 'attach_image',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'wrapper_bg_image',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'header_type',
                    'value'         => array('image')
                ),
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Header Background Image Display', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'header_img_display',
                'type'              => 'dropdown',
                'value'             => array(
                    'cover'             => __( 'Cover', 'auxin-elements' ),
                    'tile'              => __( 'Tiled Image', 'auxin-elements' ),
                    'center'            => __( 'Centered, with original size', 'auxin-elements' ),
                    'fixed'             => __( 'Fixed', 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'background_display',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'header_type',
                    'value'         => array('image')
                ),
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon or image', 'auxin-elements' ),
                'description'       => __('Please choose an icon from avaialable icons.', 'auxin-elements'),
                'heading'           => __('Display Icon or Image', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'icon_or_image',
                'type'              => 'dropdown',
                'def_value'         => 'icon',
                'value'             => array(
                    'icon'          => __( 'Icon'  , 'auxin-elements' ),
                    'image'         => __( 'Image' , 'auxin-elements' ),
                ),
                'holder'            => '',
                'class'             => 'icon_or_image',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon', 'auxin-elements' ),
                'description'       => __('Please choose an icon from the list.', 'auxin-elements'),
                'param_name'        => 'icon',
                'type'              => 'aux_iconpicker',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'aux_iconpicker',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'icon_or_image',
                    'value'         => array('icon')
                ),
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Image','auxin-elements'),
                'description'       => '',
                'param_name'        => 'image',
                'type'              => 'attach_image',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'image',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'icon_or_image',
                    'value'         => array('image')
                ),
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Image Size','auxin-elements'),
                'description'       => '',
                'param_name'        => 'image_size',
                'type'              => 'dropdown',
                'def_value'         => '',
                'value'             => array(
                    'full'          => __( 'Orginal Size'  , 'auxin-elements' ),
                    'large'         => __( 'Large'         , 'auxin-elements' ),
                    'medium'        => __( 'Medium'        , 'auxin-elements' ),
                    'thumbnail'     => __( 'Thumbnail'     , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'image_size',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'icon_or_image',
                    'value'         => array('image')
                ),
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon color','auxin-elements'),
                'description'       => __('Choose a color for icon.','auxin-elements'),
                'param_name'        => 'icon_color',
                'type'              => 'colorpicker',
                'def_value'         => '#888',
                'value'             => '',
                'holder'            => '',
                'class'             => 'icon_color',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'icon_or_image',
                    'value'         => array('icon')
                ),
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon size','auxin-elements'),
                'description'       => '',
                'param_name'        => 'icon_size',
                'type'              => 'dropdown',
                'def_value'         => '',
                'value'             => array(
                    'small'   => __( 'Small'   , 'auxin-elements' ),
                    'medium'  => __( 'Medium'  , 'auxin-elements' ),
                    'large'   => __( 'Large'   , 'auxin-elements' ),
                    'x-large' => __( 'X-Large' , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'icon_size',
                'admin_label'       => false,
                 'dependency'        => array(
                    'element'       => 'icon_or_image',
                    'value'         => array('icon')
                ),
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon background color','auxin-elements'),
                'description'       => __('Choose a color for background of icon.','auxin-elements'),
                'param_name'        => 'icon_bg_color',
                'type'              => 'colorpicker',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'icon_bg_color',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'icon_or_image',
                    'value'         => array('icon')
                ),
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __( 'Icon/Image outline color', 'auxin-elements' ),
                'description'       => __( 'Choose a color for the border around the icon or image.', 'auxin-elements' ),
                'param_name'        => 'icon_border_color',
                'type'              => 'colorpicker',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'icon_border_color',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => 'Icon & Image',
                'edit_field_class'  => ''
            ),

            array(
                'heading'           => __( 'Icon/Image outline width', 'auxin-elements' ),
                'description'       => __( 'Choose a width for the border around the icon or image.', 'auxin-elements' ),
                'param_name'        => 'icon_border_width',
                'type'              => 'textfield',
                'def_value'         => '1',
                'value'             => '',
                'holder'            => '',
                'class'             => 'icon_border_width',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon background shape','auxin-elements'),
                'description'       => '',
                'param_name'        => 'icon_shape',
                'type'              => 'aux_visual_select',
                'choices'           => array(
                    'circle'          => array(
                        'label'     => __('Circle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-circle.svg'
                    ),
                    'semi-circle'      => array(
                        'label'     => __('Semi-circle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-semi-circle.svg'
                    ),
                    'round-rect'    => array(
                        'label'     => __('Round Rectangle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-round-rectangle.svg'
                    ),
                    'cross-rect'    => array(
                        'label'     => __('Cross Rectangle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-cross-rectangle.svg'
                    ),
                    'rect'    => array(
                        'label'     => __('Rectangle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-rectangle.svg'
                    )
                ),
                'holder'            => '',
                'class'             => 'icon_shape',
                'dependency'        => array(
                    'element'       => 'icon_or_image',
                    'value'         => array('icon')
                ),
                'admin_label'       => false,
                'weight'            => '',
                'group'             =>__( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Image shape','auxin-elements'),
                'description'       => '',
                'param_name'        => 'img_shape',
                'type'              => 'aux_visual_select',
                'choices'           => array(
                    'default'    => array(
                        'label'     => __('Default Aspect', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-rectangle.svg'
                    ),
                    'circle'          => array(
                        'label'     => __('Circle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-circle.svg'
                    ),
                    'semi-circle'      => array(
                        'label'     => __('Semi-circle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-semi-circle.svg'
                    ),
                    'round-rect'    => array(
                        'label'     => __('Round Rectangle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-round-rectangle.svg'
                    ),
                    'rect'    => array(
                        'label'     => __('Rectangle', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/icon-style-rectangle.svg'
                    )
                ),
                'holder'            => '',
                'dependency'        => array(
                    'element'       => 'icon_or_image',
                    'value'         => array('image')
                ),
                'class'             => 'img_shape',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Image or icon position','auxin-elements'),
                'description'       => '',
                'param_name'        => 'image_position',
                'type'              => 'aux_visual_select',
                'def_value'         => '',
                'choices'           => array(
                    'top'           => array(
                        'label'     => __('Top', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/column-icon-top.svg'
                    ),
                    'left'          => array(
                        'label'     => __('Left', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/column-icon-left.svg'
                    ),
                    'right'         => array(
                        'label'     => __('Right', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/column-icon-right.svg'
                    )
                ),
                'holder'            => '',
                'class'             => 'image_position',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Icon & Image', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
           array(
                'heading'           => __('Display button', 'auxin-elements' ),
                'description'       => __('Display a button in text widget', 'auxin-elements' ),
                'param_name'        => 'display_button',
                'type'              => 'checkbox',
                'def_value'         => '',
                'value'             => '',
                'class'             => 'display_button',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
           array(
                'heading'           => __('Button label', 'auxin-elements' ),
                'description'       => __('The label of button.', 'auxin-elements' ),
                'param_name'        => 'btn_label',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'label',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Button size','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'btn_size',
                'type'              => 'dropdown',
                'def_value'         => 'medium',
                'value'             => array(
                    'exlarge' => __('Exlarge', 'auxin-elements' ),
                    'large'   => __('Large'  , 'auxin-elements' ),
                    'medium'  => __('Medium' , 'auxin-elements' ),
                    'small'   => __('Small'  , 'auxin-elements' ),
                    'tiny'    => __('Tiny'   , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'round',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Button shape style','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'btn_border',
                'type'             => 'aux_visual_select',
                'value'            => '',
                'class'            => 'border',
                'admin_label'      => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'           => '',
                'group'            => __( 'Button', 'auxin-elements' ),
                'edit_field_class' => '',
                'choices'          => array(
                    'none'      => array(
                        'label' => __('Box', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    'round'     => array(
                        'label' => __('Round', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-curved.svg'
                    ),
                    'curve'     => array(
                        'label' => __('Curve', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-rounded.svg'
                    )
                )
            ),
            array(
                'heading'          => __('Button style','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'btn_style',
                'type'             => 'aux_visual_select',
                'value'            => '',
                'class'            => 'style',
                'admin_label'      => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'           => '',
                'group'            => __( 'Button', 'auxin-elements' ),
                'edit_field_class' => '',
                'choices'          => array(
                    'none'      => array(
                        'label' => __('Normal', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    '3d'        => array(
                        'label' => __('3D', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-3d.svg'
                    ),
                    'outline'   => array(
                        'label' => __('Outline', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-outline.svg'
                    )
                )
            ),
            array(
                'heading'           => __('Uppercase label','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'btn_uppercase',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => 'uppercase',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Darken the label','auxin-elements' ),
                'description'       => __('Darken label of button while mouse over it.','auxin-elements' ),
                'param_name'        => 'btn_dark',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => 'dark',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon for button','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'btn_icon',
                'type'              => 'aux_iconpicker',
                'value'             => '',
                'class'             => 'icon-name',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon alignment','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'btn_icon_align',
                'type'              => 'dropdown',
                'def_value'         => 'default',
                'value'             => array(
                   'default'        =>  __('Default'            , 'auxin-elements' ),
                   'left'           =>  __('Left'               , 'auxin-elements' ),
                   'right'          =>  __('Right'              , 'auxin-elements' ),
                   'over'           =>  __('Over'               , 'auxin-elements' ),
                   'left-animate'   =>  __('Animate from Left'  , 'auxin-elements' ),
                   'right-animate'  =>  __('Animate from Right' , 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'icon-align',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Color of button','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'btn_color_name',
                'type'              => 'aux_visual_select',
                'value'             => 'carmine-pink',
                'choices'           => auxin_get_famous_colors_list(),
                'holder'            => '',
                'class'             => 'color',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Link','auxin-elements' ),
                'description'       => __('If you want to link your button.', 'auxin-elements' ),
                'param_name'        => 'btn_link',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'link',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Open link in','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'btn_target',
                'type'              => 'dropdown',
                'def_value'         => '_self',
                'value'             => array(
                    '_self'  => __('Current page' , 'auxin-elements' ),
                    '_blank' => __('New page', 'auxin-elements' )
                ),
                'holder'            => '',
                'class'             => 'btn_target',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_button',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'group'             => __( 'Button', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Content','auxin-elements'),
                'description'       => __('Enter a text as a text content.','auxin-elements'),
                'param_name'        => 'content',
                'type'              => 'textarea_html',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'div',
                'class'             => 'content',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Footer Shape','auxin-elements'),
                'description'       => '',
                'param_name'        => 'footer_shape',
                'type'              => 'aux_visual_select',
                'def_value'         => 'simple',
                'choices'           => array(
                    'none'          => array(
                        'label'     => __('None', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/text-normal.svg'
                    ),
                    'wave'      => array(
                        'label'     => __('Wave', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/text-outline.svg'
                    ),
                    'tail'    => array(
                        'label'     => __('Tail', 'auxin-elements'),
                        'image'     => AUXIN_URL . 'images/visual-select/text-boxed.svg'
                    )
                ),
                'holder'            => '',
                'class'             => 'footer_shape',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Wrapper Layout', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'     => __( 'Footer Shape color', 'auxin-elements' ),
                'description' => __( 'Select color for shape', 'auxin-elements' ),
                'param_name'  => 'footer_shape_color',
                'type'        => 'colorpicker',
                'value'       => '',
                'class'       => 'footer_shape_color',
                'dependency'  => array(
                    'element' => 'footer_shape',
                    'value'   => array('tail', 'wave')
                ),
                'group'       => __( 'Wrapper Layout', 'auxin-elements' ),
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements'),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements'),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_text_master_array', 10, 1 );

// This is the widget call back in fact the front end out put of this widget comes from this function
function auxin_widget_column_callback( $atts, $shortcode_content = null ){

    global $aux_content_width;

    // Defining default attributes
    $default_atts = array(
        'title'              => '',                // section title
        'subtitle'           => '',                // Text as subtitle under the title
        'title_link'         => '',                // the link on title
        'wrapper_style'      => 'simple',          // box, outline,
        'text_align'         => 'center',          // left, right, center
        'text_align_resp'    => 'center',          // left, right, center
        'text_color_mode'    => 'dark',            // dark, light
        'wrapper_type'       => '',                // color, image
        'wrapper_bg_color'   => '',
        'wrapper_bg_image'   => '',
        'background_display' => 'center',          //  center, fixed , cover, tile
        'overlay_color'      => '',
        'header_type'        => 'color',           //color, image
        'header_bg_color'    => '',
        'header_bg_img'      => '',
        'header_img_display' => 'center',          //  center, fixed , cover, tile
        'icon_or_image'      => 'icon',            //icon, image
        'icon'               => '',                // icon on column side
        'image'              => '',                // image on column side
        'size'               => 'medium_large',    // image size
        'width'              => '',                // final width of image
        'height'             => '',                // final height of imageone
        'lightbox'           => 'no',              // open in lightbox or not
        'preloadable'        => '0',
        'preload_preview'    => '0',
        'preload_bgcolor'    => '',
        'icon_size'          => 'large',           // small, medium, large, x-large
        'icon_shape'         => '',                // circle, semi-circle, round-rect, rect, fill, ...
        'img_shape'          => '',                // circle, semi-circle, round-rect, rect, fill, ...
        'image_position'     => 'top',             // top,left,right
        'content'            => '',                // the content
        'display_button'     => '0',
        'btn_label'          => '',
        'btn_size'           => '',
        'btn_border'         => '',
        'btn_style'          => '',
        'btn_uppercase'      => '',
        'btn_dark'           => '',
        'btn_icon'           => '',
        'btn_icon_align'     => '',
        'btn_color_name'     => '',
        'btn_link'           => '',
        'btn_target'         => '',
        'btn_nofollow'       => false,
        'footer_shape'       => '',
        'footer_shape_color' => '',
        'icon_svg_inline'    => '',
        'extra_classes'      => '',                // custom css class names for this element
        'custom_el_id'       => '',                // custom id attribute for this element
        'base_class'         => 'aux-widget-text' // base class name for container
    );


    $result                = auxin_get_widget_scafold( $atts, $default_atts );

    extract( $result['parsed_atts'] );

    if( empty( $size ) ){
        $size = 'medium_large';
    }
    if( 'custom' == $size ){
        $size = array( 'width' => $width, 'height' => $height );
    }

    if( ! empty( $image ) && is_numeric( $image ) ) {
        $image = auxin_get_the_responsive_attachment( $image,
            array(
                'quality'         => 100,
                'preloadable'     => auxin_is_true( $preloadable ),
                'preload_preview' => $preload_preview,
                'preload_bgcolor' => $preload_bgcolor,
                'size'            => $size,
                'crop'            => true,
                'add_hw'          => true,
                'upscale'         => false,
                'original_src'    => 'full' === $size ? true : false,
                'attr'            => array( 'class' => "aux-attachment aux-featured-image aux-attachment-id-$image" )
            )
        );
    }

    if ( ! empty( $wrapper_bg_image ) && is_numeric( $wrapper_bg_image ) ){
         $wrapper_bg_image = wp_get_attachment_image_url( $wrapper_bg_image, 'full' );
    }

    if ( ! empty( $header_bg_img ) && is_numeric( $header_bg_img ) ){
         $header_bg_img = wp_get_attachment_image_url( $header_bg_img, 'full' );
    }

    $content                = empty( $content    ) ? $shortcode_content : $content;

    // Box Main Classes

    $main_classes  = 'aux-widget-advanced-text ';
    $main_classes .= 'aux-wrap-style-' . esc_attr( $wrapper_style ) . ' ';
    $main_classes .= 'aux-ico-pos-' . esc_attr( $image_position ) . ' ';
    $main_classes .= 'aux-text-' . esc_attr( $text_align ) . ' ';
    $main_classes .= 'aux-text-resp-' . esc_attr( $text_align_resp ) . ' ';
    if( $text_color_mode ){
        $main_classes .= 'aux-text-color-' . esc_attr( $text_color_mode ) . ' ';
    }
    $main_classes .= 'aux-text-widget-bg-' . esc_attr( $background_display ) . ' ';
    $main_classes .= empty( $header_bg_color ) ? '' : 'aux-text-fill-header ' ;
    $main_classes .= empty( $header_bg_img ) ? '' : 'aux-text-img-header ' ;
    $main_classes .= empty( $header_bg_img ) ? '' : 'aux-text-img-header ' ;
    $main_classes .= empty( $content ) ? 'aux-text-no-content ' : ' ' ;
    $main_classes .= ! empty( $icon ) && empty( $icon_bg_color ) && ( 'top' != $image_position ) ? 'aux-text-padding-fix ' : '';
    $main_classes .= ! empty( $header_bg_color ) && ( 'top' != $image_position ) ? 'aux-text-header-fix ' : '';

    //---------------------------------------------
    // Overlay Inline Styles

    $overlay_style  = '';
    $overlay_style  .= empty( $overlay_color ) ? '' : 'background-color: ' . esc_attr( $overlay_color ) . '; ';

    $overlay_style   = ! empty( $overlay_style ) ? 'style="' . $overlay_style . '"' : '';

    //---------------------------------------------
    // Box Inline Styles

    $main_styles   = '';
    $main_styles  .= empty( $wrapper_bg_color ) ? '' : 'background-color: ' . esc_attr( $wrapper_bg_color ) . '; ';
    $main_styles  .= empty( $wrapper_bg_image ) ? '' : 'background-image: url(' . esc_attr( $wrapper_bg_image ) . '); ';

    $main_styles   = ! empty( $main_styles ) ? 'style="' . $main_styles . '"' : '';

    //---------------------------------------------
    // Header Inline Styles

    $header_styles  = '';
    $header_styles .= empty( $header_bg_color ) ? '' : 'background-color: ' . esc_attr( $header_bg_color ) . '; ';
    $header_styles .= empty( $header_bg_img ) ? '' : 'background-image: url(' . esc_attr( $header_bg_img ) . '); ';

    $header_styles  = ! empty( $header_styles ) ? 'style="' . $header_styles . '"' : '';
    //---------------------------------------------
    // Header Classnames

    $header_classess  = '';
    $header_classess .= empty( $header_bg_img ) ? '' : 'aux-text-widget-bg-' . esc_attr( $header_img_display ) . ' ';

    //---------------------------------------------
    // Icon Classnames

    $icon_box_classnames  = '';
    $icon_box_classnames .= ! empty ( $icon_size ) ? 'aux-ico-' . esc_attr( $icon_size ) . ' ' : '';
    $icon_box_classnames .= ! empty ( $icon ) ? 'aux-ico-shape-' . esc_attr( $icon_shape ) . ' ' : '';
    $icon_box_classnames .= ! empty ( $image ) ? 'aux-img-box aux-ico-shape-' . esc_attr( $img_shape ) . ' ' : '';
    $icon_box_classnames .= empty( $icon_bg_color ) ? 'aux-ico-clear' : '';

    $icon_classname       = empty( $icon ) ? '' : $icon ;

    //---------------------------------------------
    // Footer Classnames

    $footer_classess  = '';
    $footer_classess .= empty( $footer_shape ) ? '' : 'aux-border-shape-' . esc_attr( $footer_shape ) . ' ';

    //---------------------------------------------
    // Footer Inline Styles

    $footer_styles  = '';
    $footer_styles .= ! empty ( $footer_shape_color ) && 'wave' === $footer_shape ? 'fill: ' . esc_attr( $footer_shape_color) . '; ' : '';
    $footer_styles .= ! empty ( $footer_shape_color ) && 'tail' === $footer_shape ? 'border-top-color: ' . esc_attr( $footer_shape_color) . '; ' : '';

    $footer_styles  = ! empty( $footer_styles ) ? 'style="' . $footer_styles . '"' : '';

    //---------------------------------------------

    $btn_atts = array(
        'label'      => $btn_label,
        'size'       => $btn_size,
        'border'     => $btn_border,
        'style'      => $btn_style,
        'uppercase'  => $btn_uppercase,
        'dark'       => $btn_dark,
        'icon'       => $btn_icon,
        'icon_align' => $btn_icon_align,
        'color_name' => $btn_color_name,
        'link'       => $btn_link,
        'target'     => $btn_target,
        'nofollow'   => $btn_nofollow
    );

    ob_start();
    // widget header ------------------------------
    echo $result['widget_header'];
?>
        <div class="<?php echo $main_classes ;?>" <?php echo $main_styles ;?>>
        <?php if ( ! empty( $overlay_color ) ) { ?>
            <div class="aux-text-widget-overlay" <?php echo $overlay_style ;?>></div>
        <?php } ?>

            <?php if( ! empty( $icon ) || ! empty( $image ) ||  ! empty( $header_bg_img ) || ! empty( $icon_svg_inline ) ) { ?>
                <div class="aux-text-widget-header <?php echo $header_classess ;?>" <?php echo $header_styles ;?> >
                        <div class="aux-ico-box <?php echo $icon_box_classnames ;?> ">
                            <?php if ( ! empty( $icon ) ){ ;?>
                                <span class="aux-ico <?php echo esc_attr( $icon_classname ) ;?>" > </span>
                            <?php } elseif ( ! empty( $image ) ) { ?>
                                    <?php echo $image; ?>
                            <?php } else { ?>
                                    <?php echo $icon_svg_inline ;?>
                            <?php }; ?>
                        </div>
                </div>
            <?php } ?>

            <div class="aux-text-inner aux-text-widget-content">
                <?php if( ! empty( $title ) && empty( $title_link ) ) { ?>
                <h4 class="col-title"><?php echo $title; ?></h4>
                <?php } elseif( ! empty( $title ) && ! empty( $title_link ) ) { ?>
                <h4 class="col-title"><a href="<?php echo $title_link; ?>"><?php echo $title; ?></a></h4>
                <?php } if( ! empty( $subtitle ) ) { ?>
                <h5 class="col-subtitle"><?php echo $subtitle; ?></h5>

                <?php } if( ! empty( $content ) ) { ?>
                <div class="widget-content">
                    <?php $encoding_flag =  defined('ENT_HTML401') ? ENT_HTML401 : ENT_QUOTES; ?>
                    <?php echo do_shortcode( html_entity_decode( $content, $encoding_flag, 'UTF-8') ); ?>
                </div>
                <?php } if ( auxin_is_true( $display_button ) ) {
                    echo auxin_widget_button_callback( $btn_atts );
                } ?>
            </div>
        </div>
        <?php if ( ! empty( $footer_classess ) ) { ?>
            <div class="aux-text-widget-footer">
                <div class="<?php echo $footer_classess; ?>"<?php echo $footer_styles; ?>>
                <?php if ( 'wave' === $footer_shape ){?>
                <svg width="100%" height="16">
                    <defs>
                        <pattern id="pattern-shape-wave" x="16" y="0" width="35" height="16" patternUnits="userSpaceOnUse" >
                            <path d="M16 16 L35 0 L-2 0 Z" />
                        </pattern>
                    </defs>
                    <rect x="0" y="0" width="100%" height="17" style="fill: url(#pattern-shape-wave);" />
                </svg>
                    <?php }?>
                </div>
            </div>
        <?php } ?>

<?php
    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
