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

function auxin_metabox_fields_post_video(){

    $model            = new Auxin_Metabox_Model();
    $model->id        = 'post-video';
    $model->title     = __('Video Post options', 'auxin-elements');
    $model->css_class = 'aux-format-tab';
    $model->fields    = array(

        array(
            'title'         => __('Video file', 'auxin-elements'),
            'description'   => __('Please upload an MP4/OGG/WEBM file for self hosted video player.', 'auxin-elements'),
            'id'            => '_format_video_attachment',
            'type'          => 'video',
            'default'       => ''
        ),
        array(
            'title'         => __('Poster image', 'auxin-elements'),
            'description'   => __('Please specify an image as a poster for self hosted video player.', 'auxin-elements'),
            'id'            => '_format_video_attachment_poster',
            'type'          => 'image',
            'default'       => ''
        ),
        array(
            'title'         => __('Video URL (oEmbed)', 'auxin-elements'),
            'description'   => __('Youtube, Vimeo, TED, SmugMug, Kickstarter, Hulu, Flickr, DailyMotion, Blip, Animoto, Wistia link or iFrame code.', 'auxin-elements'),
            'id'            => '_format_video_embed',
            'id_deprecated' => 'youtube',
            'type'          => 'textarea',
            'default'       => ''
        ),
        array(
            'title'         => __('Player Skin', 'auxin-elements'),
            'description'   => __('Specifies the skin for audio player.', 'auxin-elements'),
            'id'            => '_format_video_player_skin',
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
