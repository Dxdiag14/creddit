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
 * Elementor 'Tabs' widget.
 *
 * Elementor widget that displays an 'Tabs' with lightbox.
 *
 * @since 1.0.0
 */
class Tabs extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Tabs' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_tabs';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Tabs' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Tabs', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Tabs' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-tabs auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Tabs' widget icon.
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
     * Register 'Tabs' widget controls.
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
                'label'   => __( 'Tab Items', 'auxin-elements' ),
                'type'    => Controls_Manager::REPEATER,
                'default' => array(
                    array(
                        'tab_label' => __( 'Tab #1', 'auxin-elements' ),
                        'content'   => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'auxin-elements' )
                    ),
                    array(
                        'tab_label' => __( 'Tab #2', 'auxin-elements' ),
                        'content'   => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'auxin-elements' )
                    ),
                    array(
                        'tab_label' => __( 'Tab #3', 'auxin-elements' ),
                        'content'   => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'auxin-elements' )
                    )
                ),
                'fields' => array(
                    array(
                        'name'        => 'tab_label',
                        'label'       => __( 'Title & Content', 'auxin-elements' ),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => __( 'Tab Title' , 'auxin-elements' ),
                        'dynamic'     => array(
                            'active'      => true
                        ),
                        'label_block' => true
                    ),
                    array(
                        'name'       => 'content',
                        'label'      => __( 'Content', 'auxin-elements' ),
                        'type'       => Controls_Manager::WYSIWYG,
                        'default'    => __( 'Tab Content', 'auxin-elements' ),
                        'show_label' => false,
                    )
                ),
                'title_field' => '{{{ tab_label }}}'
            )
        );

        $this->add_control(
            'skin',
            array(
                'label'       => __('Skin','auxin-elements' ),
                'type'        => Controls_Manager::SELECT, //'aux-visual-select',
                'options'     => array(
                    'bordered'          => __('Bordered', 'auxin-elements' ),
                    'aux-stripe'        => __('No border', 'auxin-elements')
                ),
                'default'     => 'bordered'
            )
        );

        /*$this->add_control(
            'type',
            array(
                'label'       => __('Type','auxin-elements' ),
                'type'        => Controls_Manager::SELECT, //'aux-visual-select',
                'options'     => array(
                    'horizontal'      => __('Horizontal', 'auxin-elements' ),
                    'vertical'        => __('Vertical'  , 'auxin-elements')
                ),
                'default'     => 'horizontal'
            )
        );*/

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*   Tabs Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'tab_section',
            array(
                'label' => __( 'Tabs', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'tab_cursor',
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
                    '{{WRAPPER}} .tabs > li:not(.active)' => 'cursor: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'tab_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tabs > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        /*$this->add_responsive_control(
            'tab_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .tabs > li' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );*/

        $this->add_control(
            'tab_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tabs > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
                ),
                'allowed_dimensions' => 'all',
                'separator'  => 'after'
            )
        );

        $this->start_controls_tabs( 'tab_status' );

        $this->start_controls_tab(
            'tab_status_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'tab_boxshadow_normal',
                'label'     => __( 'Box Shadow', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .tabs > li',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'tab_border_normal',
                'selector'  => '{{WRAPPER}} .tabs > li',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'tab_background_normal',
                'selector'  => '{{WRAPPER}} .tabs > li',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'tab_status_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'tab_boxshadow_hover',
                'label'     => __( 'Box Shadow Normal', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .tabs > li:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'tab_border_hover',
                'selector'  => '{{WRAPPER}} .tabs > li:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'tab_background_hover',
                'selector'  => '{{WRAPPER}} .tabs > li:hover',
                'separator' => 'none'
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
                'label'     => __( 'Box Shadow Normal', 'auxin-elements' ),
                'selector'  => '{{WRAPPER}} .tabs > li.active',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'title_bar_border_active',
                'selector'  => '{{WRAPPER}} .tabs > li.active',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'title_bar_background_active',
                'selector'  => '{{WRAPPER}} .tabs > li.active',
                'separator' => 'none'
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
                    '{{WRAPPER}} .tabs a' => 'color: {{VALUE}} !important;'
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
                    '{{WRAPPER}} .tabs li:hover a' => 'color: {{VALUE}} !important;',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .tabs a'
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
                    '{{WRAPPER}} .tabs-content .entry-editor' => 'color: {{VALUE}}'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .tabs-content .entry-editor'
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
                    '{{WRAPPER}} .tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tabs-content' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
                    '{{WRAPPER}} .tabs-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
                ),
                'allowed_dimensions' => 'all',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'content_shadow',
                'selector'  => '{{WRAPPER}} .tabs-content',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'content_border',
                'selector'  => '{{WRAPPER}} .tabs-content',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'content_background',
                'selector'  => '{{WRAPPER}} .tabs-content',
                'separator' => 'none'
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render 'Tabs' widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        add_filter( 'auxin/core_elements/dynamic_tags/shortcode/should_escape', '__return_false' );

        $settings = $this->get_settings_for_display();

        $args     = array(
            'style'        => $settings['skin'],
            'tabs'         => $settings['tab_items'],
            //'type'         => $settings['type'],
            'tab_id_prefix'=> substr( $this->get_id_int(), 0, 3 )
        );

        // pass the args through the corresponding shortcode callback
        echo auxin_widget_tabs_callback( $args );
        
        add_filter( 'auxin/core_elements/dynamic_tags/shortcode/should_escape', '__return_true' );
    }


    /**
     * Render tabs element in the editor.
     *
     * @access protected
     */
    protected function _content_template() {
        ?>
        <section class="widget-container aux-widget-tabs">
            <div class="widget-container widget-tabs {{{ settings.style }}}">
                <div class="widget-inner">
                    <ul class="tabs">
                    <#
                    if ( settings.tab_items ) {
                        var tabindex = view.getIDInt().toString().substr( 0, 3 );

                        _.each( settings.tab_items, function( item, index ) {
                            var tabLabelKey = view.getRepeaterSettingKey( 'tab_label', 'tab_items', index ),
                                IdNumber = tabindex + index + 1;

                            view.addRenderAttribute( tabLabelKey, {
                                'id': 'aux-tab-' + IdNumber,
                                'href': '',
                                'tabindex': IdNumber,
                                'role': 'tab',
                                'aria-controls': 'aux-tab-content-' + IdNumber
                            } );
                            #>
                            <li><a {{{ view.getRenderAttributeString( tabLabelKey ) }}}>{{{ item.tab_label }}}</a></li>
                            <#
                        });
                    }
                    #>
                    </ul>
                    <ul class="tabs-content">
                    <#
                    if ( settings.tab_items ) {
                        var tabindex = view.getIDInt().toString().substr( 0, 3 );

                        _.each( settings.tab_items, function( item, index ) {
                            var tabContentKey = view.getRepeaterSettingKey( 'content', 'tab_items', index ),
                                IdNumber = tabindex + index + 1;

                            view.addRenderAttribute( tabContentKey, {
                                'id': 'aux-tab-content-' + IdNumber,
                                'class': [ 'entry-editor' ],
                                'role': 'tabpanel',
                                'aria-labelledby': 'aux-tab-' + IdNumber
                            } );

                            view.addInlineEditingAttributes( tabContentKey, 'advanced' );
                            #>
                            <li>
                                <div {{{ view.getRenderAttributeString( tabContentKey ) }}}>
                                <p>{{{ item.content }}}</p>
                                </div>
                            </li>
                            <#
                        });
                    }
                    #>
                    </ul>
                </div>
            </div>
        </section>
        <?php
    }

}
