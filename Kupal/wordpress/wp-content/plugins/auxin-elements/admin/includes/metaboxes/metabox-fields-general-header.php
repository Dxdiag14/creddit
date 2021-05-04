<?php
 /**
 * Adds fields for header metabox
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


function auxin_metabox_fields_general_header(){

    $model                = new Auxin_Metabox_Model();
    $model->id            = 'general-header';
    $model->title         = __('Header', 'auxin-elements');
    $model->is_deprecated = true;
    $model->fields        = array(

        array(
            'title'            => __( 'Use Legacy Header', THEME_DOMAIN ),
            'description'      => __( 'Disable it to replace header section with an Elementor template', THEME_DOMAIN ),
            'id'               => 'page_header_section_use_legacy',
            'type'             => 'select',
            'transport'        => 'postMessage',
            'default'          => 'default',
            'related_controls' => ['page_header_use_legacy'],
            'choices'       => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            ),
        ),

        array(
            'title'       => __( 'Page Custom Menu', 'auxin-elements' ),
            'description' => __( 'Specifies a custom menu for this page.', 'auxin-elements' ),
            'id'          => 'page_header_menu',
            'type'        => 'select',
            'choices'     => auxin_registered_nav_menus(),
            'default'     => 'default',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
        ),

        array(
            'title'       => __( 'Page Cover', 'auxin-elements' ),
            'description' => __( 'Specifies a cover with animations for this page.', 'auxin-elements' ),
            'id'          => 'display_page_header_cover',
            'type'        => 'switch',
            'default'     => '0',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
        ),

        array(
            'title'       => __( 'Page Cover Image', 'auxin-elements' ),
            'description' => __( 'Specifies a image for page cover.', 'auxin-elements' ),
            'id'          => 'page_header_cover_image',
            'type'        => 'image',
            'dependency'  => array(
                array(
                    'id'    => 'display_page_header_cover',
                    'value' => array('1'),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => '',
        ),

        array(
            'title'       => __( 'Page Cover Text', 'auxin-elements' ),
            'description' => __( 'Your custom text for add on the page cover', 'auxin-elements' ),
            'id'          => 'page_header_cover_title',
            'type'        => 'editor',
            'dependency'  => array(
                array(
                    'id'    => 'display_page_header_cover',
                    'value' => array('1'),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => '',
        ),

        array(
            'title'       => __( 'Page Cover Link Text', 'auxin-elements' ),
            'description' => __( 'Specifies text for page cover link.', 'auxin-elements' ),
            'id'          => 'page_header_discover_text',
            'type'        => 'text',
            'dependency'  => array(
                array(
                    'id'    => 'display_page_header_cover',
                    'value' => array('1'),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => '',
        ),

        array(
            'title'       => __( 'Header Menu Layout', 'auxin-elements' ),
            'description' => __( 'Specifies the header layout for this page. By choosing a layout, the corresponding options will appear below.', 'auxin-elements' ),
            'id'          => 'page_header_navigation_layout',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default',
            'type'        => 'select',
            'transport'   => 'refresh',
            'type'        => 'radio-image',
            'choices'     => array(
                'default' => array(
                    'label' => __( 'Theme Default', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/default3.svg'
                ),
                'horizontal-menu-right' => array(
                    'label' => __( 'Logo left, Menu right', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-1.svg'
                ),
                'burger-right' => array(
                    'label' => __( 'Logo left, Burger menu right', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-2.svg'
                ),
                'horizontal-menu-left' => array(
                    'label'     => __( 'Logo right, Menu left', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-7.svg'
                ),
                'burger-left' => array(
                    'label' => __( 'Logo Right, Burger menu left', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-8.svg'
                ),
                'horizontal-menu-center' => array(
                    'label' => __( 'Logo middle in top, Menu middle in bottom', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-4.svg'
                ),
                'logo-left-menu-bottom-left' => array(
                    'label' => __( 'Logo left in top, Menu left in bottom', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-3.svg'
                ),
                'vertical'  => array(
                    'label' => __( 'Vertical Menu', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-6.svg'
                ),
                'burger-right-msg'  => array(
                    'label' => __( 'Logo left, Burger right, Message right', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-6.svg'
                ),
                'logo-left-menu-middle'  => array(
                    'label' => __( 'Logo left, Menu Middle', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-layout-6.svg'
                ),
                'no-header' => array(
                    'label' => __( 'No header', 'auxin-elements' ),
                    'image' => AUXIN_URL . 'images/visual-select/header-none.svg'
                )
            )
        ),

        array(
            'title'         => __( 'Header Width', 'auxin-elements' ),
            'description'   => sprintf(__( 'Specifies the width of header, boxed or full width. %1$s Only works if %2$s Website Layout %3$s option sets to %2$s Full layout %3$s', 'auxin-elements' ), '<br>', '<code>', '</code>'),
            'id'            => 'page_header_width',
            'type'          => 'radio-image',
            'choices'       => array(
                'default'         => array(
                    'label'     => __( 'Theme Default', 'auxin-elements' ),
                    'css_class' => 'axiAdminIcon-default',
                ),
                'boxed'         => array(
                    'label'     => __( 'Boxed', 'auxin-elements' ),
                    'css_class' => 'axiAdminIcon-content-boxed',
                ),
                'semi-full'     => array(
                    'label'     => __( 'Full Width', 'auxin-elements' ),
                    'css_class' => 'axiAdminIcon-content-full',
                )
            ),
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'       => 'default'
        ),

        array(
            'title'          => __( 'Header Height', 'auxin-elements' ),
            'description'    => __( 'Specifies the header height in pixel for this page. Leave it blank to use the theme default value for this option.', 'auxin-elements' ),
            'id'             => 'page_header_container_height',
            'type'           => 'text',
            'dependency'     => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'style_callback' => function( $value = null ){
                $value = rtrim( $value, 'px' );
                $selector  = ".site-header-section .aux-header-elements:not(.aux-vertical-menu-elements), ";
                $selector .= ".site-header-section .aux-fill .aux-menu-depth-0 > .aux-item-content { height:%spx; }";

                return $value ? sprintf( $selector , $value ) :'';
            },
            'default'   => '',
        ),



        array(
            'title'       => __( 'Add Search Button', 'auxin-elements' ),
            'description' => __( 'Whether to add search button in the header or not.', 'auxin-elements' ),
            'id'          => 'page_header_search_button',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default',
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        ),

        array(
            'title'       => __( 'Display Logo', 'auxin-elements' ),
            'description' => __( 'Enable it to add logo in the header.', 'auxin-elements' ),
            'id'          => 'page_header_logo_display',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default',
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        ),

        array(
            'title'       => __( 'Add Border', 'auxin-elements' ),
            'description' => __( 'Whether to add border below the header on this page.', 'auxin-elements' ),
            'id'          => 'page_header_border_bottom',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default',
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        ),

        array(
            'title'       => __( 'Border Color', 'auxin-elements' ),
            'description' => __( 'Specifies the border color for header. Empty or transparent color means using the theme default value for this option.', 'auxin-elements' ),
            'id'          => 'page_header_border_bottom_color',
            'type'        => 'color',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_border_bottom',
                    'value'   => array('yes', 'default'),
                    'operator'=> '=='
                ),
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'style_callback' => function( $value = null ){
                return $value ? ".site-header-section.aux-add-border .aux-wrapper{border-color:$value;}" : '';
            },
            'default'   => ''
        ),

        array(
            'title'       => __( 'Header Animation', 'auxin-elements' ),
            'description' => __( 'Whether to animate the header after page loaded completely.', 'auxin-elements' ),
            'id'          => 'page_header_animation',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default',
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        ),

        array(
            'title'       => __( 'Header Animation Delay', 'auxin-elements' ),
            'description' => __( 'The delay amount before starting the header animation in seconds. Leave it blank to use the theme default value for this option.', 'auxin-elements' ),
            'id'          => 'page_header_animation_delay',
            'type'        => 'text',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_animation',
                    'value'   => 'yes',
                ),
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => ''
        ),

        array(
            'title'       => __( 'Header Background Color', 'auxin-elements' ),
            'description' => __( 'Specifies the background color of header on this page. Empty or transparent color means using the theme default value for this option.', 'auxin-elements' ),
            'id'          => 'page_transparent_header_bgcolor',
            'type'        => 'color',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'style_callback' => function( $value = null ){
                if( ! isset( $post ) ){
                    $post = get_post();
                }
                if ( 'default' !== auxin_get_post_meta( $post, 'page_header_navigation_layout', 'default' ) ) {
                    return $value ? ".site-header-section { background-color:$value; }" : '';
                } else {
                    return '';
                }

            },
            'default'   => ''
        ),

        array(
            'title'       => __( 'Header Menu Color Scheme', 'auxin-elements' ),
            'description' => __( 'Specifies the Color Scheme of Header', 'auxin-elements' ),
            'id'          => 'page_header_color_scheme',
            'type'        => 'select',
            'choices'     => array (
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'light'   => __( 'Light', 'auxin-elements' ),
                'dark'    => __( 'Dark', 'auxin-elements' ),
            ),
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default'
        ),

        array(
            'title'       => __( 'Sticky Header Background Color', 'auxin-elements' ),
            'description' => __( 'Specifies the background color for header when it becomes sticky. Empty or transparent color means using the theme default value for this option.', 'auxin-elements' ),
            'id'          => 'page_sticky_header_color',
            'type'        => 'color',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_top_sticky',
                    'value'   => array('yes', 'default'),
                    'operator'=> '=='
                ),
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'style_callback' => function( $value = null ){
                return $value ? ".site-header-section.aux-sticky { background-color:$value; }" : '';
            },
            'default'   => ''
        ),

        array(
            'title'       => __( 'Sticky Header Border Color', 'auxin-elements' ),
            'description' => __( 'Specifies the border color for header when it becomes sticky. Empty or transparent color means using the theme default value for this option.', 'auxin-elements' ),
            'id'          => 'page_sticky_header_border_color',
            'type'        => 'color',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_top_sticky',
                    'value'   => array('yes', 'default'),
                    'operator'=> '=='
                ),
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'style_callback' => function( $value = null ){
                return $value ? ".aux-top-sticky .site-header-section.aux-sticky .aux-wrapper{border-bottom: 1px solid $value;}" : '';
            },
            'default'   => ''
        ),

        array(
            'title'       => __( 'Sticky Header Menu Color Scheme', 'auxin-elements' ),
            'description' => __( 'Specifies the color scheme of header menu on sticky header.', 'auxin-elements' ),
            'id'          => 'page_header_sticky_color_scheme',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_top_sticky',
                    'value'   => array('yes', 'default'),
                    'operator'=> '=='
                ),
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'choices'     => array (
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'light'   => __( 'Light', 'auxin-elements' ),
                'dark'    => __( 'Dark', 'auxin-elements' ),
            ),
            'default'     => ''
        ),

        array(
            'title'       => __( 'Scale Logo on Sticky Header', 'auxin-elements' ),
            'description' => __( 'Enable this option to scale the logo on sticky mode.', 'auxin-elements' ),
            'id'          => 'page_header_logo_can_scale',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_top_sticky',
                    'value'   => array('yes', 'default'),
                    'operator'=> '=='
                ),
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'   => 'default',
            'choices'   => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        ),

        array(
            'title'         => __('Vertical Menu Background color', 'auxin-elements'),
            'description'   => __('Specifies background color of Vertical Menu on this page. Empty or transparent color means using the theme default value for this option.', 'auxin-elements'),
            'id'            => 'page_vertical_menu_background_color',
            'type'          => 'color',
            'default'       => '',
            'dependency'    => array(
                array(
                    'id'       => 'page_header_navigation_layout',
                    'value'    => 'vertical',
                    'operator' => '=='
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
        ),

        array(
            'title'       => __( 'Vertical Menu Items Align', 'auxin-elements' ),
            'description' => '',
            'id'          => 'page_vertical_header_items_align',
            'dependency'  => array(
                array(
                    'id'       => 'page_header_navigation_layout',
                    'value'    => 'vertical',
                    'operator' => '=='
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'choices'     => array (
                'default'     => __( 'Theme Default', 'auxin-elements' ),
                'center'     => __( 'Center', 'auxin-elements' ),
                'left'       => __( 'Left', 'auxin-elements' ),
            ),
            'type'     => 'select',
            'default'  => 'default'
        ),

        array(
            'title'         => __('Display Vertical Menu Footer', 'auxin-elements'),
            'description'   => __('Whether to display footer at the bottom of vertical menu.', 'auxin-elements'),
            'id'            => 'page_vertical_menu_footer_display',
            'type'          => 'select',
            'dependency'  => array(
                array(
                    'id'       => 'page_header_navigation_layout',
                    'value'    => 'vertical',
                    'operator' => '=='
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'       => 'default',
            'choices'       => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            ),
        ),

        array(
            'title'         => __('Display Search box Border', 'auxin-elements'),
            'description'   => __('Specifies the display of border under the search box', 'auxin-elements'),
            'id'            => 'page_vertical_header_search_border',
            'type'          => 'select',
            'dependency'  => array(
                array(
                    'id'       => 'page_header_navigation_layout',
                    'value'    => 'vertical',
                    'operator' => '=='
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'       => 'default',
            'choices'       => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            ),
        ),

        array(
            'title'         => __('Add Social Icons', 'auxin-elements'),
            'description'   => __('Enable it to add social icons in the header menu.', 'auxin-elements'),
            'id'            => 'page_header_social_icons',
            'type'          => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'       => 'default',
            'choices'       => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            ),
        ),

        array(
            'title'         => __('Social Icon Size', 'auxin-elements'),
            'description'   => __('Specifies the size of social icons in the header menu.', 'auxin-elements'),
            'id'            => 'page_header_social_icons_size',
            'type'          => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'       => 'page_header_social_icons',
                    'value'    => 'yes',
                    'operator' => '=='
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'       => 'default',
            'choices'       => array(
                'default'     => __( 'Theme Default', 'auxin-elements' ),
                'small'       => __( 'Small', 'auxin-elements' ),
                'medium'      => __( 'Medium', 'auxin-elements' ),
                'large'       => __( 'Large', 'auxin-elements' ),
                'extra-large' => __( 'Extra Large', 'auxin-elements' ),
            ),
        )

    );

    if ( class_exists( 'WooCommerce' ) ) {

        $model->fields[] = array(
            'title'       => __( 'Display Header Cart', 'auxin-elements' ),
            'description' => __( 'Whether to display cart on top header bar.', 'auxin-elements' ),
            'id'          => 'page_show_header_cart',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'starter'     => '1',
            'default'     => 'default',
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        );
        $model->fields[] =    array(
            'title'       => __( 'Cart Type', 'auxin-elements' ),
            'description' => '',
            'id'          => 'page_header_cart_mode',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_show_header_cart',
                    'value'   => 'yes',
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'dropdown'     => __( 'Drop Down', 'auxin-elements' ),
                'offcanvas'      => __( 'Off Canvas', 'auxin-elements' )
            ),
            'default'     => 'default'
        );
        $model->fields[] = array(
            'title'       => __( 'Icon for Cart', 'auxin-elements' ),
            'description' => '',
            'id'          => 'page_header_cart_icon',
            'type'        => 'icon',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_show_header_cart',
                    'value'   => 'yes',
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default'
        );

        $model->fields[] =    array(
            'title'       => __( 'Cart Dropdown Skin', 'auxin-elements' ),
            'description' => '',
            'id'          => 'page_header_cart_dropdown_skin',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_show_header_cart',
                    'value'   => 'yes',
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'light'     => __( 'Light', 'auxin-elements' ),
                'dark'      => __( 'Dark', 'auxin-elements' )
            ),
            'default'     => 'default'
        );

        $model->fields[] =    array(
            'title'       => __( 'Dropdown Action On', 'auxin-elements' ),
            'description' => '',
            'id'          => 'page_header_cart_dropdown_action_on',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'horizontal-menu-right', 'burger-right', 'horizontal-menu-left', 'burger-left', 'horizontal-menu-center', 'logo-left-menu-bottom-left','logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_show_header_cart',
                    'value'   => 'yes',
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'choices'     => array(
                'default' => __( 'Default', 'auxin-elements' ),
                'hover'   => __( 'Hover', 'auxin-elements' ),
                'click'   => __( 'Click', 'auxin-elements' )
            ),
            'default'     => 'default'
        );

        $model->fields[] = array(
            'title'       => __( 'Display My Account', 'auxin-elements' ),
            'description' => __( 'Whether to display my account on header.', 'auxin-elements' ),
            'id'          => 'page_show_my_account',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default',
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        );

        $model->fields[] = array(
            'title'       => __( 'Display Wishlist', 'auxin-elements' ),
            'description' => __( 'Whether to display wishlist on header.', 'auxin-elements' ),
            'id'          => 'page_show_wishlist',
            'type'        => 'select',
            'dependency'  => array(
                array(
                    'id'      => 'page_header_navigation_layout',
                    'value'   => array( 'logo-left-menu-middle' ),
                ),
                array(
                    'id'      => 'page_header_section_use_legacy',
                    'value'   => array( 'yes' ),
                    'operator'=> '!='
                ),
            ),
            'default'     => 'default',
            'choices'     => array(
                'default' => __( 'Theme Default', 'auxin-elements' ),
                'yes'     => __( 'Yes', 'auxin-elements' ),
                'no'      => __( 'No', 'auxin-elements' ),
            )
        );

    }

    return $model;
}
