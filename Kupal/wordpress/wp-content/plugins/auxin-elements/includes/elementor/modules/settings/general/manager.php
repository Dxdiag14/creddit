<?php
namespace Auxin\Plugin\CoreElements\Elementor\Settings\General;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Auxin\Plugin\CoreElements\Elementor\Settings\Base\Manager as baseManager;


class Manager extends baseManager
{

    /**
     * Register Document Controls
     *
     * Add New Controls to Elementor Page Options
     * @param $document
     */
    public function register_controls ( $document ) {

        $document->start_controls_section(
            'page_logo_section', [
                'label' => __('Logo', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $document->add_control(
            'aux_custom_logo', [
                'label'      => __('Page Logo', 'auxin-elements' ),
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $document->add_control(
            'page_secondary_logo_max_height', [
                'label'      => __( 'Footer Brand Height', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px'),
				'default' => [
					'size' => $document->get_main_meta( 'page_secondary_logo_max_height' ), // get default value,
				],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ]
                ]
            ]
        );

        $document->end_controls_section();
    }

    protected function save_settings( array $settings, $document, $data = null ){
        /**
        // TODO save the control values as page meta fields by looping through $settings array
        // $document->update_main_meta( $meta_key, $value );

        error_log( print_r( $settings, true ) );
        */
    }
}
