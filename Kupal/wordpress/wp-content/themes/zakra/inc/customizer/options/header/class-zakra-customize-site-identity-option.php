<?php
/**
 * Site Identity Options.
 *
 * @package     zakra
 */

defined( 'ABSPATH' ) || exit;

/*========================================== HEADER > SITE IDENTITY ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Site_Identity_Option' ) ) :

	/**
	 * Site Identity customizer options.
	 */
	class Zakra_Customize_Site_Identity_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_site_identity_typography_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'     => 'heading',
						'priority' => 22,
						'label'    => esc_html__( 'Typography', 'zakra' ),
						'section'  => 'title_tagline',
					),
				),

				'zakra_typography_site_title'            => array(
					'output'  => array(
						array(
							'selector' => '.site-branding .site-title',
						),
					),
					'setting' => array(
						'default'           => array(
							'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
							'variant'     => '400',
							'font-size'   => '1.313rem',
							'line-height' => '1.5',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_typography' ),
					),
					'control' => array(
						'type'     => 'typography',
						'priority' => 23,
						'label'    => esc_html__( 'Site Title', 'zakra' ),
						'section'  => 'title_tagline',
					),
				),

				'zakra_typography_site_description'      => array(
					'output'  => array(
						array(
							'selector' => '.site-branding .site-description',
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
						'priority' => 24,
						'label'    => esc_html__( 'Tagline', 'zakra' ),
						'section'  => 'title_tagline',
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_site_identity_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 100,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'title_tagline',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Site_Identity_Option();

endif;
