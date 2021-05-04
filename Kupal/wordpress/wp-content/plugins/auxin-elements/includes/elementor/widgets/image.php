<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Image' widget.
 *
 * Elementor widget that displays an 'Image' with lightbox.
 *
 * @since 1.0.0
 */
class Image extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Image' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_image';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Image' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Advanced Image', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Image' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-image auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Image' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-core' );
    }

    /**
     * Register 'Image' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Content Tab
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'image_section',
            array(
                'label'      => __('Image', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'image',
            array(
                'label'      => __('Image','auxin-elements' ),
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
                'default'    => array(
                    'url' => Utils::get_placeholder_image_src()
                )
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'       => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'separator'  => 'none',
                'default'    => 'large'
            )
        );

        $this->add_control(
            'link',
            array(
                'label'         => __('Image Link','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'http://phlox.pro',
                'show_external' => true,
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'hover_section',
            array(
                'label'      => __('Hover Image', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'display_hover',
            array(
                'label'        => __('Display Hover Image','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'hover_image',
            array(
                'label'      => __( 'Image', 'auxin-elements' ),
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
                'condition'  => array(
                    'display_hover' => 'yes'
                )
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ribbon_section',
            array(
                'label'      => __('Ribbon', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'display_ribbon',
            array(
                'label'        => __('Diplay Ribbon','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'ribbon_text',
            array(
                'label'       => __('Text','auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'NEW',
                'condition'   => array(
                    'display_ribbon' => 'yes'
                )
            )
        );

        $this->add_control(
            'ribbon_style',
            array(
                'label'       => __('Ribbon Style', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'simple',
                'options'     => array(
                    'simple' => __('Simple'  , 'auxin-elements' ) ,
                    'corner' => __('Corner'  , 'auxin-elements' ),
                    'cross'  => __('Cross'  , 'auxin-elements' )
                ),
                'condition'   => array(
                    'display_ribbon' => 'yes'
                )
            )
        );

        $this->add_control(
            'ribbon_position',
            array(
                'label'       => __('Ribbon Position', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'top-right',
                'options'     => array(
                    'top-left'     => __('Top Left'  , 'auxin-elements' ) ,
                    'top-right'    => __('Top Right'  , 'auxin-elements' ),
                    'bottom-left'  => __('Bottom Left'  , 'auxin-elements' ),
                    'bottom-right' => __('Bottom Right'  , 'auxin-elements' )
                ),
                'condition'   => array(
                    'display_ribbon' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'ribbon_thickness',
            array(
                'label'      => __('Ribbon Thickness','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 50,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 0,
                        'max'  => 3,
                        'step' => 0.1
                    )
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-ribbon-wrapper' => 'line-height: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'display_ribbon' => 'yes'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Content Tab
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'template_section',
            array(
                'label' => __('Settings', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            )
        );

        $this->add_control(
            'lightbox',
            array(
                'label'        => __('Open in lightbox','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'icon',
            array(
                'label'       => __( 'Iconic button', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'plus',
                'options'     => array(
                    'none' => __('None', 'auxin-elements' ),
                    'plus' => __('Plus', 'auxin-elements' )
                ),
                'condition'   => array(
                    'lightbox' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'alignment',
            array(
                'label'       => __('Alignment','auxin-elements' ),
                'description' => __('Image alignment in block.', 'auxin-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'left' => array(
                        'title' => __( 'Left', 'auxin-elements' ),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'auxin-elements' ),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'auxin-elements' ),
                        'icon' => 'fa fa-align-right',
                    )
                ),
                'default'     => '',
                'separator'   => 'after',
                'toggle'      => true,
                'selectors'   => array(
                    '{{WRAPPER}} .aux-widget-image' => 'text-align: {{VALUE}};',
                )
            )
        );

        $this->add_control(
            'preloadable',
            array(
                'label'        => __('Preload image','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'preload_preview',
            array(
                'label'        => __('While loading image display','auxin-elements' ),
                'label_block'  => true,
                'type'         => Controls_Manager::SELECT,
                'options'      => auxin_get_preloadable_previews(),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'preloadable' => 'yes'
                )
            )
        );

        $this->add_control(
            'preload_bgcolor',
            array(
                'label'     => __( 'Placeholder color while loading image', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'preloadable'     => 'yes',
                    'preload_preview' => array('no', 'simple-spinner', 'simple-spinner-light', 'simple-spinner-dark')
                )
            )
        );

        $this->add_control(
            'tilt',
            array(
                'label'        => __( 'Tilt Effect','auxin-elements' ),
                'description'  => __( 'Adds tilt effect to the image.', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'colorized_shadow',
            array(
                'label'        => __( 'Colorized Shadow', 'auxin-elements' ),
                'description'  => __( 'Adds colorized shadow to the image. Note: This feature is not available when image hover is active.', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => array(
                    'display_hover!' => 'yes'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Tab
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'image_style_section',
            array(
                'label'     => __( 'Image', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'image!' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'image_max_width',
            array(
                'label'      => __('Max Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .aux-media-image' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'image_max_height',
            array(
                'label'      => __('Max Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .aux-media-image' => 'max-height:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'image_border_radius',
            array(
                'label'      => __( 'Border radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-media-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
                'separator' => 'after'
            )
        );

        $this->start_controls_tabs( 'image_style_tabs' );

        $this->start_controls_tab(
            'image_status_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'image_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-media-image',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'image_border',
                'selector'  => '{{WRAPPER}} .aux-media-image',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'image_background',
                'selector'  => '{{WRAPPER}} .aux-media-image',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'image_status_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'image_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .aux-media-image:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'image_border_hover',
                'selector'  => '{{WRAPPER}} .aux-media-image:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'image_background_hover',
                'selector'  => '{{WRAPPER}} .aux-media-image:hover',
                'separator' => 'none'
            )
        );

        $this->add_control(
            'image_transition_duration',
            array(
                'label'      => __('Transition Duration','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 2000,
                        'step' => 10
                    )
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-media-image' => 'transition-duration: {{SIZE}}ms;',
                )
            )
        );

        $this->add_responsive_control(
            'image_translate_y',
            array(
                'label'      => __('Vertical Move','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 10
                    )
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-media-image:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'ribbon_style_section',
            array(
                'label'     => __( 'Ribbon', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'display_ribbon' => 'yes',
                ),
            )
        );

        $this->add_control(
            'ribbon_bg_color',
            array(
                'label' => __( 'Background Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-ribbon-wrapper' => 'background-color: {{VALUE}} !important;',
                )
            )
        );

        $this->add_control(
            'ribbon_border_color',
            array(
                'label' => __( 'Border Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-ribbon-wrapper::before' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'ribbon_style' => array('cross'),
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'label'    => __( 'Box Shadow', 'auxin-elements' ),
                'name'     => 'header_box_shadow',
                'selector' => '{{WRAPPER}} .aux-ribbon-wrapper'
            )
        );

        $this->add_control(
            'ribbon_text_color',
            array(
                'label' => __( 'Text Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-ribbon-wrapper span' => 'color: {{VALUE}} !important;',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'ribbon_typography',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-ribbon-wrapper span'
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'ribbon_text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-ribbon-wrapper span',
            )
        );

        $this->end_controls_section();
    }

  /**
   * Render image box widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since 1.0.0
   * @access protected
   */
  protected function render() {

    $settings    = $this->get_settings_for_display();

    $args        = array(
        'image_html'       => Group_Control_Image_Size::get_attachment_image_html( $settings, 'image' ),

        'attach_id'        => auxin_get_array_value( $settings['image'], 'id' ),
        'size'             => $settings['image_size'],
        'width'            => auxin_get_array_value( $settings['image_custom_dimension'], 'width'  ),
        'height'           => auxin_get_array_value( $settings['image_custom_dimension'], 'height' ),
        'link'             => auxin_get_array_value( $settings['link'], 'url' ),
        'nofollow'         => auxin_get_array_value( $settings['link'], 'nofollow' ),
        'target'           => auxin_get_array_value( $settings['link'], 'is_external', false ) ? '_blank' : '_self',

        'attach_id_hover'  => auxin_get_array_value( $settings['hover_image'], 'id' ),

        'display_ribbon'   => $settings['display_ribbon'],
        'ribbon_text'      => $settings['ribbon_text'],
        'ribbon_style'     => $settings['ribbon_style'],
        'ribbon_position'  => $settings['ribbon_position'],

        'lightbox'         => $settings['lightbox'],
        'icon'             => $settings['icon'],
        'preloadable'      => $settings['preloadable'],
        'preload_preview'  => $settings['preload_preview'],
        'preload_bgcolor'  => $settings['preload_bgcolor'],
        'tilt'             => $settings['tilt'],
        'colorized_shadow' => $settings['colorized_shadow']
        //'align'            => $settings['align'],
    );

    // pass the args through the corresponding shortcode callback
    echo auxin_widget_image_callback( $args );
  }

}
