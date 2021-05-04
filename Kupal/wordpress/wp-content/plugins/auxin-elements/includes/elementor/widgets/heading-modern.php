<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'ModernHeading' widget.
 *
 * Elementor widget that displays an 'ModernHeading' with lightbox.
 *
 * @since 1.0.0
 */
class ModernHeading extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'ModernHeading' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_modern_heading';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'ModernHeading' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Modern Heading', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'ModernHeading' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-heading auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'ModernHeading' widget icon.
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
     * Register 'ModernHeading' widget controls.
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

        $this->start_controls_section(
            'title_section',
            array(
                'label'      => __('Heading', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'       => __( 'Title', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => array(
                    'active'  => true
                ),
                'default'     => __( 'Add your heading text here ..', 'auxin-elements' ),
                'label_block' => true
            )
        );

        $this->add_control(
            'link',
            array(
                'label'         => __('Link','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'http://your-link.com',
                'show_external' => true,
                'label_block'   => true,
                'dynamic'       => array(
                    'active'    => true
                )
            )
        );

        $this->add_control(
            'title_tag',
            array(
                'label'   => __( 'HTML Tag', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p'
                ),
                'default'   => 'h2',
            )
        );

        $this->add_responsive_control(
            'alignment',
            array(
                'label'       => __('Alignment', 'auxin-elements'),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => '',
                'options'     => array(
                    'left' => array(
                        'title' => __( 'Left', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-right',
                    )
                ),
                'selectors_dictionary' => [
					'left'   => '',
					'center' => 'text-align:center;margin-left:auto !important;margin-right:auto !important;',
					'right'  => 'text-align:right;margin-left:auto !important;'
                ],
                'selectors' => [
					'{{WRAPPER}} .aux-widget-inner > *' => '{{VALUE}}'
				]
            )
        );

        /*   Divider
        /*-------------------------------------*/

        $this->add_control(
            'divider',
            array(
                'label'        => __( 'Display Divider', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'divider_position',
            array(
                'label'   => __( 'Divider Position', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'before'  => __( 'Before Heading', 'auxin-elements' ),
                    'between' => __( 'Between Headings', 'auxin-elements' ),
                    'after'   => __( 'After Headings', 'auxin-elements' )
                ),
                'default'   => 'after',
                'condition' => array(
                    'divider' => 'yes'
                )
            )
        );

        $this->end_controls_section();


        /*   Secondary heading
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_secondary_section',
            array(
                'label'      => __('Secondary Heading', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'title_secondary_before',
            array(
                'label'       => __( 'Before Text', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array(
                    'active'  => true
                ),
                'default'     => '',
                'label_block' => true
            )
        );

        $this->add_control(
            'title_secondary_highlight',
            array(
                'label'       => __( 'Highlighted Text', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array(
                    'active'  => true
                ),
                'default'     => '',
                'label_block' => true
            )
        );

        $this->add_control(
            'title_secondary_after',
            array(
                'label'       => __( 'After Text', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array(
                    'active'  => true
                ),
                'default'     => '',
                'label_block' => true
            )
        );

        $this->add_control(
            'link_secondary',
            array(
                'label'         => __('Link','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'http://your-link.com',
                'show_external' => true,
                'label_block'   => true,
                'dynamic'       => array(
                    'active'    => true
                )
            )
        );

        $this->add_control(
            'title_tag_secondary',
            array(
                'label'   => __( 'HTML Tag', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p'
                ),
                'default'   => 'h3'
            )
        );

        $this->end_controls_section();

        /*   Description
        /*-------------------------------------*/

        $this->start_controls_section(
            'description_primary_section',
            array(
                'label'      => __('Description', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'description',
            array(
                'label'       => __( 'Description', 'auxin-elements' ),
                'type'        => Controls_Manager::WYSIWYG,
                'dynamic'     => array(
                    'active'  => true
                ),
                'label_block' => true,
                'separator'   => 'before'
            )
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*   Title Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Heading', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['title!' => '']
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-modern-heading-primary'
            )
        );

        $this->start_controls_tabs( 'title_tabs' );

        $this->start_controls_tab(
            'title_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_responsive_control(
            'title_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-primary' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'title_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-heading-primary'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title_background',
                'selector' => '{{WRAPPER}} .aux-modern-heading-primary',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_responsive_control(
            'title_hover_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-primary:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'title_hover_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-heading-primary:hover'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title_hover_background',
                'selector' => '{{WRAPPER}} .aux-modern-heading-primary:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'title_margin',
            [
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-primary' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator'          => 'before'
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'              => __( 'Padding', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-primary' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'title_border_radius',
            [
                'label'              => __( 'Border radius', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'all',
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-primary' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'title_width',
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
                    '{{WRAPPER}} .aux-modern-heading-primary' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*   Secondary title Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title2_style_heading',
            [
                'label'     => __( 'Secondary Heading', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,

                'conditions'   =>
                    [
                        'relation' => 'or',
                        'terms'    => [
                            [
                                'name' => 'title_secondary_before',
                                'operator' => '!==',
                                'value' => '',
                            ], [
                                'name' => 'title_secondary_after',
                                'operator' => '!==',
                                'value' => '',
                            ]
                        ]
                    ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title2_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-modern-heading-secondary'
            )
        );

        $this->start_controls_tabs( 'title2_tabs' );

        $this->start_controls_tab(
            'title2_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_responsive_control(
            'title2_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-secondary' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'title2_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-heading-secondary'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title2_background',
                'selector' => '{{WRAPPER}} .aux-modern-heading-secondary',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title2_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_responsive_control(
            'title2_hover_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-secondary:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'title2_hover_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-heading-secondary:hover'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title2_hover_background',
                'selector' => '{{WRAPPER}} .aux-modern-heading-secondary:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'title2_margin',
            [
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-secondary' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator'          => 'before'
            ]
        );

        $this->add_responsive_control(
            'title2_padding',
            [
                'label'              => __( 'Padding', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-secondary' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'title2_border_radius',
            [
                'label'              => __( 'Border radius', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'all',
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-secondary' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'title2_width',
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
                    '{{WRAPPER}} .aux-modern-heading-secondary' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();


        /*   Secondary Highlighted Style
        /*-------------------------------------*/

        $this->start_controls_section(
            'title2_highlighted_style_heading',
            [
                'label'     => __( 'Secondary Heading - Highlighted', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [ 'title_secondary_highlight!' => '' ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title2_highlighted_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight'
            )
        );

        $this->start_controls_tabs( 'title2_highlighted_tabs' );

        $this->start_controls_tab(
            'title2_highlighted_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_responsive_control(
            'title2_highlighted_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'title2_highlighted_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title2_highlighted_background',
                'selector' => '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title2_highlighted_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_responsive_control(
            'title2_highlighted_hover_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'title2_highlighted_hover_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight:hover'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title2_highlighted_hover_background',
                'selector' => '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'title2_highlighted_margin',
            [
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator'          => 'before'
            ]
        );

        $this->add_responsive_control(
            'title2_highlighted_padding',
            [
                'label'              => __( 'Padding', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'title2_highlighted_border_radius',
            [
                'label'              => __( 'Border radius', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'all',
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'title2_highlighted_width',
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
                    '{{WRAPPER}} .aux-modern-heading-secondary .aux-head-highlight' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*   Divider Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'divider_style_section',
            [
                'label'     => __( 'Divider', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'divider' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'divider_weight',
            array(
                'label'      => __( 'Weight', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'max' => 10
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-divider'   => 'height: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'divider_width',
            array(
                'label'      => __( 'Width', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 1200
                    ),
                    '%' => array(
                        'min' => 1,
                        'max' => 100
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-divider' => 'width: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'divider_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-modern-heading-divider' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'divider_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-divider'  => 'background-color: {{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'divider_shadow',
                'selector'  => '{{WRAPPER}} .aux-modern-heading-divider',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'divider_backgoundcolor',
                'label'     => __( 'Background', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .aux-modern-heading-divider'
            )
        );

        $this->end_controls_section();

        /*   Description Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'description_style_section',
            array(
                'label'     => __( 'Description', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [ 'description!' => '' ]
            )
        );

        $this->add_responsive_control(
            'description_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-modern-heading-description' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'description_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-modern-heading-description'
            )
        );

        $this->add_responsive_control(
            'description_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-modern-heading-description' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'description_width',
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
                    '{{WRAPPER}} .aux-modern-heading-description' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*   Wrapper Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'wrapper_style_section',
            array(
                'label'     => __( 'Wrapper', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'width',
            array(
                'label'      => __('Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%', 'vw'),
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
                    '{{WRAPPER}} .aux-widget-modern-heading .aux-widget-inner' => 'width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'height',
            array(
                'label'      => __('Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%', 'vh'),
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
                    '{{WRAPPER}} .aux-widget-modern-heading .aux-widget-inner' => 'height:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'wrapper_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em'),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-widget-modern-heading .aux-widget-inner' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'wrapper_padding',
            array(
                'label'              => __( 'Padding', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em', '%'),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-widget-modern-heading .aux-widget-inner' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render 'ModernHeading' widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $divider_markup  = auxin_is_true( $settings['divider'] ) ? '<div class="aux-modern-heading-divider"></div>' : '';

        echo '<section class="aux-widget-modern-heading">
            <div class="aux-widget-inner">';

                // Maybe print divider before
                if( empty( $settings['divider_position'] ) || 'before' == $settings['divider_position'] ){
                    echo $divider_markup;
                }

                // Print Primary Heading
                if( ! empty( $settings['link']['url'] ) ){

                    // Make Link attributes
                    $this->add_render_attribute( 'link-primary', 'href', $settings['link']['url'] );
                    $this->add_render_attribute( 'link-primary', 'class', 'aux-modern-heading-primary-link' );
                    if ( $settings['link']['is_external'] ) {
                        $this->add_render_attribute( 'link-primary', 'target', '_blank' );
                    }
                    if ( $settings['link']['nofollow'] ) {
                        $this->add_render_attribute( 'link-primary', 'rel', 'nofollow' );
                    }

                    printf( '<a %1$s><%2$s class="aux-modern-heading-primary">%3$s</%2$s></a>',
                        $this->get_render_attribute_string( 'link-primary' ),
                        esc_attr( $settings['title_tag'] ),
                        $settings['title']
                    );

                } else {
                    printf( '<%1$s class="aux-modern-heading-primary">%2$s</%1$s>',
                        esc_attr( $settings['title_tag'] ),
                        $settings['title']
                    );
                }

                // Maybe print divider between
                if( 'between' == $settings['divider_position'] ){
                    echo $divider_markup;
                }

                // Print Secondary Heading
                $before_heading    = $settings['title_secondary_before']    ? '' . '<span class="aux-head-before">' . $settings['title_secondary_before'] . '</span>' : '';
                $highlight_heading = $settings['title_secondary_highlight'] ? '' . '<span class="aux-head-highlight">' . $settings['title_secondary_highlight'] . '</span>' : '';
                $after_heading     = $settings['title_secondary_after' ]    ? '' . '<span class="aux-head-after">'  . $settings['title_secondary_after' ] . '</span>' : '';

                if( $before_heading || $highlight_heading || $after_heading ){

                    if( ! empty( $settings['link_secondary']['url'] ) ){

                        // Make Link attributes
                        $this->add_render_attribute( 'link-secondary', 'href', $settings['link_secondary']['url'] );
                        $this->add_render_attribute( 'link-secondary', 'class', 'aux-modern-heading-secondary-link' );
                        if ( $settings['link_secondary']['is_external'] ) {
                            $this->add_render_attribute( 'link-secondary', 'target', '_blank' );
                        }
                        if ( $settings['link_secondary']['nofollow'] ) {
                            $this->add_render_attribute( 'link-secondary', 'rel', 'nofollow' );
                        }

                        printf( '<a %1$s><%2$s class="aux-modern-heading-secondary">%3$s%4$s%5$s</%2$s></a>',
                            $this->get_render_attribute_string( 'link-secondary' ),
                            esc_attr( $settings['title_tag_secondary'] ),
                            $before_heading,
                            $highlight_heading,
                            $after_heading
                        );
                    } else {
                        printf( '<%1$s class="aux-modern-heading-secondary">%2$s%3$s%4$s</%1$s>',
                            esc_attr( $settings['title_tag_secondary'] ),
                            $before_heading,
                            $highlight_heading,
                            $after_heading
                        );
                    }

                }

                // Maybe Print divider after
                if( 'after' == $settings['divider_position'] ){
                    echo $divider_markup;
                }

                if( ! empty( $settings['description'] ) ){
                    printf( '<div class="aux-modern-heading-description">%s</div>',
                        $settings['description']
                    );
                }

        echo '</div>
        </section>';
    }

}
