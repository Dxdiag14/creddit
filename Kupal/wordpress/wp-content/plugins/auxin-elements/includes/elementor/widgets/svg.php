<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor 'Simple_SVG' widget.
 *
 * Elementor widget that displays an 'Simple_SVG' with lightbox.
 *
 * @since 1.0.0
 */
class Simple__SVG extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Simple_SVG' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_simple_svg';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Simple_SVG' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Simple SVG', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Simple_SVG' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-integration auxin-badge-pro';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Simple_SVG' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-pro' );
    }

    /**
     * Register 'Simple_SVG' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Content Tab
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'svg_image_section',
            array(
                'label'     => __( 'SVG', 'auxin-elements' )
            )
        );

        $this->add_control(
            'type',
            array(
                'label'   => __( 'Type', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'image'        => __( 'Image', 'auxin-elements' ),
                    'inline'       => __( 'Inline', 'auxin-elements' )
                ),
                'default'      => 'image'
            )
        );

        $this->add_control(
            'image',
            array(
                'label'   => __( 'Choose Image', 'elementor' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'condition' => array(
                    'type' => 'image'
                )
            )
        );

        $this->add_control(
            'inline',
            array(
                'label'       => '',
                'type'        => Controls_Manager::CODE,
                'default'     => '',
                'placeholder' => __( 'Enter inline SVG content here', 'elementor' ),
                'show_label'  => false,
                'condition' => array(
                    'type' => 'inline'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Tab
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'svg_style_section',
            array(
                'label'     => __( 'Style', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            '_width',
            array(
                'label'      => __('Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 0,
                        'step' => 1
                    )
                ),
                'desktop_default' => array(
                    'size' => 300,
                    'unit' => 'px'
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-the-svg' => 'width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            '_height',
            array(
                'label'      => __( 'Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 0,
                        'step' => 1
                    )
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .aux-the-svg' => 'height:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            '_max_width',
            array(
                'label'      => __('Max Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 0,
                        'step' => 1
                    )
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-widget-container-inner' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            '_max_height',
            array(
                'label'      => __('Max Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 0,
                        'max'  => 2000,
                        'step' => 10
                    ),
                    'em' => array(
                        'min'  => 0,
                        'step' => 1
                    )
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-widget-container-inner' => 'max-height:{{SIZE}}{{UNIT}};'
                ),
                'separator'  => 'after'
            )
        );

        $this->add_responsive_control(
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
                'selectors' => array(
                    '{{WRAPPER}} .aux-widget-container' => 'text-align: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'svg_border_radius',
            array(
                'label'      => __( 'Border radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-the-svg > *' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'after'
            )
        );

        $this->start_controls_tabs( 'svg_style_tabs' );

        $this->start_controls_tab(
            'svg_status_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'svg_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-the-svg > *',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'svg_border',
                'selector'  => '{{WRAPPER}} .aux-the-svg > *',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'svg_background',
                'selector'  => '{{WRAPPER}} .aux-the-svg > *',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'svg_status_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'svg_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .aux-the-svg:hover > *',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'svg_border_hover',
                'selector'  => '{{WRAPPER}} .aux-the-svg:hover > *',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'svg_background_hover',
                'selector'  => '{{WRAPPER}} .aux-the-svg:hover > *',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }


    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if( 'image' == $settings['type'] ){
            $content = Group_Control_Image_Size::get_attachment_image_html( $settings );
        } else {
            $content = $this->get_settings_for_display( 'inline' );
        }
    ?>
    <div class="aux-widget-container aux-simple-svg-container">
        <div class="aux-widget-container-inner">
            <div class="aux-the-svg"><?php echo $content; ?></div>
        </div>
    </div>
    <?php
    }


    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     */
    protected function _content_template() {
    ?>
    <div class="aux-widget-container aux-simple-svg-container">
        <div class="aux-widget-container-inner">
            <div class="aux-the-svg">
            <# if( settings.type == 'image' ){

                if ( settings.image.url ) {
                    #><img src="{{ settings.image.url }}" /><#
                }

            } else { #>
               {{{ settings.inline }}}
            <# } #>
            </div>
        </div>
    </div>
    <?php
    }

}
