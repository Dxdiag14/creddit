<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Auxin_Pages_Url extends Tag {

	public function get_name() {
		return 'aux-pages-url';
	}

	public function get_title() {
		return __( 'Pages URL', 'auxin-elements' );
	}

	public function get_group() {
		return 'URL';
	}

	public function get_categories() {
		return [
			TagsModule::URL_CATEGORY
		];
    }

    public function get_pages_list() {

		$items = [
            '' => __( 'Select...', 'auxin-elements' ),
        ];
        $pages = get_posts( array(
            'post_type'   => 'page',
            'numberposts' => -1
		) );
		$home_id = get_option( 'page_on_front' );
        foreach ( $pages as $page ) {
			$page->post_title = $home_id == $page->ID ? __( 'Home Page', 'auxin-elements' ) : $page->post_title; 
            $items[ $page->ID ] = $page->post_title;
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
				'label'   => __( 'Pages URL', 'auxin-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_pages_list(),
				'default' => ''
            ]
        );
	}

	protected function get_page_url() {
		if( $key = $this->get_settings( 'key' ) ){
			return get_permalink( $key );
		}

		return '';
	}

	public function get_value( array $options = [] ) {
		return $this->get_page_url();
	}

	public function render() {
		echo $this->get_page_url();
	}

}
