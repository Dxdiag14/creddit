<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor 'ContactBox' widget.
 *
 * Elementor widget that displays an 'ContactBox' with lightbox.
 *
 * @since 1.0.0
 */
class ContactBox extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'ContactBox' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_contactbox';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'ContactBox' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Contact Box', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'ContactBox' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-image-hotspot auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'ContactBox' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-core' );
    }

    /**
     * Register 'ContactBox' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Contact Info section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'contactinfo_section',
            array(
                'label'      => __('Contact Info', 'auxin-elements' )
            )
        );

        $this->add_control(
            'email',
            array(
                'label'       => __('Email','auxin-elements'),
                'description' => __('Contact box email address.', 'auxin-elements'),
                'type'        => Controls_Manager::TEXT,
                'input_type'  => 'email'
            )
        );

        $this->add_control(
            'telephone',
            array(
                'label'       => __('Telephone','auxin-elements'),
                'description' => __('Contact box phone number.', 'auxin-elements'),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'show_socials',
            array(
                'label'       => __('Show site socials','auxin-elements'),
                'description' => __('Show socials below the info.','auxin-elements'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => __( 'On', 'auxin-elements' ),
                'label_off'   => __( 'Off', 'auxin-elements' ),
                'default'     => 'yes'
            )
        );

        $this->add_control(
            'address',
            array(
                'label'       => __('Address','auxin-elements'),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'Type your address here', 'auxin-elements')
            )
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'map_section',
            array(
                'label'      => __('Map', 'auxin-elements' )
            )
        );

        $this->add_control(
            'show_map',
            array(
                'label'       => __('Show map','auxin-elements'),
                'description' => __('Show map above the info.','auxin-elements'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => __( 'On', 'auxin-elements' ),
                'label_off'   => __( 'Off', 'auxin-elements' ),
                'default'     => 'yes'
            )
        );

        $this->add_control(
            'map_position',
            array(
                'label'       => __('Map position','auxin-elements'),
                'description' => __('Whether to show map above the contact details or below them.', 'auxin-elements'),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 'down',
                'options'     => array(
                    'down'          => __('Below the contact details.', 'auxin-elements' ),
                    'up'            => __('Above the contact details.', 'auxin-elements' )
                ),
                'return_value' => 'down',
                'condition'    => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'height',
            array(
                'label'           => __( 'Map height', 'auxin-elements' ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px','em'),
                'desktop_default' => array(
                    'size' => 160,
                    'unit' => 'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 0.1
                    )
                ),
                'selectors'    => array(
                    '{{WRAPPER}} .aux-map-wrapper' => 'height:{{SIZE}}{{UNIT}} !important;'
                ),
                'return_value' => 160,
                'condition'    => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_control(
            'latitude',
            array(
                'label'           => __( 'Latitude', 'auxin-elements' ),
                'description'     => __('Latitude location over the map.', 'auxin-elements'),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'default'         => array(
                    'size' => 40.7,
                    'unit' => 'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => -90,
                        'max'  =>  90,
                        'step' => 0.01
                    )
                ),
                'return_value' => 40.7,
                'condition'    => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_control(
            'longitude',
            array(
                'label'           => __('Longitude','auxin-elements'),
                'description'     => __('Longitude location over the map.', 'auxin-elements'),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'default'         => array(
                    'size' => -74,
                    'unit' => 'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => -180,
                        'max'  =>  180,
                        'step' => 0.01
                    )
                ),
                'return_value' => -74,
                'condition'    => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_control(
            'type',
            array(
                'label'       => __('Map type','auxin-elements'),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 'ROADMAP',
                'options'     => array(
                    'ROADMAP'   => __('ROADMAP', 'auxin-elements'),
                    'SATELLITE' => __('SATELLITE', 'auxin-elements')
                ),
                'return_value' => 'ROADMAP',
                'condition'    => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_control(
            'style',
            array(
                'label'       => __('Map style','auxin-elements'),
                'description' => __('This feild allows you to customize the presentation of the standard Google base maps. You can find many preset styles in ', 'auxin-elements') .'<a href="https://snazzymaps.com/" target="_blank">' . __('this website.', 'auxin-elements') . '</a>' ,
                'type'         => Controls_Manager::CODE,
                'condition'    => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_control(
            'marker_info',
            array(
                'label'       => __('Marker info','auxin-elements'),
                'description' => __('Marker popup text, leave it empty if you don\'t need it.', 'auxin-elements'),
                'type'        => Controls_Manager::TEXT,
                'condition'    => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_control(
            'show_mapcontrols',
            array(
                'label'       => __('Navigation control','auxin-elements'),
                'description' => __('Show nacigation control on map.','auxin-elements'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => __( 'On', 'auxin-elements' ),
                'label_off'   => __( 'Off', 'auxin-elements' ),
                'default'     => 'no',
                'condition'   => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_control(
            'zoom',
            array(
                'label'           => __('Zoom','auxin-elements'),
                'description'     => __('The initial resolution at which to display the map, between 1 to 20.', 'auxin-elements'),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'default'         => array(
                    'size' => 10,
                    'unit' => 'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 20,
                        'step' => 0.1
                    )
                ),
                'return_value' => 10,
                'condition'    => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->add_control(
            'zoom_wheel',
            array(
                'label'       => __('Zoom with mouse wheel','auxin-elements'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => __( 'On', 'auxin-elements' ),
                'label_off'   => __( 'Off', 'auxin-elements' ),
                'default'     => 'no',
                'condition'   => array(
                    'show_map' => 'yes'
                )
            )
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  Style Tab
        /*-----------------------------------------------------------------------------------*/

        /*  Color Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'color_section',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->end_controls_section();
    }

  /**
   * Render 'ContactBox' widget output on the frontend.
   *
   * @access protected
   */
    protected function render() {

        $settings   = $this->get_settings_for_display();

        $args       = array(
            'style'             => $settings['style'],
            'show_map'          => $settings['show_map'],
            'map_position'      => $settings['map_position'],
            'show_socials'      => $settings['show_socials'],
            'email'             => $settings['email'],
            'telephone'         => $settings['telephone'],
            'address'           => $settings['address'],
            'type'              => $settings['type'],
            'zoom'              => $settings['zoom']['size'],
            'zoom_wheel'        => $settings['zoom_wheel'],
            'marker_info'       => $settings['marker_info'],
            'show_mapcontrols'  => $settings['show_mapcontrols'],
            'latitude'          => $settings['latitude']['size'],
            'longitude'         => $settings['longitude']['size'],
            'height'            => $settings['height']['size']
        );

        // pass the args through the corresponding shortcode callback
        echo auxin_widget_contact_box( $args );
    }

}
