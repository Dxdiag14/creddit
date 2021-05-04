<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'RecentProducts' widget.
 *
 * Elementor widget that displays an 'RecentProducts' with lightbox.
 *
 * @since 1.0.0
 */
class Text extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'RecentProducts' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_text';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'RecentProducts' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Info Box', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'RecentProducts' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-info-box auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'RecentProducts' widget icon.
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
     * Register 'RecentProducts' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Content TAB
        /*-----------------------------------------------------------------------------------*/

        /*  header_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'header_section',
            array(
                'label'      => __( 'Header', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'icon_or_image',
            array(
                'label'       => __( 'Type', 'auxin-elements' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'icon',
                'options'     => array(
                    'none'    => __( 'None'  , 'auxin-elements' ),
                    'icon'    => __( 'Icon'  , 'auxin-elements' ),
                    'image'   => __( 'Image'  , 'auxin-elements' ),
                    'inline-svg' => __( 'Inline SVG' , 'auxin-elements' )
                )
            )
        );

       $this->add_control(
            'aux_text_icon',
            array(
                'label'       => __('Icon','auxin-elements' ),
                'label_block' => true,
                'description' => __('Please choose an icon from the list.', 'auxin-elements'),
                'type'        => Controls_Manager::ICONS,
                'condition'   => array(
                    'icon_or_image' => array('icon')
                )
            )
        );

        $this->add_control(
            'image',
            array(
                'label'       => __('Image','auxin-elements' ),
                'type'        => Controls_Manager::MEDIA,
                'condition'   => array(
                    'icon_or_image' => array('image')
                )
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'       => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'separator'  => 'none',
                'default'    => 'full',
                'condition'   => array(
                    'icon_or_image' => array('image')
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
                'default'      => 'no',
                'condition'   => array(
                    'icon_or_image' => array('image')
                )
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
                    'icon_or_image' => array('image'),
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
                    'icon_or_image' => array('image'),
                    'preloadable'     => 'yes',
                    'preload_preview' => array('no', 'simple-spinner', 'simple-spinner-light', 'simple-spinner-dark')
                )
            )
        );

        $this->add_control(
            'svg_inline',
            array(
                'label'       => '',
                'type'        => Controls_Manager::CODE,
                'default'     => '',
                'placeholder' => __( 'Enter inline SVG content here', 'auxin-elements' ),
                'show_label'  => false,
                'condition' => array(
                    'icon_or_image' => 'inline-svg'
                )
            )
        );

        $this->end_controls_section();

        /*  content_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'content_section',
            array(
                'label'      => __('Content', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'       => __('Title','auxin-elements' ),
                'description' => __('Text title, leave it empty if you don`t need title.', 'auxin-elements'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Great Idea'
            )
        );

        $this->add_control(
            'subtitle',
            array(
                'label'       => __('Subtitle','auxin-elements' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'title_link',
            array(
                'label'         => __('Title Link','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'http://phlox.pro',
                'show_external' => false,
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'content',
            array(
                'label'         => __('Content','auxin-elements' ),
                'description'   => __('Enter a text as a text content.', 'auxin-elements'),
                'type'          => Controls_Manager::WYSIWYG,
                'default'       => 'NVestibulum rutum, mi nec elementum vehicula, eros quam gravida nisl, id fringilla neque ante.'
            )
        );

        $this->end_controls_section();

        /*  button_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'button_section',
            array(
                'label'      => __('Button', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'display_button',
            array(
                'label'        => __('Display button','auxin-elements' ),
                'description'  => __('Display a button in text widget', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'btn_label',
            array(
                'label'        => __('Button label','auxin-elements' ),
                'type'         => Controls_Manager::TEXT,
                'default'      => __( 'Read More', 'auxin-elements' ),
                'condition'    => array(
                    'display_button' => 'yes',
                )
            )
        );

       $this->add_control(
            'aux_text_btn_icon',
            array(
                'label'        => __('Icon for button','auxin-elements' ),
                'type'         => Controls_Manager::ICONS,
                'condition'    => array(
                    'display_button' => 'yes',
                )
            )
        );

        $this->add_control(
            'btn_link',
            array(
                'label'         => __('Link','auxin-elements' ),
                'description'   => __('If you want to link your button.', 'auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'http://phlox.pro',
                'show_external' => true,
                'condition'     => [
                    'display_button' => 'yes',
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*  header_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'header_style_section',
            array(
                'label' => __('Header', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => array(
                    'icon_or_image' => array( 'icon', 'image', 'inline-svg' )
                )
            )
        );

        $this->add_control(
            'image_position',
            array(
                'label'       => __('Header Position','auxin-elements'),
                'type'        => 'aux-visual-select',
                'style_items' => 'max-width:30%;',
                'options'     => array(
                    'top'   => array(
                        'label' => __('Top', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/column-icon-top.svg'
                    ),
                    'left'  => array(
                        'label' => __('Left', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/column-icon-left.svg'
                    ),
                    'right' => array(
                        'label' => __('Right', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/column-icon-right.svg'
                    )
                ),
                'default'     => 'top'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'header_background',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-text-widget-header'
            )
        );

        $this->add_responsive_control(
            'header_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-widget-text .aux-text-widget-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*  icon_image_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'icon_image_style_section',
            array(
                'label' => __('Icon/Image', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => array(
                    'icon_or_image' => array( 'icon', 'image', 'inline-svg' )
                )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'icon_border',
                'label'    => __( 'Border', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-ico-box',
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => __( 'Icon Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 16,
                        'max' => 512,
                        'step' => 2,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-ico' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'icon_or_image' => array('icon')
                )
            )
        );

        $this->add_control(
            'icon_shape',
            array(
                'label'       => __('Icon background shape','auxin-elements'),
                'style_items' => 'max-width:30%;',
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'circle' => array(
                        'label' => __('Circle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-circle.svg'
                    ),
                    'semi-circle' => array(
                        'label' => __('Semi-circle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-semi-circle.svg'
                    ),
                    'round-rect' => array(
                        'label' => __('Round Rectangle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-round-rectangle.svg'
                    ),
                    'cross-rect' => array(
                        'label' => __('Cross Rectangle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-cross-rectangle.svg'
                    ),
                    'rect' => array(
                        'label' => __('Rectangle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-rectangle.svg'
                    )
                ),
                'default'     => 'circle',
                'condition'   => array(
                    'icon_or_image' => array('icon')
                )
            )
        );

        $this->add_responsive_control(
            'icon_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-ico-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'icon_or_image' => array('icon')
                )
            )
        );

        $this->start_controls_tabs( 'icon_style_tabs' );

        $this->start_controls_tab(
            'icon_style_normal',
            array(
                'label'     => __( 'Normal' , 'auxin-elements' )
            )
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'header_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-ico-box'
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label'       => __('Icon color', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .aux-ico-box' => 'color:{{VALUE}};',
                ),
                'condition'   => array(
                    'icon_or_image' => array('icon')
                )
            )
        );

        $this->add_control(
            'icon_bg_color',
            array(
                'label'       => __('Icon background color','auxin-elements' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors' => array(
                    '{{WRAPPER}} .aux-ico-box' => 'background-color:{{VALUE}};',
                ),
                'condition'   => array(
                    'icon_or_image' => array('icon')
                )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'icon_background_normal',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-ico-box'
            )
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_style_hover',
            array(
                'label'     => __( 'Hover' , 'auxin-elements' )
            )
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'header_box_shadow_hover',
                'selector'  => '{{WRAPPER}}:hover .aux-ico-box'
            )
        );

        $this->add_control(
            'icon_color_hover',
            array(
                'label'       => __('Icon color', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors' => array(
                    '{{WRAPPER}}:hover .aux-ico-box' => 'color:{{VALUE}} !important;',
                ),
                'condition'   => array(
                    'icon_or_image' => ['icon']
                )
            )
        );

        $this->add_control(
            'icon_bg_color_hover',
            array(
                'label'       => __('Icon background color','auxin-elements' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors' => array(
                    '{{WRAPPER}}:hover .aux-ico-box' => 'background-color:{{VALUE}} !important;',
                ),
                'condition'   => array(
                    'icon_or_image' => ['icon']
                )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'icon_background_hover',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'gradient' ),
                'selector' => '{{WRAPPER}}:hover .aux-ico-box'
            )
        );

        $this->add_control(
            'icon_transition',
            array(
                'label'     => __( 'Transition duration', 'auxin-elements' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 2000,
                        'step' => 50
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-ico-box' => 'transition:all {{SIZE}}ms ease;'
                ),
                'condition'   => array(
                    'icon_or_image' => ['icon']
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'img_shape',
            array(
                'label'       => __('Image shape','auxin-elements'),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'default' => array(
                        'label' => __('Default Aspect', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-rectangle.svg'
                    ),
                    'circle' => array(
                        'label' => __('Circle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-circle.svg'
                    ),
                    'semi-circle' => array(
                        'label' => __('Semi-circle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-semi-circle.svg'
                    ),
                    'round-rect' => array(
                        'label' => __('Round Rectangle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-round-rectangle.svg'
                    ),
                    'rect' => array(
                        'label' => __('Rectangle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-rectangle.svg'
                    ),
                ),
                'default'     => 'default',
                'condition'   => array(
                    'icon_or_image' => array('image')
                )
            )
        );

        $this->end_controls_section();

        /*  title_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'title!' => ''
                )
            )
        );

        $this->start_controls_tabs( 'title_colors' );

        $this->start_controls_tab(
            'title_color_normal',
            array(
                'label'     => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .col-title a, {{WRAPPER}} .col-title' => 'color: {{VALUE}} !important;',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            array(
                'label'     => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}:hover .col-title a, {{WRAPPER}}:hover .col-title' => 'color: {{VALUE}} !important;'
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .col-title, {{WRAPPER}} .col-title a'
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'title_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .col-title'
            )
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => -200,
                        'max' =>  200
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .col-title' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*  subtitle_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'subtitle_style_section',
            array(
                'label'     => __( 'Subtitle', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'subtitle!' => ''
                )
            )
        );

        $this->start_controls_tabs( 'subtitle_colors' );

        $this->start_controls_tab(
            'subtitle_color_normal',
            array(
                'label'     => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'subtitle_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .col-subtitle' => 'color: {{VALUE}} !important;',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'subtitle_color_hover',
            array(
                'label'     => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'subtitle_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}:hover .col-subtitle' => 'color: {{VALUE}} !important;'
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_typography',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .col-subtitle'
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'subtitle_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .col-subtitle'
            )
        );

        $this->add_responsive_control(
            'subtitle_margin_top',
            array(
                'label' => __( 'Top space', 'auxin-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => -200,
                        'max' =>  200
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .col-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'subtitle_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => -200,
                        'max' =>  200
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .col-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*  content_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => __( 'Content', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );


        $this->start_controls_tabs( 'content_colors' );

        $this->start_controls_tab(
            'content_color_normal',
            array(
                'label'     => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .widget-content' => 'color:{{VALUE}} !important;',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'content_color_hover',
            array(
                'label'     => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'content_hover_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}:hover .widget-content' => 'color:{{VALUE}} !important;'
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .widget-content'
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'content_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .widget-content'
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-widget-text .aux-text-widget-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'content_margin_top',
            array(
                'label' => __( 'Top space', 'auxin-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => -200,
                        'max' =>  200
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .widget-content' => 'margin-top: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'content_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => -200,
                        'max' =>  200
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .widget-content' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*  button_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'button_style_section',
            array(
                'label'     => __('Button', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'display_button' => 'yes',
                )
            )
        );

        $this->add_control(
            'btn_size',
            array(
                'label'       => __('Size', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'medium',
                'options'     => array(
                    'exlarge' => __('Exlarge', 'auxin-elements' ),
                    'large'   => __('Large'  , 'auxin-elements' ),
                    'medium'  => __('Medium' , 'auxin-elements' ),
                    'small'   => __('Small'  , 'auxin-elements' ),
                    'tiny'    => __('Tiny'   , 'auxin-elements' )
                )
            )
        );

        $this->add_control(
            'button_skin_heading',
            array(
                'label'     => __( 'Button Skin', 'auxin-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'btn_color_name',
            array(
                'label'       => __('Skin', 'auxin-elements'),
                'type'        => 'aux-visual-select',
                'default'     => 'black',
                'options'     => auxin_get_famous_colors_list()
            )
        );

        $this->start_controls_tabs( 'button_background_tab' );

        $this->start_controls_tab(
            'button_bg_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'button_background',
                'label'    => __( 'Background', 'auxin-elements' ),
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-button'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'button_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-button'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_bg_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'hover_button_background',
                'label'    => __( 'Background', 'auxin-elements' ),
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-button .aux-overlay::after'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'hover_button_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-button:hover'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_icon_heading',
            array(
                'label'     => __( 'Button Icon', 'auxin-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                // @TODO: un comment after some release
                // 'condition'   => array(
                //     'aux_text_btn_icon!' => '',
                // )
            )
        );

        $this->add_control(
            'btn_icon_align',
            array(
                'label'       => __('Icon alignment', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'options'     => array(
                   'default'        =>  __('Default'            , 'auxin-elements' ),
                   'left'           =>  __('Left'               , 'auxin-elements' ),
                   'right'          =>  __('Right'              , 'auxin-elements' ),
                   'over'           =>  __('Over'               , 'auxin-elements' ),
                   'left-animate'   =>  __('Animate from Left'  , 'auxin-elements' ),
                   'right-animate'  =>  __('Animate from Right' , 'auxin-elements' )
                ),
                // @TODO: un comment after some release
                // 'condition'   => array(
                //     'aux_text_btn_icon!' => '',
                // )
            )
        );

        $this->add_responsive_control(
            'btn_icon_size',
            array(
                'label'      => __( 'Icon Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 512,
                        'step' => 2,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                // @TODO: un comment after some release
                // 'condition'   => array(
                //     'aux_text_btn_icon!' => ''
                // )
            )
        );

        $this->start_controls_tabs(
            'btn_icon_color',
            array(
                // @TODO: un comment after some release
                // 'condition'   => array(
                //     'aux_text_btn_icon!' => '',
                // )
            )
         );

        $this->start_controls_tab(
            'icon_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'button_icon_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btn_icon_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'hover_button_icon_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-button:hover .aux-icon' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_text_heading',
            array(
                'label'     => __( 'Button Text', 'auxin-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                // @TODO: un comment after some release
                // 'condition'   => array(
                //     'aux_text_btn_icon!' => '',
                // )
            )
        );

        $this->start_controls_tabs( 'button_text' );

        $this->start_controls_tab(
            'button_text_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'btn_text_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-text' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'btn_text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-button',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'button_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-text'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_text_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'hover_btn_text_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-button:hover .aux-text' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'hover_btn_text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-button:hover',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'hover_button_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-text'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_wrapper_heading',
            array(
                'label'     => __( 'Button Wrapper', 'auxin-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                // @TODO: un comment after some release
                // 'condition'   => array(
                //     'aux_text_btn_icon!' => '',
                // )
            )
        );

        $this->add_responsive_control(
            'btn_icon_margin',
            array(
                'label'      => __( 'Icon Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_control(
            'btn_border',
            array(
                'label'       => __('Shape Style','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'style_items' => 'max-width:30%;',
                'options'     => array(
                    'none' => array(
                        'label' => __('Box', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    'round'   => array(
                        'label' => __('Round', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-curved.svg'
                    ),
                    'curve'  => array(
                        'label' => __('Curve', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-rounded.svg'
                    )
                ),
                'default'     => 'round'
            )
        );

        $this->add_control(
            'btn_style',
            array(
                'label'       => __('Button Style','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'style_items' => 'max-width:30%;',
                'options'     => array(
                    'none'    => array(
                        'label' => __('Normal', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    '3d'      => array(
                        'label' => __('3D', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-3d.svg'
                    ),
                    'outline' => array(
                        'label' => __('Outline', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-outline.svg'
                    )
                ),
                'default'     => 'outline'
            )
        );

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();


        /*  wrapper_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'wrapper_style_section',
            array(
                'label'     => __( 'Wrapper', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'overlay_color',
            array(
                'label'       => __('Overlay', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR
            )
        );

        $this->add_responsive_control(
            'text_align',
            array(
                'label'      => __('Text Align','auxin-elements'),
                'type'       => Controls_Manager::CHOOSE,
                'options'    => array(
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
                    ),
                ),
                'default'    => 'center',
                'toggle'     => true,
                'selectors'  => array(
                    '{{WRAPPER}} .aux-widget-advanced-text' => 'text-align: {{VALUE}} !important;'
                )
            )
        );

        $this->add_responsive_control(
            'wrapper_content_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-widget-advanced-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'wrapper_content_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-text-widget-overlay, {{WRAPPER}} .aux-widget-advanced-text' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ),
                'allowed_dimensions' => 'all',
                'separator'  => 'after'
            )
        );

        $this->start_controls_tabs( 'wrapper_content_status' );

        $this->start_controls_tab(
            'wrapper_content_status_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'wrapper_content_border_normal',
                'selector'  => '{{WRAPPER}} .aux-widget-advanced-text',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'wrapper_content_background_normal',
                'selector'  => '{{WRAPPER}} .aux-widget-advanced-text',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'wrapper_content_status_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'wrapper_content_border_hover',
                'selector'  => '{{WRAPPER}} .aux-widget-advanced-text:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'wrapper_content_background_hover',
                'selector'  => '{{WRAPPER}} .aux-widget-advanced-text:hover',
                'separator' => 'none'
            )
        );

        $this->add_control(
            'wrapper_content_transition',
            array(
                'label'     => __( 'Transition duration', 'auxin-elements' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 2000,
                        'step' => 50
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-widget-advanced-text' => 'transition-duration:{{SIZE}}ms;'
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*  footer_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'footer_section',
            array(
                'label' => __('Footer', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'footer_shape',
            array(
                'label'       => __('Footer Shape','auxin-elements'),
                'type'        => 'aux-visual-select',
                'style_items' => 'max-width:30%;',
                'options'     => array(
                    'none' => array(
                        'label' => __('None', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/text-normal.svg'
                    ),
                    'wave' => array(
                        'label' => __('Wave', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/text-outline.svg'
                    ),
                    'tail' => array(
                        'label' => __('Tail', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/text-boxed.svg'
                    )
                ),
                'default'     => 'none'
            )
        );

        $this->add_control(
            'footer_shape_color',
            array(
                'label'       => __('Color of button', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'condition'   => array(
                    'footer_shape' => array('tail', 'wave')
                )
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

        $settings   = $this->get_settings_for_display();
        $main_icon  = '';

        if( 'icon' == $settings['icon_or_image'] ){
            $main_icon = ! empty( $settings['aux_text_icon']['value'] ) ? $settings['aux_text_icon']['value'] : ( ! empty( $settings['icon'] ) ? $settings['icon'] : '' ) ;
        }

        $btn_icon_value = ! empty( $settings['aux_text_btn_icon']['value'] ) ? $settings['aux_text_btn_icon']['value'] : ( ! empty( $settings['btn_icon'] ) ? $settings['btn_icon'] : '' ) ;

        $args       = array(
            'title'              => $settings['title'],
            'subtitle'           => $settings['subtitle'],
            'title_link'         => auxin_get_array_value( $settings['title_link'], 'url' ),
            'content'            => $settings['content'],

            'display_button'     => $settings['display_button'],
            'btn_label'          => $settings['btn_label'],
            'btn_size'           => $settings['btn_size'],
            'btn_border'         => $settings['btn_border'],
            'btn_style'          => $settings['btn_style'],
            'btn_icon'           => $btn_icon_value,
            'btn_icon_align'     => $settings['btn_icon_align'],
            'btn_color_name'     => $settings['btn_color_name'],
            'btn_link'           => auxin_get_array_value( $settings['btn_link'], 'url' ),
            'btn_nofollow'       => auxin_get_array_value( $settings['btn_link'], 'nofollow' ),
            'btn_target'         => auxin_get_array_value( $settings['btn_link'], 'is_external', false ) ? '_blank' : '_self',

            'icon_or_image'      => $settings['icon_or_image'],
            'icon'               => $main_icon,
            'icon_color'         => $settings['icon_color'],
            'icon_bg_color'      => $settings['icon_bg_color'],
            'icon_shape'         => $settings['icon_shape'],
            'image'              => auxin_get_array_value( $settings['image'], 'id' ),
            'size'               => $settings['image_size'],
            'width'              => auxin_get_array_value( $settings['image_custom_dimension'], 'width'  ),
            'height'             => auxin_get_array_value( $settings['image_custom_dimension'], 'height' ),
            'preloadable'        => $settings['preloadable'],
            'preload_preview'    => $settings['preload_preview'],
            'preload_bgcolor'    => $settings['preload_bgcolor'],
            'image_size'         => $settings['image_size'],
            'img_shape'          => $settings['img_shape'],
            'image_position'     => $settings['image_position'],
            'icon_svg_inline'    => $settings['svg_inline'],

            'text_align'         => $settings['text_align'],
            'text_align_resp'    => $settings['text_align_mobile'],
            'overlay_color'      => $settings['overlay_color'],

            'footer_shape'       => $settings['footer_shape'],
            'footer_shape_color' => $settings['footer_shape_color']
        );

        // get the shortcode base blog page
        echo auxin_widget_column_callback( $args );
    }

}
