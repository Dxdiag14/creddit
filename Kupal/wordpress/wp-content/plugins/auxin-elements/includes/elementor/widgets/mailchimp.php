<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'MailChimp' widget.
 *
 * Elementor widget that displays an 'MailChimp' with lightbox.
 *
 * @since 1.0.0
 */
class MailChimp extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'MailChimp' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_mailchimp';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'MailChimp' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('MailChimp', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'MailChimp' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-mailchimp auxin-badge';
    }

    /**
     * Get forms list.
     *
     * Retrieve 'MailChimp' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_forms() {
        $options = array( 0 => __('Select the form to show', 'auxin-elements' ) ) ;

        if( ! function_exists('mc4wp_get_forms') ){
            return $options;
        }

        $forms   = mc4wp_get_forms();
        foreach( $forms as $form ) {
            $options[ $form->ID ] = $form->name;
        }

        return $options;
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'MailChimp' widget icon.
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
     * Register 'MailChimp' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Content TAB
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'forms_section',
            [
                'label'      => __('Form', 'auxin-elements' ),
            ]
        );

        $this->add_control(
            'form_type',
            [
                'label'       => __( 'Form Type', 'auxin-elements' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'options'     => [
                    'default' => __( 'Defaults'  , 'auxin-elements' ),
                    'custom'  => __( 'Custom'  , 'auxin-elements' )
                ]
            ]
        );

        $this->add_control(
            'form_id',
            [
                'label'       => __( 'MailChimp Sign-Up Form', 'auxin-elements' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 0,
                'options'     => $this->get_forms(),
                'condition'   => [
                    'form_type' => ['default']
                ]
            ]
        );

        $this->add_control(
            'html',
            [
                'label'       => __( 'Custom Form', 'auxin-elements' ),
                'type'        => Controls_Manager::CODE,
                'language'    => 'html',
                'description' => __( 'Enter your custom form markup', 'auxin-elements' ),
                'condition'   => [
                    'form_type' => ['custom']
                ]
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'text_input_section',
            [
                'label'      => __('Input', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]',
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'text_input_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_input_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]' => 'max-width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'text_input_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'input_border',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]'
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label' => __( 'Border Radius', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => __( 'Padding', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        // Background and Box Shadow for input - START
        $this->start_controls_tabs( 'input_tabs' );

        $this->start_controls_tab(
            'input_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'input_background',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="text"], {{WRAPPER}} .mc4wp-form input[type="email"]',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="text"], {{WRAPPER}} .mc4wp-form input[type="email"]'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'input_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'input_background_hover',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="text"]:hover, {{WRAPPER}} .mc4wp-form input[type="email"]:hover',
                'types' => [ 'classic', 'gradient']
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_box_shadow_hover',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="text"]:hover,{{WRAPPER}} .mc4wp-form input[type="email"]:hover'
            ]
        );

        $this->add_control(
            'input_transition',
            [
                'label' => __( 'Transition Duration', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="text"],{{WRAPPER}} .mc4wp-form input[type="email"]' => "transition:all ease-out {{SIZE}}s;"
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // Background and Box Shadow for input - END


        $this->end_controls_section();

        $this->start_controls_section(
            'placeholder_section',
            [
                'label'    => __('Input Placeholder Text', 'auxin-elements' ),
                'tab'      => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'placeholder_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="text"]::placeholder,{{WRAPPER}} .mc4wp-form input[type="email"]::placeholder'
            ]
        );

        $this->add_control(
            'placeholder_color',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="text"]::placeholder,{{WRAPPER}} .mc4wp-form input[type="email"]::placeholder' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'submit_input_section',
            [
                'label'      => __('Subscribe Button', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submit_input_typography',
                'label' => __( 'Typography', 'auxin-elements' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="submit"]',
            ]
        );

        $this->add_responsive_control(
            'submit_input_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_input_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="submit"]' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_input_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="submit"]' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submit_border',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="submit"]'
            ]
        );

        $this->add_responsive_control(
            'submit_border_radius',
            [
                'label' => __( 'Border Radius', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="submit"]' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_input_padding',
            [
                'label' => __( 'Padding', 'auxin-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="submit"]' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        // Background and box shadow Options for submit button - START
        $this->start_controls_tabs( 'submit_tabs' );

        $this->start_controls_tab(
            'submit_input_tab_normal_state',
            [
                'label' => __( 'Normal', 'auxin-elements' ),
            ]
        );

        $this->add_control(
            'submit_input_color_normal',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submit_input_background',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="submit"]',
                'types' => [ 'classic', 'gradient'],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'sbumit_input_box_shadow',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="submit"]'
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'submit_input_tab_hover_state',
            [
                'label' => __( 'Hover', 'auxin-elements' ),
            ]
        );

        $this->add_control(
            'submit_input_color_hover',
            [
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="submit"]:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submit_input_background_hover',
                'types' => [ 'classic', 'gradient'],
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="submit"]:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'sbumit_input_box_shadow_hover',
                'selector' => '{{WRAPPER}} .mc4wp-form input[type="submit"]:hover'
            ]
        );

        $this->add_control(
            'submit_input_hover_transition',
            [
                'label' => __( 'Transition Duration', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form input[type="submit"]' => "transition: all ease-out {{SIZE}}s;"
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // Background and box shadow Options for submit button - END


        $this->end_controls_section();


        $this->start_controls_section(
            'form_container_section',
            [
                'label'     => __('Form Container', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'form_container_width',
            [
                'label' => __( 'Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields' => 'width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'form_container_max_width',
            [
                'label' => __( 'Max Width', 'auxin-elements' ),
                'size_units' => [ 'px','em', '%'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields' => 'max-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'form_container_height',
            [
                'label' => __( 'Height', 'auxin-elements' ),
                'size_units' => [ 'px', 'em'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields' => 'height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

  /**
   * Render mailchimp custom form markup
   *
   *
   * @since 1.0.0
   * @access protected
   */
  public function custom_form( $content ) {
    $settings   = $this->get_settings_for_display();

    if( ! empty( $settings['html'] ) ) {
        $content = $settings['html'];
    }

    return $content;
  }

    /**
    * Render mailchimp widget output on the frontend.
    *
    * Written in PHP and used to generate the final HTML.
    *
    * @since 1.0.0
    * @access protected
    */
    protected function render() {
        // Check whether required resources are available
        if( ! function_exists('mc4wp_show_form') ) {
            auxin_elementor_plugin_missing_notice( array( 'plugin_name' => __( 'MailChimp', 'auxin-elements' ) ) );
            return;
        }

        $settings = $this->get_settings_for_display();

        if(  $settings['form_type'] === 'custom' ) {
            add_filter( 'mc4wp_form_content', array( $this, 'custom_form'), 10, 1 );
            $settings['form_id'] = 0;
        } elseif( get_post_type( $settings['form_id'] ) !== 'mc4wp-form' ){
            $settings['form_id'] = 0;
        }

        return mc4wp_show_form( $settings['form_id'] );
    }

}
