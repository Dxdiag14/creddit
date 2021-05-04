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

function auxin_get_facebook_master_array( $master_array ) {

    $master_array['aux_facebook']  = array(
        'name'                    => __('Custom Facebook Feed', 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_widget_facebook_callback',
        'base'                    => 'aux_facebook',
        'description'             => __('It uses Custom Facebook Feed plugin.', 'auxin-elements'),
        'class'                   => 'aux-widget-facebook',
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
        'icon'                    => 'aux-element aux-pb-icons-facebook',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Page ID', 'auxin-elements'),
                'description'       => sprintf(__('ID of your Facebook Page or Group. Use %sthis tool%s to help you in finding this.', 'auxin-elements'), '<a target="_blank" href="https://lookup-id.com/">','</a>'),
                'param_name'        => 'id',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'id',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Page type', 'auxin-elements'),
                'description'      => __('Specifies whether to display posts from a page or group.','auxin-elements'),
                'param_name'       => 'pagetype',
                'type'             => 'dropdown',
                'value'            => array(
                    'page'         => __('Page' , 'auxin-elements'),
                    'group'        => __('Group', 'auxin-elements')
                ),
                'def_value'        => 'page',
                'holder'           => '',
                'class'            => 'pagetype',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'           => __('Number of posts', 'auxin-elements'),
                'description'       => __('The number of posts you wish to display.', 'auxin-elements'),
                'param_name'        => 'num',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'num',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display posts by others', 'auxin-elements'),
                'description'       => __('Specifies to display posts by only the page owner, anyone who posts on your page, or only others who post on your page.', 'auxin-elements'),
                'param_name'        => 'showpostsby',
                'type'              => 'dropdown',
                'value'             => array(
                    'me'            => __('Only the page owner' , 'auxin-elements'),
                    'others'        => __('Page owner and other people', 'auxin-elements'),
                    'onlyothers'    => __('Only other people', 'auxin-elements')
                ),
                'def_value'         => 'me',
                'holder'            => '',
                'class'             => 'showpostsby',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Post text length', 'auxin-elements'),
                'description'       => __('The maximum character length of the post text.', 'auxin-elements'),
                'param_name'        => 'textlength',
                'type'              => 'textfield',
                'value'             => '',
                'class'             => 'textlength',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display post author', 'auxin-elements'),
                'description'       => __('Enable it to display name and avatar of the post author.', 'auxin-elements'),
                'param_name'        => 'showauthor',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => 'showauthor',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Display the feed header', 'auxin-elements'),
                'description'       => __('Enable it to display a customizable header at the top of the feed.', 'auxin-elements'),
                'param_name'        => 'showheader',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => 'showheader',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Ajax', 'auxin-elements'),
                'description'       => __('Enable it for being loaded via Ajax.', 'auxin-elements'),
                'param_name'        => 'ajax',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => 'ajax',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements' ),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements' ),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'textfield',
                'class'             => 'extra_classes',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Extras', 'auxin-elements' ),
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_facebook_master_array', 10, 1 );

function auxin_widget_facebook_callback( $atts, $shortcode_content = null ){

    $default_atts = array(
        'id'            => '',
        'pagetype'      => 'page',
        'num'           => '',
        'showpostsby'   => 'me',
        'textlength'    => '',
        'showauthor'    => '1',
        'showheader'    => '0',
        'ajax'          => '1',
        'extra_classes' => '',
        'custom_el_id'  => '',
        'base_class'    => 'aux-widget-facebook'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    $facebook_shortcode = "";

    ob_start();

    $pagetype = 'pagetype="' . $pagetype . '"';
    $showpostsby = 'showpostsby="' . $showpostsby . '"';
    if( ! empty( $id ) ) {
        $id = 'id="' . $id . '"';
    }
    if( ! empty( $num ) ) {
        $num = 'num="' . $num . '"';
    }
    if( ! empty( $textlength ) ) {
        $textlength = 'textlength="' . $textlength . '"';
    }
    if( ! empty( $extra_classes ) ) {
        $extra_classes = 'class="' . $extra_classes . '"';
    }

    if( empty( $showauthor ) ){
        $showauthor = "false";
    } else {
        $showauthor = "ture";
    }
    $showauthor = 'showauthor="' . $showauthor .'"';

    if( empty( $showheader ) ){
        $showheader = "false";
    } else {
        $showheader = "ture";
    }
    $showheader = 'showheader="' . $showheader .'"';

    if( empty( $ajax ) ){
        $ajax = "false";
    } else {
        $ajax = "ture";
    }
    $ajax = 'ajax="' . $ajax .'"';

    $facebook_shortcode = '[custom-facebook-feed ' . $id . ' ' . $num . ' ' . $textlength . ' ' . $pagetype . ' ' . $showpostsby . ' ' . $showauthor . ' ' . $ajax . ' ' . $extra_classes . ' ' .  $showheader .']';

    echo do_shortcode( $facebook_shortcode );

    return ob_get_clean();

}

