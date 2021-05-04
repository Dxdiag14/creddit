<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements\Theme_Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'ModernSearch' widget.
 *
 * Elementor widget that displays an 'ModernSearch'.
 *
 * @since 1.0.0
 */
class ModernSearch extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'ModernSearch' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_modern_search';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'ModernSearch' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Modern Search', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'ModernSearch' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-search auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'ModernSearch' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return [ 'auxin-core', 'auxin-theme-elements' ];
    }

    /**
     * Register 'ModernSearch' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*  Search Section
        /*-------------------------------------*/

        $this->start_controls_section(
			'section_search',
			[
				'label' => __( 'Button', 'auxin-elements' ),
			]
        );

        $this->add_control(
			'icon',
			[
				'label'   => __( 'Icon', 'auxin-elements' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'auxicon-search-4',
                    'library' => 'auxicon'
                ]
			]
        );

        $this->add_control(
			'text',
			[
				'label'       => __( 'Text', 'auxin-elements' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
				],
				'default'     => '',
				'placeholder' => __( 'Submit', 'auxin-elements' ),
			]
		);

        $this->end_controls_section();

        /*  Search Section
        /*-------------------------------------*/

        $this->start_controls_section(
			'fullscreen',
			[
				'label' => __( 'Fullscreen Search Input', 'auxin-elements' ),
			]
        );

        $this->add_control(
            'post_types',
            [
                'label'       => __('Post Types', 'auxin-elements'),
                'description' => __('Specifies a post type that you want to show posts from it.', 'auxin-elements' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_post_types(),
                'default'     => [ 'post' ],
            ]
        );

        $this->add_control(
            'use_ajax',
            [
                'label'        => __( 'Enable Ajax Search', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before'
            ]
        );

        $this->add_control(
            'fullscreen_display_submit',
            [
                'label'        => __( 'Display Submit Button', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before'
            ]
        );

        $this->add_control(
            'fullscreen_display_fill_submit',
            [
                'label'        => __( 'Display Fill Submit Button', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
                'condition' => [
                    'fullscreen_display_submit' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'fullscreen_display_cats',
            [
                'label'        => __( 'Display Categories', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $this->add_control(
			'search_field_placeholder_text',
			[
				'label'       => __( 'Custom Placeholder Text', 'auxin-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Search ...', 'auxin-elements' ),
			]
        );

        $this->add_control(
			'search_field_title',
			[
				'label'       => __( 'Search Title', 'auxin-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Type to Search', 'auxin-elements' ),
			]
        );


        $this->end_controls_section();

        /*  Icon Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'icon_style_section',
            [
                'label'     => __( 'Icon', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );

        $this->start_controls_tabs( 'icon_styles' );

        $this->start_controls_tab(
            'icon_style_normal',
            [
                'label'     => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_control(
            'icon_color_normal',
            [
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-submit i' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_size_normal',
            [
                'label'      => __( 'Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px'       => [
                        'min'  => 16,
                        'max'  => 512,
                        'step' => 2,
                    ],
                    '%'        => [
                        'min'  => 0,
                        'max'  => 100,
                    ],
                ],
                'default' => [
                    'size' => 22,
                    'unit' => 'px'
                ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-submit i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_style_hover',
            [
                'label'     => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-submit:hover i' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_size_hover',
            [
                'label'      => __( 'Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px'       => [
                        'min'  => 16,
                        'max'  => 512,
                        'step' => 2,
                    ],
                    '%'        => [
                        'min'  => 0,
                        'max'  => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-submit:hover i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*  Text Style Section
        /*-------------------------------------*/
        $this->start_controls_section(
            'text_style_section',
            [
                'label'     => __( 'Text', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['text!' => '']
            ]
        );

        $this->start_controls_tabs( 'text_styles' );

        $this->start_controls_tab(
            'text_style_normal',
            [
                'label'     => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_control(
            'text_color_normal',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-submit' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'text_typo_normal',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-search-submit'
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'text_text_shadow_normal',
                'selector'  => '{{WRAPPER}} .aux-search-submit'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_style_hover',
            [
                'label'     => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_control(
            'text_color_hover',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-submit:hover' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'text_typo_hover',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-search-submit:hover'
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'text_text_shadow_hover',
                'selector'  => '{{WRAPPER}} .aux-search-submit:hover'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*  Button Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'button_style_section',
            [
                'label'     => __( 'Button', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'button_styles' );

        $this->start_controls_tab(
            'button_style_normal',
            [
                'label'     => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_bg_normal',
                'selector' => '{{WRAPPER}} .aux-search-submit',
                'types' => [ 'classic', 'gradient'],
                'separator'  => 'after'
            ]
        );

        $this->add_responsive_control(
            'button_padding_normal',
            [
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'button_border_normal',
                'selector'  => '{{WRAPPER}} .aux-search-submit',
                'separator' => 'none',
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'button_border_radius_normal',
            [
                'label'              => __( 'Border Radius', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'all',
                'separator'          => 'before',
                'selectors'          => [
                    '{{WRAPPER}} .aux-search-submit' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'button_box_shadow_normal',
                'selector'  => '{{WRAPPER}} .aux-search-submit',
                'separator' => 'before'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_style_hover',
            [
                'label'     => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'button_bg_hover',
                'selector'  => '{{WRAPPER}} .aux-search-submit: hover',
                'types'     => [ 'classic', 'gradient'],
                'separator' => 'after'
            ]
        );

        $this->add_responsive_control(
            'button_padding_hover',
            [
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-submit:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'button_border_hover',
                'selector'  => '{{WRAPPER}} .aux-search-submit:hover',
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'button_border_radius_hover',
            [
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-submit:hover' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'allowed_dimensions' => 'all',
                'separator'  => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'button_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .aux-search-submit:hover',
                'separator' => 'before'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_cursor',
            [
                'label'       => __( 'Cursor', 'auxin-elements' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'default' => __( 'Default', 'auxin-elements' ),
                    'pointer' => __( 'Pointer', 'auxin-elements' ),
                    'zoom-in' => __( 'Zoom', 'auxin-elements' ),
                    'help'    => __( 'Help', 'auxin-elements' )
                ],
                'default'     => 'pointer',
                'selectors'   => [
                    '{{WRAPPER}} .aux-search-submit' => 'cursor: {{VALUE}};'
                ],
                'separator'   => 'before'
            ]
        );

        $this->end_controls_section();

        /*  Input Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'input_style_section',
            [
                'label'     => __( 'Fullscreen Search Input', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );


        $this->add_control(
            'input_color',
            [
                'label' => __( 'Text Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-popup .aux-search-field' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => __( 'Placeholder Text Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-popup .aux-search-field::placeholder' => 'color:{{VALUE}};'
                ],
                'separator'  => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'input_typo',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-search-popup .aux-search-field',
                'separator'  => 'before'
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-popup .aux-search-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before'
            ]
        );

        $this->add_responsive_control(
            'input_border_size',
            [
                'label'      => __( 'Border Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px'       => [
                        'min'  => 0,
                        'max'  => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 2,
                    'unit' => 'px'
                ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-popup .aux-search-input-form' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'before'
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => __( 'Border Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-popup .aux-search-input-form' => 'border-color: {{VALUE}};'
                ],
                'separator'  => 'before'
            ]
        );

        $this->add_control(
            'input_icon_enabled',
            array(
                'label'       => __( 'Display Search Icon', 'auxin-elements' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => __( 'Show', 'auxin-elements' ),
                'label_off'   => __( 'Hide', 'auxin-elements' ),
                'default'     => 'yes',
                'separator'   => 'before'
            )
        );

        $this->add_responsive_control(
            'input_icon_size',
            [
                'label'      => __( 'Search Icon Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px'       => [
                        'min'  => 16,
                        'max'  => 512,
                        'step' => 2,
                    ],
                    '%'        => [
                        'min'  => 0,
                        'max'  => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px'
                ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-popup .aux-submit-icon-container:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'input_icon_enabled' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'input_icon_color',
            [
                'label' => __( 'Search Icon Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-popup .aux-submit-icon-container:before' => 'color: {{VALUE}} !important;'
                ],
                'condition' => [
                    'input_icon_enabled' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'input_bg',
                'selector' => '{{WRAPPER}} .aux-search-popup',
                'types'    => [ 'classic', 'gradient'],
                'separator'  => 'before',
                'fields_options' => [
					'background' => [
						'label' => __( 'Overlay Background', 'auxin-elements' ),
					]
				]
            ]
        );

        $this->end_controls_section();

        /*  Input Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'close_style_section',
            [
                'label'     => __( 'Fullscreen Close Button', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'close_position_top',
            [
                'label'      => __('Top Position','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .aux-search-popup .aux-panel-close' => 'top:{{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'close_position_right',
            [
                'label'      => __('Right Position','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .aux-search-popup .aux-panel-close' => 'right:{{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();


        /*  Search Title Style
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            [
                'label'     => __( 'Search Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_field_title!' => ''
                ]
            ]
        );


        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-search-form-legend' => 'color:{{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'title_typo',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-search-form-legend'
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => __( 'Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-search-form-legend' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'       => 'title_bg',
                'selector'   => '{{WRAPPER}} .aux-search-form-legend',
                'types'      => [ 'classic', 'gradient'],
                'separator'  => 'before'
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Search Input.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_search_button( $args = [] ) { ;

        $defaults = [
            'submit_class'  => 'aux-search-submit aux-search-fullscreen',
            'submit_text'   => '',
            'wrapper_class' => 'aux-modern-search-wrapper',
            'icon'          => 'auxicon-search-4',
            'target'        => '.aux-search-popup-' . $this->get_id(),
            'use_ajax'      => false
        ];

        $args = wp_parse_args( $args, $defaults );

        $this->add_render_attribute( 'wrapper', 'class', $args['wrapper_class'] );
        $this->add_render_attribute( 'button', 'class', $args['submit_class'] );
        $this->add_render_attribute( 'button', 'data-target', $args['target'] );
        $this->add_render_attribute( 'submit_text', 'class', 'aux-submit-text' );
        if ( empty( $args['icon']['library'] ) || $args['icon']['library'] != 'svg'  ) {
            $this->add_render_attribute( 'icon', 'class', $args['icon'] );
        }
    ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' );?> >
            <button <?php echo $this->get_render_attribute_string( 'button' );?> >
                <?php if ( empty( $args['icon']['library'] ) || $args['icon']['library'] != 'svg'  ) { ?>
                    <i <?php echo $this->get_render_attribute_string( 'icon' );?>></i>
                <?php } else { ?>
                    <img src="<?php echo esc_url( $args['icon']['value']['url'] ); ?>">
                <?php } ?>
                <span <?php echo $this->get_render_attribute_string( 'submit_text' );?> ><?php echo $args['submit_text']; ?></span>
            </button>
        </div>
    <?php

    }
    /**
     * Render Search Overlay.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_search_overlay( $args = [] ) { ;

        $defaults = [
            'wrapper_class'    => 'aux-search-popup aux-search-popup-' . $this->get_id(),
            'use_ajax'         => false,
            'post_types'       => [],
            'placeholder_text' => __('Search...', 'auxin-elements' ),
            'search_title'     => ''
        ];

        $args = wp_parse_args( $args, $defaults );

        $form_args  = [
            'use_ajax'         => $args['use_ajax'],
            'display_submit'   => $args['display_submit'],
            'display_fill'     => $args['display_fill'],
            'display_cats'     => $args['display_cats'],
            'post_types'       => $args['post_types'],
            'placeholder_text' => $args['placeholder_text'],
            'search_title'     => $args['search_title']
        ];

        $this->add_render_attribute( 'overlay_wrapper', 'class', $args['wrapper_class'] );
    ?>
        <div <?php echo $this->get_render_attribute_string( 'overlay_wrapper' );?>>
            <div class="aux-panel-close">
                <div class="aux-close aux-cross-symbol aux-thick-medium"></div>
            </div>
            <div class="aux-search-popup-content">
                <?php $this->render_search_form( $form_args ) ;?>
                <?php if ( $args['use_ajax'] ) { ;?>
                    <div class="aux-search-ajax-container">
                        <div class="aux-search-ajax-output"></div>
                        <div class="aux-loading-spinner aux-spinner-hide">
                            <div class="aux-loading-loop">
                            <svg class="aux-circle" width="100%" height="100%" viewBox="0 0 42 42">
                                <circle class="aux-stroke-bg" r="20" cx="21" cy="21" fill="none"></circle>
                                <circle class="aux-progress" r="20" cx="21" cy="21" fill="none" transform="rotate(-90 21 21)"></circle>
                            </svg>
                            </div>
                        </div>
                    </div>
                <?php };?>
            </div>
        </div>
    <?php
    }

    /**
     * Render Search Overlay.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_search_form( $args = [] ) { ;

        $defaults = [
            'wrapper_class'   => 'aux-search-form',
            'use_ajax'         => false,
            'display_submit'   => true,
            'display_fill'     => false,
            'display_cats'     => false,
            'post_types'       => [],
            'placeholder_text' => __('Search...', 'auxin-elements' ),
            'search_title'     => ''
        ];

        $args = wp_parse_args( $args, $defaults );

        $this->add_render_attribute( 'form_wrapper', 'class', $args['wrapper_class'] );

        if ( $args['use_ajax'] ) {
            $this->add_render_attribute( 'form_wrapper', 'class', 'aux-search-ajax' );
        }
    ?>
        <div <?php echo $this->get_render_attribute_string( 'form_wrapper' );?>>
    <?php if( ! empty( $args['search_title'] ) ){
        echo '<h5 class="aux-search-form-legend">'. esc_html( $args['search_title'] ) .'</h5>';
    } ?>
            <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" >
                <div class="aux-search-input-form">
                    <input type="text" class="aux-search-field" placeholder="<?php echo esc_attr( $args['placeholder_text'] ); ?>" name="s" autocomplete="off" data-post-types="<?php echo esc_attr ( wp_json_encode( $args['post_types'] ) ) ;?>" />
                    <input type="hidden" name='post_type' value="<?php echo implode( ',', $args['post_types'] );?>">
                    <?php if ( $args['display_cats'] ) { ;?>
                        <?php $this->render_category( ['post_types' => $args['post_types'] ] );?>
                    <?php };?>
                    <?php if ( $args['display_submit'] ) { ;?>
                        <?php if ( $args['display_fill'] ) { ;?>
                            <input type="submit" class="aux-fill-search-submit" value="<?php esc_attr_e( 'Search', THEME_DOMAIN ); ?> " >
                        <?php } else { ;?>
                            <div class="aux-submit-icon-container auxicon-search-4">
                                <input type="submit" class="aux-iconic-search-submit" value="<?php esc_attr_e( 'Search', 'auxin-elements' ); ?>" >
                            </div>
                        <?php };?>
                    <?php };?>
                </div>
            </form>
        </div>
    <?php
    }

    /**
     * Render Search Overlay.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_category( $args ) {
        $taxonomies     = $args['post_types'];
        $post_types     = [];
        $options_output = '';

        foreach( $taxonomies as $taxonomy ) {

            $terms     = get_terms( $taxonomy );
            $post_type = get_taxonomy( $taxonomy )->object_type;
            $post_types = array_merge( $post_types, $post_type );

            foreach ($terms as $term => $term_args) {
                $options_output .= '<option data-post-type="' . esc_attr( wp_json_encode( $post_type ) ) . '" data-taxonomy="' . esc_attr( wp_json_encode( [$term_args->taxonomy] ) ) . '" value="'. esc_attr( $term_args->term_id ) .'">'. esc_html( $term_args->name ) .'</option>';
            }

        }

        $options_output = '<option value="all" data-taxonomy="' . esc_attr ( wp_json_encode( $taxonomies ) ) . '" data-post-type="' . esc_attr ( wp_json_encode( $post_types ) ) . '">' . __('All Categories', THEME_DOMAIN) . '</option>' . $options_output ;

        $output  = '<div class="aux-search-cats">';
            $output .= '<select class="aux-modern-search-cats" name="cat">' . $options_output . '</select>';
        $output .= '</div>';

        echo $output;
    }

    /**
     * Get All Active Post Types.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function get_post_types() {
        return auxin_get_available_post_types_for_search();
    }

     /**
     * Render Modern Search widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        echo '<div class="aux-modern-search">';

        $this->render_search_button([
            'submit_text' => $settings['text'],
            'icon'        => $settings['icon']
        ]);

        $this->render_search_overlay([
            'use_ajax'         => auxin_is_true( $settings['use_ajax'] ),
            'display_submit'   => auxin_is_true( $settings['fullscreen_display_submit'] ),
            'display_cats'     => auxin_is_true( $settings['fullscreen_display_cats']),
            'display_fill'     => auxin_is_true( $settings['fullscreen_display_fill_submit'] ),
            'post_types'       => $settings['post_types'],
            'placeholder_text' => $settings['search_field_placeholder_text'],
            'search_title'     => $settings['search_field_title']
        ]);

        echo '</div>';
    }

}