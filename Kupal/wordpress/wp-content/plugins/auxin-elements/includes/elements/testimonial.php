<?php
/**
 * Testimonial Widget
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function  auxin_get_testimonial_master_array( $master_array )  {

     $master_array['aux_testimonial'] = array(
        'name'                    => __('Testimonial ', 'auxin-elements'),
        'auxin_output_callback'   => 'auxin_widget_testimonial_callback',
        'base'                    => 'aux_testimonial',
        'description'             => __('Testimonial Element', 'auxin-elements'),
        'class'                   => 'aux-widget-testimonial',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'is_so'                   => false,
        'is_vc'                   => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'so_api'                  => false,
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-testimonial',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'          => __( 'Testimonial Templates','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'template',
                'type'             => 'aux_visual_select',
                'def_value'        => 'default',
                'holder'           => '',
                'class'            => 'template',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => '',
                'choices'          => array(
                    'default'    => array(
                        'label'    => __( 'Default Template', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/testimonial-1.svg'
                    ),
                    'def-img'  => array(
                        'label'    => __( 'Default Template With Image', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/testimonial-2.svg'
                    ),
                    'bordered'  => array(
                        'label'    => __( 'Bordered On Content', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/testimonial-3.svg'
                    ),
                    'quote'  => array(
                        'label'    => __( 'Quotation Mark on Top of the Content', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/testimonial-4.svg'
                    ),
                    'info-top'  => array(
                        'label'    => __( 'Show Info on Top of Content', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/testimonial-5.svg'
                    ),
                    'image-top'  => array(
                        'label'    => __( 'Show Image on Top of the Content', 'auxin-elements' ),
                        'image'    => AUXELS_ADMIN_URL . '/assets/images/visual-select/testimonial-6.svg'
                    )
                )
            ),
            array(
                'heading'          => __('Customer Name','auxin-elements'),
                'description'      => __('Customer Name, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'       => 'title',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'title',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Customer Link','auxin-elements'),
                'description'      => __('Customer Link, leave it empty if you don`t need it', 'auxin-elements'),
                'param_name'       => 'link',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'title',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Customer Occupation','auxin-elements'),
                'description'      => __('Customer Occupation, leave it empty if you don`t need it.', 'auxin-elements'),
                'param_name'       => 'subtitle',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => 'textfield',
                'class'            => 'subtitle',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Customer Rating','auxin-elements'),
                'description'      => __('Customer Rating, Set it to "None" if you don`t need it.', 'auxin-elements'),
                'param_name'       => 'rating',
                'type'             => 'dropdown',
                'def_value'        => '',
                'value'            => array(
                    'none'             => __( 'None'  , 'auxin-elements' ),
                    '1'            => '1',
                    '2'            => '2',
                    '3'            => '3',
                    '4'            => '4',
                    '5'            => '5',
                ),
                'holder'           => 'dropdown',
                'class'            => 'rating',
                'admin_label'      => true,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'           => __('Customer Image', 'auxin-elements'),
                'description'       => '',
                'param_name'        => 'customer_img',
                'type'              => 'attach_image',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'customer-img',
                'admin_label'       => true,
                'dependency'        => array(
                            'element' => 'template',
                            'value' => array('bordered', 'def-img', 'info-top', 'image-top')
                ),
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
           array(
                'heading'           => __('Content','auxin-elements'),
                'description'       => __('Enter a text as a text content.','auxin-elements'),
                'param_name'        => 'content',
                'type'              => 'textarea_html',
                'value'             => '',
                'def_value'         => '',
                'holder'            => 'div',
                'class'             => 'content',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_testimonial_master_array', 10, 1 );


/**
 * Testimonial Widget Markup
 *
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_testimonial_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(

        'template'      => 'default',
        'title'         => '',
        'subtitle'      => '',
        'rating'        => '',
        'link'          => '',
        'customer_img'  => '',
        'show_image'    => true,
        'image_html'    => '',
        'image_size'    => 'thumbnail',
        'content'       => '',
        'extra_classes' => '', // custom css class names for this element
        'custom_el_id'  => '', // custom id attribute for this element
        'base_class'    => 'aux-widget-testimonial-container'  // base class name for container

    );

    $result = auxin_get_widget_scafold( $atts, $default_atts );

    extract( $result['parsed_atts'] );

    // Validate boolean variables
    $show_image =  auxin_is_true( $show_image );

    $image_above_content = $template === 'image-top';

    $image      = empty( $image_html ) ? wp_get_attachment_image( $customer_img, $image_size, "", array( "class" => "img-square" ) ) : $image_html;
    $content    = empty( $content    ) ? $shortcode_content : $content ;
    $main_class = ' aux-widget-testimonial aux-testimonial-' . $template ;

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
?>
    <div class=" <?php echo esc_attr( $main_class );?> ">
        <?php if( ! empty( $content ) && ! $image_above_content ) { ?>
        <div class="aux-testimonial-content">
            <div class="entry-content">
                <?php $encoding_flag =  defined('ENT_HTML401') ? ENT_HTML401 : ENT_QUOTES; ?>
                <?php echo do_shortcode( html_entity_decode( $content, $encoding_flag, 'UTF-8') ); ?>
            </div>
        </div>
        <?php } ?>
        <div class="aux-testimonial-infobox">
            <?php if ( !empty( $image ) && $show_image ) { ?>
            <div class="aux-testimonial-image">
                    <?php echo $image ;?>
            </div>
            <?php } ?>
            <div class="aux-testimonial-info">
                <?php if( ! empty( $title ) && empty( $link ) ) { ?>
                <h4 class="col-title"><?php echo $title; ?></h4>
                <?php } elseif( ! empty( $title ) && ! empty( $link ) ) {?>
                <h4 class="col-title"><a href="<?php echo esc_url( $link ); ?>">
                <?php echo $title; ?></a>
                </h4>
                <?php } if( ! empty( $subtitle ) ) { ?>
                <h5 class="col-subtitle"><?php echo $subtitle; ?></h5>
                <?php } if ( 'none' !== $rating ) { ?>
                <div class="aux-rating-box aux-star-rating">
                    <span class="aux-star-rating-avg" style="width: <?php echo ( $rating / 5 ) * 100 ;?>%"> </span>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php if( ! empty( $content ) && $image_above_content ) { ?>
        <div class="aux-testimonial-content">
            <div class="entry-content">
                <?php $encoding_flag =  defined('ENT_HTML401') ? ENT_HTML401 : ENT_QUOTES; ?>
                <?php echo do_shortcode( html_entity_decode( $content, $encoding_flag, 'UTF-8') ); ?>
            </div>
        </div>
        <?php } ?>
    </div>

<?php

    // widget footer ------------------------------
    echo $result['widget_footer'];
    return ob_get_clean();

}
