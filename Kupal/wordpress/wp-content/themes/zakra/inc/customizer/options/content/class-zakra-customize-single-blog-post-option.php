<?php
/**
 * Single blog post options.
 *
 * @package     zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*========================================== CONTENT > SINGLE POST ==========================================*/
if ( ! class_exists( 'Zakra_Customize_Single_Blog_Post_Option' ) ) :

	/**
	 * Single Blog Post option.
	 */
	class Zakra_Customize_Single_Blog_Post_Option extends Zakra_Customize_Base_Option {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$elements = array(

				'zakra_single_post_content_structure_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'        => 'heading',
						'label'       => esc_html__( 'Single Post Content Order', 'zakra' ),
						'description' => esc_html__( 'Drag & Drop items to re-arrange the order', 'zakra' ),
						'section'     => 'zakra_single_blog_post',
						'priority'    => 5,
					),
				),

				'zakra_single_post_content_structure'   => array(
					'setting' => array(
						'default'           => array(
							'featured_image',
							'title',
							'meta',
							'content',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_sortable' ),
					),
					'control' => array(
						'type'     => 'sortable',
						'priority' => 10,
						'section'  => 'zakra_single_blog_post',
						'choices'  => array(
							'featured_image' => esc_attr__( 'Featured Image', 'zakra' ),
							'title'          => esc_attr__( 'Title', 'zakra' ),
							'meta'           => esc_attr__( 'Meta Tags', 'zakra' ),
							'content'        => esc_attr__( 'Content', 'zakra' ),
						),
					),
				),

				'zakra_single_blog_post_meta_structure_heading' => array(
					'setting' => array(),
					'control' => array(
						'type'        => 'heading',
						'label'       => esc_html__( 'Meta Tags Order', 'zakra' ),
						'description' => esc_html__( 'Drag & Drop items to re-arrange the order', 'zakra' ),
						'section'     => 'zakra_single_blog_post',
						'priority'    => 15,
					),
				),

				'zakra_single_blog_post_meta_structure' => array(
					'setting' => array(
						'default'           => array(
							'author',
							'date',
							'categories',
							'tags',
							'comments',
						),
						'sanitize_callback' => array( 'Zakra_Customizer_Sanitize', 'sanitize_sortable' ),
					),
					'control' => array(
						'type'     => 'sortable',
						'priority' => 20,
						'section'  => 'zakra_single_blog_post',
						'choices'  => array(
							'comments'   => esc_attr__( 'Comments', 'zakra' ),
							'categories' => esc_attr__( 'Categories', 'zakra' ),
							'author'     => esc_attr__( 'Author', 'zakra' ),
							'date'       => esc_attr__( 'Date', 'zakra' ),
							'tags'       => esc_attr__( 'Tags', 'zakra' ),
						),
					),
				),

			);

			if ( ! zakra_is_zakra_pro_active() ) {
				$elements['zakra_single_blog_post_upgrade'] = array(
					'setting' => array(
						'default' => '',
					),
					'control' => array(
						'type'        => 'upgrade',
						'priority'    => 30,
						'label'       => esc_html__( 'Learn more', 'zakra' ),
						'description' => esc_html__( 'Unlock more features available for this section.', 'zakra' ),
						'section'     => 'zakra_single_blog_post',
					),
				);
			}

			return $elements;

		}

	}

	new Zakra_Customize_Single_Blog_Post_Option();

endif;
