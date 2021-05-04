<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


class Section {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;


    function __construct(){
        // Modify section render
        // add_action( 'elementor/frontend/section/before_render', array( $this, 'modify_render' ) );

        // Add new controls
        add_action( 'elementor/element/after_section_end', array( $this, 'add_pseudo_background_controls' ), 20, 3 );

        // Go pro notice for inner container options
        add_action( 'elementor/element/section/section_typo/after_section_end'          , array( $this, 'add_inner_container_notice' ), 90 );
    }

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * Modify the render of section element
     *
     * @param  Element_Section $section Instance of Section element
     *
     * @return void
     */
    public function modify_render( $section ){

    }


    /**
     * Add extra controls to section element
     *
     * @param  Element_Section $section Instance of Section element
     *
     * @return void
     */
    public function add_controls( $section ){

    }

    /**
     * Add option for styling the inner container
     *
     * @return void
     */
    public function add_inner_container_notice( $widget ){

        if( defined('THEME_PRO') && THEME_PRO ){
            return;
        }

        $widget->start_controls_section(
            'aux_pro_inner_container_notice',
            array(
                'label'     => __( 'Box Container', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $widget->add_control(
            'inner_container_go_pro_notice',
            array(
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'raw' => '<div class="auxin-elementor-panel-notice">' .
                        '<i class="auxin-elementor-upgrade-notice-icon eicon-favorite" aria-hidden="true"></i>
                        <div class="auxin-elementor-panel-notice-title">' .
                            __( 'Parallax Effect', 'auxin-elements' ) .
                        '</div>
                        <div class="auxin-elementor-panel-notice-message">' .
                            __( 'Container options lets you add border, box shadow and background to box container.', 'auxin-elements' ) .
                        '</div>
                        <div class="auxin-elementor-panel-notice-message">' .
                            __( 'This feature is only available on Phlox Pro.', 'auxin-elements' ) .
                        '</div>
                        <a class="auxin-elementor-panel-notice-link elementor-button elementor-button-default auxin-elementor-go-pro-link" href="http://phlox.pro/go-pro/?utm_source=elementor-panel&utm_medium=phlox-free&utm_campaign=phlox-go-pro&utm_content=inner-container" target="_blank">' .
                            __( 'Get Phlox Pro', 'auxin-elements' ) .
                        '</a>
                        </div>'
            )
        );

        $widget->end_controls_section();
    }

    /**
     * Adds controls to define background for pseudo elements
     *
     * @return void
     */
    public function add_pseudo_background_controls( $widget, $section_id, $args ){

        if( 'section' !== $widget->get_name() ){
            return;
        }

        // Anchor element sections
        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }

        $widget->start_controls_section(
            'aux_core_background_pseudo_section',
            array(
                'label'     => __( 'Pseudo Background (developers)', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_control(
            'background_pseudo_description',
            array(
                'raw'  => __( 'Adds background to pseudo elements like ::before and ::after selectors. (developers only)', 'auxin-elements' ),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor'
            )
        );

        $widget->add_control(
            'background_pseudo_before_heading',
            array(
                'label'     => __( 'Background ::before', 'auxin-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            )
        );

        $widget->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'background_pseudo_before',
                'types'    => array( 'classic', 'gradient'),
                'selector' => '{{WRAPPER}} .elementor-container:before'
            )
        );

        $widget->add_control(
            'background_pseudo_after_heading',
            array(
                'label'     => __( 'Background ::after', 'auxin-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            )
        );

        $widget->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'background_pseudo_after',
                'types'    => array( 'classic', 'gradient'),
                'selector' => '{{WRAPPER}} > .elementor-container:after'
            )
        );

        $widget->end_controls_section();
    }

}
