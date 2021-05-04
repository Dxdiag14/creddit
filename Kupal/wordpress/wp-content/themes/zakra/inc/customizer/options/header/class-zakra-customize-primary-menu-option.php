<?php
/**
 * Primary menu.
 *
 * @package zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== MENU > PRIMARY MENU ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Primary_Menu_Option' ) ) :

	/**
	 * Primary Menu option.
	 */
	class Zakra_Customize_Primary_Menu_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_primary_menu_disabled'            => array(
					'setting' => array(
						'default'           => false,
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_checkbox' ),
					),
					'control' => array(
						'type'     => 'toggle',
						'priority' => 10,
						'label'    => esc_html__( 'Disable Primary Menu', 'zakra' ),
						'section'  => 'zakra_primary_menu',
					),
				),

				'zakra_primary_menu_extra'               => array(
					'setting' => array(
						'default'           => false,
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_checkbox' ),
					),
					'control' => array(
						'type'            => 'toggle',
						'priority'        => 20,
						'label'           => esc_html__( 'Keep Menu Items on One Line', 'zakra' ),
						'section'         => 'zakra_primary_menu',
						'active_callback' => array(
							array(
								'setting'  => 'zakra_primary_menu_disabled',
								'operator' => '===',
								'value'    => false,
							),
						),
					),
				),

				'zakra_primary_menu_border_heading'      => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Border Bottom', 'zakra' ),
						'section'  => 'zakra_primary_menu',
						'priority' => 80,
					),
				),

				'zakra_primary_menu_border_bottom_width' => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-header .main-navigation',
							'property' => 'border-bottom-width',
						),
					),
					'setting' => array(
						'default'           => array(
							'slider' => 0,
							'suffix' => 'px',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_slider' ),
					),
					'control' => array(
						'type'            => 'slider',
						'priority'        => 90,
						'label'           => esc_html__( 'Size', 'zakra' ),
						'section'         => 'zakra_primary_menu',
						'input_attrs'     => array(
							'min'  => 0,
							'max'  => 20,
							'step' => 1,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_primary_menu_disabled',
								'operator' => '===',
								'value'    => false,
							),
						),
					),
				),

				'zakra_primary_menu_border_bottom_color' => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-header .main-navigation',
							'property' => 'border-bottom-color',
						),
					),
					'setting' => array(
						'default'           => '#e9ecef',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 100,
						'label'           => esc_html__( 'Color', 'zakra' ),
						'section'         => 'zakra_primary_menu',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_primary_menu_disabled',
								'operator' => '===',
								'value'    => false,
							),
						),
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_primary_menu_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 110,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_primary_menu',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Primary_Menu_Option();

endif;
