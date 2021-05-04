<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Staff' widget.
 *
 * Elementor widget that displays an 'Staff' with lightbox.
 *
 * @since 1.0.0
 */
class Staff extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Staff' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_staff';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Staff' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Staff', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Staff' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-person auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Staff' widget icon.
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
     * Register 'Staff' widget controls.
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
                'label'      => __('Content', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'       => __('Staff Name','auxin-elements' ),
                'description' => __('Text title, leave it empty if you don`t need title.', 'auxin-elements'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'John Doe'
            )
        );

        $this->add_control(
            'subtitle',
            array(
                'label'       => __('Staff Occupation','auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Manager'
            )
        );

        $this->add_control(
            'staff_link',
            array(
                'label'         => __('Staff Page Link','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'dynamic'       => [
					'active' => true
				]
            )
        );


        $this->add_control(
            'content',
            array(
                'label'       => __('Content','auxin-elements'),
                'description' => __('Enter a text as a text content.','auxin-elements'),
                'type'        => Controls_Manager::TEXTAREA
            )
        );

        $this->add_control(
            'max_words',
            array(
                'label'       => __('Maximum Words','auxin-elements' ),
                'description' => __('Limit the number of words in the Content','auxin-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '',
                'step'        => 1,
                'condition'    => array(
                    'content!' => '',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  image_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'image_section',
            array(
                'label'      => __('Image', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'staff_img',
            array(
                'label'       => __('Image','auxin-elements' ),
                'type'        => Controls_Manager::MEDIA,
                'default'    => array(
                    'url' => Utils::get_placeholder_image_src()
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'       => 'staff_img', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'separator'  => 'none',
                'default'    => 'full'
            )
        );

        $this->add_control(
            'img_shape',
            array(
                'label'       => __('Image shape','auxin-elements'),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'circle' => array(
                        'label' => __('Circle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-circle.svg'
                    ),
                    'semi-circle' => array(
                        'label' => __('Semi-circle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-semi-circle.svg'
                    ),
                    'round-rect' => array(
                        'label' => __('Round Rectangle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-round-rectangle.svg'
                    ),
                    'rect' => array(
                        'label' => __('Rectangle', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/icon-style-rectangle.svg'
                    )
                ),
                'default'     => 'circle'
            )
        );

        $this->add_control(
            'preloadable',
            array(
                'label'        => __('Preload image','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'preload_preview',
            array(
                'label'        => __('While loading image display','auxin-elements' ),
                'label_block'  => true,
                'type'         => Controls_Manager::SELECT,
                'options'      => auxin_get_preloadable_previews(),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'preloadable' => 'yes'
                )
            )
        );

        $this->add_control(
            'preload_bgcolor',
            array(
                'label'     => __( 'Placeholder color while loading image', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'preloadable'     => 'yes',
                    'preload_preview' => array('simple-spinner', 'simple-spinner-light', 'simple-spinner-dark')
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  social_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'social_section',
            array(
                'label'      => __('Social', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'socials',
            array(
                'label'        => __('Enable Socials','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'default'      => 'no'
            )
        );

        $this->add_control(
            'social_twitter',
            array(
                'label'         => __('Twitter Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_facebook',
            array(
                'label'         => __('Facebook Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_gp',
            array(
                'label'         => __('Google Plus Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_flickr',
            array(
                'label'         => __('Flickr Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_delicious',
            array(
                'label'         => __('Delicious Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_pinterest',
            array(
                'label'         => __('Pinterest Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_github',
            array(
                'label'         => __('GitHub Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_instagram',
            array(
                'label'         => __('Instagram Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_dribbble',
            array(
                'label'         => __('Dribbble Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'social_linkedin',
            array(
                'label'         => __('LinkedIn Address','auxin-elements' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'condition'     => [
                    'socials' => 'yes'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  title_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->start_controls_tabs( 'title_colors' );

        $this->start_controls_tab(
            'title_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' ),
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .col-title a, {{WRAPPER}} .col-title' => 'color: {{VALUE}} !important;',
                ),
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' ),
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .col-title a:hover' => 'color: {{VALUE}} !important;',
                ),
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .col-title, {{WRAPPER}} .col-title a',
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin_top',
            array(
                'label' => __( 'Top space', 'auxin-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-staff-content .col-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'title!' => ''
                )
            )
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-staff-content  .col-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'title!' => ''
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  subtitle_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'subtitle_style_section',
            array(
                'label'     => __( 'Subtitle', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'subtitle!' => '',
                ),
            )
        );

        $this->add_control(
            'subtitle_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .col-subtitle' => 'color: {{VALUE}} !important;',
                ),
                'condition' => array(
                    'subtitle!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'subtitle_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-staff-content  .col-subtitle',
                'condition' => array(
                    'subtitle!' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'subtitle_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .col-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'subtitle!' => '',
                ),
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  content_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => __( 'Content', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'content!' => '',
                ),
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-content' => 'color: {{VALUE}} !important;',
                ),
                'condition' => array(
                    'content!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'content_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-content',
                'condition' => array(
                    'content!' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'content_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'content!' => '',
                ),
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  content_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'socials_style_section',
            array(
                'label'     => __( 'Socials', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'socials' => 'yes'
                ),
            )
        );

        $this->start_controls_tabs( 'socials_colors' );

        $this->start_controls_tab(
            'socials_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' ),
                'condition' => array(
                    'socials' => 'yes'
                ),
            )
        );

        $this->add_control(
            'socials_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-social-list a' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'socials' => 'yes'
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'socials_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' ),
                'condition' => array(
                    'socials' => 'yes'
                ),
            )
        );

        $this->add_control(
            'socials_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-social-list a:hover' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'socials' => 'yes'
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'icon_align',
            array(
                'label'       => __('Icon Direction', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'aux-horizontal',
                'options'     => array(
                    'aux-vertical'   => __( 'Vertical'   , 'auxin-elements' ),
                    'aux-horizontal' => __( 'Horizontal'  , 'auxin-elements' ),
                ),
                'condition'   => array(
                    'socials' => 'yes'
                )
            )
        );


        $this->add_control(
            'icon_size',
            array(
                'label'       => __('Socials Icon size', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'aux-medium',
                'options'     => array(
                    'aux-small'       => __( 'Small'   , 'auxin-elements' ),
                    'aux-medium'      => __( 'Medium'  , 'auxin-elements' ),
                    'aux-large'       => __( 'Large'   , 'auxin-elements' ),
                    'aux-extra-large' => __( 'X-Large' , 'auxin-elements' )
                ),
                'condition'   => array(
                    'socials' => 'yes'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'socials_separator',
                'label'    => __( 'Separator', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .aux-widget-staff .aux-staff-footer',
            )
        );

        $this->add_responsive_control(
            'socials_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-widget-staff .aux-staff-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'socials' => 'yes'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Wrapper section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wrapper_style_section',
            array(
                'label'     => __( 'Wrappers', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->add_control(
            'layout_style',
            array(
                'label'       => __('Layout','auxin-elements'),
                'type'        => 'aux-visual-select',
                'style_items' => 'max-width:31%;',
                'options'     => array(
                    'top'   => array(
                        'label' => __('Top', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/column-icon-top.svg'
                    ),
                    'right' => array(
                        'label' => __('Right', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/column-icon-right.svg'
                    ),
                    'bottom' => array(
                        'label' => __('Bottom', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/column-icon-bottom.svg'
                    ),
                    'left'  => array(
                        'label' => __('Left', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/column-icon-left.svg'
                    )
                ),
                'default'     => 'top'
            )
        );

        $this->add_control(
            'text_align',
            array(
                'label'      => __('Text Align','auxin-elements'),
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
                'default'    => 'left',
                'toggle'     => true,
            )
        );

        $this->add_responsive_control(
            'wrapper_main_padding',
            array(
                'label'      => __( 'Padding for main wrapper', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-widget-staff > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'wrapper_content_padding',
            array(
                'label'      => __( 'Padding for content wrapper', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-widget-staff .aux-staff-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
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

        $settings   = $this->get_settings_for_display();

        $args       = array(
            'title'              => $settings['title'],
            'subtitle'           => $settings['subtitle'],
            'staff_link'         => $settings['staff_link']['url'],
            'content'            => $settings['content'],
            'max_words'          => $settings['max_words'],
            'width'              => $settings['staff_img_custom_dimension']['width'],
            'height'             => $settings['staff_img_custom_dimension']['height'],

            'staff_img'          => $settings['staff_img']['id'],
            'staff_img_size'     => $settings['staff_img_size'],
            'img_shape'          => $settings['img_shape'],
            'preloadable'        => $settings['preloadable'],
            'preload_preview'    => $settings['preload_preview'],
            'preload_bgcolor'    => $settings['preload_bgcolor'],

            'socials'            => $settings['socials'],
            'social_twitter'     => $settings['social_twitter']['url'],
            'social_facebook'    => $settings['social_facebook']['url'],
            'social_gp'          => $settings['social_gp']['url'],
            'social_flickr'      => $settings['social_flickr']['url'],
            'social_delicious'   => $settings['social_delicious']['url'],
            'social_pinterest'   => $settings['social_pinterest']['url'],
            'social_github'      => $settings['social_github']['url'],
            'social_instagram'   => $settings['social_instagram']['url'],
            'social_dribbble'    => $settings['social_dribbble']['url'],
            'social_linkedin'    => $settings['social_linkedin']['url'],

            'icon_size'          => $settings['icon_size'],
            'icon_align'         => $settings['icon_align'],

            'wrapper_style'      => 'simple', //$settings['wrapper_style'],
            'layout_style'       => $settings['layout_style'],
            //'wrapper_bg_color' => $settings['wrapper_bg_color'],
            'text_align'         => $settings['text_align'],
        );

        echo auxin_widget_staff_callback( $args );
    }

}
