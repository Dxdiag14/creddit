<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements\Theme_Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Shopping_Cart' widget.
 *
 * Elementor widget that displays an 'Shopping_Cart' with lightbox.
 *
 * @since 1.0.0
 */
class Shopping_Cart extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Shopping_Cart' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_shopping_cart';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Shopping_Cart' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Shopping Cart', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Shopping_Cart' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-cart-light auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Shopping_Cart' widget icon.
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
     * Register 'Shopping_Cart' widget controls.
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
            'button_section',
            array(
                'label'      => __('Settings', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'aux_shopping_cart_icon',
            array(
                'label'   => __( 'Icon', 'auxin-elements' ),
                'type'        => Controls_Manager::ICONS
            )
        );

        $this->add_control(
            'cart_style',
            array(
                'label' => __( 'Cart Type', 'auxin-elements' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'dropdown' => __( 'DropDown', 'auxin-elements' ),
                    'offcanvas' => __( 'Off Canvas', 'auxin-elements' )
                ),
                'default' => 'dropdown'
            )
        );

        $this->add_control(
            'cart_content_skin',
            array(
                'label'       => __('Cart Skin', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'light',
                'options'     => array(
                    'light' => __('Light', 'auxin-elements' ),
                    'dark' => __('Dark'  , 'auxin-elements' )
                )
            )
        );

        $this->add_control(
            'action',
            array(
                'label'       => __('Display Basket', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'hover',
                'options'     => array(
                    'hover' => __('On Hover', 'auxin-elements' ),
                    'click' => __('On Click'  , 'auxin-elements' )
                )
            )
        );

        $this->add_control(
            'basket_animation',
            array(
                'label' => __( 'Basket Animation', 'auxin-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'auxin-elements' ),
                'label_off' => __( 'No', 'auxin-elements' ),
                'return_value' => 'yes',
                'default' => 'yes'
            )
        );

        $this->add_control(
            'simple_cart_mode',
            array(
                'label' => __( 'Simple Cart', 'auxin-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'auxin-elements' ),
                'label_off' => __( 'No', 'auxin-elements' ),
                'return_value' => 'yes',
                'default' => 'yes'
            )
        );

        $this->add_control(
			'cart_header_text',
			[
				'label' => __( 'Text for cart', 'auxin-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Shopping Basket', 'auxin-elements' ),
                'condition' => array(
                    'simple_cart_mode' => ''
                )
			]
        );

        $this->add_responsive_control(
            'align',
            array(
                'label' => __( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'start' => array(
                        'title' => __( 'Left', 'elementor' ),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'elementor' ),
                        'icon' => 'fa fa-align-center',
                    ),
                    'end' => array(
                        'title' => __( 'Right', 'elementor' ),
                        'icon' => 'fa fa-align-right',
                    ),
                ),
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .aux-cart-element-container' => 'display: flex; justify-content: {{VALUE}};',
                ]
            )
        );

        $this->add_control(
			'total_price_text_in_dropdown',
			[
				'label' => __( 'Total Price Text', 'auxin-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Sub Total', 'auxin-elements' )
			]
        );

        $this->add_control(
			'checkout_text',
			[
				'label'  => __( 'Checkout Text', 'auxin-elements' ),
                'type'   => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __( 'Checkout', 'auxin-elements' )
			]
        );


        $this->end_controls_section();

        /*  title_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'bubble_section',
            array(
                'label'     => __( 'Counter Bubble', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'bubble_bg_color',
            array(
                'label' => __( 'Background', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-cart-contents > span' => 'background-color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'bubble_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-cart-contents > span'
            )
        );

        $this->add_control(
            'bubble_text_color',
            array(
                'label' => __( 'Text Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-cart-contents > span' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'label'    => __( 'Box Shadow', 'auxin-elements' ),
                'name'     => 'bubble_box_shadow',
                'selector' => '{{WRAPPER}} .aux-cart-contents > span'
            )
        );

        $this->add_responsive_control(
            'bubble_position_bottom',
            array(
                'label'      => __('Position from Bottom','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -15,
                        'max'  => 15,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-cart-contents > span' => 'bottom:{{SIZE}}{{UNIT}};'
                )
            )
        );
        $this->add_responsive_control(
            'bubble_position_right',
            array(
                'label'      => __('Position from right','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -15,
                        'max'  => 15,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-cart-contents > span' => 'right:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'bubble_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-cart-contents > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  text_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'dropdown_section',
            array(
                'label'      => __('Dropdown', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'dropdown_position_bottom',
            array(
                'label'      => __('Position from Bottom','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-card-dropdown' => 'bottom:{{SIZE}}{{UNIT}};'
                )
            )
        );
        $this->add_responsive_control(
            'dropdown_position_left',
            array(
                'label'      => __('Position from Left','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-card-dropdown' => 'left:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'dropdown_width',
            array(
                'label'      => __('Max Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 120,
                        'max'  => 1600,
                        'step' => 1
                    )
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .aux-card-dropdown' => 'width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'dropdown_padding',
            array(
                'label'              => __( 'Padding', 'auxin-elements' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em', '%'),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-card-dropdown' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'dropdown_background',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-card-dropdown'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'dropdown_border',
                'selector'  => '{{WRAPPER}} .aux-card-dropdown',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'label'    => __( 'Box Shadow', 'auxin-elements' ),
                'name'     => 'dropdown_box_shadow',
                'selector' => '{{WRAPPER}} .aux-card-dropdown'
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  text_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'item_style',
            array(
                'label'      => __('Item', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );


        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'       => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'separator'  => 'none',
                'default'    => 'thumbnail'
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-cart-wrapper .aux-card-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'       => __('Title color', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#303030',
                'selectors' => array(
                    '{{WRAPPER}} .aux-cart-wrapper .aux-card-item h3' => 'color: {{VALUE}}',
                )
            )
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'     => __('Title Typography', 'auxin-elements' ),
                'name'      => 'title_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-cart-wrapper .aux-card-item h3'
            )
        );

        $this->add_control(
            'price_color',
            array(
                'label'       => __('Price color', 'auxin-elements'),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#303030',
                'selectors' => array(
                    '{{WRAPPER}} .aux-cart-wrapper .aux-card-item span' => 'color: {{VALUE}}',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'     => __('Price Typography', 'auxin-elements' ),
                'name'      => 'price_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-cart-wrapper .aux-card-item span'
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
    $align    = ! empty( $settings['align'] ) ? ' aux-dropdown-' .  $settings['align'] : '';
    $dropdown_class = "";

    if ( $settings['cart_style'] === 'offcanvas' ) {
        $dropdown_class .= ' aux-desktop-off aux-tablet-off';
    }

    if ( $settings['simple_cart_mode'] = auxin_is_true( $settings['simple_cart_mode'] ) ) {
        $dropdown_class .= ' aux-modern-dropdown';
    }

    $icon_value = ! empty( $settings['aux_shopping_cart_icon']['value'] ) ? $settings['aux_shopping_cart_icon']['value'] : ( ! empty( $settings['icon'] ) ? $settings['icon'] : 'auxicon-shopping-bag-4' ) ;

    $settings['image_custom_dimension']['width'] = empty( $settings['image_custom_dimension']['width'] ) ? '' : $settings['image_custom_dimension']['width'];
    $settings['image_custom_dimension']['height'] = empty( $settings['image_custom_dimension']['height'] ) ? '' : $settings['image_custom_dimension']['height'];

    echo '<div class="aux-cart-element-container">';
    echo auxin_wc_add_to_cart(
        array(
            'css_class' => 'aux-cart-element'. $align .' aux-cart-type-'. $settings['cart_style'],
            'dropdown_class' => $dropdown_class,
            'dropdown_skin' => $settings['cart_content_skin'],
            'action_on' => $settings['action'],
            'icon'      => $icon_value,
            'size'      => $settings['image_size'],
            'width'     => $settings['image_custom_dimension']['width'],
            'height'    => $settings['image_custom_dimension']['height'],
            'simple_mode' => $settings['simple_cart_mode'],
            'basket_animation' => auxin_is_true( $settings['basket_animation'] ),
            'cart_header_text' => $settings['cart_header_text'],
            'total_price_text_in_dropdown' => $settings['total_price_text_in_dropdown'],
            'checkout_text'   => $settings['checkout_text']
        )
    );
    echo '</div>';

  }

}
