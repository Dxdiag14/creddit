<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'BeforeAfter' widget.
 *
 * Elementor widget that displays an 'BeforeAfter' with lightbox.
 *
 * @since 1.0.0
 */
class BeforeAfter extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'BeforeAfter' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux-before-after';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'BeforeAfter' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Before After Slider', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'BeforeAfter' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-image-before-after auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'BeforeAfter' widget icon.
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
     * Register 'BeforeAfter' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  images_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'images_section',
            array(
                'label' => __( 'Images', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'before_image',
            array(
                'label'      => __('Before image','auxin-elements' ),
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'       => 'before', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'exclude'    => array( 'custom' ),
                'separator'  => 'none',
                'default'    => 'large',
            )
        );

        $this->add_control(
            'after_image',
            array(
                'label'      => __('After image','auxin-elements' ),
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'       => 'after', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'exclude'    => array( 'custom' ),
                'separator'  => 'none',
                'default'    => 'large',
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'style_section',
            array(
                'label' => __( 'Style', 'auxin-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'default_offset',
            array(
                'label'       => __( 'Start offset','auxin-elements' ),
                'description' => __( 'How much of the before image is visible when the page loads.', 'auxin-elements' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('px'),
                'default'     => array(
                    'size' => 50
                ),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    )
                )
            )
        );

        $this->add_control(
            'width',
            array(
                'label'      => __('Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1400,
                        'step' => 1,
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-before-after' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'height',
            array(
                'label'      => __('Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1400,
                        'step' => 1,
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-before-after' => 'max-height: {{SIZE}}{{UNIT}};',
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

    $settings     = $this->get_settings_for_display();
    $before_image = '';
    $after_image  = '';

    if( ! empty( $settings['before_image'] ) ) {
        $before_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'before', 'before_image' );
    }

    if( ! empty( $settings['after_image'] ) ) {
        $after_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'after', 'after_image' );
    }

    $this->add_render_attribute(
        'wrapper',
        [
            'class'       => [ 'aux-before-after' ],
            'data-offset' => ( (int) $settings['default_offset']['size'] ) / 100
        ]
    );

    if ( Plugin::instance()->editor->is_edit_mode() ) {
        $this->add_render_attribute( 'wrapper', [
            'class' => 'elementor-clickable',
        ] );
    }

    if( ! empty( $settings['after_image'] ) ) {
        echo sprintf( '<div class="widget-container aux-widget-before-after"><div %s >%s %s</div></div>',
                        $this->get_render_attribute_string( 'wrapper' ),
                        $before_image,
                        $after_image
                    ) ;
    } else {
        echo sprintf( '<div class="widget-container aux-widget-before-after"><div %s >%s</div></div>',
                        $this->get_render_attribute_string( 'wrapper' ),
                        $before_image
                    );
    }

  }

  /**
   * Render image box widget output in the editor.
   *
   * Written as a Backbone JavaScript template and used to generate the live preview.
   *
   * @since 1.0.0
   * @access protected
   */
  protected function _content_template() {
    ?>
    <#

    var images = '';

    if ( settings.before_image ) {
        var before_image = {
            id: settings.before_image.id,
            url: settings.before_image.url,
            size: settings.before_size,
            dimension: settings.before_custom_dimension,
            model: view.getEditModel()
        };
        var before_image_url = elementor.imagesManager.getImageUrl( before_image );
        images += '<img src="' + before_image_url + '" />';
    }

    if ( settings.after_image ) {
        var after_image = {
            id: settings.after_image.id,
            url: settings.after_image.url,
            size: settings.after_size,
            dimension: settings.after_custom_dimension,
            model: view.getEditModel()
        };
        var after_image_url = elementor.imagesManager.getImageUrl( after_image );
        images += '<img src="' + after_image_url + '" />';
    }

    view.addRenderAttribute(
        'wrapper',
        {
            'class'      : [ 'aux-before-after' ],
            'data-offset': Number( settings.default_offset.size )/100
        }
    );

    #>
    <div class="widget-container aux-widget-before-after">
        <div {{{  view.getRenderAttributeString( 'wrapper' ) }}}>
            {{{ images }}}
        <div>
    </div>
    <?php
  }

}
