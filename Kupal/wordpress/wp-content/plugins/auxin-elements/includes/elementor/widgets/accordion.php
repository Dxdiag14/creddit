<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Accordion' widget.
 *
 * Elementor widget that displays an 'Accordion' with lightbox.
 *
 * @since 1.0.0
 */
class Accordion extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Accordion' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_accordion';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Accordion' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Accordion', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Accordion' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-accordion auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Accordion' widget icon.
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
     * Register 'Accordion' widget controls.
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
            'tab_items_section',
            array(
                'label'      => __('Content', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'tab_items',
            array(
                'label'   => __( 'Accordion Items', 'auxin-elements' ),
                'type'    => Controls_Manager::REPEATER,
                'default' => array(
                    array(
                        'accordion_label' => __( 'Accordion #1', 'auxin-elements' ),
                        'content'         => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'auxin-elements' )
                    ),
                    array(
                        'accordion_label' => __( 'Accordion #2', 'auxin-elements' ),
                        'content'         => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'auxin-elements' )
                    )
                ),
                'fields' => array(
                    array(
                        'name'        => 'accordion_label',
                        'label'       => __( 'Title & Content', 'auxin-elements' ),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => __( 'Accordion Title' , 'auxin-elements' ),
                        'dynamic'     => array(
                            'active'      => true
                        ),
                        'label_block' => true
                    ),
                    array(
                        'name'       => 'content',
                        'label'      => __( 'Content', 'auxin-elements' ),
                        'type'       => Controls_Manager::WYSIWYG,
                        'default'    => __( 'Accordion Content', 'auxin-elements' ),
                        'show_label' => false,
                    )
                ),
                'title_field' => '{{{ accordion_label }}}'
            )
        );

        $this->add_control(
            'type',
            array(
                'label'       => __('Type','auxin-elements' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT, //'aux-visual-select',
                'options'     => array(
                    'false'   => __('Toggle', 'auxin-elements'),
                    'true'    => __('Accordion (auto close other open tabs)', 'auxin-elements')
                ),
                'default'     => 'true'
            )
        );

        $this->add_control(
            'title_tag',
            array(
                'label'   => __( 'Title HTML Tag', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'div'     => 'div',
                    'section' => 'section',
                    'h1'      => 'H1',
                    'h2'      => 'H2',
                    'h3'      => 'H3',
                    'h4'      => 'H4',
                    'h5'      => 'H5',
                    'h6'      => 'H6'
                ),
                'default'   => 'h5'
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*   Accordion Item Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'item_container_section',
            array(
                'label' => __( 'Item Wrapper', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'item_container_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-toggle-item' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_control(
            'item_container_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-toggle-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
                ),
                'allowed_dimensions' => 'all',
                'separator'  => 'after'
            )
        );

        $this->start_controls_tabs( 'item_container_status' );

        $this->start_controls_tab(
            'item_container_status_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'item_container_boxshadow_normal',
                'label'     => __( 'Box Shadow', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .aux-toggle-item',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'item_container_background_normal',
                'selector'  => '{{WRAPPER}} .aux-toggle-item',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'item_container_border_normal',
                'selector'  => '{{WRAPPER}} .aux-toggle-item',
                'separator' => 'before'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'item_container_status_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'item_container_boxshadow_hover',
                'label'     => __( 'Box Shadow', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .aux-toggle-item:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'item_container_background_hover',
                'selector'  => '{{WRAPPER}} .aux-toggle-item:hover',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'item_container_border_hover',
                'selector'  => '{{WRAPPER}} .aux-toggle-item:hover',
                'separator' => 'before'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'item_container_status_active',
            array(
                'label' => __( 'Selected' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'item_container_boxshadow_active',
                'label'     => __( 'Box Shadow', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .active.aux-toggle-item',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'item_container_background_active',
                'selector'  => '{{WRAPPER}} .active.aux-toggle-item',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'item_container_border_active',
                'selector'  => '{{WRAPPER}} .active.aux-toggle-item',
                'separator' => 'before'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();

        /*   Title Bar Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_bar_section',
            array(
                'label' => __( 'Title Bar', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'title_bar_cursor',
            array(
                'label'   => __( 'Mouse Cursor', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'default' => __( 'Default', 'auxin-elements' ),
                    'pointer' => __( 'Pointer', 'auxin-elements' ),
                    'zoom-in' => __( 'Zoom', 'auxin-elements' ),
                    'help'    => __( 'Help', 'auxin-elements' )
                ),
                'default'   => 'pointer',
                'selectors'  => array(
                    '{{WRAPPER}} .widget-inner > :not(.active) .aux-toggle-header' => 'cursor: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'title_bar_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-toggle-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'title_bar_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-toggle-header' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_control(
            'title_bar_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-toggle-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
                ),
                'allowed_dimensions' => 'all',
                'separator'  => 'after'
            )
        );

        $this->start_controls_tabs( 'title_bar_status' );

        $this->start_controls_tab(
            'title_bar_status_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'title_bar_boxshadow_normal',
                'label'     => __( 'Box Shadow', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .aux-toggle-header',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'title_bar_background_normal',
                'selector'  => '{{WRAPPER}} .aux-toggle-header',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'title_bar_border_normal',
                'selector'  => '{{WRAPPER}} .aux-toggle-header',
                'separator' => 'before'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'title_bar_status_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'title_bar_boxshadow_hover',
                'label'     => __( 'Box Shadow', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .aux-toggle-header:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'title_bar_background_hover',
                'selector'  => '{{WRAPPER}} .aux-toggle-header:hover',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'title_bar_border_hover',
                'selector'  => '{{WRAPPER}} .aux-toggle-header:hover',
                'separator' => 'before'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'title_bar_status_active',
            array(
                'label' => __( 'Selected' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'title_bar_boxshadow_active',
                'label'     => __( 'Box Shadow', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .active .aux-toggle-header',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'title_bar_background_active',
                'selector'  => '{{WRAPPER}} .active .aux-toggle-header',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'title_bar_border_active',
                'selector'  => '{{WRAPPER}} .active .aux-toggle-header',
                'separator' => 'before'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();


        /*   Title Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->start_controls_tabs( 'title_colors' );

        $this->start_controls_tab(
            'title_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-toggle-header' => 'color: {{VALUE}} !important;'
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-toggle-header:hover' => 'color: {{VALUE}} !important;',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_selected',
            array(
                'label' => __( 'Selected' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'title_selected_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .active .aux-toggle-header' => 'color: {{VALUE}} !important;',
                )
            )
        );

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-toggle-header'
            )
        );

        $this->end_controls_section();

        /*   Content Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => __( 'Content', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-toggle-content' => 'color: {{VALUE}}'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-toggle-content'
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .aux-toggle-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'content_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-toggle-content' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_control(
            'content_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-toggle-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
                ),
                'allowed_dimensions' => 'all',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'content_shadow',
                'selector'  => '{{WRAPPER}} .aux-toggle-content',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'content_background',
                'selector'  => '{{WRAPPER}} .aux-toggle-content',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'content_border',
                'selector'  => '{{WRAPPER}} .aux-toggle-content',
                'separator' => 'before'
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render 'Accordion' widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $args     = array(
            'type'         => $settings['type'],
            'accordion'    => $settings['tab_items'],
            'title_tag'    => $settings['title_tag'],
            'tab_id_prefix'=> substr( $this->get_id_int(), 0, 3 )
        );

        // pass the args through the corresponding shortcode callback
        echo auxin_widget_accordion_callback( $args );
    }

    /**
     * Render accordion element in the editor.
     *
     * @access protected
     */
    protected function _content_template() {
        ?>
        <# var typeClass = settings.type == 'true' ? 'aux-type-toggle' : 'aux-type-accordion'; #>
        <section class="widget-container aux-widget-accordion widget-toggle">
            <div class="widget-inner {{{ typeClass }}}" data-toggle="{{{ settings.type }}}">
            <#
            if ( settings.tab_items ) {
                var tabindex = view.getIDInt().toString().substr( 0, 3 );

                _.each( settings.tab_items, function( item, index ) {
                    var tabLabelKey = view.getRepeaterSettingKey( 'accordion_label', 'tab_items', index ),
                        tabContentKey = view.getRepeaterSettingKey( 'content', 'tab_items', index ),
                        IdNumber = tabindex + index + 1;

                    view.addRenderAttribute( tabLabelKey, {
                        'id': 'aux-toggle-header-' + IdNumber,
                        'class': [ 'aux-toggle-header', 'toggle-header' ],
                        'tabindex': IdNumber,
                        'role': 'tab',
                        'aria-controls': 'aux-toggle-content-' + IdNumber
                    } );

                    view.addRenderAttribute( tabContentKey, {
                        'id': 'aux-toggle-content-' + IdNumber,
                        'class': [ 'aux-toggle-content', 'toggle-content' ],
                        'role': 'tabpanel',
                        'aria-labelledby': 'aux-toggle-header-' + IdNumber
                    } );

                    view.addInlineEditingAttributes( tabContentKey, 'advanced' );
                    #>
                    <section>
                        <{{{ settings.title_tag }}} {{{ view.getRenderAttributeString( tabLabelKey ) }}}>
                            {{{ item.accordion_label }}}
                        </{{{ settings.title_tag }}}>
                        <div {{{ view.getRenderAttributeString( tabContentKey ) }}}>
                            <p>{{{ item.content }}}</p>
                        </div>
                    </section>
                    <#
                });
            } #>
            </div>
        </section>
        <?php
    }

}
