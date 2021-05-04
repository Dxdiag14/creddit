<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Auxin_Featured_Colors extends Tag {

	public function get_name() {
		return 'aux-featured-colors';
	}

	public function get_title() {
		return __( 'Featured Colors', 'auxin-elements' );
	}

	public function get_group() {
		return 'colors';
	}

	public function get_categories() {
		return [
			TagsModule::COLOR_CATEGORY
		];
    }

    public function get_colors() {

		$items = [
            '' => [
				'label' => __( 'Select...', 'auxin-elements' ),
			]
        ];

		for( $i = 1; $i <= 8 ; ++$i ) {
			$items[$i] = [
				'label' =>  sprintf( __( 'Color %s', THEME_DOMAIN ), $i ),
				'color'	=> auxin_get_option( 'site_featured_color_' . $i )
			];
		}

        return $items;
    }

	public function is_settings_required() {
		return true;
	}

	protected function _register_controls() {
		$this->add_control(
			'key',
			[
				'label'   => __( 'Colors', 'auxin-elements' ),
				'type'    => 'aux-featured-color',
				'options' => $this->get_colors(),
				'default' => ''
            ]
        );
	}

	protected function get_color() {
		if( $key = $this->get_settings( 'key' ) ){
			return "var( --auxin-featured-color-{$key} )";
		}

		return '';
	}

	public function get_value( array $options = [] ) {
		return $this->get_color();
	}

	public function render() {
		echo $this->get_color();
	}

}
