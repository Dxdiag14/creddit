<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\ThemeBuilder;

use Elementor\Core\Base\Module AS Module_Base;
use Elementor\Core\Base\Document;
use Elementor\Elements_Manager;
use Auxin\Plugin\CoreElements\Elementor\Modules\ThemeBuilder\Theme_Document as Theme_Document;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public static function is_preview() {
		return Plugin::instance()->preview->is_preview_mode() || is_preview();
	}

	public function get_name() {
		return 'theme-builder';
	}

	/**
	 * @return Classes\Preview_Manager
	 */
	public function get_preview_manager() {
		return $this->get_component( 'preview' );
	}

	/**
	 * @param $post_id
	 *
	 * @return Theme_Document
	 */
	public function get_document( $post_id ) {
		$document = null;

		try {
			$document = Plugin::instance()->documents->get( $post_id );
		} catch ( \Exception $e ) {
			// Do nothing.
			unset( $e );
		}

		// if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
		// 	$document = null;
		// }

		return $document;
	}

	public function create_new_dialog_types( $types ) {
		/**
		 * @var Theme_Document[] $document_types
		 */
		foreach ( $types as $type => $label ) {
			$document_type = Plugin::instance()->documents->get_document_type( $type );
			$instance = new $document_type();

			if ( $instance instanceof Theme_Document && 'section' !== $type ) {
				$types[ $type ] .= $instance->get_location_label();
			}
		}

		return $types;
	}

	public function print_location_field() {
		$locations = $this->get_locations_manager()->get_locations( [
			'public' => true,
		] );

		if ( empty( $locations ) ) {
			return;
		}
		?>
		<div id="elementor-new-template__form__location__wrapper" class="elementor-form-field">
			<label for="elementor-new-template__form__location" class="elementor-form-field__label">
				<?php echo __( 'Select a Location', 'auxin-elements' ); ?>
			</label>
			<div class="elementor-form-field__select__wrapper">
				<select id="elementor-new-template__form__location" class="elementor-form-field__select" name="meta_location">
					<option value="">
						<?php echo __( 'Select...', 'auxin-elements' ); ?>
					</option>
					<?php

					foreach ( $locations as $location => $settings ) {
						echo sprintf( '<option value="%1$s">%2$s</option>', $location, $settings['label'] );
					}
					?>
				</select>
			</div>
		</div>
		<?php
	}

	public function print_post_type_field() {
		$post_types = get_post_types( [
			'exclude_from_search' => false,
		], 'objects' );

		unset( $post_types['product'] );

		if ( empty( $post_types ) ) {
			return;
		}
		?>
		<div id="elementor-new-template__form__post-type__wrapper" class="elementor-form-field">
			<label for="elementor-new-template__form__post-type" class="elementor-form-field__label">
				<?php echo __( 'Select Post Type', 'auxin-elements' ); ?>
			</label>
			<div class="elementor-form-field__select__wrapper">
				<select id="elementor-new-template__form__post-type" class="elementor-form-field__select" name="_elementor_template_sub_type">
					<option value="">
						<?php echo __( 'Select', 'auxin-elements' ); ?>...
					</option>
					<?php

					foreach ( $post_types as $post_type => $post_type_config ) {
						$doc_type = Plugin::instance()->documents->get_document_type( $post_type );
						$doc_name = ( new $doc_type() )->get_name();

						if ( 'post' === $doc_name || 'page' === $doc_name ) {
							echo sprintf( '<option value="%1$s">%2$s</option>', $post_type, $post_type_config->labels->singular_name );
						}
					}

					// 404.
					echo sprintf( '<option value="%1$s">%2$s</option>', 'not_found404', __( '404 Page', 'auxin-elements' ) );

					?>
				</select>
			</div>
		</div>
		<?php
	}

	public function __construct() {
		$this->add_component( 'preview', new Classes\Preview_Manager() );
	}
}
