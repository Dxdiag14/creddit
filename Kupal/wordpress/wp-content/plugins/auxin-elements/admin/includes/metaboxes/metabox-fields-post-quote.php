<?php
/**
 * Add quote Option meta box for quote post format
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

function auxin_metabox_fields_post_quote(){

    $model            = new Auxin_Metabox_Model();
    $model->id        = 'post-quote';
    $model->title     = __('Quote Post options', 'auxin-elements');
    $model->css_class = 'aux-format-tab';
    $model->fields    = array(

        array(
            'title'         => __('Source Name', 'auxin-elements'),
            'description'   => __('The Source name', 'auxin-elements'),
            'id'            => '_format_quote_source_name',
            'id_deprecated' => 'the_author',
            'type'          => 'text',
            'default'       => ''
        ),
        array(
            'title'         => __('Source URL', 'auxin-elements'),
            'description'   => __('Add the URL', 'auxin-elements'),
            'id'            => '_format_quote_source_url',
            'type'          => 'text',
            'default'       => ''
        )

    );

    return $model;
}
