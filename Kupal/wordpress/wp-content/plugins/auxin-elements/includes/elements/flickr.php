<?php
/**
 * Divider element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function auxin_get_flickr_master_array( $master_array ) {

    $master_array['aux_flickr']  = array(
        'name'                    => __('Flickr Justified Gallery', 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_widget_flickr_callback',
        'base'                    => 'aux_flickr',
        'description'             => __('It uses Flickr Justified Gallery plugin.', 'auxin-elements'),
        'class'                   => 'aux-widget-flickr',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'is_so'                   => true,
        'is_vc'                   => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-flickr',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('User ID', 'auxin-elements'),
                'description'       => sprintf(__('Displays the photostream of the specified user, no matter what is the default user ID in the settings. Remember that you can use %sidgettr%s to retrieve the user_id.', 'auxin-elements'), '<a target="_blank" href="http://idgettr.com/">','</a>'),
                'param_name'        => 'user_id',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'user_id',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Flickr image type', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'flickr_type',
                'type'              => 'dropdown',
                'value'             => array(
                    'photostream'      => __('Photostream' , 'auxin-elements'),
                    'galleries'        => __('Galleries', 'auxin-elements'),
                    'album'            => __('Album', 'auxin-elements'), // flickr set
                    'group'            => __('Group pools', 'auxin-elements'),
                    'tags'             => __('Tags', 'auxin-elements'),
                ),
                'def_value'         => 'none',
                'holder'            => '',
                'class'             => 'flickr_type',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Gallery ID', 'auxin-elements'),
                'description'       => __('To show the photos of a particular gallery, you need to know its id. For example, the id of the gallery located in the URL: http://www.flickr.com/photos/miro-mannino/galleries/72157636382842016/ is: 72157636382842016', 'auxin-elements'),
                'param_name'        => 'gallery_id',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'gallery_id',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'flickr_type',
                    'value'         => 'galleries'
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Album ID', 'auxin-elements'),
                'description'       => __('To show the photos of a particular photo set (also called "album"), you need to know its photoset_id. For example, the photoset_id of the photo set located in the URL: http://www.flickr.com/photos/miro-mannino/sets/72157629228993613/ is: 72157629228993613', 'auxin-elements'),
                'param_name'        => 'photoset_id',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'photoset_id',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'flickr_type',
                    'value'         => 'album'
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Group pools', 'auxin-elements'),
                'description'       => sprintf(__('Display photos of a particular group pool, you need to know the group id, that you can retrieve using %sidgettr%s.', 'auxin-elements'), '<a target="_blank" href="http://idgettr.com/">','</a>'),
                'param_name'        => 'group_id',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'group_id',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'flickr_type',
                    'value'         => 'group'
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Tags', 'auxin-elements'),
                'description'       => __('Display photos that have some particular tags, Seprate words with , for example: cat, square, nikon', 'auxin-elements'),
                'param_name'        => 'tags',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'tags',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'flickr_type',
                    'value'         => 'tags'
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Lightbox','auxin-elements'),
                'description'       => __('In case of using Colorbox or Swipebox, you need to enable it on Flickr Justified Gallery plugin settings.','auxin-elements'),
                'param_name'        => 'lightbox',
                'type'              => 'dropdown',
                'value'             => array(
                    'none'            => __('None' , 'auxin-elements'),
                    'colorbox'        => __('Colorbox', 'auxin-elements'),
                    'swipebox'        => __('Swipebox', 'auxin-elements'),
                ),
                'def_value'         => 'none',
                'holder'            => '',
                'class'             => 'lightbox',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Images height', 'auxin-elements'),
                'description'       => __('You can use the this option to set images height in px.', 'auxin-elements'),
                'param_name'        => 'images_height',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'images_height',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Max number of photos','auxin-elements'),
                'description'       => __('Maximum number of photos. Please note if pagination option is activaited then this options is used as maximum number of photos per page.', 'auxin-elements'),
                'param_name'        => 'max_num_photos',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'max_num_photos',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Pagination','auxin-elements'),
                'description'       => '',
                'param_name'        => 'pagination',
                'type'              => 'dropdown',
                'value'             => array(
                    'none'            => __('None' , 'auxin-elements'),
                    'prevnext'        => __('Previous and Next', 'auxin-elements'),
                    'numbers'         => __('Page Numbers', 'auxin-elements'),
                ),
                'def_value'         => 'none',
                'holder'            => '',
                'class'             => 'pagination',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Fixed height', 'auxin-elements'),
                'description'       => __('Each row has the same height, but the images will be cut more.', 'auxin-elements'),
                'param_name'        => 'fixed_height',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => 'fixed_height',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Randomize images order', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'randomize',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => 'randomize',
                'admin_label'       => 0,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Margin between the images','auxin-elements'),
                'description'       => __('Specifies the space between images.', 'auxin-elements'),
                'param_name'        => 'margins',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'margins',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_flickr_master_array', 10, 1 );

function auxin_widget_flickr_callback( $atts, $shortcode_content = null ){

    $default_atts = array(
        'user_id'          => '',
        'flickr_type'      => 'photostream',
        'gallery_id'       => '',
        'photoset_id'      => '',
        'group_id'         => '',
        'tags'             => '',
        'max_num_photos'   => '',
        'images_height'    => '',
        'lightbox'         => 'none',
        'pagination'       => 'none',
        'margins'          => '',
        'fixed_height'     => false,
        'randomize'        => false,
        'custom_el_id'     => '',
        'base_class'       => 'aux-widget-flickr'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    $shortcode_name = "";
    $justify_shortcode = "";

    ob_start();


    switch ($flickr_type) {
        case 'photostream':
            $shortcode_name = "flickr_photostream";
        break;
        case 'galleries':
            $shortcode_name = 'flickr_gallery id="' . $gallery_id . '"';
        break;
        case 'album':
            $shortcode_name = 'flickr_set id="' . $photoset_id . '"';
        break;
        case 'group':
            $shortcode_name = 'flickr_group id="' . $group_id . '"';
        break;
        case 'tags':
            $shortcode_name = 'flickr_tags tags="' . $tags . '"';
        break;
    }

    if( ! empty( $user_id ) ) {
        $user_id = 'user_id="' . $user_id . '"';
    }

    if( ! empty( $pagination ) ) {
        $pagination = 'pagination="' . $pagination . '"';
    }

    if( ! empty( $max_num_photos ) ) {
        $max_num_photos = 'max_num_photos="' . $max_num_photos . '"';
    }

     if( ! empty( $images_height ) ) {
        $images_height = 'images_height="' . $images_height . '"';
    }


    if( ! empty( $lightbox ) ) {
        $lightbox = 'lightbox="' . $lightbox . '"';
    }

    if( ! empty( $margins ) ) {
        $margins = 'margins="' . $margins . '"';
    }

    if( empty( $fixed_height ) ){
        $fixed_height = 0;
    } else {
        $fixed_height = 1;
    }
    $fixed_height = 'fixed_height="' . $fixed_height .'"';

     if( empty( $randomize ) ){
        $randomize = 0;
    } else {
        $randomize = 1;
    }
    $randomize = 'randomize="' . $randomize .'"';

    $justify_shortcode = '[' . $shortcode_name . ' ' . $user_id . ' ' . $images_height . ' ' . $margins . ' ' . $fixed_height . ' ' . $max_num_photos . ' ' . $randomize . ' ' . $lightbox . ' ' . $pagination . ']';

    echo do_shortcode( $justify_shortcode );

    return ob_get_clean();

}

