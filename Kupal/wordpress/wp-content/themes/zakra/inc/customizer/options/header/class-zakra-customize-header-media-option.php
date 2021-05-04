<?php
/**
 * Site Identity Options.
 *
 * @package     zakra
 */

defined( 'ABSPATH' ) || exit;

/*========================================== HEADER > SITE IDENTITY ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Header_Media_Option' ) ) :

	/**
	 * Site Identity customizer options.
	 */
	class Zakra_Customize_Header_Media_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array();

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_header_media_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 100,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'header_image',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Header_Media_Option();

endif;
