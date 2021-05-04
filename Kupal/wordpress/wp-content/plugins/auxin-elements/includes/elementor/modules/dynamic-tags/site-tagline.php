<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_Tagline extends Tag {
	public function get_name() {
		return 'aux-site-tagline';
	}

	public function get_title() {
		return __( 'Site Tagline', 'auxin-elements' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ TagsModule::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_bloginfo( 'description' ) );
	}
}
