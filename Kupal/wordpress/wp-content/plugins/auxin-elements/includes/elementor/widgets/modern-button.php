<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'ModernButton' widget.
 *
 * Elementor widget that displays an 'ModernButton' with lightbox.
 *
 * @since 1.0.0
 */
class ModernButton extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'ModernButton' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_modern_button';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'ModernButton' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Modern Button', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'ModernButton' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-button auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'ModernButton' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return [ 'auxin-core' ];
    }

    /**
     * Register 'ModernButton' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Button
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'button_section',
            [
                'label'      => __('Button', 'auxin-elements' ),
            ]
        );

        $this->add_control(
            'label',
            [
                'label'        => __('Text','auxin-elements' ),
                'type'         => Controls_Manager::TEXT,
                'default'      => __('Click Here','auxin-elements' )
            ]
        );

        $this->add_control(
            'label2',
            [
                'label'        => __('Highlighted Text', 'auxin-elements' ),
                'type'         => Controls_Manager::TEXT,
                'default'      => __('','auxin-elements' )
            ]
        );

        $this->add_control(
            'label3',
            [
                'label'        => __('After Text', 'auxin-elements' ),
                'type'         => Controls_Manager::TEXT,
                'default'      => __('','auxin-elements' )
            ]
        );

        $this->add_control(
			'vertical_align',
			[
				'label' => __( 'Text Vertical Align', 'auxin-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'auxin-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'auxin-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'auxin-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .aux-text' => 'display:flex; align-items:{{VALUE}};'
				],
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'bottom' => 'flex-end'
                ],
                'conditions'   => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => 'label2',
                            'operator' => '!==',
                            'value'    => ''
                        ],
                        [
                            'name'     => 'label3',
                            'operator' => '!==',
                            'value'    => ''
                        ]
                    ]
                ],
				'default' => ''
			]
		);

		$this->add_responsive_control(
			'btn_align',
			[
				'label' => __( 'Alignment', 'auxin-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'auxin-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'auxin-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'auxin-elements' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'auxin-elements' ),
						'icon' => 'eicon-text-align-justify',
					]
				],
                'default' => 'left',
                'selectors'  => [
                    '{{WRAPPER}} .aux-modern-button-wrapper' => 'text-align:{{VALUE}};',
                ]
			]
        );

        $this->add_control(
            'link',
            [
                'label'         => __('Link','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => true,
				'dynamic' => [
					'active' => true
                ]
            ]
        );

        $this->add_control(
            'open_video_in_lightbox',
            array(
                'label'        => __('Open Video in Lightbox','auxin-elements' ),
                'description'  => __( 'To use this option, the above link option should be a video', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => ''
            )
        );

        $this->add_control(
            'icon_display',
            [
                'label'        => __( 'Display Icon', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $this->add_control(
			'icon',
			[
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'auxicon-arrow-down',
                    'library' => 'auxicon'
                ],
                'condition' => [
                    'icon_display' => 'yes'
                ],
			]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Button Style Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_btn',
            [
                'label'      => __('Button', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_type',
            [
                'label'       => __('Type', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'options'     => [
                    'default' => __('Default', 'auxin-elements' ),
                    'outline' => __('Outline'  , 'auxin-elements' ),
                ]
            ]
        );

        $this->add_control(
            'btn_shape',
            [
                'label'       => __('Shape', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'normal',
                'options'     => [
                    'normal' => __('Normal', 'auxin-elements' ),
                    'round'  => __('Round'  , 'auxin-elements' ),
                    'curve'  => __('Curve' , 'auxin-elements' ),
                ]
            ]
        );

        $this->add_control(
            'btn_size',
            [
                'label'       => __('Size', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'md',
                'options'     => [
                    'xl' => __('Exlarge', 'auxin-elements' ),
                    'lg' => __('Large'  , 'auxin-elements' ),
                    'md' => __('Medium' , 'auxin-elements' ),
                    'sm' => __('Small'  , 'auxin-elements' ),
                    'xs' => __('Tiny'   , 'auxin-elements' )
                ]
            ]
        );

        $this->add_control(
            'btn_skin',
            [
                'label'       => __('Skin', 'auxin-elements'),
                'label_block' => false,
                'type'        => 'aux-visual-select',
                'default'     => 'black',
                'options'     =>[
                    'black'   => [
                        'label'     => __('black', 'auxin-elements'),
                        'css_class' => 'aux-color-selector aux-button aux-visual-selector-black'
                    ],
                    'white'    => [
                        'label'     => __('White', 'auxin-elements'),
                        'css_class' => 'aux-color-selector aux-button aux-visual-selector-white'
                    ],
                    'info'    => [
                        'label'     => __('Ball Blue', 'auxin-elements'),
                        'css_class' => 'aux-color-selector aux-button aux-visual-selector-ball-blue'
                    ],
                    'warning'    => [
                        'label'     => __('Mikado Yellow', 'auxin-elements'),
                        'css_class' => 'aux-color-selector aux-button aux-visual-selector-mikado-yellow'
                    ],
                    'caution'    => [
                        'label'     => __('Carmine Pink', 'auxin-elements'),
                        'css_class' => 'aux-color-selector aux-button aux-visual-selector-carmine-pink'
                    ]
                ]
            ]
        );

        $this->start_controls_tabs( 'btn_styles' );

        $this->start_controls_tab(
            'btn_normal',
            [
                'label' => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_bg_normal',
                'label'    => __( 'Background', 'auxin-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .aux-modern-button.aux-modern-button-outline .aux-overlay:before, {{WRAPPER}} .aux-modern-button .aux-overlay:before'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'btn_box_shadow_normal',
                'selector'  => '{{WRAPPER}} .aux-overlay:before, {{WRAPPER}} .aux-overlay:after'
            ]
        );

        $this->add_responsive_control(
            'btn_padding_normal',
            [
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-modern-button' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius_normal',
            [
                'label'              => __( 'Border Radius', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'all',
                'separator'          => 'before',
                'selectors'          => [
                    '{{WRAPPER}} .aux-overlay:before, {{WRAPPER}} .aux-overlay:after' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [
                'label' => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_bg_hover',
                'label'    => __( 'Background', 'auxin-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .aux-modern-button.aux-modern-button-outline .aux-overlay:after, {{WRAPPER}} .aux-modern-button .aux-overlay:after'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'btn_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .aux-modern-button:hover .aux-overlay:before, {{WRAPPER}} .aux-modern-button:hover .aux-overlay:after'
            ]
        );

        $this->add_responsive_control(
            'btn_padding_hover',
            [
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-modern-button:hover' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius_hover',
            [
                'label'              => __( 'Border Radius', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'all',
                'separator'          => 'before',
                'selectors'          => [
                    '{{WRAPPER}} .aux-modern-button:hover .aux-overlay:before, {{WRAPPER}} .aux-modern-button:hover .aux-overlay:after' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Label Style Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_text',
            [
                'label'  => __('Text', 'auxin-elements' ),
                'tab'    => Controls_Manager::TAB_STYLE
            ]
        );


        $this->start_controls_tabs( 'text_styles' );

        $this->start_controls_tab(
            'text_normal',
            [
                'label' => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_normal_typo',
                'scheme' => Typography::TYPOGRAPHY_1,
               'selector' => '{{WRAPPER}} .aux-text'
            ]
        );

        $this->add_control(
            'text_normal_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
               'selectors' => [
                    '{{WRAPPER}} .aux-text' => 'color: {{VALUE}};'
               ]
            ]

        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_normal_text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-text'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_hover',
            [
                'label' => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text_hover_typo',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-modern-button:hover .aux-text'
            ]
        );

        $this->add_control(
            'text_hover_color',
            [
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-modern-button:hover .aux-text' => 'color:{{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text_hover_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-button:hover .aux-text'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  Label Highlighted Style Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_text2',
            [
                'label'  => __('Highlighted Text', 'auxin-elements' ),
                'tab'    => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'label2!' => ''
                ]
            ]
        );


        $this->start_controls_tabs( 'text2_styles' );

        $this->start_controls_tab(
            'text2_normal',
            [
                'label' => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text2_normal_typo',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-text-highlighted'
            ]
        );

        $this->add_control(
            'text2_normal_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
               'selectors' => [
                    '{{WRAPPER}} .aux-text-highlighted' => 'color: {{VALUE}};'
               ]
            ]

        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text2_normal_text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-text-highlighted'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'text2_hover',
            [
                'label' => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text2_hover_typo',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-modern-button:hover .aux-text-highlighted'
            ]
        );

        $this->add_control(
            'text2_hover_color',
            [
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-modern-button:hover .aux-text-highlighted' => 'color:{{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text2_hover_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-button:hover .aux-text-highlighted'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'text2_margin',
            [
                'label'      => __( 'Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-text-highlighted' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Label After Style Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_text3',
            [
                'label'  => __('After Text', 'auxin-elements' ),
                'tab'    => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'label3!' => ''
                ]
            ]
        );


        $this->start_controls_tabs( 'text3_styles' );

        $this->start_controls_tab(
            'text3_normal',
            [
                'label' => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text3_normal_typo',
                'scheme' => Typography::TYPOGRAPHY_1,
               'selector' => '{{WRAPPER}} .aux-text-after'
            ]
        );

        $this->add_control(
            'text3_normal_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
               'selectors' => [
                    '{{WRAPPER}} .aux-text-after' => 'color: {{VALUE}};'
               ]
            ]

        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text3_normal_text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-text-after'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'text3_hover',
            [
                'label' => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text3_hover_typo',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-modern-button:hover .aux-text-after'
            ]
        );

        $this->add_control(
            'text3_hover_color',
            [
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-modern-button:hover .aux-text-after' => 'color:{{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text3_hover_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-modern-button:hover .aux-text-after'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'text3_margin',
            [
                'label'      => __( 'Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-text-after' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Button Style Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_icon',
            [
                'label'     => __('Icon', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon_display' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label'       => __('Alignment', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'left',
                'options'     => [
                    'top'    => __('Over', 'auxin-elements' ),
                    'bottom' => __('Below', 'auxin-elements' ),
                    'left'   => __('Left', 'auxin-elements' ),
                    'right'  => __('Right', 'auxin-elements' ),
                ]
            ]
        );

        $this->start_controls_tabs( 'icon_styles' );

        $this->start_controls_tab(
            'icon_normal',
            [
                'label' => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_responsive_control(
            'icon_normal_size',
            [
                'label'      => __( 'Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 512,
                        'step' => 2,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'icon_normal_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-icon' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_normal_bg',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .aux-icon:before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'icon_normal_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-icon'
            ]
        );

        $this->add_responsive_control(
            'icon_normal_margin',
            [
                'label'      => __( 'Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_normal_padding',
            [
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-icon' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_normal_border_radius',
            [
                'label'              => __( 'Border Radius', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'all',
                'separator'          => 'before',
                'selectors'          => [
                    '{{WRAPPER}} .aux-icon' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_hover',
            [
                'label' => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_responsive_control(
            'icon_hover_size',
            [
                'label'      => __( 'Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 512,
                        'step' => 2,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-modern-button:hover .aux-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-modern-button:hover .aux-icon' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_hover_bg',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .aux-icon:after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'icon_hover_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-modern-button:hover .aux-icon'
            ]
        );

        $this->add_responsive_control(
            'icon_hover_margin',
            [
                'label'      => __( 'Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-modern-button:hover .aux-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_hover_padding',
            [
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-modern-button:hover .aux-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_hover_border_radius',
            [
                'label'              => __( 'Border Radius', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'all',
                'separator'          => 'before',
                'selectors'          => [
                    '{{WRAPPER}} .aux-icon:hover' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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

      $url = '';
      $settings   = $this->get_settings_for_display();

    $this->add_render_attribute( 'wrapper', 'class', 'aux-modern-button-wrapper' );
    $this->add_render_attribute( 'wrapper', 'class', 'aux-modern-button-align-' . $settings['btn_align'] );

    $this->add_render_attribute( 'button', 'class', 'aux-modern-button' );
    $this->add_render_attribute( 'button', 'class', 'aux-' . $settings['btn_skin'] );
    $this->add_render_attribute( 'button', 'class', 'aux-modern-button-' . $settings['btn_size'] );
    $this->add_render_attribute( 'button', 'class', 'aux-modern-button-' . $settings['btn_shape'] );
    $this->add_render_attribute( 'button', 'class', 'aux-modern-button-' . $settings['btn_type'] );
    $this->add_render_attribute( 'button', 'class', 'aux-icon-' . $settings['icon_align'] );


    if ( ! empty( $settings['link']['url'] ) ) {
        $this->add_link_attributes( 'button', $settings['link'] );
        $url = $settings['link']['url'];
    }

    ?>
    <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> >
        <?php
        if ( auxin_is_true( $settings['open_video_in_lightbox'] ) && strlen( $url ) > 3 ) { // disable lighbox if anchor is set

            $this->add_render_attribute( 'button', 'class', 'aux-open-video' );
            $this->add_render_attribute( 'button', 'data-type', 'video' );

            echo '<span class="aux-lightbox-video ">';
        }
        ?>
            <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                <div class="aux-overlay"></div>
                <?php if ( $settings['icon_display'] ) { ;?>
                    <div class="aux-icon ">
                        <?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );?>
                    </div>
                <?php };?>
                <div class="aux-text">
<?php
    if( ! empty( $settings['label']  ) ){ printf( '<span class="aux-text-before">%s</span>', do_shortcode( $settings['label'] ) ); }
    if( ! empty( $settings['label2'] ) ){ printf( '<span class="aux-text-highlighted">%s</span>', do_shortcode( $settings['label2'] ) ); }
    if( ! empty( $settings['label3'] ) ){ printf( '<span class="aux-text-after">%s</span>', do_shortcode( $settings['label3'] ) ); }
?>
                </div>
            </a>
            <?php
        if ( auxin_is_true( $settings['open_video_in_lightbox'] ) ) {
            echo '</span>';
        }
        ?>

    </div>

    <?php
  }

}