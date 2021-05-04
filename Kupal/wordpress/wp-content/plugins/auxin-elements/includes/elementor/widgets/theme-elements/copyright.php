<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements\Theme_Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor 'Copyright' widget.
 *
 * Elementor widget that displays an 'Copyright'.
 *
 * @since 1.0.0
 */
class Copyright extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve 'Copyright' widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'aux_copyright';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'Copyright' widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Copyright', 'auxin-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'Copyright' widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-t-letter auxin-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve 'Copyright' widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_categories() {
		return array( 'auxin-core', 'auxin-theme-elements' );
	}

	/**
	 * Register 'Copyright' widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'auxin-elements' ),
			)
		);

		$this->add_control(
			'copyright_text',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Copyright Text', 'auxin-elements' ),
				'label_block' => true,
				'default'     => sprintf( __( '&copy; %s. All rights reserved.', 'auxin-elements' ), '{{Y}} {{sitename}}' ),
				'separator'   => 'after',
			)
		);

		$this->add_control(
			'attribution',
			array(
				'label'        => __( 'Show Theme Attribution', 'auxin-elements' ),
				'description'  => __( 'Show the "Powered By" text with link to theme homepage in footer.', 'auxin-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'auxin-elements' ),
				'label_off'    => __( 'Off', 'auxin-elements' ),
				'return_value' => 'yes',
				'default' 	   => 'no'
			)
		);

		$this->add_control(
			'show_privacy_policy',
			array(
				'label'        => __( 'Show Privacy Policy', 'auxin-elements' ),
				'description'  => __( 'Show a link to privacy policy page in the footer.', 'auxin-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'auxin-elements' ),
				'label_off'    => __( 'Off', 'auxin-elements' ),
				'return_value' => 'yes',
				'default'      => 'no'
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'auxin-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'auxin-elements' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'auxin-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'auxin-elements' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_copyright',
			array(
				'label' => __( 'Copyright', 'auxin-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'copyright_colors' );

		$this->start_controls_tab(
			'copyright_color_normal',
			array(
				'label' => __( 'Normal', 'auxin-elements' ),
			)
		);

		$this->add_control(
			'copyright_color',
			array(
				'label'     => __( 'Color', 'auxin-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} small' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'copyright_typo',
				'scheme'   => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} small',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'copyright_text_shadow',
				'selector' => '{{WRAPPER}} small',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'copyright_color_hover',
			array(
				'label' => __( 'Hover', 'auxin-elements' ),
			)
		);

		$this->add_control(
			'copyright_hover_color',
			array(
				'label'     => __( 'Color', 'auxin-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} small:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'copyright_typo_hover',
				'scheme'   => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} small:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'copyright_text_shadow_hover',
				'selector' => '{{WRAPPER}} small:hover',
			)
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
		$settings = $this->get_settings_for_display();

		$args = [
			'copyright_text'      => $settings['copyright_text'],
			'attribution'         => $settings['attribution'],
			'show_privacy_policy' => $settings['show_privacy_policy']
		];

		auxin_footer_copyright_markup( true, $args );
	}

}
