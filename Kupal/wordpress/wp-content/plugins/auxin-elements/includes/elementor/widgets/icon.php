<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor icon widget.
 *
 * Elementor widget that displays an icon from over 600+ icons.
 *
 * @since 1.0.0
 */
class Icon extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve icon widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_icon';
    }

    /**
     * Get widget title.
     *
     * Retrieve icon widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Icon Picker', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve icon widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-favorite auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the icon widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return array( 'auxin-core' );
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return array( 'icon' );
    }

    /**
     * Register icon widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_icon',
            array(
                'label' => __( 'Icon', 'elementor' ),
            )
        );

        $this->add_control(
            'aux_new_icon',
            array(
                'label'   => __( 'Icon', 'elementor' ),
                'type'    => Controls_Manager::ICONS
            )
        );

        $this->add_control(
            'view',
            array(
                'label' => __( 'View', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'default' => __( 'Default', 'elementor' ),
                    'stacked' => __( 'Stacked', 'elementor' ),
                    'framed' => __( 'Framed', 'elementor' ),
                ),
                'default' => 'default',
                'prefix_class' => 'elementor-view-',
            )
        );

        $this->add_control(
            'shape',
            array(
                'label' => __( 'Shape', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'circle' => __( 'Circle', 'elementor' ),
                    'square' => __( 'Square', 'elementor' ),
                ),
                'default' => 'circle',
                'condition' => array(
                    'view!' => 'default',
                ),
                'prefix_class' => 'elementor-shape-',
            )
        );

        $this->add_control(
            'link',
            array(
                'label' => __( 'Link', 'elementor' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true
                ),
                'placeholder' => __( 'https://your-link.com', 'elementor' ),
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label' => __( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __( 'Left', 'elementor' ),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'elementor' ),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'elementor' ),
                        'icon' => 'fa fa-align-right',
                    ),
                ),
                'default' => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-wrapper' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            array(
                'label' => __( 'Icon', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs( 'icon_colors' );

        $this->start_controls_tab(
            'icon_colors_normal',
            array(
                'label' => __( 'Normal', 'elementor' ),
            )
        );

        $this->add_control(
            'primary_color',
            array(
                'label' => __( 'Primary Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ),
            )
        );

        $this->add_control(
            'secondary_color',
            array(
                'label' => __( 'Secondary Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => array(
                    'view!' => 'default',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_colors_hover',
            array(
                'label' => __( 'Hover', 'elementor' ),
            )
        );

        $this->add_control(
            'hover_primary_color',
            array(
                'label' => __( 'Primary Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'hover_secondary_color',
            array(
                'label' => __( 'Secondary Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => array(
                    'view!' => 'default',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'hover_animation',
            array(
                'label' => __( 'Hover Animation', 'elementor' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'size',
            array(
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 6,
                        'max' => 300,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'icon_padding',
            array(
                'label' => __( 'Padding', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                ),
                'condition' => array(
                    'view!' => 'default',
                ),
            )
        );

        $this->add_control(
            'rotate',
            array(
                'label' => __( 'Rotate', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 0,
                    'unit' => 'deg',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ),
            )
        );

        $this->add_control(
            'border_width',
            array(
                'label' => __( 'Border Width', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'view' => 'framed',
                ),
            )
        );

        $this->add_control(
            'border_radius',
            array(
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'view!' => 'default',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render icon widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'wrapper', 'class', 'elementor-icon-wrapper' );

        $this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon' );

        if ( ! empty( $settings['hover_animation'] ) ) {
            $this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-animation-' . $settings['hover_animation'] );
        }

        $icon_tag = 'div';

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'icon-wrapper', 'href', $settings['link']['url'] );
            $icon_tag = 'a';

            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'icon-wrapper', 'target', '_blank' );
            }

            if ( $settings['link']['nofollow'] ) {
                $this->add_render_attribute( 'icon-wrapper', 'rel', 'nofollow' );
            }
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <<?php echo $icon_tag . ' ' . $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
                <?php 
                if ( ! empty( $settings['aux_new_icon']['value'] ) ) { 
                    \Elementor\Icons_Manager::render_icon( $settings['aux_new_icon'], [ 'aria-hidden' => 'true' ] );
                } else {
                    if ( ! empty( $settings['icon'] ) ) {
                        $this->add_render_attribute( 'icon', 'class', $settings['icon'] );
                        $this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
                    ?>
                    <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                    <?php
                    }
                }    
                ?>
            </<?php echo $icon_tag; ?>>
        </div>
        <?php
    }

    /**
     * Render icon widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _content_template() {
        ?>
        <# var  link = settings.link.url ? 'href="' + settings.link.url + '"' : '',
                iconHTML = elementor.helpers.renderIcon( view, settings.aux_new_icon, { 'aria-hidden': true }, 'i' , 'object' ),
                iconTag = link ? 'a' : 'div'; #>
        <div class="elementor-icon-wrapper">
            <{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}" {{{ link }}}>
                <# if ( iconHTML && iconHTML.rendered && settings.aux_new_icon.value !== '' ) { #>
					{{{ iconHTML.value }}}
				<# } else if ( settings.icon !== '' ) { #>
					<i class="{{ settings.icon }}" aria-hidden="true"></i>
				<# } #>
            </{{{ iconTag }}}>
        </div>
        <?php
    }
}
