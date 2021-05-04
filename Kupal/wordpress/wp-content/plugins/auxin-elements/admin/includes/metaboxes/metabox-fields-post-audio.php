<?php
/**
 * Add Video Option meta box for video post format
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


/*======================================================================*/

function auxin_metabox_fields_post_audio(){

    $model            = new Auxin_Metabox_Model();
    $model->id        = 'post-audio';
    $model->title     = __('Audio Post options', 'auxin-elements');
    $model->css_class = 'aux-format-tab';
    $model->fields    = array(

        array(
            'title'         => __('Audio file (MP3 or ogg)', 'auxin-elements'),
            'description'   => __('Please upload an MP3 file for self hosted audio player.', 'auxin-elements'),
            'id'            => '_format_audio_attachment',
            'type'          => 'audio',
            'default'       => ''
        ),
        array(
            'title'         => __('Audio URL (oEmbed)', 'auxin-elements'),
            'description'   => __('SoundCloud, MixCloud, ReverbNation, Spotify link or iFrame code.', 'auxin-elements'),
            'id'            => '_format_audio_embed',
            'id_deprecated' => 'soundcloud',
            'type'          => 'textarea',
            'default'       => ''
        ),
        array(
            'title'         => __('Player Skin', 'auxin-elements'),
            'description'   => __('Specifies the skin for audio player.', 'auxin-elements'),
            'id'            => '_format_audio_player_skin',
            'type'          => 'radio-image',
            'default'       => 'default',
            'choices' => array(
                'default' => array(
                    'label'  => __('Default (set in theme options)', 'auxin-elements'),
                    'image' => AUXIN_URL . 'images/visual-select/default2.svg'
                ),
                'light' => array(
                    'label'  => __('Light', 'auxin-elements'),
                    'image' => AUXIN_URL . 'images/visual-select/audio-player-light.svg'
                ),
                'dark' => array(
                    'label'  => __('Dark', 'auxin-elements'),
                    'image' => AUXIN_URL . 'images/visual-select/audio-player-dark.svg'
                )
            )
        )

    );

    return $model;
}
