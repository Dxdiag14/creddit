<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements\Theme_Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'SearchBox' widget.
 *
 * Elementor widget that displays an 'SearchBox'.
 *
 * @since 1.0.0
 */
class SearchBox extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'SearchBox' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_search_box';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'SearchBox' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Search Form', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'SearchBox' widget icon.
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
     * Retrieve 'SearchBox' widget icon.
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
     * Register 'SearchBox' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'general',
            array(
                'label'      => __('General', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'type',
            array(
                'label'       => __('Type', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'icon',
                'options'     => array(
                   'form'    => __('Form' , 'auxin-elements' ),
                   'icon'    => __('Icon' , 'auxin-elements' )
                )
            )
        );

        $this->add_control(
            'submit_type',
            array(
                'label'       => __('Submit Button Type', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'none',
                'options'     => array(
                   'none'   => __('None'   , 'auxin-elements' ),
                   'icon'   => __('Icon'   , 'auxin-elements' ),
                   'button' => __('Button' , 'auxin-elements' ),
                ),
                'condition' => array(
                    'type' => 'form'
                )
            )
        );

        $this->add_control(
            'aux_search_icon',
            array(
                'label'       => __('Icon','auxin-elements' ),
                'description' => __('Please choose an icon from the list.', 'auxin-elements'),
                'type'        => Controls_Manager::ICONS,
                'conditions'   => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'type',
                            'operator' => '===',
                            'value'    => 'icon'
                        ),
                        array(
                            'name'     => 'submit_type',
                            'operator' => '===',
                            'value'    => 'icon'
                        )
                    )
                )
            )
        );

        $this->add_control(
            'has_category',
            array(
                'label'       => __('Search by Category', 'auxin-elements'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => true,
                'default'      => false,
                'condition' => array(
                    'type' => 'form'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*  Icon Section
        /*-------------------------------------*/
        $this->start_controls_section(
            'icon_section',
            array(
                'label'     => __( 'Icon', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'conditions'   => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'type',
                            'operator' => '===',
                            'value'    => 'icon'
                        ),
                        array(
                            'name'     => 'submit_type',
                            'operator' => '===',
                            'value'    => 'icon'
                        )
                    )
                )
            )
        );

        $this->add_control(
            'icon_background_color',
            array(
                'label'       => __('Background Color', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'default'     => 'transparent',
                'selectors'  => array(
                    '{{WRAPPER}} .aux-search-form .aux-submit-icon-container,{{WRAPPER}} .aux-search-section button.aux-search-icon' => 'background-color: {{VALUE}}',
                )
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => __( 'Size', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'max' => 100
                    ),
                    'em' => array(
                        'max' => 10
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-search-icon:before, {{WRAPPER}} .aux-submit-icon-container:before' => 'font-size: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label'       => __('Icon color', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#303030',
                'selectors' => array(
                    '{{WRAPPER}} .aux-search-icon:before, {{WRAPPER}} .aux-submit-icon-container:before' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .aux-search-icon, {{WRAPPER}} .aux-submit-icon-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*  Icon Section
        /*-------------------------------------*/
        $this->start_controls_section(
            'form_section',
            array(
                'label'     => __( 'Form', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'conditions'   => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'type',
                            'operator' => '===',
                            'value'    => 'form'
                        )
                    )
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'form_typgraphy',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-search-form .aux-search-field'
            )
        );


        $this->add_responsive_control(
            'form_width',
            array(
                'label'      => __('Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 120,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 120,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1900,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-search-form .aux-search-field' => 'max-width:{{SIZE}}{{UNIT}};'
                ),
            )
        );

        $this->add_responsive_control(
            'form_margin',
            array(
                'label'      => __( 'Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-search-form .aux-search-field' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'form_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-search-form .aux-search-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_control(
            'form_color',
            array(
                'label'       => __('Form Background Color', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#FFF',
                'selectors'  => array(
                    '{{WRAPPER}} .aux-search-form .aux-search-field, {{WRAPPER}} .aux-search-form select' => 'background-color: {{VALUE}}',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'form_border',
                'selector'  => '{{WRAPPER}} .aux-search-form .aux-search-field',
                'separator' => 'none'
            )
        );

        $this->add_control(
            'form_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-search-form .aux-search-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
                ),
                'allowed_dimensions' => 'all',
                'separator'  => 'after'
            )
        );

        $this->end_controls_section();

        /*  Icon Section
        /*-------------------------------------*/
        $this->start_controls_section(
            'button_section',
            array(
                'label'     => __( 'Button', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'conditions'   => array(
                    'relation' => 'and',
                    'terms'    => array(
                        array(
                            'name'     => 'type',
                            'operator' => '===',
                            'value'    => 'form'
                        ),
                        array(
                            'name'     => 'submit_type',
                            'operator' => '===',
                            'value'    => 'button'
                        )
                    )
                )
            )
        );

        $this->add_control(
            'button_color',
            array(
                'label'       => __('Background Color', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#303030',
                'selectors'  => array(
                    '{{WRAPPER}} .aux-search-form .aux-search-submit' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .aux-search-form .aux-search-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'button_margin',
            array(
                'label'      => __( 'Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-search-form .aux-search-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'button_typgraphy',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}}  .aux-search-form .aux-search-submit'
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

        $args = [];

        $is_edit = \Elementor\Plugin::instance()->editor->is_edit_mode();
        $is_preview = isset( $_GET['preview'] ) && $_GET['preview'] ? true : false;

        if ( $is_edit || $is_preview ) {
            auxin_add_hidden_blocks();
        }

        if ( 'icon' === $settings['type'] ) {
            $args['has_form'] = false;
            $args['is_ajax']  = false;
            $args['toggle_icon_class'] = 'aux-overlay-search';

        } else {
            $args['has_toggle_icon'] = false;
            $args['has_category']    = $settings['has_category'];

            switch( $settings['submit_type'] ) {
                case 'none' :
                    $args['has_submit'] = false;
                    break;
                case 'icon' :
                    $args['has_submit_icon'] = true;
                    break;
                case 'button' :
                    $args['has_submit'] = true;
                    break;
                default :
                    break;
            }

        }

        $icon_value = ! empty( $settings['aux_search_icon']['value'] ) ? $settings['aux_search_icon']['value'] : ( ! empty( $settings['icon'] ) ? $settings['icon'] : 'auxicon-search-4' ) ;

        $args['icon_classname'] = $icon_value;
        $args['css_class'] = 'aux-search-elementor-element';

        echo auxin_get_search_box( $args );
    }

}
