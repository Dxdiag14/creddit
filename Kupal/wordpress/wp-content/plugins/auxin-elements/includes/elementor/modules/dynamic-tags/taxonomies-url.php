<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Auxin_Taxonomies_Url extends Tag {

	public function get_name() {
		return 'aux-tax-url';
	}

	public function get_title() {
		return __( 'Taxonomies URL', 'auxin-elements' );
	}

	public function get_group() {
		return 'URL';
	}

	public function get_categories() {
		return [
			TagsModule::URL_CATEGORY
		];
    }

    public function get_categories_list() {

		$items = [
            '' => __( 'Select...', 'auxin-elements' ),
        ];

        $categories = auxin_general_post_types_category_slug();
        foreach( $categories as $category_slug => $post_type_name ) {
            $terms = get_categories( array( 'taxonomy' => $category_slug ) );
            foreach ( $terms as $term ) {
                $items[ $term->term_id ] = $post_type_name . ' - ' . $term->name;
            }
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
				'label'   => __( 'Categories URL', 'auxin-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_categories_list(),
				'default' => ''
            ]
        );
	}

	protected function get_category_url() {
		if( $key = $this->get_settings( 'key' ) ){
			return get_category_link( $key );
		}

		return '';
	}

	public function get_value( array $options = [] ) {
		return $this->get_category_url();
	}

	public function render() {
		echo $this->get_category_url();
	}

}
