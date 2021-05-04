<?php
/**
 * Add gallery Option meta box for gallery post format
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

//  @TODO: attachment type is not working and it should check later.
function auxin_metabox_fields_post_gallery(){

    $model            = new Auxin_Metabox_Model();
    $model->id        = 'post-gallery';
    $model->title     = __('Gallery Post options', 'auxin-elements');
    $model->css_class = 'aux-format-tab';
    $model->fields    = array(

        array(
            'title'         => __('The Gallery Images', 'auxin-elements'),
            'description'   => '',
            'id'            => '_format_gallery_type',
            'type'          => 'images',
            'default'       => ''
        )

    );

    return $model;
}
