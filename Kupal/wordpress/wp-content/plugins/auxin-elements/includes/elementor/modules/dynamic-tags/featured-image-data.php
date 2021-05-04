<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Featured_Image_Data extends Tag {

	public function get_name() {
		return 'aux-featured-image-data';
	}

	public function get_group() {
		return 'media';
	}

	public function get_categories() {
		return [
			TagsModule::TEXT_CATEGORY,
			TagsModule::URL_CATEGORY,
			TagsModule::POST_META_CATEGORY,
		];
	}

	public function get_title() {
		return __( 'Featured Image Data', 'auxin-elements' );
	}

	private function get_attacment() {
		$settings = $this->get_settings();
		$id = get_post_thumbnail_id();

		if ( ! $id ) {
			return false;
		}

		return get_post( $id );
	}

	public function render() {
		$settings = $this->get_settings();
		$attachment = $this->get_attacment();

		if ( ! $attachment ) {
			return '';
		}

		$value = '';

		switch ( $settings['attachment_data'] ) {
			case 'alt':
				$value = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
				break;
			case 'caption':
				$value = $attachment->post_excerpt;
				break;
			case 'description':
				$value = $attachment->post_content;
				break;
			case 'href':
				$value = get_permalink( $attachment->ID );
				break;
			case 'src':
				$value = $attachment->guid;
				break;
			case 'title':
				$value = $attachment->post_title;
				break;
		}
		echo wp_kses_post( $value );
	}

	protected function _register_controls() {

		$this->add_control(
			'attachment_data',
			[
				'label' => __( 'Data', 'auxin-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title' => __( 'Title', 'auxin-elements' ),
					'alt' => __( 'Alt', 'auxin-elements' ),
					'caption' => __( 'Caption', 'auxin-elements' ),
					'description' => __( 'Description', 'auxin-elements' ),
					'src' => __( 'File URL', 'auxin-elements' ),
					'href' => __( 'Attachment URL', 'auxin-elements' ),
				],
			]
		);
	}
}
