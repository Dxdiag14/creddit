<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class User_Info extends Tag {

	public function get_name() {
		return 'aux-user-info';
	}

	public function get_title() {
		return __( 'User Info', 'auxin-elements' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ TagsModule::TEXT_CATEGORY ];
	}

	public function render() {
		$type = $this->get_settings( 'type' );
		$user = wp_get_current_user();
		if ( empty( $type ) || 0 === $user->ID ) {
			return;
		}

		$value = '';
		switch ( $type ) {
			case 'login':
			case 'email':
			case 'url':
			case 'nicename':
				$field = 'user_' . $type;
				$value = isset( $user->$field ) ? $user->$field : '';
				break;
			case 'id':
			case 'description':
			case 'first_name':
			case 'last_name':
			case 'display_name':
				$value = isset( $user->$type ) ? $user->$type : '';
				break;
			case 'meta':
				$key = $this->get_settings( 'meta_key' );
				if ( ! empty( $key ) ) {
					$value = get_user_meta( $user->ID, $key, true );
				}
				break;
		}

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'type';
	}

	protected function _register_controls() {
		$this->add_control(
			'type',
			[
				'label' => __( 'Field', 'auxin-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Choose', 'auxin-elements' ),
					'id' => __( 'ID', 'auxin-elements' ),
					'display_name' => __( 'Display Name', 'auxin-elements' ),
					'login' => __( 'Username', 'auxin-elements' ),
					'first_name' => __( 'First Name', 'auxin-elements' ),
					'last_name' => __( 'Last Name', 'auxin-elements' ),
					'description' => __( 'Bio', 'auxin-elements' ),
					'email' => __( 'Email', 'auxin-elements' ),
					'url' => __( 'Website', 'auxin-elements' ),
					'meta' => __( 'User Meta', 'auxin-elements' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label' => __( 'Meta Key', 'auxin-elements' ),
				'condition' => [
					'type' => 'meta',
				],
			]
		);
	}
}
