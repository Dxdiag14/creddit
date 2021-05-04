<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Comments_URL extends Data_Tag {

	public function get_name() {
		return 'aux-comments-url';
	}

	public function get_title() {
		return __( 'Comments URL', 'auxin-elements' );
	}

	public function get_group() {
		return 'comments';
	}

	public function get_categories() {
		return [ TagsModule::URL_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		return get_comments_link();
	}
}
