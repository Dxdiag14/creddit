<?php
/**
 * Primary menu item.
 *
 * @package zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== MENU > PRIMARY MENU ITEM ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Primary_Menu_Item_Option' ) ) :

	/**
	 * Primary Menu option.
	 */
	class Zakra_Customize_Primary_Menu_Item_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_primary_menu_text_active_effect'  => array(
					'setting' => array(
						'default'           => 'tg-primary-menu--style-underline',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'select',
						'label'           => esc_html__( 'Active Menu Item Style', 'zakra' ),
						'priority'        => 20,
						'is_default_type' => true,
						'section'         => 'zakra_primary_menu_item',
						'choices'         => array(
							'tg-primary-menu--style-none' => esc_html__( 'None', 'zakra' ),
							'tg-primary-menu--style-underline' => esc_html__( 'Underline Border', 'zakra' ),
							'tg-primary-menu--style-left-border' => esc_html__( 'Left Border', 'zakra' ),
							'tg-primary-menu--style-right-border' => esc_html__( 'Right Border', 'zakra' ),
						),
						'active_callback' => apply_filters(
							'zakra_primary_menu_item_style_cb',
							array(
								array(
									'setting'  => 'zakra_primary_menu_disabled',
									'operator' => '===',
									'value'    => false,
								),
							)
						),
					),
				),

				'zakra_primary_menu_item_colors_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Colors', 'zakra' ),
						'section'  => 'zakra_primary_menu_item',
						'priority' => 50,
					),
				),

				'zakra_primary_menu_text_color'          => array(
					'output'  => array(
						array(
							'selector' => '.tg-primary-menu > div > ul li:not(.tg-header-button-wrap) a',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 55,
						'label'           => esc_html__( 'Menu Item Color', 'zakra' ),
						'section'         => 'zakra_primary_menu_item',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => apply_filters(
							'zakra_primary_menu_item_style_cb',
							array(
								array(
									'setting'  => 'zakra_primary_menu_disabled',
									'operator' => '===',
									'value'    => false,
								),
							)
						),
					),
				),

				'zakra_primary_menu_text_hover_color'    => array(
					'output'  => array(
						array(
							'selector' => '.tg-primary-menu > div > ul li:not(.tg-header-button-wrap):hover > a',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 60,
						'label'           => esc_html__( 'Menu Item Hover Color', 'zakra' ),
						'section'         => 'zakra_primary_menu_item',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => apply_filters(
							'zakra_primary_menu_item_style_cb',
							array(
								array(
									'setting'  => 'zakra_primary_menu_disabled',
									'operator' => '===',
									'value'    => false,
								),
							)
						),
					),
				),

				'zakra_primary_menu_text_active_color'   => array(
					'output'  => array(
						array(
							'selector' => '.tg-primary-menu > div ul li:active > a, .tg-primary-menu > div ul > li:not(.tg-header-button-wrap).current_page_item > a, .tg-primary-menu > div ul > li:not(.tg-header-button-wrap).current_page_ancestor > a, .tg-primary-menu > div ul > li:not(.tg-header-button-wrap).current-menu-item > a, .tg-primary-menu > div ul > li:not(.tg-header-button-wrap).current-menu-ancestor > a',
							'property' => 'color',
						),
						array(
							'selector' => '.tg-primary-menu.tg-primary-menu--style-underline > div ul > li:not(.tg-header-button-wrap).current_page_item > a::before, .tg-primary-menu.tg-primary-menu--style-underline > div ul > li:not(.tg-header-button-wrap).current_page_ancestor > a::before, .tg-primary-menu.tg-primary-menu--style-underline > div ul > li:not(.tg-header-button-wrap).current-menu-item > a::before, .tg-primary-menu.tg-primary-menu--style-underline > div ul > li:not(.tg-header-button-wrap).current-menu-ancestor > a::before, .tg-primary-menu.tg-primary-menu--style-left-border > div ul > li:not(.tg-header-button-wrap).current_page_item > a::before, .tg-primary-menu.tg-primary-menu--style-left-border > div ul > li:not(.tg-header-button-wrap).current_page_ancestor > a::before, .tg-primary-menu.tg-primary-menu--style-left-border > div ul > li:not(.tg-header-button-wrap).current-menu-item > a::before, .tg-primary-menu.tg-primary-menu--style-left-border > div ul > li:not(.tg-header-button-wrap).current-menu-ancestor > a::before, .tg-primary-menu.tg-primary-menu--style-right-border > div ul > li:not(.tg-header-button-wrap).current_page_item > a::before, .tg-primary-menu.tg-primary-menu--style-right-border > div ul > li:not(.tg-header-button-wrap).current_page_ancestor > a::before, .tg-primary-menu.tg-primary-menu--style-right-border > div ul > li:not(.tg-header-button-wrap).current-menu-item > a::before, .tg-primary-menu.tg-primary-menu--style-right-border > div ul > li:not(.tg-header-button-wrap).current-menu-ancestor > a::before',
							'property' => 'background-color',
						),
					),
					'setting' => array(
						'default'           => '',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 65,
						'label'           => esc_html__( 'Menu Item Active Color', 'zakra' ),
						'section'         => 'zakra_primary_menu_item',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => apply_filters(
							'zakra_primary_menu_item_style_cb',
							array(
								array(
									'setting'  => 'zakra_primary_menu_disabled',
									'operator' => '===',
									'value'    => false,
								),
							)
						),
					),
				),

				'zakra_typography_primary_menu_heading'  => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Typography', 'zakra' ),
						'section'  => 'zakra_primary_menu_item',
						'priority' => 115,
					),
				),

				'zakra_typography_primary_menu'          => array(
					'output'  => array(
						array(
							'selector' => '.tg-primary-menu > div ul li a',
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
						'label'    => esc_html__( 'Primary Menu', 'zakra' ),
						'priority' => 120,
						'section'  => 'zakra_primary_menu_item',
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_primary_menu_item_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 125,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_primary_menu_item',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Primary_Menu_Item_Option();

endif;
