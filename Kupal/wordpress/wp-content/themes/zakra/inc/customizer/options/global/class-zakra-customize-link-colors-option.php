<?php
/**
 * Link Colors.
 *
 * @package     zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== COLORS > LINK COLORS ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Link_Colors_Option' ) ) :

	/**
	 * Link option.
	 */
	class Zakra_Customize_Link_Colors_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_link_color'       => array(
					'output'  => array(
						array(
							'selector' => '.entry-content a',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#269bd1',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'     => 'color',
						'priority' => 10,
						'label'    => esc_html__( 'Link Color', 'zakra' ),
						'section'  => 'zakra_link_colors',
						'choices'  => array(
							'alpha' => true,
						),
					),
				),

				'zakra_link_hover_color' => array(
					'output'  => array(
						array(
							'selector' => '.entry-content a:hover, .entry-content a:focus',
							'property' => 'color',
						),
					),
					'setting' => array(
						'default'           => '#1e7ba6',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_alpha_color' ),
					),
					'control' => array(
						'type'     => 'color',
						'priority' => 20,
						'label'    => esc_html__( 'Link Hover Color', 'zakra' ),
						'section'  => 'zakra_link_colors',
						'choices'  => array(
							'alpha' => true,
						),
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_link_colors_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 30,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_link_colors',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Link_Colors_Option();

endif;
