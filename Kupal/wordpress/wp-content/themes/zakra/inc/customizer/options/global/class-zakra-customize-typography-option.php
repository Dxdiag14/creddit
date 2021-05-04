<?php
/**
 * Typography.
 * @package     zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== TYPOGRAPHY ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Typography_Option' ) ) :

	/**
	 * Typography option.
	 */
	class Zakra_Customize_Typography_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_base_typography_body'    => array(
					'output'  => array(
						array(
							'selector' => 'body',
						),
					),
					'setting' => array(
						'default'           => array(
							'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
							'variant'     => '400',
							'font-size'   => '15px',
							'line-height' => '1.8',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 10,
						'label'    => esc_html__( 'Body', 'zakra' ),
						'section'  => 'zakra_base_typography',
					),
				),

				'zakra_base_typography_heading' => array(
					'output'  => array(
						array(
							'selector' => 'h1, h2, h3, h4, h5, h6',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_base_typography_heading_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '400',
								'line-height' => '1.3',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 20,
						'label'    => esc_html__( 'Heading', 'zakra' ),
						'section'  => 'zakra_base_typography',
					),
				),

				'zakra_typography_h1'           => array(
					'output'  => array(
						array(
							'selector' => 'h1',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_h1_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '500',
								'font-size'   => '2.5rem',
								'line-height' => '1.3',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 60,
						'label'    => esc_html__( 'Heading 1', 'zakra' ),
						'section'  => 'zakra_headings_typography',
					),
				),

				'zakra_typography_h2'           => array(
					'output'  => array(
						array(
							'selector' => 'h2',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_h2_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '500',
								'font-size'   => '2.25rem',
								'line-height' => '1.3',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 70,
						'label'    => esc_html__( 'Heading 2', 'zakra' ),
						'section'  => 'zakra_headings_typography',
					),
				),

				'zakra_typography_h3'           => array(
					'output'  => array(
						array(
							'selector' => 'h3',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_h3_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '500',
								'font-size'   => '2rem',
								'line-height' => '1.3',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 80,
						'label'    => esc_html__( 'Heading 3', 'zakra' ),
						'section'  => 'zakra_headings_typography',
					),
				),

				'zakra_typography_h4'           => array(
					'output'  => array(
						array(
							'selector' => 'h4',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_h4_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '500',
								'font-size'   => '1.75rem',
								'line-height' => '1.3',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 90,
						'label'    => esc_html__( 'Heading 4', 'zakra' ),
						'section'  => 'zakra_headings_typography',
					),
				),

				'zakra_typography_h5'           => array(
					'output'  => array(
						array(
							'selector' => 'h5',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_h5_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '500',
								'font-size'   => '1.313rem',
								'line-height' => '1.3',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 100,
						'label'    => esc_html__( 'Heading 5', 'zakra' ),
						'section'  => 'zakra_headings_typography',
					),
				),

				'zakra_typography_h6'           => array(
					'output'  => array(
						array(
							'selector' => 'h6',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_h6_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '500',
								'font-size'   => '1.125rem',
								'line-height' => '1.3',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 110,
						'label'    => esc_html__( 'Heading 6', 'zakra' ),
						'section'  => 'zakra_headings_typography',
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_base_typography_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 120,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_base_typography',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Typography_Option();

endif;
