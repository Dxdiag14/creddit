<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'RecentProducts' widget.
 *
 * Elementor widget that displays an 'RecentProducts' with lightbox.
 *
 * @since 1.0.0
 */
class RecentProducts extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'RecentProducts' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_recent_product';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'RecentProducts' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Recent Products', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'RecentProducts' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-woocommerce auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'RecentProducts' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-dynamic' );
    }

    /**
     * Retrieve the terms in a given taxonomy or list of taxonomies.
     *
     * Retrieve 'RecentProducts' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_terms() {
        // Get terms
        $terms = get_terms(
            array(
                'taxonomy'   => 'product_cat',
                'orderby'    => 'count',
                'hide_empty' => true
            )
        );

        // Then create a list
        $list  = array( ' ' => __('All Categories', 'auxin-elements' ) ) ;

        if ( ! is_wp_error( $terms ) && is_array( $terms ) ){
            foreach ( $terms as $key => $value ) {
                $list[$value->term_id] = $value->name;
            }
        }

        return $list;
    }

    /**
     * Register 'RecentProducts' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  layout_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'layout_section',
            array(
                'label' => __('Layout', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_LAYOUT
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => __( 'Columns', 'auxin-elements' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '4',
                'tablet_default' => 'inherit',
                'mobile_default' => '1',
                'options'        => array(
                    'inherit' => __( 'Inherited from larger', 'auxin-elements' ),
                    '1'       => '1',
                    '2'       => '2',
                    '3'       => '3',
                    '4'       => '4',
                    '5'       => '5',
                    '6'       => '6'
                ),
                'frontend_available' => true,
            )
        );

        // $this->add_control(
        //     'content_layout',
        //     array(
        //         'label'       => __('Content layout', 'auxin-elements'),
        //         'type'        => Controls_Manager::SELECT,
        //         'default'     => 'default',
        //         'options'     => array(
        //             'default'     => __('Full Content', 'auxin-elements'),
        //             'entry-boxed' => __('Boxed Content', 'auxin-elements')
        //         )
        //     )
        // );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  display_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'display_section',
            array(
                'label' => __('Display', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_LAYOUT
            )
        );

        $this->add_control(
            'display_price',
            array(
                'label'        => __('Display products price','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'display_sale_badge',
            array(
                'label'        => __('Display sale badge','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'show_media',
            array(
                'label'        => __('Display post media (image, video, etc)','auxin-elements' ),
                'label_block'  => true,
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'label_block'  => true
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
                'default'      => 'no',
                'condition'    => array(
                    'show_media' => 'yes',
                )
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

        $this->add_control(
            'display_title',
            array(
                'label'        => __('Display product title', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'display_categories',
            array(
                'label'        => __('Display Categories','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'display_button',
            array(
                'label'        => __('Display Add To Cart','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  query_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'query_section',
            array(
                'label'      => __('Query', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'cat',
            array(
                'label'       => __('Categories', 'auxin-elements'),
                'description' => __('Specifies a category that you want to show posts from it.', 'auxin-elements' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_terms(),
                'default'     => array( ' ' ),
            )
        );

        $this->add_control(
            'num',
            array(
                'label'       => __('Number of posts to show', 'auxin-elements'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '8',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->add_control(
            'exclude_without_media',
            array(
                'label'        => __('Exclude posts without media','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'exclude_custom_post_formats',
            array(
                'label'        => __('Exclude custom post formats','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
            )
        );

        $this->add_control(
            'exclude_quote_link',
            array(
                'label'        => __('Exclude quote and link post formats','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => array(
                    'exclude_custom_post_formats' => 'yes',
                )
            )
        );

        $this->add_control(
            'order_by',
            array(
                'label'       => __('Order by', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'date',
                'options'     => array(
                    'date'            => __('Date', 'auxin-elements'),
                    'menu_order date' => __('Menu Order', 'auxin-elements'),
                    'title'           => __('Title', 'auxin-elements'),
                    'ID'              => __('ID', 'auxin-elements'),
                    'rand'            => __('Random', 'auxin-elements'),
                    'comment_count'   => __('Comments', 'auxin-elements'),
                    'modified'        => __('Date Modified', 'auxin-elements'),
                    'author'          => __('Author', 'auxin-elements'),
                    'post__in'        => __('Inserted Post IDs', 'auxin-elements')
                ),
            )
        );

        $this->add_control(
            'order',
            array(
                'label'       => __('Order', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'DESC',
                'options'     => array(
                    'DESC'          => __('Descending', 'auxin-elements'),
                    'ASC'           => __('Ascending', 'auxin-elements'),
                ),
            )
        );

        $this->add_control(
            'only_products__in',
            array(
                'label'       => __('Only products','auxin-elements' ),
                'description' => __('If you intend to display ONLY specific products, you should specify the products here. You have to insert the Products IDs that are separated by comma (eg. 53,34,87,25).', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'include',
            array(
                'label'       => __('Include products','auxin-elements' ),
                'description' => __('If you intend to include additional products, you should specify the products here. You have to insert the Products IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'exclude',
            array(
                'label'       => __('Exclude products','auxin-elements' ),
                'description' => __('If you intend to exclude specific products from result, you should specify the products here. You have to insert the Products IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'offset',
            array(
                'label'       => __('Start offset','auxin-elements' ),
                'description' => __('Number of post to displace or pass over.', 'auxin-elements' ),
                'type'        => Controls_Manager::NUMBER
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  paginate_section
        /*-----------------------------------------------------------------------------------*/

        // $this->start_controls_section(
        //     'paginate_section',
        //     array(
        //         'label'      => __('Paginate', 'auxin-elements' ),
        //     )
        // );

        // $this->add_control(
        //     'loadmore_type',
        //     array(
        //         'label'       => __('Load More Type','auxin-elements' ),
        //         'type'        => 'aux-visual-select',
        //         'options'     => array(
        //             ''       => array(
        //                 'label' => __('None', 'auxin-elements' ),
        //                 'image' => AUXIN_URL . 'images/visual-select/load-more-none.svg'
        //             ),
        //             'scroll' => array(
        //                 'label' => __('Infinite Scroll', 'auxin-elements' ),
        //                 'image' => AUXIN_URL . 'images/visual-select/load-more-infinite.svg'
        //             ),
        //             'next'   => array(
        //                 'label' => __('Next Button', 'auxin-elements' ),
        //                 'image' => AUXIN_URL . 'images/visual-select/load-more-button.svg'
        //             ),
        //             'next-prev'  => array(
        //                 'label' => __('Next Prev', 'auxin-elements' ),
        //                 'image' => AUXIN_URL . 'images/visual-select/load-more-next-prev.svg'
        //             )
        //         ),
        //         'default'     => 'scroll'
        //     )
        // );

        // $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  image_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'image_style_section',
            array(
                'label'     => __( 'Image', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_media' => 'yes',
                ),
            )
        );

        $this->add_control(
            'image_aspect_ratio',
            array(
                'label'       => __('Image aspect ratio', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => '0.75',
                'options'     => array(
                    '0.75' => __('Horizontal 4:3' , 'auxin-elements'),
                    '0.56' => __('Horizontal 16:9', 'auxin-elements'),
                    '1.00' => __('Square 1:1'     , 'auxin-elements'),
                    '1.33' => __('Vertical 3:4'   , 'auxin-elements')
                ),
                'condition' => array(
                    'show_media' => 'yes',
                ),
            )
        );

        $this->add_control(
            'img_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-media-frame img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ),
                'condition'  => array(
                    'show_media' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  title_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'      => __( 'Title', 'auxin-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .woocommerce-loop-product__title' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .woocommerce-loop-product__title',
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            array(
                'label' => __( 'Spacing', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .woocommerce-loop-product__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  category_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'category_style_section',
            array(
                'label'      => __( 'Category', 'auxin-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'display_categories' => 'yes',
                ),
            )
        );

        $this->start_controls_tabs( 'category_colors' );

        $this->start_controls_tab(
            'category_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' ),
                'condition' => array(
                    'display_categories' => 'yes',
                ),
            )
        );

        $this->add_control(
            'category_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .auxshp-meta-terms a, {{WRAPPER}} .auxshp-meta-terms' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'display_categories' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'category_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' ),
                'condition' => array(
                    'display_categories' => 'yes',
                ),
            )
        );

        $this->add_control(
            'category_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .auxshp-meta-terms a:hover' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'display_categories' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'category_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .auxshp-meta-terms, {{WRAPPER}} .auxshp-meta-terms a',
                'condition' => array(
                    'display_categories' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'category_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .product_meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'display_categories' => 'yes',
                ),
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
        // Check whether required resources are available
        if( ! auxin_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            auxin_elementor_plugin_missing_notice( array( 'plugin_name' => __( 'WooCommerce', 'auxin-elements' ) ) );
            return;
        }

        $settings = $this->get_settings_for_display();

        $args     = array(
            // Display Section
            'display_price'               => $settings['display_price'],
            'display_sale_badge'          => $settings['display_sale_badge'],
            'show_media'                  => $settings['show_media'],
            'preloadable'                 => $settings['preloadable'],
            'preload_preview'             => $settings['preload_preview'],
            'preload_bgcolor'             => $settings['preload_bgcolor'],
            'display_title'               => $settings['display_title'],
            'display_categories'          => $settings['display_categories'],
            'display_button'              => $settings['display_button'],

            // Layout Section
            'desktop_cnum'                => $settings['columns'],
            'tablet_cnum'                 => $settings['columns_tablet'],
            'phone_cnum'                  => $settings['columns_mobile'],
            // 'content_layout'           => $settings['content_layout'],


            // Query Section
            'cat'                         => $settings['cat'],
            'num'                         => $settings['num'],
            'exclude_without_media'       => $settings['exclude_without_media'],
            'exclude_custom_post_formats' => $settings['exclude_custom_post_formats'],
            'exclude_quote_link'          => $settings['exclude_quote_link'],
            'order_by'                    => $settings['order_by'],
            'order'                       => $settings['order'],
            'only_products__in'           => $settings['only_products__in'],
            'include'                     => $settings['include'],
            'exclude'                     => $settings['exclude'],
            'offset'                      => $settings['offset'],

            // Style Section
            'image_aspect_ratio'          => $settings['image_aspect_ratio'],
        );

        // get the shortcode base blog page
        echo auxin_widget_the_recent_products_callback( $args );
    }

}
