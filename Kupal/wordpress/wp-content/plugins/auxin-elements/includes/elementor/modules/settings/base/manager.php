<?php
namespace Auxin\Plugin\CoreElements\Elementor\Settings\Base;

use Elementor\Core\Base\Document;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


abstract class Manager {

    function __construct(){
        $this->register_hooks();
    }

    protected function register_hooks(){
        add_action( 'elementor/documents/register_controls', [ $this, 'register_controls' ] );
        add_action( 'elementor/document/after_save', [ $this, 'on_save_settings' ], 10, 2 );
    }

    /**
     * Register Document Controls
     *
     * Add New Controls to Elementor Page Options
     * @param $document
     */
    abstract public function register_controls ( $document );

    /**
     * Stores the changed controllers
     *
     * @param array $settings    list of settings for changes controllers
     * @param Document $document Elementor base Document class
     * @param array|null $data   All document info passed for saving
     * @return mixed
     */
    abstract protected function save_settings( array $settings, $document, $data = null );

    /**
     * Parsing custom  control settings
     *
     * @param $document
     * @param $data
     */
    public function on_save_settings( $document, $data ){
        if( empty( $data['settings'] ) ){
            return;
        }
        $settings_to_save = $this->get_settings_to_save( $data['settings'] );
        $this->save_settings( $settings_to_save, $document, $data );
    }

    /**
     * Default special page settings in Elementor document
     *
     * @return array
     */
    protected function get_special_settings_names(){
        return [
			'id',
			'post_title',
			'post_status',
			'template',
			'post_excerpt',
			'post_featured_image',
		];
    }

    /**
     * Get custom control settings which are changed
     *
     * @param array $settings
     * @return array
     */
    private function get_settings_to_save( array $settings ){
        $special_settings = $this->get_special_settings_names();

		$settings_to_save = $settings;

		foreach ( $special_settings as $special_setting ) {
			if ( isset( $settings_to_save[ $special_setting ] ) ) {
				unset( $settings_to_save[ $special_setting ] );
			}
		}

		return $settings_to_save;
    }
}
