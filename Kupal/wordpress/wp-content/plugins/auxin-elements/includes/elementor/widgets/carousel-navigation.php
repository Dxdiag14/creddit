<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor 'CarouselNavigation' widget.
 *
 * Elementor widget that displays an 'CarouselNavigation' with lightbox.
 *
 * @since 1.0.0
 */
class CarouselNavigation extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'CarouselNavigation' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_carousel_navigation';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'CarouselNavigation' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Carousel Navigation', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'CarouselNavigation' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-post-navigation auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'CarouselNavigation' widget icon.
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
     * Register 'CarouselNavigation' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'navigation',
            [
                'label'      => __('Navigation', 'auxin-elements' ),
            ]
        );

        $this->add_control(
            'nav_type',
            [
                'label'       => __( 'Navigation Type', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'boxed-arrow',
                'options'     => [
                    'boxed-arrow' => __('Boxed Arrow', 'auxin-elements' ),
                    'long-arrow'  => __('Long Arrow', 'auxin-elements' ),
                    'custom'      => __('Custom', 'auxin-elements' ),
                ],
            ]
        );

        $this->add_control(
            'prev_icon',
            [
                'label'       => __('Previous Button','auxin-elements' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'auxicon2-arrows-chevron-thin-left',
                    'library' => 'auxicon'
                ],
                'condition'   => [
                    'nav_type' => ['custom']
                ]
            ]
        );

        $this->add_control(
            'next_icon',
            [
                'label'       => __('Next Button','auxin-elements' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'auxicon2-arrows-chevron-thin-right',
                    'library' => 'auxicon'
                ],
                'condition'   => [
                    'nav_type' => ['custom']
                ]
            ]
        );

        $this->add_control(
            'nav_target',
            [
                'label'         => __('Carousel Target','auxin-elements' ),
                'description'   => __('CSS selector of target carousel.','auxin-elements' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '',
                'placeholder'   => '.carousel-classname'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'navigation_style',
            [
                'label'     => __( 'Navigation', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'navigation_align',
			[
				'label' => __( 'Align', 'auxin-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					''              => __( 'Default', 'auxin-elements' ),
					'flex-start'    => __( 'Start', 'auxin-elements' ),
					'center'        => __( 'Center', 'auxin-elements' ),
					'flex-end'      => __( 'End', 'auxin-elements' ),
					'space-between' => __( 'Space Between', 'auxin-elements' ),
					'space-around'  => __( 'Space Around', 'auxin-elements' ),
					'space-evenly'  => __( 'Space Evenly', 'auxin-elements' ),
				],
				'selectors' => [
					'{{WRAPPER}} .aux-carousel-navigation' => 'justify-content: {{VALUE}}',
				],
			]
        );

        $this->add_responsive_control(
            'navigation_gap',
            [
                'label'      => __('Space Between Buttons','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'max' => 100
                    ],
                    'em' => [
                        'max' => 10
                    ]
                ],
                'default' => [
					'unit' => 'px',
					'size' => 10,
                ],
                'condition' => [
                    'navigation_align!' => ['space-between', 'space-around', 'space-evenly']
                ],
                'selectors' => [
					'{{WRAPPER}} .aux-carousel-navigation .aux-prev' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->end_controls_section();


        // Icon Tab Style
        $this->start_controls_section(
            'icon_style',
            [
                'label'     => __( 'Icon', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'icon_width',
            [
                'label'      => __('Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em','%'],
                'range'      => [
                    '%' => [
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'px' => [
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    ]
                ],
                'selectors'          => [
                    '{{WRAPPER}} .aux-custom-nav, {{WRAPPER}} .aux-custom-nav img' => 'width:{{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_height',
            [
                'label'      => __('Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em','%'],
                'range'      => [
                    '%' => [
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'px' => [
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    ]
                ],
                'selectors'          => [
                    '{{WRAPPER}} .aux-custom-nav' => 'height:{{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->start_controls_tabs( 'icon_tabs' );

        $this->start_controls_tab(
            'icon_normal_tab',
            [
                'label'     => __( 'Normal' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'icon_bg',
                'label'    => __( 'Background', 'auxin-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .aux-custom-nav'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'icon_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-custom-nav'
            ]
        );

        $this->add_responsive_control(
            'icon_color',
            [
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3d3d3d',
                'selectors' => [
                    '{{WRAPPER}} .aux-custom-nav' => 'color: {{VALUE}};'
                ],
                'condition'   => [
                    'nav_type' => ['custom']
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => __( 'Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'max' => 100
                    ],
                    'em' => [
                        'max' => 10
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .aux-custom-nav' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'condition'   => [
                    'nav_type' => ['custom']
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'icon_border',
                'selector'  => '{{WRAPPER}} .aux-custom-nav',
                'condition' => [
                    'nav_type' => ['custom']
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-custom-nav, {{WRAPPER}} .aux-arrow-nav' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'allowed_dimensions' => 'all'
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label'              => __( 'Padding', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em' ],
                'allowed_dimensions' => 'all',
                'selectors'          => [
                    '{{WRAPPER}} .aux-custom-nav, {{WRAPPER}} .aux-arrow-nav' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .aux-custom-nav > span' => 'line-height:0;'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_hover_tab',
            [
                'label'     => __( 'Hover' , 'auxin-elements' )
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'icon_hover_bg',
                'label'    => __( 'Background', 'auxin-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .aux-custom-nav:hover'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'icon_hover_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-custom-nav:hover'
            ]
        );

        $this->add_responsive_control(
            'icon_hover_color',
            [
                'label'     => __( 'Hover Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aux-custom-nav:hover' => 'color: {{VALUE}};',
                ],
                'condition'   => [
                    'nav_type' => ['custom']
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_hover_size',
            [
                'label'      => __( 'Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'max' => 100
                    ],
                    'em' => [
                        'max' => 10
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .aux-custom-nav:hover' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'condition'   => [
                    'nav_type' => ['custom']
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'icon_border_hover',
                'selector'  => '{{WRAPPER}} .aux-custom-nav:hover',
                'condition' => [
                    'nav_type' => ['custom']
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_hover_border_radius',
            [
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .aux-custom-nav:hover, {{WRAPPER}} .aux-arrow-nav:hover' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'allowed_dimensions' => 'all'
            ]
        );

        $this->add_responsive_control(
            'icon_hover_padding',
            [
                'label'              => __( 'Padding', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px', 'em' ],
                'allowed_dimensions' => 'all',
                'selectors'          => [
                    '{{WRAPPER}} .aux-custom-nav:hover, {{WRAPPER}} .aux-arrow-nav:hover' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
   * Render 'CarouselNavigation' widget output on the frontend.
   *
   * @access protected
   */
  protected function get_arrow_output( $type, $settings ) {
        ob_start();

        switch( $type ) {
            case 'boxed-arrow':
                ;?>
                <div class="aux-prev aux-prev-arrow aux-arrow-nav aux-outline aux-hover-fill">
                    <span class="aux-svg-arrow aux-small-left"></span>
                    <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-left"></span>
                </div>
                <div class="aux-next aux-next-arrow aux-arrow-nav aux-outline aux-hover-fill">
                    <span class="aux-svg-arrow aux-small-right"></span>
                    <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-right"></span>
                </div>
                <?php
                break;
            case 'long-arrow':
                ;?>
                    <div class="aux-prev aux-prev-arrow">
                        <span class="aux-svg-arrow aux-l-left"></span>
                    </div>
                    <div class="aux-next aux-next-arrow">
                        <span class="aux-svg-arrow aux-l-right"></span>
                    </div>
                <?php
                break;
            case 'custom':
                    $prev_icon = isset( $settings['prev_icon']['value'] ) ? $settings['prev_icon']['value'] : $settings['prev_icon'];
                    $next_icon = isset( $settings['next_icon']['value'] ) ? $settings['next_icon']['value'] : $settings['next_icon'];
                    
                    $prev_icon_url = ( ! empty( $settings['prev_icon']['value'] ) && $settings['prev_icon']['library'] == 'svg' ) ? $settings['prev_icon']['value']['url'] : '';
                    $next_icon_url = ( ! empty( $settings['next_icon']['value'] ) && $settings['next_icon']['library'] == 'svg' ) ? $settings['next_icon']['value']['url'] : '';
                ;?>
                    <div class="aux-prev aux-prev-custom aux-custom-nav">
                    <?php if ( empty( $prev_icon_url ) ) { ?>
                        <span class="<?php echo esc_attr( $prev_icon ); ?>"></span>
                    <?php } else { ?>
                        <img src="<?php echo esc_url( $prev_icon_url );?>">
                    <?php } ?>
                    </div>
                    <div class="aux-next aux-next-custom aux-custom-nav">
                    <?php if ( empty( $next_icon_url ) ) { ?>
                        <span class="<?php echo esc_attr( $next_icon ); ?>"></span>
                    <?php } else { ?>
                        <img src="<?php echo esc_url( $next_icon_url );?>">
                    <?php } ?>
                    </div>
                <?php
                break;
            default:
                break;

        }

        return ob_get_clean();
  }


  /**
   * Render 'CarouselNavigation' widget output on the frontend.
   *
   * @access protected
   */
    protected function render() {
        $settings   = $this->get_settings_for_display(); ?>

        <div class="aux-carousel-navigation" data-target="<?php echo $settings['nav_target'];?>">
            <?php echo $this->get_arrow_output( $settings['nav_type'] , $settings );?>
        </div>

    <?php
    }

}
