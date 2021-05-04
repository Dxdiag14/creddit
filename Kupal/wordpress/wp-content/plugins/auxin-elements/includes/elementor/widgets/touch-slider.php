<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'TouchSlider' widget.
 *
 * Elementor widget that displays an 'TouchSlider' with lightbox.
 *
 * @since 1.0.0
 */
class TouchSlider extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'TouchSlider' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_touch_slider';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'TouchSlider' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Simple Image Slider', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'TouchSlider' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-slider-device auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'TouchSlider' widget icon.
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
     * Register 'TouchSlider' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  images_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'images_section',
            array(
                'label' => __('Images', 'auxin-elements' )
            )
        );

        $this->add_control(
            'images',
            array(
                'label' => __( 'Add Images', 'auxin-elements' ),
                'type' => Controls_Manager::GALLERY,
                'show_label' => false,
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  display_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'display_section',
            array(
                'label' => __('Display', 'auxin-elements' )
            )
        );

        $this->add_control(
            'arrows',
            array(
                'label'        => __('Arrow navigation','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );


        $this->add_control(
            'loop',
            array(
                'label'        => __('Loop navigation','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'slideshow',
            array(
                'label'        =>  __('Slideshow','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'slideshow_delay',
            array(
                'label'       => __( 'Autoplay delay', 'auxin-elements' ),
                'description' => __('Specifies the delay between auto-forwarding in seconds.', 'auxin-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '2',
                'condition'    => array(
                    'slideshow' => 'yes',
                )
            )
        );

        $this->add_control(
            'add_title',
            array(
                'label'        => __('Display image title','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'add_caption',
            array(
                'label'        => __('Display image caption','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  slides_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'slides_section',
            array(
                'label' => __('Slides', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'width',
            array(
                'label'       => __('Slider image width','auxin-elements' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('px'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 2000,
                        'step' => 1
                    )
                ),
                'default' => array(
                    'unit'  => 'px',
                    'size'  => 960
                )
            )
        );

        $this->add_control(
            'height',
            array(
                'label'       => __('Slider image height','auxin-elements' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('px'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 2000,
                        'step' => 1
                    )
                ),
                'default' => array(
                    'unit'  => 'px',
                    'size'  => 560
                )
            )
        );

        $this->add_control(
            'space',
            array(
                'label'       => __('Space between slides','auxin-elements' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('px'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 200,
                        'step' => 1
                    )
                ),
                'default' => array(
                    'unit'  => 'px',
                    'size'  => 0
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  info_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'info_section',
            array(
                'label'      => __('Info Container', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'add_title',
                            'operator' => '===',
                            'value' => 'yes',
                        ), array(
                            'name' => 'add_caption',
                            'operator' => '===',
                            'value' => 'yes',
                        ),
                    ),
                ),
            )
        );

        $this->add_control(
            'info_color',
            array(
                'label' => __( 'Background Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-info-container' => 'background: {{VALUE}};',
                )
            )
        );

        $this->add_responsive_control(
            'info_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-info-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  title_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'title_section',
            array(
                'label'     => __('Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'add_title' => 'yes',
                )
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-slide-title h3' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'add_title' => 'yes',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}}  .aux-slide-title h3',
                'condition' => array(
                    'add_title' => 'yes',
                )
            )
        );

        $this->add_responsive_control(
            'title_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-slide-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'add_title' => 'yes',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  caption_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'caption_section',
            array(
                'label'     => __('Caption', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'add_caption' => 'yes',
                )
            )
        );

        $this->add_control(
            'caption_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-slide-info' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'add_caption' => 'yes',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'caption_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}}  .aux-slide-info',
                'condition' => array(
                    'add_caption' => 'yes',
                )
            )
        );

        $this->add_responsive_control(
            'caption_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-slide-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'add_caption' => 'yes',
                )
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

        if ( ! $settings['images'] ) {
            return;
        }

        $ids  = wp_list_pluck( $settings['images'], 'id' );
        $args = array(
            // images_section
            'images'          => $ids,

            // display_section
            'arrows'          => $settings['arrows'],
            'loop'            => $settings['loop'],
            'slideshow'       => $settings['slideshow'],
            'slideshow_delay' => $settings['slideshow_delay'],
            'add_title'       => $settings['add_title'],
            'add_caption'     => $settings['add_caption'],

            // slides_section
            'width'           => $settings['width']['size'],
            'height'          => $settings['height']['size'],
            'space'           => $settings['space']['size'],
        );

        // get the shortcode base blog page
        echo auxin_touch_slider_callback( $args );
    }

}
