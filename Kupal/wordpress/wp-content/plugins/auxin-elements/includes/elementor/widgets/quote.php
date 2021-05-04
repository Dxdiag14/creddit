<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

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
 * Elementor 'Quote' widget.
 *
 * Elementor widget that displays an 'Quote' with lightbox.
 *
 * @since 1.0.0
 */
class Quote extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Quote' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_blockquote';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Quote' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Blockquote', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Quote' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-blockquote auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Quote' widget icon.
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
     * Register 'Quote' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  content_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'content_section',
            array(
                'label'      => __('Text', 'auxin-elements' )
            )
        );

        $this->add_control(
            'content',
            array(
                'label'         => __('Content','auxin-elements' ),
                'description'   => __('Enter a text as a quote content.', 'auxin-elements'),
                'type'          => Controls_Manager::WYSIWYG,
                'placeholder'   => __( 'Type your description here', 'auxin-elements')
            )
        );

        /*$this->add_control(
            'type',
            array(
                'label'       => __('Blockqoute type','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'style_items' => 'max-width:48%;',
                'options'     => array(
                    'quote-normal'      => array(
                        'label'         => __('Quote Normal', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/blockquote-normal-1.svg'
                    ),
                    'blockquote-normal' => array(
                        'label'         => __('Blockquote Normal', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/blockquote-normal.svg'
                    ),
                     'pullquote-normal' => array(
                        'label'         => __('Pullquote Normal', 'auxin-elements'),
                        'image'         => AUXIN_URL . 'images/visual-select/pullquote-normal.svg'
                    )
                ),
                'default'     => 'blockquote-normal'
            )
        );*/

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style section
        /*-----------------------------------------------------------------------------------*/

        /*  Text Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'text_style_section',
            array(
                'label'     => __( 'Text', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'text_align',
            array(
                'label'       => __('Text alignment', 'auxin-elements'),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'inherit',
                'options'     => array(
                    'inherit' => array(
                        'title' => __( 'Default', 'auxin-elements' ),
                        'icon'  => 'fa fa-stream',
                    ),
                    'left' => array(
                        'title' => __( 'Left', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'auxin-elements' ),
                        'icon'  => 'fa fa-align-right',
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-elem-quote' => 'text-align: {{VALUE}};'
                )
            )
        );

        $this->add_control(
            'text_color',
            array(
                'label'     => __( 'Text Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-elem-quote p' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_typography',
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-elem-quote p'
            )
        );

        $this->end_controls_section();

        /*  Quote Symbol Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'quote_symbol_style_section',
            array(
                'label'     => __( 'Quote Symbol', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'quote_symbol',
            array(
                'label'        => __('Insert quote symbol','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'quote_symbol_color',
            array(
                'label'     => __( 'Quote symbol color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-quote-symbol:before' => 'color: {{VALUE}};'
                ),
                'condition' => array(
                    'quote_symbol' => 'yes'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'quote_symbol_typography',
                'scheme'    => Typography::TYPOGRAPHY_3,
                'selector'  => '{{WRAPPER}} .aux-quote-symbol:before',
                'condition' => array(
                    'quote_symbol' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'quote_text_indent',
            array(
                'label'      => __( 'Text indent', 'auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px','em', 'rem'),
                'range'      => array(
                    'px' => array(
                        'min'  => 12,
                        'max'  => 200,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 10,
                        'step' => 0.1
                    ),
                    'rem' => array(
                        'min'  => 1,
                        'max'  => 10,
                        'step' => 0.1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-quote-symbol p:first-child' => 'text-indent: {{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'quote_symbol' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'quote_symbol_margin',
            array(
                'label'      => __( 'Margin', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-quote-symbol:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*  Block Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'block_style_section',
            array(
                'label'     => __( 'Block', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'block_border',
                'selector'  => '{{WRAPPER}} .aux-elem-quote',
                'separator' => 'before'
            )
        );

        $this->add_control(
            'blox_background_color',
            array(
                'label'     => __( 'Background color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-elem-quote' => 'background-color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'quote_symbol_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-blockquote-normal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();
    }

  /**
   * Render 'Quote' widget output on the frontend.
   *
   * @access protected
   */
    protected function render() {

        $settings   = $this->get_settings_for_display();

        $args       = array(
            'type'              => 'blockquote-normal', //$settings['type'],
            'text_align'        => $settings['text_align'],
            'quote_symbol'      => $settings['quote_symbol']
        );

        // pass the args through the corresponding shortcode callback
        echo auxin_widget_quote_callback( $args, $settings['content'] );
    }

}
