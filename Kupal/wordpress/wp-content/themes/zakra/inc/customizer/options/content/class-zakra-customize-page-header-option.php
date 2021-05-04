<?php
/**
 * Page header options
 *
 * @package     zakra
 */

defined( 'ABSPATH' ) || exit;

/*========================================== CONTENT > PAGE HEADER ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Blog_General_Option' ) ) :

	/**
	 * Archive/Blog option.
	 */
	class Zakra_Customize_Blog_General_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_page_title_heading'             => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Page Title', 'zakra' ),
						'section'  => 'zakra_page_header',
						'priority' => 5,
					),
				),

				'zakra_page_title_enabled'             => array(
					'setting' => array(
						'default'           => 'page-header',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'radio',
						'priority'        => 10,
						'is_default_type' => true,
						'label'           => esc_html__( 'Position', 'zakra' ),
						'section'         => 'zakra_page_header',
						'choices'         => array(
							'page-header'  => esc_html__( 'Page Header', 'zakra' ),
							'content-area' => esc_html__( 'Content Area', 'zakra' ),
						),
					),
				),

				'zakra_page_title_markup'              => array(
					'setting' => array(
						'default'           => 'h1',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'            => 'select',
						'priority'        => 15,
						'is_default_type' => true,
						'label'           => esc_html__( 'Markup', 'zakra' ),
						'section'         => 'zakra_page_header',
						'choices'         => array(
							'h1'   => esc_html__( 'Heading 1', 'zakra' ),
							'h2'   => esc_html__( 'Heading 2', 'zakra' ),
							'h3'   => esc_html__( 'Heading 3', 'zakra' ),
							'h4'   => esc_html__( 'Heading 4', 'zakra' ),
							'h5'   => esc_html__( 'Heading 5', 'zakra' ),
							'h6'   => esc_html__( 'Heading 6', 'zakra' ),
							'span' => esc_html__( 'Span', 'zakra' ),
							'p'    => esc_html__( 'Paragraph', 'zakra' ),
							'div'  => esc_html__( 'Div', 'zakra' ),
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_page_title_enabled',
								'operator' => '==',
								'value'    => 'page-header',
							),
						),
					),
				),

				'zakra_page_title_alignment'           => array(
					'setting' => array(
						'default'           => 'tg-page-header--left-right',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'     => 'radio_image',
						'priority' => 20,
						'label'    => esc_html__( 'Alignment', 'zakra' ),
						'section'  => 'zakra_page_header',
						'choices'  => array(
							'tg-page-header--left-right'  => ZAKRA_PARENT_INC_ICON_URI . '/breadcrumb-right.png',
							'tg-page-header--right-left'  => ZAKRA_PARENT_INC_ICON_URI . '/breadcrumb-left.png',
							'tg-page-header--both-center' => ZAKRA_PARENT_INC_ICON_URI . '/breadcrumb-center.png',
							'tg-page-header--both-left'   => ZAKRA_PARENT_INC_ICON_URI . '/both-on-left.png',
							'tg-page-header--both-right'  => ZAKRA_PARENT_INC_ICON_URI . '/both-on-right.png',
						),
					),
				),

				'zakra_breadcrumbs_heading'            => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'priority' => 30,
						'label'    => esc_html__( 'Breadcrumbs', 'zakra' ),
						'section'  => 'zakra_page_header',
					),
				),

				'zakra_breadcrumbs_enabled'            => array(
					'setting' => array(
						'default'           => true,
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_checkbox' ),
					),
					'control' => array(
						'type'     => 'toggle',
						'priority' => 35,
						'label'    => esc_html__( 'Enable Breadcrumbs', 'zakra' ),
						'section'  => 'zakra_page_header',
					),
				),

				'zakra_breadcrumbs_font_size'          => array(
					'output'  => array(
						array(
							'selector' => apply_filters( 'zakra_breadcrumbs_font_size_selector', '.tg-page-header .breadcrumb-trail ul li' ),
							'property' => 'font-size',
						),
					),
					'setting' => array(
						'default'           => array(
							'slider' => 16,
							'suffix' => 'px',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_slider' ),
					),
					'control' => array(
						'type'            => 'slider',
						'priority'        => 55,
						'label'           => esc_html__( 'Font Size', 'zakra' ),
						'section'         => 'zakra_page_header',
						'input_attrs'     => array(
							'min'  => 8,
							'max'  => 26,
							'step' => 1,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_breadcrumbs_enabled',
								'operator' => '===',
								'value'    => true,
							),
						),
					),
				),

				'zakra_page_title_dimensions_heading'  => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Dimensions', 'zakra' ),
						'section'  => 'zakra_page_header',
						'priority' => 65,
					),
				),

				'zakra_page_title_padding'             => array(
					'output'  => array(
						array(
							'selector' => '.tg-page-header',
							'property' => 'padding',
						),
					),
					'setting' => array(
						'default'           => array(
							'top'    => '20px',
							'right'  => '0px',
							'bottom' => '20px',
							'left'   => '0px',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_dimensions' ),
					),
					'control' => array(
						'type'        => 'dimensions',
						'priority'    => 70,
						'label'       => esc_html__( 'Padding', 'zakra' ),
						'section'     => 'zakra_page_header',
						'input_attrs' => array(
							'min'  => 0,
							'step' => 1,
						),
					),
				),

				'zakra_breadcrumbs_colors_heading'     => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'COLORS', 'zakra' ),
						'section'  => 'zakra_page_header',
						'priority' => 75,
					),
				),

				'zakra_page_title_bg'                  => array(
					'output'  => array(
						array(
							'selector' => '.tg-page-header, .tg-container--separate .tg-page-header',
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
						'priority' => 80,
						'label'    => esc_html__( 'Background', 'zakra' ),
						'section'  => 'zakra_page_header',
					),
				),

				'zakra_breadcrumbs_text_color'         => array(
					'output'  => array(
						array(
							'selector' => apply_filters( 'zakra_breadcrumbs_text_color_selector', '.tg-page-header .breadcrumb-trail ul li' ),
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#16181a',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 80,
						'label'           => esc_html__( 'Text Color', 'zakra' ),
						'section'         => 'zakra_page_header',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_breadcrumbs_enabled',
								'operator' => '===',
								'value'    => true,
							),
						),
					),
				),

				'zakra_breadcrumbs_seperator_color'    => array(
					'output'  => array(
						array(
							'selector' => apply_filters( 'zakra_breadcrumbs_separator_color_selector', '.tg-page-header .breadcrumb-trail ul li::after' ),
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#51585f',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 85,
						'label'           => esc_html__( 'Separator Color', 'zakra' ),
						'section'         => 'zakra_page_header',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_breadcrumbs_enabled',
								'operator' => '===',
								'value'    => true,
							),
						),
					),
				),

				'zakra_breadcrumbs_link_color'         => array(
					'output'  => array(
						array(
							'selector' => apply_filters( 'zakra_breadcrumbs_link_color_selector', '.tg-page-header .breadcrumb-trail ul li a' ),
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#16181a',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 90,
						'label'           => esc_html__( 'Link Color', 'zakra' ),
						'section'         => 'zakra_page_header',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_breadcrumbs_enabled',
								'operator' => '===',
								'value'    => true,
							),
						),
					),
				),

				'zakra_breadcrumbs_link_hover_color'   => array(
					'output'  => array(
						array(
							'selector' => apply_filters( 'zakra_breadcrumbs_link_hover_color_selector', '.tg-page-header .breadcrumb-trail ul li a:hover ' ),
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#269bd1',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'            => 'color',
						'priority'        => 95,
						'label'           => esc_html__( 'Link Hover Color', 'zakra' ),
						'section'         => 'zakra_page_header',
						'choices'         => array(
							'alpha' => true,
						),
						'active_callback' => array(
							array(
								'setting'  => 'zakra_breadcrumbs_enabled',
								'operator' => '===',
								'value'    => true,
							),
						),
					),
				),

				'zakra_typography_page_header_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Typography', 'zakra' ),
						'section'  => 'zakra_page_header',
						'priority' => 100,
					),
				),

				'zakra_typography_post_page_title'     => array(
					'output'  => array(
						array(
							'selector' => '.tg-page-header .tg-page-header__title, .tg-page-content__title',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_post_page_title_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '500',
								'font-size'   => '18px',
								'line-height' => '1.3',
								'color'       => '#16181a',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 105,
						'label'    => esc_html__( 'Post/Page Title', 'zakra' ),
						'section'  => 'zakra_page_header',
					),
				),

				'zakra_typography_blog_post_title'     => array(
					'output'  => array(
						array(
							'selector' => apply_filters( 'zakra_typography_blog_post_title_selector', '.entry-title:not(.tg-page-content__title)' ),
						),
					),
					'setting' => array(
						'default'           => array(
							'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
							'variant'     => '500',
							'font-size'   => '2.25rem',
							'line-height' => '1.3',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 110,
						'label'    => esc_html__( 'Blog/Archive Post Title', 'zakra' ),
						'section'  => 'zakra_page_header',
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_blog_general_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 120,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_page_header',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Blog_General_Option();

endif;
