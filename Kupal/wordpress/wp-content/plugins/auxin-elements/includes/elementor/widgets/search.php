<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Audio' widget.
 *
 * Elementor widget that displays an 'Audio' with lightbox.
 *
 * @since 1.0.0
 */
class Search extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Audio' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_search';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Audio' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Search', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Audio' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-search auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Audio' widget icon.
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
     * Register 'Audio' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  skin_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'display_section',
            array(
                'label'      => __('Display', 'auxin-elements' )
            )
        );

        $this->add_control(
            'has_submit',
            array(
                'label'        => __('Display Submit','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'has_submit_icon',
            array(
                'label'        => __('Display Submit Icon','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'has_submit!' => 'yes',
                )
            )
        );

        $this->add_control(
            'has_field',
            array(
                'label'        => __('Display Field','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'has_form',
            array(
                'label'        => __('Display Form','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

       //  $this->add_control(
       //      'has_toggle_icon',
       //      array(
       //          'label'        => __('Display Toggle','auxin-elements' ),
       //          'type'         => Controls_Manager::SWITCHER,
       //          'label_on'     => __( 'On', 'auxin-elements' ),
       //          'label_off'    => __( 'Off', 'auxin-elements' ),
       //          'return_value' => 'yes',
       //          'default'      => 'no'
       //      )
       //  );

       // $this->add_control(
       //      'toggle_icon_class',
       //      array(
       //          'label'        => __('Icon for toggle','auxin-elements' ),
       //          'type'         => Controls_Manager::ICON,
       //          'condition'    => array(
       //              'has_toggle_icon' => 'yes',
       //          )
       //      )
       //  );

        $this->end_controls_section();
    }

  /**
   * Render image box widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since 1.0.0
   * @access protected
   */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $args     = array(
            'has_field'         => $settings['has_field'],
            'has_submit'        => $settings['has_submit'],
            'has_form'          => $settings['has_form'],
            'has_submit_icon'   => $settings['has_submit_icon'],
            'has_toggle_icon'   => false,
            'toggle_icon_class' => '',
        );

        echo auxin_widget_search_field_callcack( $args );
    }

}
