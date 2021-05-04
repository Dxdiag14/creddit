<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_Logo extends Data_Tag {
	public function get_name() {
		return 'aux-site-logo';
	}

	public function get_title() {
		return __( 'Site Logo', 'auxin-elements' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ TagsModule::IMAGE_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( $custom_logo_id ) {
			$url = wp_get_attachment_image_src( $custom_logo_id, 'full' )[0];
		} else {
			$url = Utils::get_placeholder_image_src();
		}

		return [
			'id' => $custom_logo_id,
			'url' => $url,
		];
	}
}
