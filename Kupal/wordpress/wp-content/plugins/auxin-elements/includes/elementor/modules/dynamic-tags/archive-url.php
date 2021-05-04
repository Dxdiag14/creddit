<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_URL extends Data_Tag {

	public function get_name() {
		return 'aux-archive-url';
	}

	public function get_group() {
		return 'URL';
	}

	public function get_categories() {
		return [ TagsModule::URL_CATEGORY ];
	}

	public function get_title() {
		return __( 'Archive URL', 'auxin-elements' );
	}

	public function get_archive_list() {

		$items = [
            '' => __( 'Select...', 'auxin-elements' ),
        ];
		
		$items = array_merge( $items, auxin_get_available_post_types_with_archive() );

        return $items;
	}
	
	public function is_settings_required() {
		return true;
	}

	protected function _register_controls() {
		$this->add_control(
			'key',
			[
				'label'   => __( 'Archives URL', 'auxin-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_archive_list(),
				'default' => ''
            ]
        );
	}

	protected function get_archive_url() {
		if( $key = $this->get_settings( 'key' ) ){
			return get_post_type_archive_link( $key );
		}

		return '';
	}

	public function get_value( array $options = [] ) {
		return $this->get_archive_url();
	}

	public function render() {
		echo $this->get_archive_url();
	}
}

