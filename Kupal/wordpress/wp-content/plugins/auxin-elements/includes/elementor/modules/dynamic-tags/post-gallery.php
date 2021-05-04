<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Gallery extends Data_Tag {

	public function get_name() {
		return 'aux-post-gallery';
	}

	public function get_title() {
		return __( 'Post Image Attachments', 'auxin-elements' );
	}

	public function get_categories() {
		return [ TagsModule::GALLERY_CATEGORY ];
	}

	public function get_group() {
		return 'post';
	}

	public function get_value( array $options = [] ) {
		$images = get_attached_media( 'image' );

		$value = [];

		foreach ( $images as $image ) {
			$value[] = [
				'id' => $image->ID,
			];
		}

		return $value;
	}
}
