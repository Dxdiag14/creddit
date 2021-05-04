<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\ThemeBuilder\Classes;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\DocumentTypes\Post;
use Elementor\Modules\PageTemplates\Module as Single;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Modules\Library\Documents\Library_Document;
use Elementor\Core\Files\CSS\Post as Post_CSS;
use Auxin\Plugin\CoreElements\Elementor\Modules\ThemeBuilder\Module;
use Auxin\Plugin\CoreElements\Elementor\Modules\ThemeBuilder\Theme_Document;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Locations_Manager {

	protected $core_locations = [];
	protected $locations = [];
	protected $did_locations = [];
	protected $current_location;
	protected $locations_queue = [];
	protected $locations_printed = [];
	protected $locations_skipped = [];

	public function __construct() {
		add_filter( 'the_content', [ $this, 'builder_wrapper' ], 9999999 ); // 9999999 = after preview->builder_wrapper
		add_action( 'template_redirect', [ $this, 'register_locations' ] );
		add_filter( 'elementor/admin/create_new_post/meta', [ $this, 'filter_add_location_meta_on_create_new_post' ] );
	}

	public function register_locations() {
		// Run Once.
		if ( ! did_action( 'elementor/theme/register_locations' ) ) {
			do_action( 'elementor/theme/register_locations', $this );
		}
	}

	/**
	 * @param string $location
	 * @param integer $document_id
	 */
	public function add_doc_to_location( $location, $document_id ) {
		if ( isset( $this->locations_skipped[ $location ][ $document_id ] ) ) {
			// Don't re-add skipped documents.
			return;
		}

		if ( ! isset( $this->locations_queue[ $location ] ) ) {
			$this->locations_queue[ $location ] = [];
		}

		$this->locations_queue[ $location ][ $document_id ] = $document_id;
	}

	public function remove_doc_from_location( $location, $document_id ) {
		unset( $this->locations_queue[ $location ][ $document_id ] );
	}

	public function skip_doc_in_location( $location, $document_id ) {
		$this->remove_doc_from_location( $location, $document_id );

		if ( ! isset( $this->locations_skipped[ $location ] ) ) {
			$this->locations_skipped[ $location ] = [];
		}

		$this->locations_skipped[ $location ][ $document_id ] = $document_id;
	}

	public function is_printed( $location, $document_id ) {
		return isset( $this->locations_printed[ $location ][ $document_id ] );
	}

	public function set_is_printed( $location, $document_id ) {
		if ( ! isset( $this->locations_printed[ $location ] ) ) {
			$this->locations_printed[ $location ] = [];
		}

		$this->locations_printed[ $location ][ $document_id ] = $document_id;
		$this->remove_doc_from_location( $location, $document_id );
	}

	public function set_global_authordata() {
		global $authordata;
		if ( ! isset( $authordata->ID ) ) {
			$post = get_post();
			$authordata = get_userdata( $post->post_author ); // WPCS: override ok.
		}
	}

	public function get_documents_for_location( $location ){
		$locations = $this->get_locations();
		$documents = array();
		foreach ( $locations as $name => $args ) {
			$template = get_page_by_path( auxin_get_option( 'site_' . $name . '_template', ' ' ), OBJECT, 'elementor_library' );
			if( isset( $template->ID ) && $template->ID !== ' ' && get_post_status( $template->ID ) ){
				$documents[ $template->ID ] = 'gu';
			}
		}
	}

	public function do_location( $document_id, $location ) {

		$this->add_doc_to_location( $location, $document_id );


		// Locations Queue can contain documents that added manually.
		if ( empty( $this->locations_queue[ $location ] ) ) {
			return false;
		}

		if ( is_singular() ) {
			$this->set_global_authordata();
		}

		$document = Module::instance()->get_document( $document_id );

		if ( ! $document || $this->is_printed( $location, $document_id ) ) {
			$this->skip_doc_in_location( $location, $document_id );
			return false;
		}

		$this->current_location = $location;
		$document->print_content();
		$this->did_locations[] = $this->current_location;
		$this->current_location = null;

		$this->set_is_printed( $location, $document_id );

		return true;
	}

	public function did_location( $location ) {
		return in_array( $location, $this->did_locations, true );
	}

	public function get_current_location() {
		return $this->current_location;
	}

	public function builder_wrapper( $content ) {
		$post_id = get_the_ID();

		if ( $post_id ) {
			$document = Module::instance()->get_document( $post_id );
			if ( $document ) {
				$document_location = $document->get_location();
				$location_settings = $this->get_location( $document_location );
				// If is a `content` document or the theme is not support the document location (header/footer and etc.).
				if ( $location_settings && ! $location_settings['edit_in_content'] ) {
					$content = '<div class="elementor-theme-builder-content-area">' . __( 'Content Area', 'auxin-elements' ) . '</div>';
				}
			}
		}

		return $content;
	}

	public function get_locations( $filter_args = [] ) {
		$this->register_locations();
		return wp_list_filter( $this->locations, $filter_args );
	}

	public function get_location( $location ) {
		$locations = $this->get_locations();

		if ( isset( $locations[ $location ] ) ) {
			$location_config = $locations[ $location ];
		} else {
			$location_config = [];
		}

		return $location_config;
	}

	public function get_doc_location( $post_id ) {
		$document = Plugin::instance()->documents->get( $post_id );
		return $document->get_location();
	}

	public function get_core_locations() {
		return $this->core_locations;
	}

	public function register_all_core_location() {
		foreach ( $this->core_locations as $location => $settings ) {
			$this->register_location( $location, $settings );
		}
	}

	public function register_location( $location, $args = [] ) {
		$args = wp_parse_args( $args, [
			'label' => $location,
			'multiple' => false,
			'public' => true,
			'edit_in_content' => true,
			'hook' => 'elementor/theme/' . $location,
		] );

		$this->locations[ $location ] = $args;

		add_action( $args['hook'], function() use ( $location, $args ) {
			$did_location = Module::instance()->get_locations_manager()->do_location( $location );

			if ( $did_location && ! empty( $args['remove_hooks'] ) ) {
				foreach ( $args['remove_hooks'] as $item ) {
					remove_action( $args['hook'], $item );
				}
			}
		}, 5 );
	}

	public function register_core_location( $location, $args = [] ) {
		if ( ! isset( $this->core_locations[ $location ] ) ) {
			/* translators: %s: Location name. */
			wp_die( esc_html( sprintf( __( 'Location \'%s\' is not a core location.', 'auxin-elements' ), $location ) ) );
		}

		$args = array_replace_recursive( $this->core_locations[ $location ], $args );

		$this->register_location( $location, $args );
	}

	public function location_exits( $location = '', $check_match = false ) {
		$location_exits = ! ! $this->get_location( $location );

		// if ( $location_exits && $check_match ) {
		// 	$location_exits = ! ! Module::instance()->get_conditions_manager()->get_documents_for_location( $location );
		// }

		return $location_exits;
	}

	public function filter_add_location_meta_on_create_new_post( $meta ) {
		if ( ! empty( $_GET['meta_location'] ) ) {
			$meta[ Theme_Document::LOCATION_META_KEY ] = $_GET['meta_location'];
		}

		return $meta;
	}

}
