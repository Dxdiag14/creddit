<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements\Theme_Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Current_Time' widget.
 *
 * Elementor widget that displays an 'Current_Time'.
 *
 * @since 1.0.0
 */
class Current_Time extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Current_Time' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_current_time';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Current_Time' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Current Time', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Current_Time' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-date auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Current_Time' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-core', 'auxin-theme-elements' );
    }

    /**
     * Register 'Current_Time' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  button_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'general',
            array(
                'label'      => __('General', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'type',
            array(
                'label'       => __('Type of time', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'custom',
                'options'     => array(
                   'custom'    => __('Custom'   , 'auxin-elements' ),
                   'mysql'     => __('MySql' , 'auxin-elements' ),
                   'timestamp' => __('TimeStamp'    , 'auxin-elements' )
                )
            )
        );

        $this->add_control(
            'date_format',
            array(
                'label'        => __('Date Format String','auxin-elements' ),
                'type'         => Controls_Manager::TEXT,
                'default'      => get_option( 'date_format' ),
                'condition'    => array(
                    'type' => array('custom'),
                )
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label'      => __('Align','auxin-elements'),
                'type'       => Controls_Manager::CHOOSE,
                'options'    => array(
                    'left' => array(
                        'title' => __( 'Left', 'auxin-elements' ),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'auxin-elements' ),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'auxin-elements' ),
                        'icon' => 'fa fa-align-right',
                    ),
                ),
                'toggle'     => true,
                'selectors'  => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  style
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'text_section',
            array(
                'label'      => __('Text', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );


        $this->add_control(
            'text_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-current-time' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-current-time',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'text_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-current-time'
            )
        );

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
    $type     = $settings['type'] === 'custom' ? $settings['date_format'] : $settings['type'];

    echo sprintf( '<div class="aux-current-time">%s</div>', current_time( esc_html( $type ) ) );
  }

}
