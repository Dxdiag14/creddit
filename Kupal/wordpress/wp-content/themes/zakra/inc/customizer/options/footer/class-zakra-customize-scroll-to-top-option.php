<?php
/**
 * Scroll to top styling.
 *
 * @package     zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== STYLING >  SCROLL TO TOP  ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Scroll_To_Top_Option' ) ) :

	/**
	 * Scroll_To_Top option.
	 */
	class Zakra_Customize_Scroll_To_Top_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_scroll_to_top_enabled'        => array(
					'setting' => array(
						'default'           => true,
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_checkbox' ),
					),
					'control' => array(
						'type'     => 'toggle',
						'priority' => 10,
						'label'    => esc_html__( 'Enable Scroll to Top', 'zakra' ),
						'section'  => 'zakra_footer_scroll_to_top',
						'choices'  => array(
							'alpha' => true,
						),
					),
				),

				'zakra_scroll_to_top_color_heading'  => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Colors', 'zakra' ),
						'section'  => 'zakra_footer_scroll_to_top',
						'priority' => 55,
					),
				),

				'zakra_scroll_to_top_color'          => array(
					'output'  => array(
						array(
							'selector' => '.tg-scroll-to-top',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#ffffff',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 60,
						'label'           => esc_html__( 'Color', 'zakra' ),
						'section'         => 'zakra_footer_scroll_to_top',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_scroll_to_top_enabled',
								'value'    => true,
								'operator' => '==',
							),
						),
					),
				),

				'zakra_scroll_to_top_hover_color'    => array(
					'output'  => array(
						array(
							'selector' => '.tg-scroll-to-top:hover',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#ffffff',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 65,
						'label'           => esc_html__( 'Hover Color', 'zakra' ),
						'section'         => 'zakra_footer_scroll_to_top',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_scroll_to_top_enabled',
								'value'    => true,
								'operator' => '==',
							),
						),
					),
				),

				'zakra_scroll_to_top_bg_color'       => array(
					'output'  => array(
						array(
							'selector' => '.tg-scroll-to-top',
							'property' => 'background-color',
						),
					),
					'setting' => array(
						'default'           => '#16181a',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 70,
						'label'           => esc_html__( 'Background Color', 'zakra' ),
						'section'         => 'zakra_footer_scroll_to_top',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_scroll_to_top_enabled',
								'value'    => true,
								'operator' => '==',
							),
						),
					),
				),

				'zakra_scroll_to_top_bg_hover_color' => array(
					'output'  => array(
						array(
							'selector' => '.tg-scroll-to-top.tg-scroll-to-top--show:hover',
							'property' => 'background-color',
						),
					),
					'setting' => array(
						'default'           => '#1e7ba6',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 75,
						'label'           => esc_html__( 'Background Hover Color', 'zakra' ),
						'section'         => 'zakra_footer_scroll_to_top',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_scroll_to_top_enabled',
								'value'    => true,
								'operator' => '==',
							),
						),
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_footer_scroll_to_top_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 100,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_footer_scroll_to_top',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Scroll_To_Top_Option();

endif;
