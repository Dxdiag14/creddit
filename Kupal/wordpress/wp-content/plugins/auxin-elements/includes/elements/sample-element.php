<?php
/**
 * Sample Element element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
/**
 * http://docs.averta.net/display/ADD/Add+New+Element
 */
function auxin_get_sample_master_array( $master_array ) {

    $master_array['aux_sample'] = array( // the key should be same as 'base' param

        'name'                    => __('Auxin Callout ',  'auxin-elements'),                             // [str] name of your shortcode for human reading inside element list
        'auxin_output_callback'   => 'auxin_widget_sample_callback',                              // [str] Name of widget function callback which will be defined after this array
        'base'                    => 'aux_sample',                                              // [str] shortcode tag. For [my_shortcode] shortcode base is my_shortcode
        'description'             => __('The element description',  'auxin-elements'),                    // [str] short description of your element, it will be visible in "Add element" window
        'class'                   => 'aux-widget-sample',                                       // [str] CSS class which will be added to the shortcode's content element in the page edit screen in Visual Composer backend edit mode.
        'show_settings_on_create' => true,                                                        // [Boolean] set it to false if content element's settings page shouldn't open automatically after adding it to the stage
        'weight'                  => 1,                                                           // [Int] content elements with greater weight will be rendered first in "Content Elements" grid, higher appear upper
        'is_widget'               => true,                                                        // whether to add widget not not
        'is_shortcode'            => true,                                                        // whether to add shortcode not not
        'is_so'                   => true,                                                        // whether to add the element to siteorigin page builder or not
        'is_vc'                   => true,                                                        // whether to add the element to visual composer or not
        'category'                => THEME_NAME,                                                  // [str] category which best suites to describe functionality of this shortcode.
        'group'                   => '',                                                          // [str] TODO: no idea Group your params in groups, they will be divided in tabs in the edit element window
        'admin_enqueue_js'        => '',                                                          // [str/arr] this js will be loaded in the js_composer edit mode
        'admin_enqueue_css'       => '',                                                          // [str/arr] absolute url to css file
        'front_enqueue_js'        => '',                                                          // [str/arr] to load custom js file in the frontend editing mode
        'front_enqueue_css'       => '',                                                          // [str/arr] to load custom css file in the frontend editing mode
        'icon'                    => '',                                                          // URL or CSS class with icon image
        'custom_markup'           => '',                                                          // [str] custom html markup for representing shortcode in visual composer editor. This will replace visual composer element where shows the param and its value
        'js_view'                 => '',                                                          // TODO: no idea Set custom backbone.js view controller for this content element there is a sample which sets it to
        'html_template'           => '',                                                          // it uses to override the output of shortcode. Path to shortcode template. This is useful if you want to reassign path of existing content elements lets say override the seprator defined by visual composer.
        'deprecated'              => '',                                                          // Enter version number of visual composer from which content element will be deprecated
        'content_element'         => '',                                                          // If set to false, content element will be hidden from "Add element" window. It is handy to use this param in pair with 'deprecated' param
        'as_parent'               => '',                                                                              // use only|except attributes to limit child shortcodes (separate multiple values with comma)
        'as_child'                => '',                                                                               // use only|except attributes to limit parent (separate multiple values with comma)
        'params'                  => array(                                                                              // array of all parameter
            array(                                                              // array of a parameter
                'param_name'    => 'title',                                     // [str] must be the same as your parameter name
                'type'          => 'textfield',                                 // [str] attribute type
                'value'         => 'Sample Title',                              // [str/arr] default attribute's value
                'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                'class'         => 'title',                                     // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface it also uses for widget param name it is better to be same as param name if you do not know what you are doing
                'heading'       => __('Title', 'auxin-elements'),                       // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                'description'   => __('Description for this element',  'auxin-elements'),
                'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                'weight'    => '',                                              // [int] params with greater weight will be rendered first
                'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
            ),
            array(                                                              // array of parameter
                'param_name'    => 'title2',                                    // [str] must be the same as your parameter name
                'type'          => 'textfield',                                 // [str] attribute type
                'value'         => 'Yes',                                       // [str/arr] default attribute's value
                'holder'        => 'textfield',                                 // [str] HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode
                'class'         => 'title2',                                    // [str] class name that will be added to the "holder" HTML tag. Useful if you want to target some CSS rules to specific items in the backend edit interface it also uses for widget param name it is better to be same as param name if you do not know what you are doing
                'heading'       => __('Title', 'auxin-elements'),                       // [str] human friendly title of your param. Will be visible in shortcode's edit screen
                'description'   => __('If you choose Callout a big box appears around the content', 'auxin-elements'),
                'admin_label'   => true,                                        // [bool] show value of param in Visual Composer editor
                'dependency'    => '',                                          // [arr] define param visibility depending on other field value
                'weight'    => '',                                              // [int] params with greater weight will be rendered first
                'group' => '' ,                                                 // [str] use it to divide your params within groups (tabs)
                'edit_field_class'  => ''                                       // [str] set param container width in content element edit window. According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
            )
        ),
        'shortcode_atts' => array(
            'size'      =>  100, // section size
            'caption'   => '',
            'type'      => 'callout', // callout, stunning
            'bgcolor'   => 'default', // #ffcc00
            'btn_label' => '',
            'btn_link'  => '',
            'title'     => 'Sample Title', // section title
            'target'    => 'self', // button link target
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_sample_master_array', 10, 1 );








/**
 * Sample element markup for front-end
 * In other words, the front-end output of this element is returned by the following function
 *
 * This is a sample element markup for an element.
 * This function will be called via Master array mapper, so we need to tell
 * the mapper about this function
 */


/**
 * Dynamic element with result in columns
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode
 *                                   containing widget info too
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_sample_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'             => '', // header title

        'extra_classes'     => '', // custom css class names for this element
        'custom_el_id'      => '', // custom id attribute for this element
        'base_class'        => 'aux-widget-sample'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    // query --------------------------------------

    // Create wp_query to get pages
    $query_args = array(
        'post_type'             => 'page',
        'orderby'               => "menu_order date",
        'order'                 => "DESC",
        'post_status'           => 'publish',
        'posts_per_page'        => -1,
        'ignore_sticky_posts'   => 1
    );

    $query_res = null;
    $query_res = new WP_Query( $query_args );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    echo '<div class="aux-col-wrapper aux-col3">';

    // widget custom output -----------------------


    if( $query_res->have_posts() ): while ( $query_res->have_posts() ) : $query_res->the_post();
?>

        <article  class="aux-col">
        <!-- @TODO - The output for each element here -->
        </article>

<?php
    endwhile; endif;
    wp_reset_query();


    // widget footer ------------------------------
    echo '</div><!-- aux-col-wrapper -->';
    echo $result['widget_footer'];

    return ob_get_clean();
}





/**
 * Element without loop and column
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode
 *                                   containing widget info too
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_sample_callback2( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'             => '', // header title

        'extra_classes'     => '', // custom css class names for this element
        'custom_el_id'      => '', // custom id attribute for this element
        'base_class'        => 'aux-widget-sample'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    // widget custom output -----------------------



    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
