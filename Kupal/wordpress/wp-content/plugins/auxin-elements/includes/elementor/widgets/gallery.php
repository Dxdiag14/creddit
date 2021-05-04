<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'gallery' widget.
 *
 * Elementor widget that displays an 'gallery' with lightbox.
 *
 * @since 1.0.0
 */
class Gallery extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'gallery' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux-gallery';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'gallery' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Modern Gallery', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'gallery' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-gallery-grid auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'gallery' widget icon.
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
     * Register 'gallery' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'section_gallery',
            array(
                'label' => __( 'Gallery Images', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'wp_gallery',
            array(
                'label' => __( 'Add Images', 'auxin-elements' ),
                'type' => Controls_Manager::GALLERY,
                'show_label' => false,
                'dynamic' => array(
                    'active' => true,
                )
            )
        );

        $this->add_control(
            'layout',
            array(
                'label' => __( 'Gallery layout', 'auxin-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => array(
                    'grid' => __( 'Grid', 'auxin-elements' ),
                    'masonry' => __( 'Masonry', 'auxin-elements' ),
                    'tiles' => __( 'Tiles', 'auxin-elements' ),
                ),
            )
        );

        $this->add_control(
            'tile_style_pattern',
            array(
                'label' => __( 'Tile styles', 'auxin-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default' => __( 'Default', 'auxin-elements' ),
                    'pattern-1' => __( 'Pattern 1', 'auxin-elements' ),
                    'pattern-2' => __( 'Pattern 2', 'auxin-elements' ),
                    'pattern-3' => __( 'Pattern 3', 'auxin-elements' ),
                    'pattern-4' => __( 'Pattern 4', 'auxin-elements' ),
                    'pattern-5' => __( 'Pattern 5', 'auxin-elements' ),
                    'pattern-6' => __( 'Pattern 6', 'auxin-elements' ),
                    'pattern-7' => __( 'Pattern 7', 'auxin-elements' )
                ),
                'condition' => array(
                    'layout' => 'tiles',
                ),
            )
        );

        $gallery_columns = range( 1, 6 );
        $gallery_columns = array_combine( $gallery_columns, $gallery_columns );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => __( 'Columns', 'auxin-elements' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => $gallery_columns,
                'condition'      => array(
                    'layout' => array('masonry','grid')
                )
            )
        );

        $this->add_control(
            'space',
            array(
                'label'     => __( 'Image spacing', 'auxin-elements' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => array(
                    'size' => 10,
                ),
                'range' => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 40,
                        'step' => 1,
                    )
                ),
                'condition'  => array(
                    'layout' => array('masonry','grid', 'tiles')
                )
            )
        );

        $this->add_responsive_control(
            'image_aspect_ratio',
            array(
                'label'     => __( 'Image aspect ratio', 'auxin-elements' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => array(
                    'size' => 0.75,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.01,
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-layout-masonry .aux-frame-ratio' => 'padding-bottom:calc( {{SIZE}} * 100% )'
                ),
                'condition' => array(
                    'layout' => 'grid'
                )
            )
        );

        $this->add_control(
            'link',
            array(
                'label' => __( 'Link to', 'auxin-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'lightbox',
                'options' => array(
                    'file'       => __( 'Media File', 'auxin-elements' ),
                    'attachment' => __( 'Attachment Page', 'auxin-elements' ),
                    'lightbox'   => __( 'Lightbox', 'auxin-elements' ),
                    'none'       => __( 'None', 'auxin-elements' )
                ),
            )
        );

        $this->add_control(
            'pagination',
            array(
                'label' => __( 'Pagination', 'auxin-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'On', 'auxin-elements' ),
                'label_on' => __( 'Off', 'auxin-elements' ),
                'default' => 'no'
            )
        );

        $this->add_control(
            'lazyload',
            array(
                'label' => __( 'Enable lazyload', 'auxin-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'On', 'auxin-elements' ),
                'label_on' => __( 'Off', 'auxin-elements' ),
                'default' => 'no',
                'condition' => array(
                    'pagination' => 'yes'
                )
            )
        );

        $this->add_control(
            'perpage',
            array(
                'label' => __( 'Images per page', 'auxin-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    )
                ),
                'default' => array(
                    'size' => 24,
                ),
                'condition' => array(
                    'pagination' => 'yes'
                )
            )
        );

        $this->add_control(
            'wp_order',
            array(
                'label' => __( 'Order by query', 'auxin-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'On', 'auxin-elements' ),
                'label_on' => __( 'Off', 'auxin-elements' ),
                'default' => 'no'
            )
        );

        $this->add_control(
            'order',
            array(
                'label' => __( 'Order', 'auxin-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => array(
                    'ASC' => __( 'ASC', 'auxin-elements' ),
                    'DESC' => __( 'DESC', 'auxin-elements' )
                ),
                'condition' => array(
                    'wp_order' => 'yes',
                )
            )
        );

        $this->add_control(
            'orderby',
            array(
                'label' => __( 'Order images by', 'auxin-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'menu_order ID',
                'options' => array(
                    'menu_order ID' => __( 'Menu Order', 'auxin-elements' ),
                    'date' => __( 'date', 'auxin-elements' ),
                    'ID' => __( 'ID', 'auxin-elements' ),
                    'none' => __( 'None', 'auxin-elements' )
                ),
                'condition' => array(
                    'wp_order' => 'yes',
                )
            )
        );

        $this->end_controls_section();

        /*$this->start_controls_section(
            'section_caption',
            array(
                'label' => __( 'Caption', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'caption_display',
            array(
                'label'   => __( 'Display', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    'yes' => __( 'Show', 'auxin-elements' ),
                    'no'  => __( 'Hide', 'auxin-elements' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .gallery-item .gallery-caption' => 'display: {{VALUE}};',
                )
            )
        );

        $this->add_control(
            'caption_align',
            array(
                'label' => __( 'Alignment', 'auxin-elements' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
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
                    'justify' => array(
                        'title' => __( 'Justified', 'auxin-elements' ),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'default' => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .gallery-item .gallery-caption' => 'text-align: {{VALUE}};',
                ),
                'condition' => array(
                    'caption_display' => 'yes'
                ),
            )
        );

        $this->add_control(
            'caption_color',
            array(
                'label'     => __( 'Text Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .gallery-item .gallery-caption' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'caption_display' => 'yes'
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'typography',
                'scheme'    => Typography::TYPOGRAPHY_4,
                'selector'  => '{{WRAPPER}} .gallery-item .gallery-caption',
                'condition' => array(
                    'caption_display' => 'yes'
                ),
            )
        );

        $this->end_controls_section();*/

        $this->start_controls_section(
            'section_image_hover',
            array(
                'label' => __( 'Image Hover', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'image_hover_overlay_color',
            array(
                'label'  => __( 'Image hover overlay color', 'auxin-elements' ),
                'type'   => Controls_Manager::COLOR,
                'scheme' => array(
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ),
                'default'   => 'rgba(0, 0, 0, 0.7)',
                'selectors' => array(
                    '{{WRAPPER}} .aux-gallery-container .aux-frame-darken::after' => 'background-color: {{VALUE}}'
                )
            )
        );

        $this->add_control(
            'image_hover_transition_duration',
            array(
                'label'     => __( 'Image hover transition duration (milliseconds)', 'auxin-elements' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => array(
                    'size' => 1000,
                ),
                'range' => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 5000,
                        'step' => 10
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-gallery-container .aux-frame-darken::after, {{WRAPPER}} .aux-gallery-container .aux-frame-mask-plain' => 'transition-duration:{{SIZE}}ms;'
                )
            )
        );

        $this->add_control(
            'image_hover_zoom',
            array(
                'label'     => __( 'Image hover zoom', 'auxin-elements' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => array(
                    'size' => 120,
                ),
                'range' => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 250,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-gallery-container .aux-hover-active:hover .aux-frame-mask-plain' =>
                    '-webkit-transform: perspective(1000) translateZ(-{{SIZE}}px); transform: perspective(1000) translateZ(-{{SIZE}}px);'
                )
            )
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_image_border',
            array(
                'label' => __( 'Image Border', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'image_border',
                'selector'  => '{{WRAPPER}} .aux-gallery-container .gallery-item .aux-frame-ratio-inner',
                'separator' => 'before'
            )
        );

        $this->add_control(
            'image_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-gallery-container .gallery-item .aux-frame-ratio-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
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
        global $post;
        $settings = $this->get_settings_for_display();

        if ( ! $settings['wp_gallery'] ) {
            return;
        }

        $ids = wp_list_pluck( $settings['wp_gallery'], 'id' );

        // Gallery attributes
        $args = array(
            //'default_image_size' => 'medium',
            'include'            => $ids,
            'order'              => $settings['order'],
            'orderby'            => $settings['orderby'],
            'columns'            => $settings['columns'],
            'tablet_cnum'        => $settings['columns_tablet'],
            'phone_cnum'         => $settings['columns_mobile'],
            'space'              => $settings['space']['size'],
            'image_aspect_ratio' => $settings['image_aspect_ratio']['size'],
            'layout'             => $settings['layout'],
            'tile_style_pattern' => $settings['tile_style_pattern'],
            'link'               => $settings['link'],
            'perpage'            => $settings['perpage']['size'],
            'pagination'         => $settings['pagination'],
            'lazyload'           => $settings['lazyload'],
            'wp_order'           => $settings['wp_order']
            // 'caption_display'    => $settings['caption_display'],
            // 'caption_align'      => $settings['caption_align'],
            // 'caption_color'      => $settings['caption_color']
        );

        // render the markup using element base fallback
        echo auxin_widget_gallery_callback( $args );
    }

}
