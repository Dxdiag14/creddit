<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Title extends Tag {
	public function get_name() {
		return 'aux-post-title';
	}

	public function get_title() {
		return __( 'Post Title', 'auxin-elements' );
	}

	public function get_group() {
		return 'post';
	}

	public function get_categories() {
		return [ TagsModule::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_the_title() );
	}
}
