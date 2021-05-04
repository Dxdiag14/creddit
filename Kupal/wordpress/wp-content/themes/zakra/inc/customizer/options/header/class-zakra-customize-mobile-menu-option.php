<?php
/**
 * Mobile Menu Options.
 *
 * @package zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== MENU > MOBILE MENU ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Mobile_Menu_Option' ) ) :

	/**
	 * Header button customizer options.
	 */
	class Zakra_Customize_Mobile_Menu_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_typography_mobile_menu_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'priority' => 200,
						'label'    => esc_html__( 'Typography', 'zakra' ),
						'section'  => 'zakra_mobile_menu',
					),
				),

				'zakra_typography_mobile_menu'         => array(
					'output'  => array(
						array(
							'selector' => '.tg-mobile-navigation a',
						),
					),
					'setting' => array(
						'default'           => array(
							'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
							'variant'     => '400',
							'font-size'   => '1rem',
							'line-height' => '1.8',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 205,
						'label'    => esc_html__( 'Mobile Menu', 'zakra' ),
						'section'  => 'zakra_mobile_menu',
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_mobile_menu_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 210,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_mobile_menu',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Mobile_Menu_Option();

endif;
