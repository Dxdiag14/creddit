<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements\Theme_Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Logo' widget.
 *
 * Elementor widget that displays an 'Logo'.
 *
 * @since 1.0.0
 */
class Logo extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Logo' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_logo';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Logo' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Logo', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Logo' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-date auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Logo' widget icon.
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
     * Register 'Logo' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  button_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'general',
            array(
                'label'      => __('General', 'auxin-elements' ),
            )
        );

        $custom_logo_id = get_theme_mod( 'custom_logo' );


        if ( empty( $custom_logo_id ) ) {
            $this->add_control(
				'custom_logo_page',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( '<strong>There are no logo in your site.</strong><br>Go to the <a href="%s" target="_blank">Customizer</a> to add one.', 'auxin-elements' ), admin_url( 'customize.php' ) ),
                    'separator' => 'after',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                )
			);
        } else {
            $this->add_control(
				'custom_logo_page',
				array(
					'type'=> Controls_Manager::RAW_HTML,
					'raw' => '<strong>'. __( 'Your site logo is set in customizer.', 'auxin-elements' ). '</strong><br>'.
                    sprintf( __( 'Go to %s Customizer %s to change it.', 'auxin-elements' ), '<a href="'. admin_url( 'customize.php' ) .' target="_blank">', "</a>" ),
                    'separator' => 'after',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                )
			);
        }

        $this->add_control(
			'logo_type',
			array(
				'label'   => __( 'Type', 'auxin-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'primary',
				'options' => array(
					'primary'   => __( 'Primary', 'auxin-elements' ),
					'secondary' => __( 'Secondary', 'auxin-elements' ),
				),
			)
        );

        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
            )
        );

        $this->add_responsive_control(
			'align',
			array(
				'label' => __( 'Alignment', 'auxin-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => __( 'Left', 'auxin-elements' ),
						'icon' => 'eicon-text-align-left',
                    ),
					'center' => array(
						'title' => __( 'Center', 'auxin-elements' ),
						'icon' => 'eicon-text-align-center',
                    ),
					'right' => array(
						'title' => __( 'Right', 'auxin-elements' ),
						'icon' => 'eicon-text-align-right',
                    ),
                    ),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ),
            )
        );

		$this->add_control(
			'link_to',
			array(
				'label'   => __( 'Link', 'auxin-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'home',
				'options' => array(
					'home'   => __( 'Home Page', 'auxin-elements' ),
					'file'   => __( 'Media File', 'auxin-elements' ),
					'custom' => __( 'Custom URL', 'auxin-elements' ),
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label' => __( 'Link', 'auxin-elements' ),
				'type'  => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'auxin-elements' ),
                'dynamic' => [
					'active' => true
				],
				'condition' => array(
					'link_to' => 'custom',
				),
				'show_label' => false,
			)
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Image', 'auxin-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
            )
		);

		$this->add_responsive_control(
			'width',
			array(
				'label' => __( 'Width', 'auxin-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units' => array( '%', 'px', 'vw' ),
				'range' => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .aux-has-logo img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label' => __( 'Max Width', 'auxin-elements' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units' => array( '%' ),
				'range' => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .aux-has-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'separator_panel_style',
			array(
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
            )
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			array(
				'label' => __( 'Normal', 'auxin-elements' ),
            )
		);

		$this->add_control(
			'opacity',
			array(
				'label' => __( 'Opacity', 'auxin-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .aux-has-logo img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .aux-has-logo img',
            )
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			array(
				'label' => __( 'Hover', 'auxin-elements' ),
            )
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label' => __( 'Opacity', 'auxin-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}  .aux-has-logo:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .aux-has-logo:hover img',
            )
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label' => __( 'Transition Duration', 'auxin-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .aux-has-logo img' => 'transition-duration: {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .aux-has-logo img',
				'separator' => 'before',
            )
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label' => __( 'Border Radius', 'auxin-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .aux-has-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'image_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .aux-has-logo img',
			)
        );
        
        $this->add_control(
            'image_sticky_scale',
            array(
                'label'        => __( 'Scale on sticky', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'off',
            )
        );

        $this->add_responsive_control(
            'sticky_scale',
            array(
                'label'      => __('Scale','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05
                    )
                ),
                'default' => array(
					'unit' => 'px',
					'size' => 0.85,
                ),
                'condition' => array(
					'image_sticky_scale' => 'yes',
                ),
                'selectors' => array(
                    '.aux-sticky {{WRAPPER}} .aux-logo-anchor' => 'transition: transform 300ms ease-out; transform-origin: left; transform: scale({{SIZE}});'
                )

            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'auxin-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'title_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .site-title a, {{WRAPPER}} .site-title' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'title_hover_color',
            array(
                'label'     => __( 'Hover Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .site-title a:hover, {{WRAPPER}} .site-title:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .site-title a'
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
                    '{{WRAPPER}} .site-title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'title_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .site-title a'
            )
        );

        $this->add_responsive_control(
            'title_width',
            array(
                'label'      => __('Max Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .site-title' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_style_desc',
			array(
				'label' => __( 'Description', 'auxin-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'desc_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .site-description' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'desc_hover_color',
            array(
                'label'     => __( 'Hover Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .site-description:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'desc_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .site-description'
            )
        );

        $this->add_responsive_control(
            'desc_margin',
            array(
                'label'              => __( 'Margin', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .site-description' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'desc_text_shadow',
                'label'    => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .site-description'
            )
        );

        $this->add_responsive_control(
            'desc_width',
            array(
                'label'      => __('Max Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .site-description' => 'max-width:{{SIZE}}{{UNIT}};'
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
    global $aux_main_post;
    $settings          = $this->get_settings_for_display();
    $logo_desc         = get_bloginfo( 'description' );

    // get logo id 
    if ( ! empty( $aux_main_post ) ) {
        if( empty( $custom_logo_id = auxin_get_post_meta( $aux_main_post->ID, 'aux_custom_logo' ) ) || ! is_numeric( $custom_logo_id ) ){
            $custom_logo_id    = get_theme_mod( 'custom_logo' );
        }

        if( empty( $secondary_logo_id = auxin_get_post_meta( $aux_main_post->ID, 'aux_custom_logo2' ) ) || ! is_numeric( $secondary_logo_id ) ){
            $secondary_logo_id = auxin_get_option('custom_logo2');
        }
    } else {
        $custom_logo_id    = get_theme_mod( 'custom_logo' );
        $secondary_logo_id = auxin_get_option('custom_logo2');
    }
    
    $blog_display_name = get_bloginfo( 'name', 'display' );
    $blog_name         = get_bloginfo( 'name' );
    $home_url          = home_url( '/' );
    $link_attr         = array(
        'class' => 'aux-logo-anchor aux-has-logo',
        'title' => esc_attr( $blog_display_name )
    );
    $logo_markup       = '';
    $output            = '';


    if ( 'home' === $settings['link_to'] ) {
        $link_attr['href'] = esc_url ( $home_url );
    } else if ( 'file' === $settings['link_to'] ) {
        $link_attr['href'] = esc_url ( wp_get_attachment_url( $custom_logo_id ) ) ;
    } else {
        $link_attr['href'] = esc_url ( $settings['link']['url'] ) ;

        if ( auxin_is_true( $settings['link']['is_external'] ) ) {
            $link_attr['target'] = '_blank';
        }

        if ( auxin_is_true( $settings['link']['nofollow'] ) ) {
            $link_attr['rel'] = 'nofollow';
        }
    }

    $link_attr = auxin_make_html_attributes( $link_attr );

    if ( $custom_logo_id ) {

        $logo_image_markup = '';
        $logo_id = 'primary' === $settings['logo_type'] ? $custom_logo_id : $secondary_logo_id;

        if ( $settings['image_custom_dimension'] ) {
            $image_size = $settings['image_custom_dimension'];
        } else {
            $image_size = $settings['image_size'];
        }

        $logo_image_markup = auxin_get_the_responsive_attachment( $logo_id,
                array(
                    'quality'         => 100,
                    'size'            => $image_size,
                    'crop'            => true,
                    'add_hw'          => true,
                    'upscale'         => false,
                    'preloadable'     => false
                )
        );

        $logo_markup = '<a ' . $link_attr . ' >' . $logo_image_markup . '</a>';
        
        // add secondary sticky logo 
        if ( $secondary_logo_id ) {
            $secondary_logo_image_markup = auxin_get_the_responsive_attachment( $secondary_logo_id,
                    array(
                        'quality'         => 100,
                        'size'            => $image_size,
                        'crop'            => true,
                        'add_hw'          => true,
                        'upscale'         => false,
                        'preloadable'     => false
                    )
            );
            $logo_2_link_attr = \str_replace( 'aux-logo-anchor', 'aux-logo-anchor aux-logo-sticky aux-logo-hidden', $link_attr );
            $logo_markup .= '<a ' . $logo_2_link_attr . ' >' . $secondary_logo_image_markup . '</a>';
        }
    }

    $output .= '<div class="aux-widget-logo">';
        $output .= $logo_markup;
        $output .= '<section class="aux-logo-text">';
            $output .= '<h3 class="site-title">';
                    $output .= '<a href="' . esc_url( $home_url ) . '" title="' . esc_attr( $blog_display_name ) . '">';
                        $output .= $blog_name;
                    $output .= '</a>';
            $output .= '</h3>';
            $output .= $logo_desc ? '<p class="site-description">' . $logo_desc . '</p>' : '';
        $output .= '</section>';
    $output .= '</div>';

    echo $output;

  }

}
