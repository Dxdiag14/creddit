<?php
/**
 * Meta styles.
 *
 * @package     zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== CONTENT > META ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Blog_Meta_Option' ) ) :

	/**
	 * Single Blog Post option.
	 */
	class Zakra_Customize_Blog_Meta_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_blog_archive_meta_style'         => array(
					'setting' => array(
						'default'           => 'tg-meta-style-one',
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_radio' ),
					),
					'control' => array(
						'type'     => 'radio_image',
						'priority' => 10,
						'label'    => esc_html__( 'Meta Style', 'zakra' ),
						'section'  => 'zakra_meta',
						'choices'  => array(
							'tg-meta-style-one' => ZAKRA_PARENT_INC_ICON_URI . '/meta-style-one.png',
							'tg-meta-style-two' => ZAKRA_PARENT_INC_ICON_URI . '/meta-style-two.png',
						),
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_meta_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 20,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_meta',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Blog_Meta_Option();

endif;
