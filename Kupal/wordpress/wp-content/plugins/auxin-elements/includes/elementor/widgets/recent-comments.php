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
 * Elementor 'RecentComments' widget.
 *
 * Elementor widget that displays an 'RecentComments' with lightbox.
 *
 * @since 1.0.0
 */
class RecentComments extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'RecentComments' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_recentcomments';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'RecentComments' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Recent Commented Posts', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'RecentComments' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-comments auxin-badge';
    }

    /**
     * Get recent comments.
     *
     * Retrieve 'RecentComments' widget query.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Query.
     */
    public function get_post_types( $args = array()  ) {
        // Result variable
        $result = array( 'all' => __( 'All Post Types', 'auxin-elements'  ) );
        // Get all public post types
        $get_post_types = get_post_types( array(
            'public' => true,
            'exclude_from_search' => false
            )
        );
        foreach ( $get_post_types as $key =>  $value ) {
            $result[ $key ] = ucfirst( $value );
        }

        return $result;
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'RecentComments' widget icon.
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
     * Register 'RecentComments' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Content TAB
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'general',
            array(
                'label'      => __('General', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'post_types',
            array(
                'label'       => __( 'Post Types', 'auxin-elements' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 0,
                'options'     => $this->get_post_types()
            )
        );

        $this->add_control(
            'number',
            array(
                'label'       => __('Number of comments to show', 'auxin-elements'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '8',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->add_control(
            'show_info',
            array(
                'label'        => __('Display info','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
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
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->start_controls_tabs( 'title_colors' );

        $this->start_controls_tab(
            'title_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-title a' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-title a:hover' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-title'
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
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  info_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'info_style_section',
            array(
                'label'     => __( 'Meta Info', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->start_controls_tabs( 'info_colors' );

        $this->start_controls_tab(
            'info_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'info_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-info a, {{WRAPPER}} .entry-info' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'info_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-elements' )
            )
        );

        $this->add_control(
            'info_hover_color',
            array(
                'label' => __( 'Color', 'auxin-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-info a:hover' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'info_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-info, {{WRAPPER}} .entry-info a'
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
                )
            )
        );

        $this->end_controls_section();

    }


    /**
     * Get recent comments.
     *
     * Retrieve 'RecentComments' widget query.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Query.
     */
    public function get_comments( $args = array()  ) {
        // The Query
        $comments_query = new WP_Comment_Query;
        $comments = $comments_query->query( $args );
        return $comments;
    }

    /**
    * Render recentcomments widget output on the frontend.
    *
    * Written in PHP and used to generate the final HTML.
    *
    * @since 1.0.0
    * @access protected
    */
    protected function render() {
        $settings   = $this->get_settings_for_display();
        $query_args = array();

        if( $settings['post_types'] !== 'all' ){
            $query_args['post_type'] = $settings['post_types'];
        }

        if( isset( $settings['number'] ) ){
            $query_args['number'] = $settings['number'];
        }

        $comments   = auxin_get_comments( $query_args );

        // Defining default attributes
        $default_atts = array(
            'title'         => '',
            'direction'     => 'horizontal',
            'size'          => 'medium',

            'extra_classes' => '',
            'custom_el_id'  => '',
            'base_class'    => 'aux-widget-recent-comments'
        );

        $result = auxin_get_widget_scafold( array(), $default_atts );
        extract( $result['parsed_atts'] );

        // widget header ------------------------------
        echo $result['widget_header'];
        echo $result['widget_title'];

        if( empty( $comments ) ){
            echo sprintf( '<p>%s<p>', __( 'No comments found!', 'auxin-elements' ) );
        } else {
            // widget output -----------------------
            ob_start();
            foreach ( $comments as $key => $comment ):
        ?>
            <div id="aux-comment-<?php echo esc_attr( $comment->comment_ID ); ?>" class="aux-comment">
                <div class="entry-header">
                    <h3 class="entry-title aux-h3">
                        <a href="<?php echo get_comment_link( $comment->comment_ID ); ?>" title="<?php echo esc_attr( get_the_title( $comment->comment_post_ID ) ); ?>">
                            <?php echo get_the_title( $comment->comment_post_ID ); ?>
                        </a>
                    </h3>
                </div>
                <?php if( isset( $settings['show_info'] ) && auxin_is_true( $settings['show_info'] ) ) : ?>
                <div class="entry-info">
                    <div class="entry-date">
                        <?php echo human_time_diff( strtotime( $comment->comment_date_gmt ) ) . ' ' . __( 'Ago', 'auxin-elements' ); ?>
                    </div>
                    <div class="entry-author">
                        <span class="meta-sep"><?php echo __( 'By', 'auxin-elements' ); ?></span>
                        <span class="author vcard"><?php echo esc_html( $comment->comment_author ); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php
            endforeach;
            echo ob_get_clean();
        }

        // widget footer ------------------------------
        echo $result['widget_footer'];

    }

}
