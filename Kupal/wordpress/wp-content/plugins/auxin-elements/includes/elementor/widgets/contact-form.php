<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Contact_Form' widget.
 *
 * Elementor widget that displays an 'Contact_Form' with lightbox.
 *
 * @since 1.0.0
 */
class ContactForm extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Contact_Form' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_contact_form';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Contact_Form' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Contact Form', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Contact_Form' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-form-horizontal auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Contact_Form' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return ['auxin-core'];
    }

    /**
     * Register 'Contact_Form' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  contact_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'contact_section',
            array(
                'label'      => __('Contact', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'type',
            array(
                'label'       => __('Contact form Type','auxin-elements' ),
                'description' => __('Specifies contact form element\'s type. Whether to use built-in form or Contact Form 7.', 'auxin-elements' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 'phlox',
                'options'     => array(
                    'phlox' => __('Phlox Contact Form', 'auxin-elements' ),
                    'cf7'   => __('Contact Form 7 Shortcode', 'auxin-elements' ),
                )
            )
        );

        $this->add_control(
            'cf7_shortcode',
            array(
                'label'       => __('Shortcode','auxin-elements' ),
                'description' => __('Put a Contact Form 7 shortcode that you have already created.','auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'separator'   => 'before',
                'condition'   => [
                    'type' => ['cf7']
                ],
            )
        );

        $this->add_control(
            'email',
            array(
                'label'       => __('Email','auxin-elements' ),
                'description' => __('Email address of message\'s recipient', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'input_type'  => 'email',
                'separator'   => 'before',
                'condition'   => [
                    'type' => ['phlox']
                ]
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /* -------------------------------------------------------------------------- */
        /* General inputs
        /* -------------------------------------------------------------------------- */

        $this->start_controls_section(
            'general_input_section',
            [
                'label'      => __('All Inputs', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'general_input_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} input:not([type="submit"])'
            ]
        );

        $this->add_control(
            'general_input_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'general_input_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])' => 'width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'general_input_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])' => 'max-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'general_input_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'after'
            ]
        );


        $this->add_responsive_control(
            'general_input_border_radius',
            [
                'label' => __( 'Border Radius', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'general_input_padding',
            [
                'label' => __( 'Padding', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'after'
            ]
        );

        $this->add_responsive_control(
            'general_input_margin',
            [
                'label' => __( 'Margin', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'after'
            ]
        );

        // Background and Box Shadow for input - START
        $this->start_controls_tabs( 'general_input_tabs' );

        $this->start_controls_tab(
            'general_input_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'general_input_background',
                'selector' => '{{WRAPPER}} input:not([type="submit"])',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'general_input_box_shadow',
                'selector' => '{{WRAPPER}} input:not([type="submit"])'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'general_input_border',
                'selector' => '{{WRAPPER}} input:not([type="submit"])'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'general_input_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'general_input_background_hover',
                'selector' => '{{WRAPPER}} input:not([type="submit"]):hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'general_input_box_shadow_hover',
                'selector' => '{{WRAPPER}} input:not([type="submit"]):hover'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'general_input_border_hover',
                'selector' => '{{WRAPPER}} input:not([type="submit"]):hover'
            ]
        );

        $this->add_control(
            'general_input_transition',
            [
                'label' => __( 'Transition Duration', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])' => "transition:all ease-out {{SIZE}}s;"
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'general_input_tab_focus_state',
            [
                'label' => __( 'Focus', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'general_input_background_focus',
                'selector' => '{{WRAPPER}} input:not([type="submit"]):focus',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'general_input_box_shadow_focus',
                'selector' => '{{WRAPPER}} input:not([type="submit"]):focus'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'general_input_border_focus',
                'selector' => '{{WRAPPER}} input:not([type="submit"]):focus'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // Background and Box Shadow for input - END

        $this->end_controls_section();


        /* -------------------------------------------------------------------------- */
        /* Placeholder Style
        /* -------------------------------------------------------------------------- */
        $this->start_controls_section(
            'placeholder_section',
            [
                'label'    => __('Input Placeholder', 'auxin-elements' ),
                'tab'      => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'placeholder_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} input:not([type="submit"])::placeholder'
            ]
        );

        $this->add_control(
            'placeholder_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])::placeholder' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();

        /* -------------------------------------------------------------------------- */
        /* Text Input Style
        /* -------------------------------------------------------------------------- */

        $this->start_controls_section(
            'text_input_section',
            [
                'label'      => __('Text Inputs', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_input_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type="text"]' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'text_input_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"]' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_input_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"]' => 'max-width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'text_input_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"]' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'text_input_border',
                'selector' => '{{WRAPPER}} input[type="text"]'
            ]
        );

        $this->add_responsive_control(
            'text_input_border_radius',
            [
                'label' => __( 'Border Radius', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_input_padding',
            [
                'label' => __( 'Padding', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        // Background and Box Shadow for input - START
        $this->start_controls_tabs( 'text_input_tabs' );

        $this->start_controls_tab(
            'text_input_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'text_input_background',
                'selector' => '{{WRAPPER}} input[type="text"]',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'text_input_box_shadow',
                'selector' => '{{WRAPPER}} input[type="text"]'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_input_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'text_input_background_hover',
                'selector' => '{{WRAPPER}} input[type="text"]:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'text_input_box_shadow_hover',
                'selector' => '{{WRAPPER}} input[type="text"]:hover'
            ]
        );

        $this->add_control(
            'text_input_transition',
            [
                'label' => __( 'Transition Duration', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} input[type="text"]' => "transition:all ease-out {{SIZE}}s;"
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // Background and Box Shadow for input - END

        $this->end_controls_section();

        /* -------------------------------------------------------------------------- */
        /* Email Input Style
        /* -------------------------------------------------------------------------- */

        $this->start_controls_section(
            'email_input_section',
            [
                'label'      => __('Email Inputs', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'email_input_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type="email"]' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'email_input_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="email"]' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'email_input_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="email"]' => 'max-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'email_input_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="email"]' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'after'
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'email_input_border',
                'selector' => '{{WRAPPER}} input[type="email"]'
            ]
        );

        $this->add_responsive_control(
            'email_input_border_radius',
            [
                'label' => __( 'Border Radius', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="email"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'email_input_padding',
            [
                'label' => __( 'Padding', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="email"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'after'
            ]
        );

        // Background and Box Shadow for input - START
        $this->start_controls_tabs( 'email_input_tabs' );

        $this->start_controls_tab(
            'email_input_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'email_input_background',
                'selector' => '{{WRAPPER}} input[type="email"]',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'email_input_box_shadow',
                'selector' => '{{WRAPPER}} input[type="email"]'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'email_input_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'email_input_background_hover',
                'selector' => '{{WRAPPER}} input[type="email"]:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'email_input_box_shadow_hover',
                'selector' => '{{WRAPPER}} input[type="email"]:hover'
            ]
        );

        $this->add_control(
            'email_input_transition',
            [
                'label' => __( 'Transition Duration', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} input[type="email"]' => "transition:all ease-out {{SIZE}}s;"
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /* -------------------------------------------------------------------------- */
        /* Dropdown Style
        /* -------------------------------------------------------------------------- */

        $this->start_controls_section(
            'dropdown_section',
            [
                'label'      => __('Dropdown', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} select',
            ]
        );

        $this->add_control(
            'dropdown_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} select' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'dropdown_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} select' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} select' => 'max-width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'dropdown_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} select' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} select'
            ]
        );

        $this->add_responsive_control(
            'dropdown_border_radius',
            [
                'label' => __( 'Border Radius', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_padding',
            [
                'label' => __( 'Padding', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_responsive_control(
            'dropdown_margin',
            [
                'label' => __( 'Margin', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        // Background and Box Shadow for input - START
        $this->start_controls_tabs( 'dropdown_input_tabs' );

        $this->start_controls_tab(
            'dropdown_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_background',
                'selector' => '{{WRAPPER}} select',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'selector' => '{{WRAPPER}} select'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'dropdown_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_background_hover',
                'selector' => '{{WRAPPER}} select:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow_hover',
                'selector' => '{{WRAPPER}} select:hover'
            ]
        );

        $this->add_control(
            'dropdown_transition',
            [
                'label' => __( 'Transition Duration', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} select' => "transition:all ease-out {{SIZE}}s;"
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // Background and Box Shadow for input - END

        $this->end_controls_section();

        /* -------------------------------------------------------------------------- */
        /* Textarea Style
        /* -------------------------------------------------------------------------- */

        $this->start_controls_section(
            'textarea_section',
            [
                'label'      => __('Textarea', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'textarea_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} textarea',
            ]
        );

        $this->add_control(
            'textarea_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} textarea' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'textarea_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} textarea' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'textarea_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} textarea' => 'max-width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'textarea_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} textarea' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_responsive_control(
            'textarea_border_radius',
            [
                'label' => __( 'Border Radius', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'textarea_padding',
            [
                'label' => __( 'Padding', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_responsive_control(
            'textarea_margin',
            [
                'label' => __( 'Margin', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        // Background and Box Shadow for input - START
        $this->start_controls_tabs( 'textarea_tabs' );

        $this->start_controls_tab(
            'textarea_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'textarea_background',
                'selector' => '{{WRAPPER}} textarea',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'textarea_box_shadow',
                'selector' => '{{WRAPPER}} textarea'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'textarea_border',
                'selector' => '{{WRAPPER}} textarea'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'textarea_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'textarea_background_hover',
                'selector' => '{{WRAPPER}} textarea:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'textarea_box_shadow_hover',
                'selector' => '{{WRAPPER}} textarea:hover'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'textarea_border_hover',
                'selector' => '{{WRAPPER}} textarea:hover'
            ]
        );

        $this->add_control(
            'textarea_transition',
            [
                'label' => __( 'Transition Duration', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} textarea' => "transition:all ease-out {{SIZE}}s;"
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'textarea_tab_focus_state',
            [
                'label' => __( 'Focus', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'textarea_background_focus',
                'selector' => '{{WRAPPER}} textarea:focus',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'textarea_box_shadow_focus',
                'selector' => '{{WRAPPER}} textarea:focus'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'textarea_border_focus',
                'selector' => '{{WRAPPER}} textarea:focus'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /* -------------------------------------------------------------------------- */
        /* Textarea Placeholder Style
        /* -------------------------------------------------------------------------- */

        $this->start_controls_section(
            'textarea_placeholder_section',
            [
                'label'    => __('Textarea Placeholder', 'auxin-elements' ),
                'tab'      => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'textarea_placeholder_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} textarea::placeholder'
            ]
        );

        $this->add_control(
            'textarea_placeholder_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} textarea::placeholder' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section();

        /* -------------------------------------------------------------------------- */
        /* Labels                                                                     */
        /* -------------------------------------------------------------------------- */

        $this->start_controls_section(
            'labels_section',
            [
                'label'      => __('Labels', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'labels_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} label'
            ]
        );

        $this->add_control(
            'labels_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} label' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();

        /* -------------------------------------------------------------------------- */
        /* Submit Button Style
        /* -------------------------------------------------------------------------- */

        $this->start_controls_section(
            'submit_input_section',
            [
                'label'      => __('Submit Button', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submit_input_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} input[type="submit"]',
            ]
        );

        $this->add_control(
            'submit_input_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'submit_input_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_input_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'max-width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'submit_input_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submit_input_border',
                'selector' => '{{WRAPPER}} input[type="submit"]'
            ]
        );

        $this->add_responsive_control(
            'submit_input_border_radius',
            [
                'label' => __( 'Border Radius', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_input_padding',
            [
                'label' => __( 'Padding', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_responsive_control(
            'submit_input_margin',
            [
                'label' => __( 'Margin', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        // Background and Box Shadow for input - START
        $this->start_controls_tabs( 'submit_input_tabs' );

        $this->start_controls_tab(
            'submit_input_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submit_input_background',
                'selector' => '{{WRAPPER}} input[type="submit"]',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submit_input_box_shadow',
                'selector' => '{{WRAPPER}} input[type="submit"]'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'submit_input_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submit_input_background_hover',
                'selector' => '{{WRAPPER}} input[type="submit"]:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submit_input_box_shadow_hover',
                'selector' => '{{WRAPPER}} input[type="submit"]:hover'
            ]
        );

        $this->add_control(
            'submit_input_transition',
            [
                'label' => __( 'Transition Duration', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => "transition:all ease-out {{SIZE}}s;"
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // Background and Box Shadow for input - END

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

        $args       = array(
            'type'          => $settings['type'],
            'email'         => $settings['email'],
            'cf7_shortcode' => $settings['cf7_shortcode']
        );

        // get the shortcode base blog page
        echo auxin_widget_contact_form_callback( $args );
    }

}
