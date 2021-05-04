<?php
/**
 * Footer bottom bar options.
 *
 * @package     zakra
 */

defined( 'ABSPATH' ) || exit;

/*========================================== FOOTER > FOOTER  BAR ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Footer_Bottom_Bar_Option' ) ) :

	/**
	 * Footer option.
	 */
	class Zakra_Customize_Footer_Bottom_Bar_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_footer_bar_style'                 => array(
					'setting' => array(
						'default'           => 'tg-site-footer-bar--center',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'radio_image',
						'priority'        => 10,
						'label'           => esc_html__( 'Style', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => apply_filters(
							'zakra_footer_bar_style_choices',
							array(
								'tg-site-footer-bar--left' => ZAKRA_PARENT_INC_ICON_URI . '/footer-left.png',
								'tg-site-footer-bar--center' => ZAKRA_PARENT_INC_ICON_URI . '/footer-center.png',
							)
						),
						'active_callback' => apply_filters( 'zakra_footer_bar_style_cb', false ),
					),
				),

				'zakra_footer_bar_left_content_heading'  => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Left Content', 'zakra' ),
						'section'  => 'zakra_footer_bottom_bar',
						'priority' => 20,
					),
				),

				'zakra_footer_bar_section_one'           => array(
					'setting' => array(
						'default'           => 'text_html',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'select',
						'priority'        => 25,
						'is_default_type' => true,
						'label'           => esc_html__( 'Left Content', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => apply_filters(
							'zakra_footer_bar_section_one_choices',
							array(
								'none'      => esc_html__( 'None', 'zakra' ),
								'text_html' => esc_html__( 'Text/HTML', 'zakra' ),
								'menu'      => esc_html__( 'Menu', 'zakra' ),
								'widget'    => esc_html__( 'Widget', 'zakra' ),
							)
						),
						'active_callback' => apply_filters( 'zakra_footer_bar_section_one_cb', false ),
					),
				),

				'zakra_footer_bar_section_one_html'      => array(
					'setting' => array(
						'default'           => sprintf(
							/* translators: 1: Current Year, 2: Site Name, 3: Theme Link, 4: WordPress Link. */
							esc_html__( 'Copyright &copy; %1$s %2$s. Powered by %3$s and %4$s.', 'zakra' ),
							'{the-year}',
							'{site-link}',
							'{theme-link}',
							'{wp-link}'
						),
						'sanitize_callback' => 'wp_kses_post',
					),
					'control' => array(
						'type'            => 'editor',
						'priority'        => 30,
						'label'           => esc_html__( 'Text/HTML for Left Content', 'zakra' ),
						'description'     => wp_kses(
							'<a href="' . esc_url( 'https://docs.zakratheme.com/en/article/dynamic-strings-for-footer-copyright-content-13empt5/' ) . '" target="_blank">' . esc_html__( 'Docs Link', 'zakra' ) . '</a>',
							array(
								'a' => array(
									'href'   => true,
									'target' => true,
								),
							)
						),
						'section'         => 'zakra_footer_bottom_bar',
						'active_callback' => apply_filters(
							'zakra_footer_bar_section_one_html_cb',
							array(
								array(
									'setting'  => 'zakra_footer_bar_section_one',
									'operator' => '==',
									'value'    => 'text_html',
								),
							)
						),
					),
				),

				'zakra_footer_bar_section_one_menu'      => array(
					'setting' => array(
						'default'           => 'none',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'select',
						'priority'        => 35,
						'is_default_type' => true,
						'label'           => esc_html__( 'Select a Menu for Left Content', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => $this->get_menu_options(),
						'active_callback' => apply_filters(
							'zakra_footer_bar_section_one_menu_cb',
							array(
								array(
									'setting'  => 'zakra_footer_bar_section_one',
									'operator' => '==',
									'value'    => 'menu',
								),
							)
						),
					),
				),

				'zakra_footer_bar_right_content_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Right Content', 'zakra' ),
						'section'  => 'zakra_footer_bottom_bar',
						'priority' => 40,
					),
				),

				'zakra_footer_bar_section_two'           => array(
					'setting' => array(
						'default'           => 'none',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'select',
						'priority'        => 45,
						'is_default_type' => true,
						'label'           => esc_html__( 'Right Content', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => apply_filters(
							'zakra_footer_bar_section_two_choices',
							array(
								'none'      => esc_html__( 'None', 'zakra' ),
								'text_html' => esc_html__( 'Text/HTML', 'zakra' ),
								'menu'      => esc_html__( 'Menu', 'zakra' ),
								'widget'    => esc_html__( 'Widget', 'zakra' ),
							)
						),
						'active_callback' => apply_filters( 'zakra_footer_bar_section_two_cb', false ),
					),
				),

				'zakra_footer_bar_section_two_html'      => array(
					'setting' => array(
						'default'           => '',
						'sanitize_callback' => 'wp_kses_post',
					),
					'control' => array(
						'type'            => 'editor',
						'priority'        => 50,
						'label'           => esc_html__( 'Text/HTML for Right Content', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'active_callback' => apply_filters(
							'zakra_footer_bar_section_two_html_cb',
							array(
								array(
									'setting'  => 'zakra_footer_bar_section_two',
									'operator' => '==',
									'value'    => 'text_html',
								),
							)
						),
					),
				),

				'zakra_footer_bar_section_two_menu'      => array(
					'setting' => array(
						'default'           => 'none',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'select',
						'priority'        => 55,
						'is_default_type' => true,
						'label'           => esc_html__( 'Select a Menu for Right Content', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => $this->get_menu_options(),
						'active_callback' => apply_filters(
							'zakra_footer_bar_section_two_menu_cb',
							array(
								array(
									'setting'  => 'zakra_footer_bar_section_two',
									'operator' => '==',
									'value'    => 'menu',
								),
							)
						),
					),
				),

				'zakra_footer_bar_colors_heading'        => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Colors', 'zakra' ),
						'section'  => 'zakra_footer_bottom_bar',
						'priority' => 70,
					),
				),

				'zakra_footer_bar_bg'                    => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-footer .tg-site-footer-bar',
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
						'type'            => 'background',
						'priority'        => 75,
						'label'           => esc_html__( 'Background', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'active_callback' => apply_filters( 'zakra_footer_bar_bg_cb', false ),
					),
				),

				'zakra_footer_bar_text_color'            => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-footer .tg-site-footer-bar',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#51585f',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 80,
						'label'           => esc_html__( 'Text Color', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => apply_filters( 'zakra_footer_bar_text_color_cb', false ),
					),
				),

				'zakra_footer_bar_link_color'            => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-footer .tg-site-footer-bar a',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#16181a',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 85,
						'label'           => esc_html__( 'Link Color', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => apply_filters( 'zakra_footer_bar_link_color_cb', false ),
					),
				),

				'zakra_footer_bar_link_hover_color'      => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-footer .tg-site-footer-bar a:hover, .tg-site-footer .tg-site-footer-bar a:focus',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#269bd1',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 90,
						'label'           => esc_html__( 'Link Hover Color', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => apply_filters( 'zakra_footer_bar_link_hover_color_cb', false ),
					),
				),

				'zakra_footer_bar_border_heading'        => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Border Top', 'zakra' ),
						'section'  => 'zakra_footer_bottom_bar',
						'priority' => 120,
					),
				),

				'zakra_footer_bar_border_top_width'      => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-footer .tg-site-footer-bar',
							'property' => 'border-top-width',
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
						'priority'        => 125,
						'label'           => esc_html__( 'Size', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'input_attrs'     => array(
							'min'  => 0,
							'max'  => 20,
							'step' => 1,
						),
						'active_callback' => apply_filters( 'zakra_footer_bar_border_top_width_cb', false ),
					),
				),

				'zakra_footer_bar_border_top_color'      => array(
					'output'  => array(
						array(
							'selector' => '.tg-site-footer .tg-site-footer-bar',
							'property' => 'border-top-color',
						),
					),
					'setting' => array(
						'default'           => '#e9ecef',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 130,
						'label'           => esc_html__( 'Color', 'zakra' ),
						'section'         => 'zakra_footer_bottom_bar',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => apply_filters( 'zakra_footer_bar_border_top_color_cb', false ),
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_footer_bottom_bar_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 135,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_footer_bottom_bar',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Footer_Bottom_Bar_Option();

endif;
