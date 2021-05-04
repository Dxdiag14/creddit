<?php
/**
 * Primary menu dropdown item options.
 *
 * @package    ThemeGrill
 * @subpackage Zakra
 * @since      Zakra 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== MENU > PRIMARY MENU: DROPDOWN ITEM ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Primary_Menu_Dropdown_Item_Option' ) ) :

	/**
	 * Header button customizer options.
	 */
	class Zakra_Customize_Primary_Menu_Dropdown_Item_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

				$elements = array(

					'zakra_typography_primary_menu_dropdown_item_heading' => array(
						'setting' => array(),
						'control' => array(
							'type'            => 'heading',
							'label'           => esc_html__( 'Typography', 'zakra' ),
							'section'         => 'zakra_primary_menu_dropdown_item',
							'priority'        => 60,
							'active_callback' => array(
								array(
									'setting'  => 'zakra_primary_menu_disabled',
									'operator' => '===',
									'value'    => false,
								),
							),
						),
					),

					'zakra_typography_primary_menu_dropdown_item' => array(
						'output'  => array(
							array(
								'selector' => '.tg-primary-menu > div ul li ul li a',
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
							'priority' => 70,
							'label'    => esc_html__( 'Dropdown', 'zakra' ),
							'section'  => 'zakra_primary_menu_dropdown_item',
						),
					),
				);

				if ( ! zakra_is_zakra_pro_active() ) {
					$elements['zakra_primary_menu_drop_down_item_upgrade'] = array(
						'setting' => array(
							'default' => '',
						),
						'control' => array(
							'type'        => 'upgrade',
							'priority'    => 80,
							'label'       => esc_html__( 'Learn more', 'zakra' ),
							'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
							'section'     => 'zakra_primary_menu_dropdown_item',
						),
					);
				}

				return $elements;

		}

	}

	new Zakra_Customize_Primary_Menu_Dropdown_Item_Option();

endif;
