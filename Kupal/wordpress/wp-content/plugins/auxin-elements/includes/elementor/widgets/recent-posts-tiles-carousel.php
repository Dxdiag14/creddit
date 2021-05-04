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
 * Elementor 'RecentPostsTilesCarousel' widget.
 *
 * Elementor widget that displays an 'RecentPostsTilesCarousel' with lightbox.
 *
 * @since 1.0.0
 */
class RecentPostsTilesCarousel extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'RecentPostsTilesCarousel' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_recent_posts_tiles_carousel';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'RecentPostsTilesCarousel' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Tiles Carousel Posts', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'RecentPostsTilesCarousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-posts-carousel auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'RecentPostsTilesCarousel' widget icon.
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
     * Retrieve 'RecentPostsTilesCarousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_terms() {
        $terms = get_terms( 'category', 'orderby=count&hide_empty=0' );
        $list  = array( ' ' => __('All Categories', 'auxin-elements' ) ) ;
        foreach ( $terms as $key => $value ) {
            $list[$value->term_id] = $value->name;
        }

        return $list;
    }

    /**
     * Register 'RecentPostsTilesCarousel' widget controls.
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

        $this->add_control(
            'tile_style_pattern',
            array(
                'label'       => __('Post Tile styles','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'default'     => 'default',
                'options'     => array(
                    'default'    => array(
                        'label'    => __( 'Default', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-5.svg'
                    ),
                    'pattern-1'  => array(
                        'label'    => __( 'Pattern 1', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-3.svg'
                    ),
                    'pattern-2'  => array(
                        'label'    => __( 'Pattern 2', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-6.svg'
                    ),
                    'pattern-3'  => array(
                        'label'    => __( 'Pattern 3', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-7.svg'
                    ),
                    'pattern-4'  => array(
                        'label'    => __( 'Pattern 4', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-8.svg'
                    ),
                    'pattern-5'  => array(
                        'label'    => __( 'Pattern 5', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-4.svg'
                    ),
                    'pattern-6'  => array(
                        'label'    => __('Pattern 6', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-1.svg'
                    ),
                    'pattern-7'  => array(
                        'label'    => __('Pattern 7', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/tile-2.svg'
                    )
                )
            )
        );

        $this->add_control(
            'tile_style',
            array(
                'label'       => __('Post tile style','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'default'     => '',
                'options'     => array(
                    ''            => array(
                        'label' => __('Standard', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    'aux-overlay' => array(
                        'label' => __('Dark', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-curved.svg'
                    )
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  carousel_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'carousel_section',
            array(
                'label' => __('Carousel', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_LAYOUT
            )
        );

        $this->add_control(
            'carousel_navigation_control',
            array(
                'label'       => __('Navigation control', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'bullets',
                'options'     => array(
                    ''        => __('None', 'auxin-elements'),
                    'arrows'  => __('Arrows', 'auxin-elements'),
                    'bullets' => __('Bullets', 'auxin-elements')
                )
            )
        );

        $this->add_control(
            'button_style',
            array(
                'label'       => __('Button Navigation Style','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'default'     => 'pattern-1',
                'options'     => array(
                    'pattern-1'            => array(
                        'label' => __('Pattern 1', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    'pattern-2' => array(
                        'label' => __('Pattern 2', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-curved.svg'
                    )
                ),
                'condition'   => array(
                    'carousel_navigation_control' => array( 'arrows' ),
                )
            )
        );

        $this->add_control(
            'carousel_navigation',
            array(
                'label'       => __('Navigation type', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'peritem',
                'options'     => array(
                   'peritem' => __('Move per column', 'auxin-elements'),
                   'perpage' => __('Move per page', 'auxin-elements'),
                   'scroll'  => __('Smooth scroll', 'auxin-elements')
                )
            )
        );

        $this->add_control(
            'carousel_loop',
            array(
                'label'        => __('Loop navigation','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'carousel_autoplay',
            array(
                'label'        => __('Autoplay carousel','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'carousel_autoplay_delay',
            array(
                'label'       => __( 'Autoplay delay', 'auxin-elements' ),
                'description' => __('Specifies the delay between auto-forwarding in seconds.', 'auxin-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '2'
            )
        );

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
            'display_title',
            array(
                'label'        => __('Display post title', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'show_info',
            array(
                'label'        => __('Display post info','auxin-elements' ),
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
            'only_posts__in',
            array(
                'label'       => __('Only posts','auxin-elements' ),
                'description' => __('If you intend to display ONLY specific posts, you should specify the posts here. You have to insert the post IDs that are separated by comma (eg. 53,34,87,25).', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'include',
            array(
                'label'       => __('Include posts','auxin-elements' ),
                'description' => __('If you intend to include additional posts, you should specify the posts here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'exclude',
            array(
                'label'       => __('Exclude posts','auxin-elements' ),
                'description' => __('If you intend to exclude specific posts from result, you should specify the posts here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
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
        /*  title_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Title', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->start_controls_tabs( 'title_colors' );

        $this->start_controls_tab(
            'title_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' ),
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
                    '{{WRAPPER}} .entry-title a' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' ),
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-title a:hover' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'display_title' => 'yes',
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
                'selector' => '{{WRAPPER}} .entry-title',
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'display_title' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  info_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'info_style_section',
            array(
                'label'     => __( 'Post Info', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_info' => 'yes',
                ),
            )
        );

        $this->start_controls_tabs( 'info_colors' );

        $this->start_controls_tab(
            'info_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' ),
                'condition' => array(
                    'show_info' => 'yes',
                ),
            )
        );

        $this->add_control(
            'info_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-info a, {{WRAPPER}} .entry-info' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_info' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'info_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' ),
                'condition' => array(
                    'show_info' => 'yes',
                ),
            )
        );

        $this->add_control(
            'info_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-info a:hover' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_info' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'info_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-info, {{WRAPPER}} .entry-info a',
                'condition' => array(
                    'show_info' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'info_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-info' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'show_info' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'info_spacing_between',
            array(
                'label' => __( 'Space between metas', 'auxin-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 30
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-info [class^="entry-"] + [class^="entry-"]:before, {{WRAPPER}} .entry-info .entry-tax a:after' =>
                    'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'show_info' => 'yes'
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

        $settings = $this->get_settings_for_display();

        $args     = array(
            // Display Section
            'display_title'               => $settings['display_title'],
            'show_info'                   => $settings['show_info'],

            // Layout section
            'tile_style_pattern'          => $settings['tile_style_pattern'],
            'tile_style'                  => $settings['tile_style'],
            'carousel_navigation'         => $settings['carousel_navigation'],
            'button_style'                => $settings['button_style'],
            'carousel_navigation_control' => $settings['carousel_navigation_control'],
            'carousel_loop'               => $settings['carousel_loop'],
            'carousel_autoplay'           => $settings['carousel_autoplay'],
            'carousel_autoplay_delay'     => $settings['carousel_autoplay_delay'],

            // Query Section
            'cat'                         => $settings['cat'],
            'num'                         => $settings['num'],
            'exclude_without_media'       => $settings['exclude_without_media'],
            'exclude_custom_post_formats' => $settings['exclude_custom_post_formats'],
            'exclude_quote_link'          => $settings['exclude_quote_link'],
            'order_by'                    => $settings['order_by'],
            'order'                       => $settings['order'],
            'only_posts__in'              => $settings['only_posts__in'],
            'include'                     => $settings['include'],
            'exclude'                     => $settings['exclude'],
            'offset'                      => $settings['offset']
        );

        // get the shortcode base blog page
        echo auxin_widget_recent_posts_tiles_carousel_callback( $args );
    }

}
