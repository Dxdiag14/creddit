<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'RecentPostsGridCarousel' widget.
 *
 * Elementor widget that displays an 'RecentPostsGridCarousel' with lightbox.
 *
 * @since 1.0.0
 */
class RecentPostsGridCarousel extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'RecentPostsGridCarousel' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_recent_posts';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'RecentPostsGridCarousel' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Grid & Carousel Posts', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'RecentPostsGridCarousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-posts-grid auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'RecentPostsGridCarousel' widget icon.
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
     * Retrieve 'RecentPostsGridCarousel' widget icon.
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
     * Register 'RecentPostsGridCarousel' widget controls.
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

        $this->add_control(
            'preview_mode',
            array(
                'label'       => __('Display items as', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'grid',
                'options'     => array(
                    'grid'            => __( 'Grid', 'auxin-elements' ),
                    'grid-table'      => __( 'Grid - Table Style', 'auxin-elements' ),
                    'grid-modern'     => __( 'Grid - Modern Style', 'auxin-elements' ),
                    'flip'            => __( 'Flip', 'auxin-elements' ),
                    'carousel-modern' => __( 'Carousel - Modern Style', 'auxin-elements' ),
                    'carousel'        => __( 'Carousel', 'auxin-elements' )
                )
            )
        );

        $this->add_control(
            'carousel_space',
            array(
                'label'       => __( 'Column space', 'auxin-elements' ),
                'description' => __( 'Specifies horizontal space between items (pixel).', 'auxin-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '25',
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
            )
        );

        $this->add_control(
            'content_layout',
            array(
                'label'       => __('Content layout', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'options'     => array(
                    'default'     => __('Full Content', 'auxin-elements'),
                    'entry-boxed' => __('Boxed Content', 'auxin-elements')
                ),
                'condition'   => array(
                    'preview_mode' => array( 'grid', 'grid-table', 'grid-modern' ),
                )
            )
        );

        $this->add_control(
            'grid_table_hover',
            array(
                'label'       => __('Mouse Over Effect', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'label_block' => true,
                'default'     => 'bgimage-bgcolor',
                'options'     => array(
                    'bgcolor'         => __( 'Background color', 'auxin-elements' ),
                    'bgimage'         => __( 'Cover image', 'auxin-elements' ),
                    'bgimage-bgcolor' => __( 'Cover image or background color', 'auxin-elements' ),
                    'none'            => __( 'Nothing', 'auxin-elements' )
                ),
                'condition'   => array(
                    'preview_mode' => array( 'grid', 'grid-table', 'grid-modern' ),
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
                ),
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
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
                ),
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
            )
        );

        $this->add_control(
            'carousel_nav_control_pos',
            array(
                'label'       => __('Control Position', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'center',
                'options'     => array(
                   'center'         => __('Center', 'auxin-elements'),
                   'side'           => __('Side', 'auxin-elements')
                ),
                'condition'   => array(
                    'carousel_navigation_control' => 'arrows',
                )
            )
        );

        $this->add_control(
            'carousel_nav_control_skin',
            array(
                'label'       => __('Control Skin', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'boxed',
                'options'     => array(
                   'boxed' => __('boxed', 'auxin-elements'),
                   'long'  => __('Long Arrow', 'auxin-elements')
                ),
                'condition'   => array(
                    'carousel_navigation_control' => 'arrows',
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
                'default'      => 'yes',
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
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
                'default'      => 'no',
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
            )
        );

        $this->add_control(
            'carousel_autoplay_delay',
            array(
                'label'       => __( 'Autoplay delay', 'auxin-elements' ),
                'description' => __('Specifies the delay between auto-forwarding in seconds.', 'auxin-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '2',
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
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
            'post_media_size',
            array(
                'label'        => __('Media size','auxin-elements' ),
                'type'         => Controls_Manager::SELECT,
                'options'      => auxin_get_available_image_sizes(),
                'default'      => 'auto',
                'condition'    => array(
                    'show_media' => 'yes'
                )
            )
        );

        $this->add_control(
            'ignore_formats',
            array(
                'label'        => __('Ignore post formats media','auxin-elements' ),
                'label_block'  => true,
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'label_block'  => true,
                'condition'    => array(
                    'show_media' => 'yes'
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
            'words_num',
            array(
                'label'       => __( 'Title Trim', 'auxin-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '',
                'condition'   => array(
                    'display_title' => 'yes',
                )
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

        $this->add_control(
            'show_format_icon',
            array(
                'label'        => __('Display format icon','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'post_info_position',
            array(
                'label'       => __('Post info position', 'auxin-elements' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'after-title',
                'options'     => array(
                    'after-title'  => __('After Title' , 'auxin-elements' ),
                    'before-title' => __('Before Title', 'auxin-elements' )
                ),
                'condition'   => array(
                    'show_info' => 'yes',
                    'preview_mode!' => 'flip'
                )
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
                'default'      => 'yes',
                'condition'   => array(
                    'show_info' => 'yes',
                )
            )
        );

        $this->add_control(
            'show_badge',
            array(
                'label'        => __('Display Category Badge', 'auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'show_date',
            array(
                'label'        => __('Display Date','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'   => array(
                    'show_info' => 'yes',
                )
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
            )
        );

        $this->add_control(
            'show_content',
            array(
                'label'        => __('Display post content','auxin-elements' ),
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
                'label'        => __('Display Comments Number', 'auxin-elements' ),
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
                    'none'     => __('None', 'auxin-elements')
                ),
                'label_block' => true
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

        $this->add_control(
            'meta_info_position',
            array(
                'label'       => __('Meta info position', 'auxin-elements' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'after-content',
                'options'     => array(
                    'after-content'  => __('After Content' , 'auxin-elements' ),
                    'before-content' => __('Before Content', 'auxin-elements' )
                ),
                'condition'   => array(
                    'preview_mode!' => 'flip'
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
            'use_wp_query',
            array(
                'label'        => __('Use wp query','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
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
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
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
                'default'      => 'no',
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
            )
        );

        $this->add_control(
            'exclude_custom_post_formats',
            array(
                'label'        => __('Exclude all custom post formats','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
            )
        );

        $this->add_control(
            'include_post_formats_in',
            array(
                'label'       => __('Include custom post formats', 'auxin-elements'),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => array(
                    'aside'    => __('Aside', 'auxin-elements'),
                    'gallery'  => __('Gallery', 'auxin-elements'),
                    'image'    => __('Image', 'auxin-elements'),
                    'link'     => __('Link', 'auxin-elements'),
                    'quote'    => __('Quote', 'auxin-elements'),
                    'video'    => __('Video', 'auxin-elements'),
                    'audio'    => __('Audio', 'auxin-elements')
                ),
                'condition'    => array(
                    'exclude_custom_post_formats!' => 'yes',
                    'use_wp_query!' => 'yes'
                )
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
                    'use_wp_query!' => 'yes'
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
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
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
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
            )
        );

        $this->add_control(
            'only_posts__in',
            array(
                'label'       => __('Only posts','auxin-elements' ),
                'description' => __('If you intend to display ONLY specific posts, you should specify the posts here. You have to insert the post IDs that are separated by comma (eg. 53,34,87,25).', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
            )
        );

        $this->add_control(
            'include',
            array(
                'label'       => __('Include posts','auxin-elements' ),
                'description' => __('If you intend to include additional posts, you should specify the posts here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
            )
        );

        $this->add_control(
            'exclude',
            array(
                'label'       => __('Exclude posts','auxin-elements' ),
                'description' => __('If you intend to exclude specific posts from result, you should specify the posts here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-elements' ),
                'type'        => Controls_Manager::TEXT,
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
            )
        );

        $this->add_control(
            'offset',
            array(
                'label'       => __('Start offset','auxin-elements' ),
                'description' => __('Number of post to displace or pass over.', 'auxin-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'condition'   => array(
                    'use_wp_query!' => 'yes'
                )
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
                    '0.75'   => __('Horizontal 4:3' , 'auxin-elements'),
                    '0.56'   => __('Horizontal 16:9', 'auxin-elements'),
                    '1.00'   => __('Square 1:1'     , 'auxin-elements'),
                    '1.33'   => __('Vertical 3:4'   , 'auxin-elements'),
                    'custom' => __('Custom'         , 'auxin-elements'),
                ),
                'condition' => array(
                    'show_media' => 'yes',
                ),
            )
        );


        $this->add_responsive_control(
            'image_aspect_ratio_custom',
            array(
                'label' => __( 'Custom Aspect Ratio', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 3,
                        'step' => 0.1
                    ),
                ),
                'condition' => array(
                    'image_aspect_ratio' => 'custom',
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

        $this->start_controls_tabs( 'img_tabs' );

        $this->start_controls_tab(
            'image_tab_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' ),
                'condition' => array(
                    'show_media' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'img_normal_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-media-image',
                'condition'  => array(
                    'show_media' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_tab_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' ),
                'condition' => array(
                    'show_media' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'img_hover_box_shadow',
                'selector'  => '{{WRAPPER}} .post:hover .aux-media-image',
                'condition'  => array(
                    'show_media' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
                'selector' => '{{WRAPPER}} .entry-title a',
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
        /*  badge_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'badge_style_section',
            array(
                'label'     => __( 'Badge', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_badge' => 'yes',
                )
            )
        );

        $this->start_controls_tabs( 'badge_colors' );

        $this->start_controls_tab(
            'badge_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'badge_background_color',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .entry-badge',
            )
        );

        $this->add_control(
            'badge_text_color',
            array(
                'label' => __( 'Text', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-badge a' => 'color: {{VALUE}} !important;',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'badge_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'badge_hover_background_color',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .entry-badge:hover',
            )
        );

        $this->add_control(
            'badge_hover_text_color',
            array(
                'label' => __( 'Text', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-badge a:hover' => 'color: {{VALUE}} !important;',
                )
            )
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'badge_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-badge a'
            )
        );


        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => __( 'Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .entry-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'badge_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-badge' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                )
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
            'meta_padding',
            array(
                'label'      => __( 'Padding for meta wrapper', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .entry-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
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


        /*  wrapper_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'wrapper_style_section',
            array(
                'label'     => __( 'Wrapper', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'wrapper_main_padding',
            array(
                'label'      => __( 'Padding for main wrapper', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .column-entry' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .type-post .entry-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->start_controls_tabs( 'button_background' );

        $this->start_controls_tab(
            'button_bg_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'background',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-col .column-entry',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'box_shadow',
                'selector'  => '{{WRAPPER}} .aux-col .column-entry'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_bg_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'general_hover_text_color',
            array(
                'label' => __( 'Text Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-col:hover .entry-main a, {{WRAPPER}} .aux-col:hover .entry-main *' => 'transition:all 150ms ease; color:{{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'hover_background',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-col:hover .column-entry',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'hover_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-col:hover .column-entry'
            )
        );

        $this->add_control(
            'hover_wrapper_transition_duration',
            array(
                'label'     => __( 'Transition duration', 'auxin-elements' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 2000,
                        'step' => 50
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-col:hover .column-entry' => 'transition-duration:{{SIZE}}ms;'
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'align',
            array(
                'label'      => __('Align','auxin-elements'),
                'type'       => Controls_Manager::CHOOSE,
                'devices'    => array( 'desktop', 'mobile' ),
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
                'selectors'  => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                )
            )
        );

        $this->end_controls_section();

        /*  flip_wrapper_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'flip_wrapper_style_section',
            array(
                'label'     => __( 'Flip Wrapper', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'preview_mode' => 'flip'
                )
            )
        );

        $this->start_controls_tabs( 'flip_button_background' );

        $this->start_controls_tab(
            'flip_button_bg_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'flip_background',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-col .aux-flip-back',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'flip_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-col .aux-flip-back'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'flip_button_bg_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'flip_general_hover_text_color',
            array(
                'label' => __( 'Text Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-col:hover .aux-flip-back .entry-main a, {{WRAPPER}} .aux-col:hover .aux-flip-back .entry-main *' => 'transition:all 150ms ease; color:{{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'flip_hover_background',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-col:hover .aux-flip-back',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'flip_hover_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-col:hover .aux-flip-back'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'flip_align',
            array(
                'label'      => __('Align','auxin-elements'),
                'type'       => Controls_Manager::CHOOSE,
                'devices'    => array( 'desktop', 'mobile' ),
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
                'selectors'  => array(
                    '{{WRAPPER}} .aux-flip-back' => 'text-align: {{VALUE}}',
                )
            )
        );

        $this->add_responsive_control(
            'flip_wrapper_padding',
            array(
                'label'      => __( 'Padding for content wrapper', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .type-post .aux-flip-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  ReadMore Button
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'btn_section',
            array(
                'label'      => __('Read More', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs( 'btn_bg_tab' );

        $this->start_controls_tab(
            'btn_bg_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'btn',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .entry-meta .aux-read-more',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'btn_shadow',
                'selector'  => '{{WRAPPER}} .entry-meta .aux-read-more'
            )
        );

        $this->add_control(
            'btn_text_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-meta .aux-read-more' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'btn_text_shadow',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .entry-meta .aux-read-more',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'btn_text_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-meta .aux-read-more'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btn_bg_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'btn_bg_hover',
                'label' => __( 'Background', 'auxin-elements' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .entry-meta .aux-read-more:hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'btn_shadow_hover',
                'selector'  => '{{WRAPPER}} .entry-meta .aux-read-more:hover'
            )
        );

        $this->add_control(
            'btn_text_color_hover',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-meta .aux-read-more:hover' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'btn_text_shadow_hover',
                'label' => __( 'Text Shadow', 'auxin-elements' ),
                'selector' => '{{WRAPPER}} .entry-meta .aux-read-more:hover',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'btn_text_typography_hover',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-meta .aux-read-more:hover'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_padding',
            array(
                'label'      => __( 'Button Padding', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .entry-meta .aux-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

    $settings = $this->get_settings_for_display();
    $settings['post_media_size'] = $settings['post_media_size'] == 'auto' ? '' : $settings['post_media_size'];

    if ( ! in_array( $settings['post_media_size'], auxin_get_available_image_sizes() ) ) {
        $settings['post_media_size'] = '';
    }
    
    $args     = array(
        // Display section
        'show_media'                  => $settings['show_media'],
        'size'                        => $settings['post_media_size'],
        'ignore_formats'              => $settings['ignore_formats'],
        'preloadable'                 => $settings['preloadable'],
        'preload_preview'             => $settings['preload_preview'],
        'preload_bgcolor'             => $settings['preload_bgcolor'],
        'display_title'               => $settings['display_title'],
        'words_num'                   => $settings['words_num'],
        'show_info'                   => $settings['show_info'],
        'show_format_icon'            => $settings['show_format_icon'],
        'post_info_position'          => $settings['post_info_position'],
        'meta_info_position'          => $settings['meta_info_position'],
        'display_comments'            => $settings['display_comments'],
        'display_like'                => $settings['display_like'],
        'show_content'                => $settings['show_content'],
        'display_categories'          => $settings['display_categories'],
        'show_badge'                  => $settings['show_badge'],
        'show_date'                   => $settings['show_date'],
        'show_excerpt'                => $settings['show_excerpt'],
        'excerpt_len'                 => $settings['excerpt_len'],
        'author_or_readmore'          => $settings['author_or_readmore'],
        'display_author_header'       => $settings['display_author_header'],
        'display_author_footer'       => $settings['display_author_footer'],

        // Content Section
        'desktop_cnum'                => $settings['columns'],
        'tablet_cnum'                 => $settings['columns_tablet'],
        'phone_cnum'                  => $settings['columns_mobile'],
        'preview_mode'                => $settings['preview_mode'],
        'content_layout'              => $settings['content_layout'],
        'grid_table_hover'            => $settings['grid_table_hover'],
        'carousel_space'              => $settings['carousel_space'],
        'carousel_navigation'         => $settings['carousel_navigation'],
        'carousel_navigation_control' => $settings['carousel_navigation_control'],
        'carousel_nav_control_pos'    => $settings['carousel_nav_control_pos'],
        'carousel_nav_control_skin'   => $settings['carousel_nav_control_skin'],
        'carousel_loop'               => $settings['carousel_loop'],
        'carousel_autoplay'           => $settings['carousel_autoplay'],
        'carousel_autoplay_delay'     => $settings['carousel_autoplay_delay'],

        // Query Section
        'cat'                         => $settings['cat'],
        'num'                         => $settings['num'],
        'exclude_without_media'       => $settings['exclude_without_media'],
        'exclude_custom_post_formats' => $settings['exclude_custom_post_formats'],
        'include_post_formats_in'     => $settings['include_post_formats_in'],
        'exclude_quote_link'          => $settings['exclude_quote_link'],
        'order_by'                    => $settings['order_by'],
        'order'                       => $settings['order'],
        'only_posts__in'              => $settings['only_posts__in'],
        'include'                     => $settings['include'],
        'exclude'                     => $settings['exclude'],
        'offset'                      => $settings['offset'],

        // Paginate Section
        'loadmore_type'               => $settings['loadmore_type'],

        // Style Section
        'image_aspect_ratio'          => $settings['image_aspect_ratio'] === 'custom' ? $settings['image_aspect_ratio_custom']['size'] : $settings['image_aspect_ratio'],
    );

    if( auxin_is_true( $settings['use_wp_query'] ) ){
        $args['use_wp_query'] =  true;
        $args['reset_query'] =  false;
        $args['request_from'] =  'archive';
        $args['paged'] =  max( 1, get_query_var('paged'), get_query_var('page') );
    }

    // get the shortcode base blog page
    echo auxin_widget_recent_posts_callback( $args );

  }

}
