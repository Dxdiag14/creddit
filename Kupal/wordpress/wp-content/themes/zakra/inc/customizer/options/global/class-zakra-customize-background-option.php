<?php
/**
 * Background options.
 *
 * @package     zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== BACKGROUND ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Background_Option' ) ) :

	/**
	 * General option.
	 */
	class Zakra_Customize_Background_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			return array(
				'zakra_inside_container_background_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'priority' => 5,
						'label'    => esc_html__( 'Inside Container', 'zakra' ),
						'section'  => 'zakra_background',
					),
				),

				'zakra_inside_container_background' => array(
					'output'  => array(
						array(
							'selector' => '#main',
						),
					),
					'setting' => array(
						'default' => array(
							'background-color'      => '#ffffff',
							'background-image'      => '',
							'background-position'   => 'center center',
							'background-size'       => 'auto',
							'background-attachment' => 'scroll',
							'background-repeat'     => 'repeat',
						),
					),
					'control' => array(
						'type'     => 'background',
						'section'  => 'zakra_background',
						'priority' => 10,
					),
				),

				'zakra_outside_container_background_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'priority' => 15,
						'label'    => esc_html__( 'Outside Container', 'zakra' ),
						'section'  => 'zakra_background',
					),
				),

			);

		}

	}

	new Zakra_Customize_Background_Option();

endif;
