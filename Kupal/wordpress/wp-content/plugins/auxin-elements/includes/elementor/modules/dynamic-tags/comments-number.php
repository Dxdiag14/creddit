<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Comments_Number extends Tag {

	public function get_name() {
		return 'aux-comments-number';
	}

	public function get_title() {
		return __( 'Comments Number', 'auxin-elements' );
	}

	public function get_group() {
		return 'comments';
	}

	public function get_categories() {
		return [ TagsModule::TEXT_CATEGORY ];
	}

	protected function _register_controls() {
		$this->add_control(
			'format_no_comments',
			[
				'label' => __( 'No Comments Format', 'auxin-elements' ),
				'default' => __( 'No Responses', 'auxin-elements' ),
			]
		);

		$this->add_control(
			'format_one_comments',
			[
				'label' => __( 'One Comment Format', 'auxin-elements' ),
				'default' => __( 'One Response', 'auxin-elements' ),
			]
		);

		$this->add_control(
			'format_many_comments',
			[
				'label' => __( 'Many Comment Format', 'auxin-elements' ),
				'default' => __( '{number} Responses', 'auxin-elements' ),
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => __( 'Link', 'auxin-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'auxin-elements' ),
					'comments_link' => __( 'Comments Link', 'auxin-elements' ),
				],
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();

		$comments_number = get_comments_number();

		if ( ! $comments_number ) {
			$count = $settings['format_no_comments'];
		} elseif ( 1 === $comments_number ) {
			$count = $settings['format_one_comments'];
		} else {
			$count = strtr( $settings['format_many_comments'], [
				'{number}' => number_format_i18n( $comments_number ),
			] );
		}

		if ( 'comments_link' === $this->get_settings( 'link_to' ) ) {
			$count = sprintf( '<a href="%s">%s</a>', get_comments_link(), $count );
		}

		echo wp_kses_post( $count );
	}
}
