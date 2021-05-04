<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements\Theme_Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Breadcrumbs' widget.
 *
 * Elementor widget that displays an 'PostTitle'.
 *
 * @since 1.0.0
 */
class Breadcrumbs extends Widget_Base {

  /**
   * Get widget name.
   *
   * Retrieve 'Breadcrumbs' widget name.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget name.
   */
  public function get_name() {
      return 'aux_breadcrumbs';
  }

  /**
   * Get widget title.
   *
   * Retrieve 'Breadcrumbs' widget title.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget title.
   */
  public function get_title() {
      return __( 'Breadcrumbs', 'auxin-elements' );
  }

  /**
   * Get widget icon.
   *
   * Retrieve 'Breadcrumbs' widget icon.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget icon.
   */
  public function get_icon() {
      return 'eicon-product-breadcrumbs auxin-badge';
  }

  /**
   * Get widget categories.
   *
   * Retrieve 'Breadcrumbs' widget icon.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget icon.
   */
	public function get_categories() {
		return array( 'auxin-core', 'auxin-theme-elements-single' );
	}


	private function is_yoast_breadcrumbs_enabled(){

		$is_enabled = false;

		if ( function_exists( 'yoast_breadcrumb' ) ) {
			if ( ! $is_enabled = current_theme_supports( 'yoast-seo-breadcrumbs' ) ) {
                $options = get_option( 'wpseo_titles' );
                $is_enabled = isset( $options['breadcrumbs-enable'] ) && ( $options['breadcrumbs-enable'] === true );
			}
		}

		return $is_enabled;
	}

	/**
     * Register 'Breadcrumbs' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
	protected function _register_controls() {

		/*--------------------------------------------------------------------*/
        /*  Content
		/*--------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_breadcrumb',
			[
				'label' => __( 'General', 'auxin-elements' ),
				]
			);

		if ( ! $yoast_breadcrumbs_enabled = $this->is_yoast_breadcrumbs_enabled() ) {

			$this->add_control(
				'show_home_icon',
				array(
					'label'        => __('Show home icon','auxin-elements' ),
					'description'   => __('Show icon instead of text for home', 'auxin-elements' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'On', 'auxin-elements' ),
					'label_off'    => __( 'Off', 'auxin-elements' ),
					'return_value' => 'yes',
					'default'      => ''
				)
			);

			$this->add_control(
				'home_icon',
				[
					'label'       => __( 'Custom Seperator Icon', 'auxin-elements' ),
					'label_block' => true,
					'type'        => Controls_Manager::ICONS,
					'recommended' => [
						'fa-solid' => [
							'home'
						],
						'auxicon' =>[
							'home-house-streamline',
							'home',
							'home-1',
							'home-2',
							'home-3'
						]
					],
					'exclude_inline_options' => [ 'svg' ],
					'default' => [
						'value'   => 'auxicon-home-house-streamline',
						'library' => 'auxicon'
					],
					'condition'   => [
						'show_home_icon' => 'yes'
					]
				]
			);

			$this->add_control(
				'separator_icon',
				[
					'label'       => __( 'Custom Separator', 'auxin-elements' ),
					'label_block' => true,
					'type'        => Controls_Manager::ICONS,
					'recommended' => [
						'auxicon2' => [
							"arrows-right-double-chevron",
							"arrows-chevron-thin-right",
							"arrow-slim-right-dashed",
							"arrow-slim-right",
							"arrow-chevron-med-right",
							"arrow-chevron-fat-right",
							"arrow-chevron-slim-right",
							"arrow-chevron-pixel-right",
							"arrow-line-med-right",
							"arrow-line-right",
							"arrow-chevron-pixel-right2",
							"arrow-pixel-fat-right",
							"arrow-thin-right",
							"arrow-chevron-small-right",
							"arrow-circle-right"
						]
					],
					'separator'   => 'before'
				]
			);

		} else {
			$this->add_control(
				'yoast_is_enabled_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __( 'Note: Breadcrumb is using "Yoast SEO" plugin. You can change the options from plugin setting page.', 'auxin-elements' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				]
			);
		}

			$this->end_controls_section();


			/*----------------------------------------------------------------*/
			/*  Style
			/*----------------------------------------------------------------*/

		if ( ! $yoast_breadcrumbs_enabled ) {

			$this->start_controls_section(
				'home_icon_style_section',
				[
					'label' => __( 'Home Icon', 'auxin-elements' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition'   => [
						'show_home_icon' => 'yes'
					]
				]
			);

			$this->add_responsive_control(
				'home_icon_position',
				[
					'label' => __( 'Position', 'auxin-elements' ),
					'type' => Controls_Manager::DIMENSIONS,
					'allowed_dimensions' => 'vertical',
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .aux-breadcrumb-home' => 'position:relative; top: {{TOP}}{{UNIT}}; bottom:{{BOTTOM}}{{UNIT}};'
					]
				]
			);

			$this->add_responsive_control(
				'home_icon_margin',
				[
					'label' => __( 'Margin', 'auxin-elements' ),
					'type' => Controls_Manager::DIMENSIONS,
					'allowed_dimensions' => 'horizontal',
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .aux-breadcrumb-home' => 'margin: auto {{RIGHT}}{{UNIT}} auto {{LEFT}}{{UNIT}};'
					]
				]
			);

			$this->add_control(
				'home_icon_color',
				[
					'label' => __( 'Icon Color', 'auxin-elements' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} span.aux-breadcrumb-home' => 'color: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'home_icon_size',
				[
					'label' => __( 'Size', 'auxin-elements' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .aux-breadcrumb-home' => 'font-size:{{SIZE}}{{UNIT}};'
					]
				]
			);

			$this->end_controls_section();
		}

		/*--------------------------------------------------------------------*/
        /*  Text style
		/*--------------------------------------------------------------------*/

		$this->start_controls_section(
			'text_style_section',
			[
				'label' => __( 'Text', 'auxin-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

    	$this->add_control(
			'link_color',
			[
				'label' => __( 'Link Color', 'auxin-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} span:not(.aux-breadcrumb-sep) a' => 'color: {{VALUE}};',
				],
			]
    	);

    	$this->add_control(
			'link_hover_color',
			[
				'label' => __( 'Link Hover Color', 'auxin-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} span:not(.aux-breadcrumb-sep) a:hover' => 'color: {{VALUE}};',
				],
			]
    	);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'auxin-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} span:not(.aux-breadcrumb-sep)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} span'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} span'
			]
    	);

    	$this->end_controls_section();

		/*--------------------------------------------------------------------*/
        /*  Separator
		/*--------------------------------------------------------------------*/

		if ( ! $yoast_breadcrumbs_enabled ) {

			$this->start_controls_section(
				'separator_style_section',
				[
					'label' => __( 'Separator', 'auxin-elements' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_responsive_control(
				'separator_margin',
				[
					'label' => __( 'Margin', 'auxin-elements' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .aux-breadcrumb-sep' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					]
				]
			);

			$this->add_control(
				'separator_icon_color',
				[
					'label' => __( 'Icon Color', 'auxin-elements' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} span.aux-breadcrumb-sep' => 'color: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'separator_size',
				[
					'label' => __( 'Size', 'auxin-elements' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .aux-breadcrumb-sep' => 'font-size:{{SIZE}}{{UNIT}};'
					]
				]
			);

			$this->end_controls_section();
		}

		/*--------------------------------------------------------------------*/
        /*  Container
		/*--------------------------------------------------------------------*/

		$this->start_controls_section(
			'container_style_section',
			[
				'label' => __( 'Container', 'auxin-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
            'container_alignment',
            [
                'label'       => __('Alignment','auxin-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'left' => [
                        'title' => __( 'Left', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-left'
					],
                    'center' => [
                        'title' => __( 'Center', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-center'
					],
                    'right' => [
                        'title' => __( 'Right', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-right'
					]
				],
                'default'     => '',
                'separator'   => 'after',
                'toggle'      => true,
                'selectors'   => [
                    '{{WRAPPER}} .aux-elementor-breadcrumbs' => 'text-align:{{VALUE}};'
				]
			]
        );

    	$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .aux-breadcrumbs'
			]
    	);

		$this->add_responsive_control(
			'container_border_radius',
			[
				'label' => __( 'Border Radius', 'auxin-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} p.aux-breadcrumbs' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => __( 'Padding', 'auxin-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} p.aux-breadcrumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'after'
			]
		);

		$this->start_controls_tabs( 'container_style_tabs' );

		$this->start_controls_tab(
			'container_tab_normal_state',
			[
				'label' => __( 'Normal', 'auxin-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'selector' => '{{WRAPPER}} p.aux-breadcrumbs',
				'types' => [ 'classic', 'gradient']
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} p.aux-breadcrumbs'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'container_tab_hover_state',
			[
				'label' => __( 'Hover', 'auxin-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background_hover',
				'selector' => '{{WRAPPER}} p.aux-breadcrumbs:hover',
				'types' => [ 'classic', 'gradient']
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow_hover',
				'selector' => '{{WRAPPER}} p.aux-breadcrumbs:hover'
			]
		);

		$this->add_control(
			'container_transition',
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
					'{{WRAPPER}} p.aux-breadcrumbs' => "transition:all ease-out {{SIZE}}s;"
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
		$settings = $this->get_settings_for_display();

		$home_icon = isset( $settings['show_home_icon'] ) && auxin_is_true( $settings['show_home_icon'] ) ? $settings['home_icon']['value'] : '';
		$separator_icon = isset( $settings['separator_icon']['value'] ) ? $settings['separator_icon']['value'] : '';

		echo '<div class="aux-elementor-breadcrumbs">';
		auxin_the_breadcrumbs( $home_icon, $separator_icon );
		echo '</div>';
  	}

}
