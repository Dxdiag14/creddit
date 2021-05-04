<?php
/**
 * Header main options.
 *
 * @package zakra
 */

defined( 'ABSPATH' ) || exit;

/*========================================== HEADER > HEADER MAIN ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Header_Main_Option' ) ) :

	/**
	 * Header main customizer options.
	 */
	class Zakra_Customize_Header_Main_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_header_main_style'                 => array(
					'setting' => array(
						'default'           => 'tg-site-header--left',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'radio_image',
						'priority'        => 20,
						'label'           => esc_html__( 'Style', 'zakra' ),
						'section'         => 'zakra_header_main',
						'choices'         => apply_filters(
							'zakra_header_main_style_choices',
							array(
								'tg-site-header--left'   => ZAKRA_PARENT_INC_ICON_URI . '/header-left.png',
								'tg-site-header--center' => ZAKRA_PARENT_INC_ICON_URI . '/header-center.png',
								'tg-site-header--right'  => ZAKRA_PARENT_INC_ICON_URI . '/header-right.png',
							)
						),
						'active_callback' => apply_filters(
							'zakra_header_main_style_cb',
							array()
						),
					),
				),

				'zakra_search_heading'                    => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'priority' => 60,
						'label'    => esc_html__( 'Search', 'zakra' ),
						'section'  => 'zakra_header_main',
					),
				),

				'tg_header_menu_search_enabled'           => array(
					'setting' => array(
						'default'           => true,
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_checkbox' ),
					),
					'control' => array(
						'type'     => 'toggle',
						'priority' => 65,
						'label'    => esc_html__( 'Enable Search Icon', 'zakra' ),
						'section'  => 'zakra_header_main',
					),
				),

				'zakra_header_main_colors_heading'        => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Colors', 'zakra' ),
						'section'  => 'zakra_header_main',
						'priority' => 105,
					),
				),

				'zakra_header_main_bg'                    => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-header, .tg-container--separate .tg-site-header',
						),
					),
					'setting' => array(
						'default'           => array(
							'background-color'      => '#ffffff',
							'background-image'      => '',
							'background-repeat'     => 'repeat',
							'background-position'   => 'center center',
							'background-size'       => 'contain',
							'background-attachment' => 'scroll',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_background' ),
					),
					'control' => array(
						'type'     => 'background',
						'priority' => 110,
						'section'  => 'zakra_header_main',
					),
				),

				'zakra_header_main_border_bottom_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Border Bottom', 'zakra' ),
						'section'  => 'zakra_header_main',
						'priority' => 115,
					),
				),

				'zakra_header_main_border_bottom_width'   => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-header',
							'property' => 'border-bottom-width',
						),
					),
					'setting' => array(
						'default'           => array(
							'slider' => 1,
							'suffix' => 'px',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_slider' ),
					),
					'control' => array(
						'type'        => 'slider',
						'priority'    => 120,
						'label'       => esc_html__( 'Size', 'zakra' ),
						'section'     => 'zakra_header_main',
						'input_attrs' => array(
							'min'  => 0,
							'max'  => 20,
							'step' => 1,
						),
					),
				),

				'zakra_header_main_border_bottom_color'   => array(
					'output'  => array(
						array(
							'selector' => '.tg-site .tg-site-header',
							'property' => 'border-bottom-color',
						),
					),
					'setting' => array(
						'default'           => '#e9ecef',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'     => 'color',
						'priority' => 125,
						'label'    => esc_html__( 'Color', 'zakra' ),
						'section'  => 'zakra_header_main',
						'choices'  => array(
							'alpha' => true,
						),
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_header_main_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 130,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_header_main',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Header_Main_Option();

endif;
