<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Author_Info extends Tag {

	public function get_name() {
		return 'aux-author-info';
	}

	public function get_title() {
		return __( 'Author Info', 'auxin-elements' );
	}

	public function get_group() {
		return 'author';
	}

	public function get_categories() {
		return [ TagsModule::TEXT_CATEGORY ];
	}

	public function render() {
		$key = $this->get_settings( 'key' );

		if ( empty( $key ) ) {
			return;
		}

		$value = get_the_author_meta( $key );

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function _register_controls() {
		$this->add_control(
			'key',
			[
				'label' => __( 'Field', 'auxin-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'description',
				'options' => [
					'description' => __( 'Bio', 'auxin-elements' ),
					'email' => __( 'Email', 'auxin-elements' ),
					'url' => __( 'Website', 'auxin-elements' ),
				],
			]
		);
	}
}
