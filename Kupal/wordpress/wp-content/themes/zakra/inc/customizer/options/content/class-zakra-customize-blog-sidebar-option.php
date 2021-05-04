<?php
/**
 * Sidebar options.
 *
 * @package     zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Zakra_Customize_Blog_Sidebar_Option' ) ) :

	/**
	 * Sidebar options.
	 */
	class Zakra_Customize_Blog_Sidebar_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_typography_widget_title_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'label'    => esc_html__( 'Typography', 'zakra' ),
						'priority' => 70,
						'section'  => 'zakra_sidebar',
					),
				),

				'zakra_typography_widget_heading'       => array(
					'output'  => array(
						array(
							'selector' => '.widget .widget-title',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_widget_heading_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '500',
								'font-size'   => '1.2rem',
								'line-height' => '1.3',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 75,
						'label'    => esc_html__( 'Title', 'zakra' ),
						'section'  => 'zakra_sidebar',
					),
				),

				'zakra_typography_widget_content'       => array(
					'output'  => array(
						array(
							'selector' => '.widget',
						),
					),
					'setting' => array(
						'default'           => apply_filters(
							'zakra_typography_widget_content_filter',
							array(
								'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
								'variant'     => '400',
								'font-size'   => '15px',
								'line-height' => '1.8',
							)
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 80,
						'label'    => esc_html__( 'Content', 'zakra' ),
						'section'  => 'zakra_sidebar',
					),
				),
			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_sidebar_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 85,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_sidebar',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Blog_Sidebar_Option();

endif;
