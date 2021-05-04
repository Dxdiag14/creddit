<?php
namespace Auxin\Plugin\CoreElements\Elementor\Settings\Page;

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
        // TODO Add controls here
    }

    protected function save_settings( array $settings, $document, $data = null ){
        // TODO save the control values as page meta fields by looping through $settings array
        // $document->update_main_meta( $meta_key, $value );
    }
}
