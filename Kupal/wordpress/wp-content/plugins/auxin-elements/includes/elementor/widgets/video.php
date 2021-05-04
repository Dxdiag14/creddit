<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Video' widget.
 *
 * Elementor widget that displays an 'Video' with lightbox.
 *
 * @since 1.0.0
 */
class Video extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Video' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_video';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Video' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Video Player', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Video' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-youtube auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Video' widget icon.
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
     * Register 'Video' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  audio_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'audio_section',
            array(
                'label'      => __('Video', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'type',
            array(
                'label'       => __('Video Type', 'auxin-elements'),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 'media',
                'options'     => array(
                    'media' => __( 'Media Library', 'auxin-elements' ),
                    'link'  => __( 'Youtube/Vimeo Link', 'auxin-elements' )
                )
            )
        );

        $this->add_control(
            'media',
            array(
                'label'        => __('Video file','auxin-elements'),
                'type'         => 'aux-media',
                'media_filter' => 'video',
                'condition'    => array(
                    'type' => array( 'media' ),
                )
            )
        );

        $this->add_control(
            'provider_link',
            array(
                'label'         => __('Youtube/Vimeo Link','auxin-elements'),
                'description'   => __('Youtube, Vimeo or any video embed link.','auxin-elements'),
                'type'          => Controls_Manager::URL,
                'show_external' => false,
                'placeholder'   => 'https://vimeo.com/119777338',
                'condition'     => [
                    'type' => 'link'
                ],
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => __('AutoPlay','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'loop',
            array(
                'label'        => __('Repeat the video','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'poster',
            array(
                'label'       => __('Video poster','auxin-elements'),
                'description' => __('An image that represents the video content.','auxin-elements'),
                'type'        => Controls_Manager::MEDIA
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  skin_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'style_section',
            array(
                'label'      => __('Style', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'width',
            array(
                'label'       => __('Width','auxin-elements'),
                'description' => __('Width size of video in pixel.','auxin-elements'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('px'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                )
            )
        );


        $this->add_control(
            'height',
            array(
                'label'       => __('Height','auxin-elements'),
                'description' => __('Height size of video in pixel.','auxin-elements'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('px'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                )
            )
        );

        $this->add_control(
            'skin',
            array(
                'label'       => __('Player skin','auxin-elements'),
                'description' => __('Specifies skin for the player.','auxin-elements'),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'dark' => array(
                        'label' => __('Dark', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/audio-player-dark.svg'
                    ),
                    'light' => array(
                        'label' => __('Light', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/audio-player-light.svg'
                    )
                ),
                'default'     => 'dark'
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

        $settings   = $this->get_settings_for_display();
        $src = '';

        if( 'media' == $settings['type'] ){
            $src = $settings['media']['id'];
        } elseif( 'link' == $settings['type'] ){
            if ( ! $src = $settings['provider_link']['url'] ){
                $src = 'https://vimeo.com/119777338';
            }
        }

        $args       = array(
            'src'      => $src,
            'url'      => '',
            'autoplay' => $settings['autoplay'],
            'loop'     => $settings['loop'],
            'poster'   => $settings['poster']['id'],

            'width'    => $settings['width']['size'],
            'height'   => $settings['height']['size'],
            'skin'     => $settings['skin']
        );

        // get the shortcode base blog page
        echo auxin_widget_video_callback( $args );
    }

}
