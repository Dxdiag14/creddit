<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Title extends Tag {
	public function get_name() {
		return 'aux-archive-title';
	}

	public function get_title() {
		return __( 'Archive Title', 'auxin-elements' );
	}

	public function get_group() {
		return 'archive';
	}

	public function get_categories() {
		return [ TagsModule::TEXT_CATEGORY ];
	}

	public function render() {
		$include_context = 'yes' === $this->get_settings( 'include_context' );

		$title = auxin_get_page_title( $include_context );

		echo wp_kses_post( $title );
	}

	protected function _register_controls() {
		$this->add_control(
			'include_context',
			[
				'label' => __( 'Include Context', 'auxin-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
	}
}
