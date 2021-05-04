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
 * Elementor 'RecentPostsTimeline' widget.
 *
 * Elementor widget that displays an 'RecentPostsTimeline' with lightbox.
 *
 * @since 1.0.0
 */
class RecentPostsTimeline extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'RecentPostsTimeline' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_recent_posts_timeline';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'RecentPostsTimeline' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Timeline Posts', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'RecentPostsTimeline' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-time-line auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'RecentPostsTimeline' widget icon.
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
     * Retrieve 'RecentPostsTimeline' widget icon.
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
     * Register 'RecentPostsTimeline' widget controls.
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
            'timeline_alignment',
            array(
                'label'       => __('Timeline aignment','auxin-elements' ),
                'description' => __('Specifies the alignment of timeline element.', 'auxin-elements'),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'center' => array(
                        'label'      => __('Center', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/blog-layout-8.svg'
                    ),
                    'left'   => array(
                        'label'      => __('Left', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/blog-layout-8-left.svg'
                    ),
                    'right'  => array(
                        'label'      => __('Right', 'auxin-elements'),
                        'image'      => AUXIN_URL . 'images/visual-select/blog-layout-8-right.svg'
                    )
                ),
                'default'     => 'center'
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
            'crop',
            array(
                'label'        => __('Crop image','auxin-elements' ),
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
                'label'        => __('Display post meta', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'display_like',
            array(
                'label'        => __('Display like button', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'display_comments',
            array(
                'label'        => __('Display Comment Number', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'show_excerpt',
            array(
                'label'        => __('Display excerpt','auxin-elements' ),
                'description'  => __('Enable it to display post summary instead of full content.','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'excerpt_len',
            array(
                'label'       => __('Excerpt length','auxin-elements' ),
                'description' => __('Specify summary content in character.','auxin-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '160',
                'condition'   => array(
                    'show_excerpt' => 'yes',
                )
            )
        );

        $this->add_control(
            'author_or_readmore',
            array(
                'label'       => __('Display author or read more', 'auxin-elements'),
                'label_block' => true,
                'description' => __('Specifies whether to show author or read more on each post.', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'readmore',
                'options'     => array(
                    'readmore' => __('Read More', 'auxin-elements'),
                    'author'   => __('Author Name', 'auxin-elements'),
                    'none'     => __('None', 'auxin-elements'),
                ),
                'label_block' => true
            )
        );

        $this->add_control(
            'display_author_header',
            array(
                'label'        => __('Display Author in Header','auxin-elements' ),
                'description'  => __('Enable it to display author name in header','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'author_or_readmore' => 'author',
                )
            )
        );

        $this->add_control(
            'display_author_footer',
            array(
                'label'        => __('Display Author in Footer','auxin-elements' ),
                'description'  => __('Enable it to display author name in footer','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => array(
                    'author_or_readmore' => 'author',
                )
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
        /*  paginate_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'paginate_section',
            array(
                'label'      => __('Paginate', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'loadmore_type',
            array(
                'label'       => __('Load More Type','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    ''       => array(
                        'label' => __('None', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/load-more-none.svg'
                    ),
                    'scroll' => array(
                        'label' => __('Infinite Scroll', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/load-more-infinite.svg'
                    ),
                    'next'   => array(
                        'label' => __('Next Button', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/load-more-button.svg'
                    ),
                    'next-prev'  => array(
                        'label' => __('Next Prev', 'auxin-elements' ),
                        'image' => AUXIN_URL . 'images/visual-select/load-more-next-prev.svg'
                    )
                ),
                'default'     => ''
            )
        );

        $this->end_controls_section();

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
                    '{{WRAPPER}} .entry-media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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

        /*-----------------------------------------------------------------------------------*/
        /*  content_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => __( 'Excerpt', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_excerpt' => 'yes',
                )
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-content' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_excerpt' => 'yes',
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
                    'show_excerpt' => 'yes',
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
                    'show_excerpt' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  meta_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'meta_style_section',
            array(
                'label'      => __( 'Meta', 'auxin-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE
            )
        );

        $this->start_controls_tabs( 'meta_colors' );

        $this->start_controls_tab(
            'meta_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' ),
            )
        );

        $this->add_control(
            'meta_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-meta a, {{WRAPPER}} .entry-meta, {{WRAPPER}} .entry-meta span' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'meta_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'meta_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-meta a:hover, {{WRAPPER}} .entry-meta span:hover' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'meta_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-meta, {{WRAPPER}} .entry-meta a, {{WRAPPER}} .entry-meta span'
            )
        );

        $this->add_responsive_control(
            'meta_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
            'show_media'                  => $settings['show_media'],
            'crop'                        => $settings['crop'],
            'preloadable'                 => $settings['preloadable'],
            'preload_preview'             => $settings['preload_preview'],
            'preload_bgcolor'             => $settings['preload_bgcolor'],
            'display_title'               => $settings['display_title'],
            'show_info'                   => $settings['show_info'],
            'display_like'                => $settings['display_like'],
            'show_excerpt'                => $settings['show_excerpt'],
            'excerpt_len'                 => $settings['excerpt_len'],
            'author_or_readmore'          => $settings['author_or_readmore'],
            'display_author_header'       => $settings['display_author_header'],
            'display_author_footer'       => $settings['display_author_footer'],
            'display_comments'            => $settings['display_comments'],

            // Layout Section
            'timeline_alignment'          => $settings['timeline_alignment'],

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
            'offset'                      => $settings['offset'],
            'loadmore_type'               => $settings['loadmore_type'],

            // Style Section
            'image_aspect_ratio'          => $settings['image_aspect_ratio'],
        );

        // get the shortcode base blog page
        echo auxin_widget_recent_posts_timeline_callback( $args );
    }

}
