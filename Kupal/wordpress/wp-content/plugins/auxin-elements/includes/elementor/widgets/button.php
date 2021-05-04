<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Button' widget.
 *
 * Elementor widget that displays an 'Button' with lightbox.
 *
 * @since 1.0.0
 */
class Button extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Button' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_button';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Button' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Button', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Button' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-button auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Button' widget icon.
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
     * Register 'Button' widget controls.
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
            'button_section',
            array(
                'label'      => __('Button', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'label',
            array(
                'label'        => __('Button label','auxin-elements' ),
                'type'         => Controls_Manager::TEXT,
                'default'      => 'Read More'
            )
        );

        $this->add_control(
            'link',
            array(
                'label'         => __('Link','auxin-elements' ),
                'description'   => __('If you want to link your button.', 'auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => true,
				'dynamic' => array(
					'active' => true
                )
            )
        );

        $this->add_control(
            'open_video_in_lightbox',
            array(
                'label'        => __('Open Video in Lightbox','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => ''
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  general_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'general_section',
            array(
                'label'      => __('Wrapper', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'size',
            array(
                'label'       => __('Button size', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'medium',
                'options'     => array(
                    'exlarge' => __('Exlarge', 'auxin-elements' ),
                    'large'   => __('Large'  , 'auxin-elements' ),
                    'medium'  => __('Medium' , 'auxin-elements' ),
                    'small'   => __('Small'  , 'auxin-elements' ),
                    'tiny'    => __('Tiny'   , 'auxin-elements' )
                )
            )
        );

        $this->add_control(
            'uppercase',
            array(
                'label'        => __('Uppercase label','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'border',
            array(
                'label'       => __('Button shape','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'none' => array(
                        'label' => __('Box', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    'round'   => array(
                        'label' => __('Round', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-curved.svg'
                    ),
                    'curve'  => array(
                        'label' => __('Curve', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-rounded.svg'
                    )
                ),
                'default'     => 'none'
            )
        );

        $this->add_control(
            'style',
            array(
                'label'       => __('Button style','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'none'    => array(
                        'label' => __('Normal', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    '3d'      => array(
                        'label' => __('3D', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-3d.svg'
                    ),
                    'outline' => array(
                        'label' => __('Outline', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-outline.svg'
                    )
                ),
                'default'     => 'none'
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
                'default'    => 'center',
                'toggle'     => true,
                'selectors'  => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                )
            )
        );

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  sking_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'sking_section',
            array(
                'label'      => __('Skin', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'color_name',
            array(
                'label'       => __('Skin', 'auxin-elements'),
                'type'        => 'aux-visual-select',
                'default'     => 'carmine-pink',
                'options'     => auxin_get_famous_colors_list()
            )
        );

        $this->start_controls_tabs( 'button_background' );

        $this->start_controls_tab(
            'button_bg_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'background',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-button',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'box_shadow',
                'selector'  => '{{WRAPPER}} .aux-button'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_bg_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'hover_background',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-button .aux-overlay::after',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'hover_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-button:hover'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  icon_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'icon_section',
            array(
                'label'      => __('Icon', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'aux_button_icon',
            array(
                'label'        => __('Icon for button','auxin-elements' ),
                'type'         => Controls_Manager::ICONS,
            )
        );

        $this->add_control(
            'icon_align',
            array(
                'label'       => __('Icon alignment', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'options'     => array(
                   'default'        =>  __('Default'            , 'auxin-elements' ),
                   'left'           =>  __('Left'               , 'auxin-elements' ),
                   'right'          =>  __('Right'              , 'auxin-elements' ),
                   'over'           =>  __('Over'               , 'auxin-elements' ),
                   'left-animate'   =>  __('Animate from Left'  , 'auxin-elements' ),
                   'right-animate'  =>  __('Animate from Right' , 'auxin-elements' )
                )
            )
        );

        $this->add_responsive_control(
            'btn_icon_size',
            array(
                'label'      => __( 'Icon Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 512,
                        'step' => 2,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'icon_margin',
            array(
                'label'      => __( 'Icon Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->start_controls_tabs( 'button_icon_color' );

        $this->start_controls_tab(
            'icon_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'hover_icon_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-button:hover .aux-icon' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'icon_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  text_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'text_section',
            array(
                'label'      => __('Text', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs( 'button_text' );

        $this->start_controls_tab(
            'button_text_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'text_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-text' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-button',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'text_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-text'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_text_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'hover_text_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-button:hover .aux-text' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'hover_text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-button:hover',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'hover_text_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-text'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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

    $settings   = $this->get_settings_for_display();

    $icon_value = ! empty( $settings['aux_button_icon']['value'] ) ? $settings['aux_button_icon']['value'] : ( ! empty( $settings['icon'] ) ? $settings['icon'] : '' ) ;
    
    $btn_target = $settings['link']['is_external'] ? '_blank' : '_self';

    $args       = array(
        'label'                  => $settings['label'],
        'size'                   => $settings['size'],
        'border'                 => $settings['border'],
        'style'                  => $settings['style'],
        'uppercase'              => $settings['uppercase'],
        'icon'                   => $icon_value,
        'icon_align'             => $settings['icon_align'],
        'color_name'             => $settings['color_name'],
        'link'                   => $settings['link']['url'],
        'nofollow'               => $settings['link']['nofollow'],
        'target'                 => $btn_target,
        'open_video_in_lightbox' => $settings['open_video_in_lightbox'],
    );

    // get the shortcode base blog page
    echo auxin_widget_button_callback( $args );

  }

}