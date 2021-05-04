<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements\Theme_Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'SiteTitle' widget.
 *
 * Elementor widget that displays an 'SiteTitle'.
 *
 * @since 1.0.0
 */
class SiteTitle extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'SiteTitle' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_site_title';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'SiteTitle' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Site Title', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'SiteTitle' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-site-title auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'SiteTitle' widget icon.
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
     * Register 'SiteTitle' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'title_section',
            array(
                'label'      => __('Title', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'title_tag',
            array(
                'label'   => __( 'Site Title HTML Tag', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'Div',
					'span' => 'Span',
					'p'    => 'P',
                ),
                'default'   => 'h1',
                'separator'    => 'after'
            )
        );

        $this->add_control(
            'title_before',
            array(
                'label'       => __( 'Before Title', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true
            )
        );

        $this->add_control(
            'title_before_tag',
            array(
                'label'   => __( 'Before Title HTML Tag', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'Div',
					'span' => 'Span',
					'p'    => 'P',
                ),
                'default'   => 'span',
                'separator'    => 'after'
            )
        );

        $this->add_control(
            'title_after',
            array(
                'label'       => __( 'After Title', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true
            )
        );

        $this->add_control(
            'title_after_tag',
            array(
                'label'   => __( 'After Title HTML Tag', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'Div',
					'span' => 'Span',
					'p'    => 'P',
                ),
                'default'   => 'span',
                'separator' => 'after'
            )
        );

		$this->add_responsive_control(
			'align',
			array(
				'label' => __( 'Alignment', 'auxin-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => __( 'Left', 'auxin-elements' ),
						'icon' => 'fa fa-align-left',
                    ),
					'center' => array(
						'title' => __( 'Center', 'auxin-elements' ),
						'icon' => 'fa fa-align-center',
                    ),
					'flex-end' => array(
						'title' => __( 'Right', 'auxin-elements' ),
						'icon' => 'fa fa-align-right',
                    ),
                ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'link_section',
            array(
                'label'      => __('Link', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'        => __( 'Disable Link', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'off',
            )
        );

        $this->add_control(
            'link_open',
            array(
                'label'        => __( 'Open in a New Window', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'off',
                'condition' => array(
                    'link!' => 'yes'
                )
            )
        );

        $this->add_control(
            'link_follow',
            array(
                'label'        => __( 'Add No Follow', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'off',
                'condition' => array(
                    'link!' => 'yes'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB

        /*-----------------------------------------------------------------------------------*/
        /*   General Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'general_style_section',
            array(
                'label'     => __( 'General', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'direction',
            array(
                'label'   => __( 'Direction', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
					'horizontal' => 'Horizontal',
					'vertical'   => 'Vertical',
                ),
                'default'   => 'horizontal',
            )
        );

        $this->end_controls_section();
        /*   Title Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Site Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'title_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-site-title-heading, {{WRAPPER}} .aux-site-title-heading a' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'title_hover_color',
            array(
                'label'     => __( 'Hover Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-site-title-heading:hover, {{WRAPPER}} .aux-site-title-heading a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-site-title-heading'
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-site-title-heading' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'title_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-site-title-heading'
            )
        );

        $this->end_controls_section();

        /* Before Title Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'before_title_style_section',
            array(
                'label'     => __( 'Before Site Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'before_title_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-site-title-before-heading' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'before_title_hover_color',
            array(
                'label'     => __( 'Hover Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-site-title-before-heading:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'before_title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-site-title-before-heading'
            )
        );

        $this->add_responsive_control(
            'before_title_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-site-title-before-heading' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'before_title_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-site-title-before-heading'
            )
        );

        $this->end_controls_section();

        /* After Title Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'after_title_style_section',
            array(
                'label'     => __( 'After Site Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'after_title_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-site-title-after-heading' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'after_title_hover_color',
            array(
                'label'     => __( 'Hover Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-site-title-after-heading:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'after_title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-site-title-after-heading'
            )
        );

        $this->add_responsive_control(
            'after_title_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-site-title-after-heading' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'after_title_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-site-title-after-heading'
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
    $output   = sprintf( '<div class="aux-widget-site-title aux-%s">', $settings['direction'] );

    $site_title = get_bloginfo('name');

    if ( !auxin_is_true( $settings['link'] ) ) {
        $site_url    = home_url( '/' );
        $no_follow   = auxin_is_true( $settings['link_follow'] ) ? 'rel = ”nofollow”': '';
        $open_window = auxin_is_true( $settings['link_open'] ) ? 'target=_blank' : '';
        $link_output = sprintf( '<a href="%s" title="%s" %s %s>%s</a>', $site_url, get_bloginfo( 'name', 'display' ), $no_follow, $open_window, $site_title );
        $site_title  = $link_output;
    }

    $before_site_title_output = $settings['title_before'] !== '' ? sprintf('<%1$s class ="aux-site-title-before-heading">%2$s</%1$s>', $settings['title_before_tag'], $settings['title_before'] ) : '';
    $after_site_title_output  = $settings['title_after'] !== '' ? sprintf('<%1$s class ="aux-site-title-after-heading">%2$s</%1$s>', $settings['title_after_tag'], $settings['title_after'] ) : '';
    $site_title_output        = sprintf('<%1$s class="aux-site-title-heading">%2$s</%1$s>', $settings['title_tag'], $site_title );

    $output .= $before_site_title_output;
    $output .= $site_title_output;
    $output .= $after_site_title_output;

    $output .= '</div>';

    echo $output;
  }

}
