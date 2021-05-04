<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor 'Divider' widget.
 *
 * Elementor widget that displays an 'Divider' with lightbox.
 *
 * @since 1.0.0
 */
class Divider extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Divider' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_divider';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Divider' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Divider', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Divider' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-divider auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Divider' widget icon.
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
     * Register 'Divider' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Divider section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'divider_section',
            array(
                'label'      => __('Divider', 'auxin-elements' )
            )
        );

        $this->add_control(
            'style',
            array(
                'label'       => __('Style','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'style_items' => 'max-width:48%;',
                'options'     => array(
                    'white-space'    => array(
                        'label'      => __('White Space', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-white-space.svg'
                    ),
                    'solid'          => array(
                        'label'      => __('Solid', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-solid.svg'
                    ),
                    'dashed'         => array(
                        'label'      => __('Dashed', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-dashed.svg'
                    ),
                    'circle-symbol'  => array(
                        'label'      => __('Circle', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-circle.svg'
                    ),
                    'diamond-symbol' => array(
                        'label'      => __('Diamond', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-diamond.svg'
                    ),
                    'vertical' => array(
                        'label'      => __('Vertical Line', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/divider-diamond.svg'
                    )
                ),
                'default'     => 'circle-symbol'
            )
        );

        $this->add_control(
            'width',
            array(
                'label'       => __('Width','auxin-elements'),
                'description' => __('Specifies the size of divider.', 'auxin-elements'),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 'medium',
                'options'     => array(
                    'large'         => __('Full' , 'auxin-elements'),
                    'medium'        => __('Medium', 'auxin-elements'),
                    'small'         => __('Small' , 'auxin-elements'),
                    'tiny'          => __('Tiny'  , 'auxin-elements'),
                    'custom'        => __('Custom', 'auxin-elements')
                ),
                'return_value' => '',
                'condition'    => array(
                    'style!' => array( 'white-space', 'vertical' )
                )
            )
        );

        $this->add_responsive_control(
            'custom_width',
            array(
                'label'           => __( 'Custom width', 'auxin-elements' ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px', '%'),
                'desktop_default' => array(
                    'size' => 100,
                    'unit' => '%'
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    )
                ),
                'return_value' => '',
                'selectors'    => array(
                    '{{WRAPPER}} hr[class*="aux-divider"]' => 'width:{{SIZE}}{{UNIT}};'
                ),
                'condition'    => array(
                    'style!' => array( 'white-space', 'vertical' ),
                    'width'  => 'custom'
                )
            )
        );

        $this->add_control(
            'alignment',
            array(
                'label'       => __('Alignment', 'auxin-elements'),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'options'     => array(
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
                'return_value' => '',
                'conditions'   => array(
                    'relation' => 'and',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '!==',
                            'value'    => 'white-space'
                        ),
                        array(
                            'name'     => 'width',
                            'operator' => '!==',
                            'value'    => 'large'
                        )
                    )
                )
            )
        );

        $this->add_control(
            'symbol_alignment',
            array(
                'label'       => __('Symbol Alignment', 'auxin-elements'),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'options'     => array(
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
                'return_value' => '',
                'condition'    => array(
                    'style' => array( 'circle-symbol', 'diamond-symbol' )
                )
            )
        );

        $this->add_responsive_control(
            'white_space',
            array(
                'label'           => __( 'Gap', 'auxin-elements' ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px','em', 'rem'),
                'desktop_default' => array(
                    'size' => 3,
                    'unit' => 'em'
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 15,
                        'step' => 0.1
                    ),
                    'rem' => array(
                        'min'  => 1,
                        'max'  => 15,
                        'step' => 0.1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} hr.aux-divider-space' => 'padding-top: {{SIZE}}{{UNIT}}; margin-top:0; margin-bottom:0;'
                ),
                'condition'    => array(
                    'style' => 'white-space'
                )
            )
        );

        $this->add_responsive_control(
            'margin_top',
            array(
                'label'           => __( 'Gap top', 'auxin-elements' ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px','em', 'rem'),
                'desktop_default' => array(
                    'size' => 3,
                    'unit' => 'em'
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 15,
                        'step' => 0.1
                    ),
                    'rem' => array(
                        'min'  => 1,
                        'max'  => 15,
                        'step' => 0.1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} hr[class*="aux-divider"]' => 'margin-top:{{SIZE}}{{UNIT}};'
                ),
                'condition'    => array(
                    'style!' => 'white-space'
                )
            )
        );

        $this->add_responsive_control(
            'margin_bottom',
            array(
                'label'           => __( 'Gap bottom', 'auxin-elements' ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px','em', 'rem'),
                'desktop_default' => array(
                    'size' => 3,
                    'unit' => 'em'
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 15,
                        'step' => 0.1
                    ),
                    'rem' => array(
                        'min'  => 1,
                        'max'  => 15,
                        'step' => 0.1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} hr[class*="aux-divider"]' => 'margin-bottom:{{SIZE}}{{UNIT}};'
                ),
                'condition'    => array(
                    'style!' => 'white-space'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Tab
        /*-----------------------------------------------------------------------------------*/

        /*  Color Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'color_section',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'    => array(
                    'style!' => 'white-space'
                )
            )
        );

        $this->add_control(
            'divider_color',
            array(
                'label'     => __( 'Divider color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} hr[class*="aux-divider"]' => 'border-color: {{VALUE}};'
                ),
                'return_value' => ''
            )
        );

        $this->add_control(
            'symbol_color',
            array(
                'label'     => __( 'Symbol color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-divider-symbolic-circle:after' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .aux-divider-symbolic-square:after' => 'background-color: {{VALUE}};'
                ),
                'condition'    => array(
                    'style!' => array( 'white-space', 'vertical' )
                )
            )
        );

        $this->end_controls_section();
    }

  /**
   * Render 'Divider' widget output on the frontend.
   *
   * @access protected
   */
    protected function render() {

        $settings   = $this->get_settings_for_display();

        $args       = array(
            'width'            => $settings['width'],
            'alignment'        => $settings['alignment'],
            'symbol_alignment' => $settings['symbol_alignment'],
            'style'            => $settings['style']
        );

        // pass the args through the corresponding shortcode callback
        echo auxin_widget_divider_callback( $args );
    }

}
