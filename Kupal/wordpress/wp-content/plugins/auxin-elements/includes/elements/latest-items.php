<?php
/**
 * This is a sample of how to create a new element on widgets and visual composer element
 * Auxin callout widget you can set all the options of this widget here like widget definitation,
 * its element on siteorigin page builder, visual composer and under appearance/ widgets
 */
function  get_auxin_latest_items_vc( $master_array ) {

    $auxin_latest_items_vc = array( // shortcode info here
                'name'                          => __("Auxin latest_items", 'auxin-elements' ),                            // [str] name of your shortcode for human reading inside element list
                'auxin_output_callback'         => 'auxin_widget_latest_items',                                     // [str] NAme of widget function callback which define below this array
                'base'                          => 'auxin_latest_items',                                         // [str] shortcode tag. For [my_shortcode] shortcode base is my_shortcode
                'description'                   => __('This is will add latest_items element', 'auxin-elements'),          // [str] short description of your element, it will be visible in "Add element" window
                'class'                         => 'auxin-latest_items',                                             // [str] CSS class which will be added to the shortcode's content element in the page edit screen in Visual Composer backend edit mode. adds 3 classes like testt_o testt_v and testt
                'show_settings_on_create'       => true,                                                            // [Boolean] set it to false if content element's settings page shouldn't open automatically after adding it to the stage
                'weight'                        => 1,                                                               // [Int] content elements with greater weight will be rendered first in "Content Elements" grid, higher appear upper
                'category'                      => THEME_NAME,                                                      // [str] category which best suites to describe functionality of this shortcode.
                'group'                         => '',                                                              // [str] TODO: no idea Group your params in groups, they will be divided in tabs in the edit element window
                'admin_enqueue_js'              => '',                                                              // [str/arr] this js will be loaded in the js_composer edit mode
                'admin_enqueue_css'             => '',                                                              // [str/arr] absolute url to css file
                'front_enqueue_js'              => '',                                                              // [str/arr] to load custom js file in the frontend editing mode
                'front_enqueue_css'             => '',                                                              // [str/arr] to load custom css file in the frontend editing mode
                'icon'                          => '',                                                              //  URL or CSS class with icon image
                'custom_markup'                 => '',                                                              // [str] custom html markup for representing shortcode in visual composer editor. This will replace visual composer element where shows the param and its value
                'js_view'                       => '',                                                              //  TODO: no idea Set custom backbone.js view controller for this content element there is a sample wich sets it to
                'html_template'                 => '',                                                              // it uses to oerride the output of shortcode. Path to shortcode template. This is useful if you want to reassign path of existing content elements lets say override the seprator defined by visual composer.
                'deprecated'                    => '',                                                               //  enter version number of visual composer from which content element will be deprecated
                'content_element'               => '',                                                               // If set to false, content element will be hidden from "Add element" window. It is handy to use this param in pair with 'deprecated' param
                'as_parent' => '',                                                                                   // use only|except attributes to limit child shortcodes (separate multiple values with comma)
                'as_child' => '',                                                                                   // use only|except attributes to limit parent (separate multiple values with comma)
                'params' => array(
                    array(                                                              // array of parameter
                        'param_name'    => 'col',                                    // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => '33',                                // [str/arr] default attribute's value
                        'def_value'         => '33',                                // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'col',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('col','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('If you choose Callout a big box appears around the content','auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                                                           // array of parameter
                        'param_name'        => 'mode',                                                                  // [str] must be the same as your parameter name
                        'type'              => 'dropdown',                                                              // [str] attribute type
                        'def_value'         => 'no',                                                                       // [str/arr] default attribute's value
                        'value'             => array( 'none' => __('none', 'auxin-elements'), 'caption-over' => __('caption-over', 'auxin-elements') ),
                        'holder'            => '',                                                              // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'             => 'mode',                                                              // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'           => __('mode type','auxin-elements'),                                                    // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'       => __('The round is used got button','auxin-elements'),
                        'admin_label'       => true,                                                                     // [bool] show value of param in Visual Composer editor
                        'dependency'        => '',                                                                       // [arr] define param visibility depending on other field value
                        'weight'            => '',                                                                           // [int] params with greater weight will be rendered first
                        'group'             => '' ,                                                                              // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                                                    // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                              // array of parameter
                        'param_name'    => 'grid',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => '',                              // [str/arr] default attribute's value
                        'def_value'         => '',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'grid',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('grid','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('attachment grid', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                              // array of parameter
                        'param_name'    => 'num',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => '',                              // [str/arr] default attribute's value
                        'def_value'         => '',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'num',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('num','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('attachment num', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                                                           // array of parameter
                        'param_name'        => 'nav',                                                                  // [str] must be the same as your parameter name
                        'type'              => 'dropdown',                                                              // [str] attribute type
                        'def_value'         => 'pagination',                                                                       // [str/arr] default attribute's value
                        'value'             => array( 'pagination' => __('pagination', 'auxin-elements'), 'regular' => __('regular', 'auxin-elements') ),
                        'holder'            => '',                                                              // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'             => 'nav',                                                              // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'           => __('nav type','auxin-elements'),                                                    // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'       => __('The round is used got button','auxin-elements'),
                        'admin_label'       => true,                                                                     // [bool] show value of param in Visual Composer editor
                        'dependency'        => '',                                                                       // [arr] define param visibility depending on other field value
                        'weight'            => '',                                                                           // [int] params with greater weight will be rendered first
                        'group'             => '' ,                                                                              // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                                                    // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                     array(                                                              // array of parameter
                        'param_name'    => 'title',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => '',                              // [str/arr] default attribute's value
                        'def_value'         => '',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'title',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('title','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('attachment link', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                                                           // array of parameter
                        'param_name'        => 'posttype',                                                                  // [str] must be the same as your parameter name
                        'type'              => 'dropdown',                                                              // [str] attribute type
                        'def_value'         => 'pagination',                                                                       // [str/arr] default attribute's value
                        'value'             => array( 'post' => __('post', 'auxin-elements'), 'regular' => __('regular', 'auxin-elements') ),
                        'holder'            => '',                                                              // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'             => 'posttype',                                                              // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'           => __('posttype type','auxin-elements'),                                                    // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'       => __('The round is used got button','auxin-elements'),
                        'admin_label'       => true,                                                                     // [bool] show value of param in Visual Composer editor
                        'dependency'        => '',                                                                       // [arr] define param visibility depending on other field value
                        'weight'            => '',                                                                           // [int] params with greater weight will be rendered first
                        'group'             => '' ,                                                                              // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                                                    // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                              // array of parameter
                        'param_name'    => 'cat_id',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => '',                              // [str/arr] default attribute's value
                        'def_value'         => '',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'cat_id',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('cat_id','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('attachment alt', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                              // array of parameter
                        'param_name'    => 'taxonomy',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => 'category',                              // [str/arr] default attribute's value
                        'def_value'         => 'category',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'taxonomy',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('width','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('attachment width', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                                                           // array of parameter
                        'param_name'        => 'view_excerpt',                                                                  // [str] must be the same as your parameter name
                        'type'              => 'dropdown',                                                              // [str] attribute type
                        'def_value'         => 'no',                                                                       // [str/arr] default attribute's value
                        'value'             => array( 'yes' => __('Yes', 'auxin-elements'), 'no' => __('No', 'auxin-elements') ),
                        'holder'            => '',                                                              // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'             => 'view_excerpt',                                                              // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'           => __('view_excerpt ?','auxin-elements'),                                                    // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'       => __('The round is used got button','auxin-elements'),
                        'admin_label'       => true,                                                                     // [bool] show value of param in Visual Composer editor
                        'dependency'        => '',                                                                       // [arr] define param visibility depending on other field value
                        'weight'            => '',                                                                           // [int] params with greater weight will be rendered first
                        'group'             => '' ,                                                                              // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                                                    // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                                                           // array of parameter
                        'param_name'        => 'view_title',                                                                  // [str] must be the same as your parameter name
                        'type'              => 'dropdown',                                                              // [str] attribute type
                        'def_value'         => 'no',                                                                       // [str/arr] default attribute's value
                        'value'             => array( 'yes' => __('Yes', 'auxin-elements'), 'no' => __('No', 'auxin-elements') ),
                        'holder'            => '',                                                              // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'             => 'view_title',                                                              // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'           => __('view_title ?','auxin-elements'),                                                    // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'       => __('The round is used got button','auxin-elements'),
                        'admin_label'       => true,                                                                     // [bool] show value of param in Visual Composer editor
                        'dependency'        => '',                                                                       // [arr] define param visibility depending on other field value
                        'weight'            => '',                                                                           // [int] params with greater weight will be rendered first
                        'group'             => '' ,                                                                              // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                                                    // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                              // array of parameter
                        'param_name'    => 'excerpt_len',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => '100',                              // [str/arr] default attribute's value
                        'def_value'         => '100',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'excerpt_len',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('excerpt_len','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('attachment width', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                              // array of parameter
                        'param_name'    => 'section_index',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => 'category',                              // [str/arr] default attribute's value
                        'def_value'         => 'category',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'section_index',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('section_index','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('attachment section_index', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                              // array of parameter
                        'param_name'    => 'paged',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => 'category',                              // [str/arr] default attribute's value
                        'def_value'         => 'category',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'paged',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('paged','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('attachment paged', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    ),
                    array(                                                              // array of parameter
                        'param_name'    => 'extra_classes',                                     // [str] must be the same as your parameter name
                        'type'          => 'textfield',                                 // [str] attribute type
                        'value'         => '',                              // [str/arr] default attribute's value
                        'def_value'         => '',                              // [str/arr] default attribute's value
                        'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                        'class'         => 'extra_classes',                                         // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface
                        'heading'       => __('Extra class name','auxin-elements'),                                     // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                        'description'   => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements'),
                        'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                        'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                        'weight'    => '',                                              // [int] params with greater weight will be rendered first
                        'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                        'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
                    )

                        // ,                                                                                         // another array for another param
                )
            );

    // push this node in master_array
    $master_array[] = $auxin_latest_items_vc;

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'get_auxin_latest_items_vc', 10, 1 );


// This is the widget call back in fact the front end out put of this widget comes from this function
function auxin_widget_latest_items( $atts, $shortcode_content = null ){


// @TODO
    // Defining default attributes
    $default_atts = array(
        'title'             => '', // header title
        'size'      =>  100, // section size
        'col'       => '33', // one .. six-column
        'mode'      => 'none', // caption-over, none
        'grid'      =>  '', // custome grid
        'num'       =>  -1,  // fetch num
        'nav'       => 'pagination', // pagination , filterable
        'title'     => '', // widget header title
        'posttype'  => 'post', // posttype
        'cat_id'    => '',
        'taxonomy'  => 'category',
        'view_excerpt' => 'yes',
        'view_title'   => 'yes',
        'excerpt_len'  => '100',
        'section_index'=> '',
        'paged'     => '1',
        'col'               => 3,
        'extra_classes'     => '', // custom css class names for this element
        'custom_el_id'      => '', // custom id attribute for this element
    );

    // Widget Info -----------------------------

    // Widget general info
    $before_widget = $after_widget  = '';
    $before_title  = $after_title   = '';

    // If widget info is passed, extract them in above variables
    if( isset( $atts['widget_info'] ) ){
        extract( $atts['widget_info'] );
    }
    $widget_title = isset( $atts['widget_title'] ) ? $atts['widget_title'] : '';

    // CSS class names for section -------------

    // The default CSS classes for widget container
    // Note that 'widget-container' should be in all element
    $default_atts['_css_classes'] = array( 'widget-container' );

    // Parse shortcode attributes
    $parsed_atts = shortcode_atts( $default_atts, $atts, __FUNCTION__ );

    // Extract array nodes in variables
    extract( $parsed_atts );

    // Defining extra class names --------------

    // Add extra class names to class list here - widget-{element_name}
    $_css_classes[] = 'widget-pages'; // @TODO define widget-{element_name}

    // Covering classes in list to class attribute for main section
    $section_class_attr = auxin_make_html_class_attribute( $_css_classes, $extra_classes );

    $content = wpb_js_remove_wpautop($shortcode_content, true); // fix unclosed/unwanted paragraph tags in $content
    // TODO: axi should change to aux
    global $axi_img_size;

    // Defining query base on needs ------------

    // create an id for this section
    $uid = "axi_pbei".$section_index;
    if(empty($section_index)) $uid = uniqid("axi_pbei");

    // validate number fetched items
    $num = ((int)$num > 0)?$num:-1;
    // set column number to 3 if its empty
    $col = empty($col)?"1/3":$col;

    // get number of grid column ---------------------------------
    // actual col size
    // get number of grid column
    $wrapper_size = empty($size)?100:$size;
    $col_actual = ($wrapper_size / 100) * (int)$col;
    $col_num = floor(100 / $col_actual);
    $col_num = $col_num > 5?5:$col_num; // max column num is 5
    // get thumbnsil size name
    $image_size_name = "i".$col_num;



    // get all taxonomy items for filtering purpose ---------------
    $tax_args = array('taxonomy' => $taxonomy, 'terms' => $cat_id );

    if(empty($cat_id) || $cat_id == "all" ) $tax_args = "";

     // get all taxonomy items for filtering purpose ---------------
    $tax_args = array('taxonomy' => $taxonomy, 'terms' => $cat_id );

    if(empty($cat_id) || $cat_id == "all" ) $tax_args = "";

    // Create wp_query to get pages
    $query_args = array(
        'post_type'  => $posttype,
          'orderby'    => "menu_order date",
          'post_status'=> 'publish',
          'posts_per_page' => $num,
          'paged'      => $paged,
          'tax_query'  => array($tax_args)
    );

    $query_res = null;
    $query_res = new WP_Query( $query_args );

    ob_start();
?>
 <?php echo $before_widget; ?>

        <section id="<?php echo $custom_el_id; ?>" <?php echo $section_class_attr; ?>>

            <?php
            if( ! empty( $before_title ) ){
                echo $before_title . $widget_title . $after_title;
            } elseif( ! empty( $title ) ){
                echo get_widget_title( $title );
            }
            ?>

           <div class="widget-inner">

                <div class="aux-col-wrapper <?php echo "aux-$col"; ?>"> // faghat vase seton

<?php if( $query_res->have_posts() ): while ( $query_res->have_posts() ) : $query_res->the_post(); ?>

                    <article  class="aux-col">
                    <!-- @TODO - The output for each element here -->
                      <?php // reset current item image size name
                      $thumb_size = $image_size_name;

                      // is current item highlighted?
                      $is_highlight = get_post_meta($th_query->post->ID, 'is_highlighted', true);

                      // this is the css class name that indicates the thumbnail size in browser
                      $classSize    = "";
                      if($is_highlight == "yes" && $mode == "caption-over"){
                           $classSize = "height2";
                           $thumb_size .= "_2"; // if the item is marked as highlited, make it 2x bigger in height
                      }else{
                           $classSize = "height1";
                           $thumb_size .= "_1";
                      }

                      // get suite thumb size
                      $dimentions = $axi_img_size[$thumb_size];

                      // retinafy thumbnail
                      $dimentions[0] =  1.8 * $dimentions[0];
                      $dimentions[1] =  1.8 * $dimentions[1];
                   ?>

                   <?php // get the current item tag for filtering content
                      $tax_name = 'portfolio-tag';
                      $tax_terms = wp_get_post_terms($th_query->post->ID, $tax_name);
                      // stores all current item tag slugs az class attrs
                      $tax_slugs = "";

                      if($tax_terms){
                          foreach($tax_terms as $term)
                              $tax_slugs .= " ".$term->slug;
                      }
                   ?>

                   <article class="col" data-filter="<?php echo $tax_slugs; ?>" >
                       <figure>
                            <div class="imgHolder <?php echo $classSize; ?>">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                        auxin_the_post_thumbnail($th_query->post->ID, $dimentions[0], $dimentions[1], true, 70);
                                    ?>
                                    <?php if ($mode == "caption-over"): ?>

                                        <em>
                                            <?php if($view_title == 'yes'){ ?>
                                            <h4><?php the_title(); ?></h4>
                                            <?php } ?>

                                            <?php
                                                  $cat_terms = wp_get_post_terms(get_the_ID(), 'project-type');
                                                  if(!empty($cat_terms)){
                                                      echo '<i>';
                                                      $cnt = 0;
                                                      foreach($cat_terms as $term){
                                                          echo $cnt == 0?'':' / ';
                                                          echo $term->name;
                                                          $cnt++;
                                                      }
                                                      echo '</i>';
                                                  }
                                            ?>
                                        </em>

                                    <?php endif; ?>
                                </a>
                            </div>
                            <?php if ($mode != "caption-over") { ?>

                            <figcaption>
                                <h4 class="fig-title">
                                    <?php if($view_title == 'yes'){ ?>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <?php } ?>
                                </h4>
                                <?php if($view_excerpt == "yes") auxin_the_trimmed_string(get_the_excerpt(),$excerpt_len); ?>
                            </figcaption>

                            <?php } ?>
                       </figure>
                   </article>
                    </article>
<?php
    endwhile; endif;
    wp_reset_query();
?>

                </div><!-- aux-col-wrapper -->
            </div><!-- widget-inner -->
        </section><!-- widget-container -->

        <?php echo $after_widget; ?>

<?php
    return ob_get_clean();
}

