<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Audio' widget.
 *
 * Elementor widget that displays an 'Audio' with lightbox.
 *
 * @since 1.0.0
 */
class Audio extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Audio' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_audio';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Audio' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Audio Player', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Audio' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-play auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Audio' widget icon.
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
     * Register 'Audio' widget controls.
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
                'label'      => __('Audio', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'src',
            array(
                'label'        => __('Audio file(MP3 or ogg)','auxin-elements' ),
                'type'         => 'aux-media',
                'media_filter' => 'audio',
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => __('AutoPlay','auxin-elements' ),
                'description'  => __('Play the audio file automatically.','auxin-elements' ),
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
                'label'        => __('Repeat the audio','auxin-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-elements' ),
                'label_off'    => __( 'Off', 'auxin-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  skin_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'skin_section',
            array(
                'label'      => __('Skin', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'skin',
            array(
                'label'       => __('Skin','auxin-elements' ),
                'description' => __('The skin of audio element.','auxin-elements' ),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'default'   => array(
                        'label' => __('Theme Default', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/default2.svg'
                    ),
                    'dark'      => array(
                        'label' => __('Dark', 'auxin-elements'),
                        'image' => AUXIN_URL . 'images/visual-select/audio-player-dark.svg'
                    ),
                    'light'     => array(
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


    $args       = array(
        'src'      => $settings['src']['id'],
        'loop'     => $settings['loop'],
        'autoplay' => $settings['autoplay'],

        'skin'     => $settings['skin'],
    );

    // get the shortcode base blog page
    echo auxin_widget_audio_callback( $args );

  }

}
