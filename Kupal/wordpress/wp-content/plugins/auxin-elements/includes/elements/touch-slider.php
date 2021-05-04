<?php
/**
 * Touch slider element
 * You should have 'Master Slider' plugin installed to use this element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_touch_slider_master_array( $master_array ) {

    $master_array['aux_touch_slider'] = array(
        'name'                    => __('Simple Image Slider ', 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_touch_slider_callback',
        'base'                    => 'aux_touch_slider',
        'description'             => __('Responsive image slider with touch swipe feature.', 'auxin-elements' ),
        'class'                   => 'aux-widget-touch-slider',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => false,
        'is_shortcode'            => true,
        'is_so'                   => true,
        'is_vc'                   => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-image',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Title','auxin-elements' ),
                'description'       => __('The slider title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Images','auxin-elements'),
                'description'      => '',
                'param_name'       => 'images',
                'type'             => 'attach_images',
                'value'            => '',
                'holder'           => '',
                'class'            => 'images',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '',
                'edit_field_class' => ''
            ),
            array(
                'heading'           => __('Slider image width','auxin-elements' ),
                'param_name'        => 'width',
                'type'              => 'textfield',
                'value'             => '960',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Slider image height','auxin-elements' ),
                'param_name'        => 'height',
                'type'              => 'textfield',
                'value'             => '560',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Arrow navigation','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'arrows',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Style', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Space between slides','auxin-elements' ),
                'param_name'        => 'space',
                'type'              => 'textfield',
                'value'             => '5',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Style', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Looped navigation','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'loop',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Style', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Slideshow','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'slideshow',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Style', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Slideshow delay in seconds','auxin-elements' ),
                'param_name'        => 'slideshow_delay',
                'type'              => 'textfield',
                'value'             => '2',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'slideshow',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Style', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Display image title','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'add_title',
                'type'             => 'aux_switch',
                'value'            => '0',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Style', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Display image caption','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'add_caption',
                'type'             => 'aux_switch',
                'value'            => '0',
                'class'            => '',
                'dependency'       => '',
                'admin_label'      => false,
                'weight'           => '',
                'group'            => __( 'Style', 'auxin-elements' ),
                'edit_field_class' => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_touch_slider_master_array', 10, 1 );

/**
 * Dynamic element with result in columns
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode
 *                                   containing widget info too
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_touch_slider_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'                 => '',
        'images'                => '',
        'width'                 => '960',
        'height'                => '560',
        'loop'                  => '0',
        'space'                 => '5',
        'slideshow'             => '0',
        'slideshow_delay'       => '2',
        'arrows'                => '1',
        'add_title'             => '0',
        'add_caption'           => '0',
        'skin'                  => 'aux-light-skin',

        'extra_classes'         => '', // custom css class names for this element
        'custom_el_id'          => '', // custom id attribute for this element
        'base_class'            => 'aux-widget-touch-slider aux-widget-post-slider'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    $attachments = auxin_get_the_resized_images_by_attach_ids( $images, $width , $height, true );
    if( empty( $attachments ) ){
        return '';
    }
    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    echo '<div class="master-carousel-slider aux-latest-posts-slider aux-no-js '.$skin.'" data-empty-height="'.$height.'" data-navigation="peritem" data-space="'.$space.'" data-auto-height="true" data-delay="'.$slideshow_delay.'" data-loop="'.$loop.'" data-autoplay="' . auxin_is_true( $slideshow ) . '">';

    // widget custom output -----------------------

    foreach ( $attachments as $attachment_id => $attachment ) {

        $slide_image = '';
?>
        <div class="aux-mc-item" >
                <div class="aux-slide-media">
                    <div class="aux-media-frame aux-media-image"><?php echo $attachment; ?></div>
                </div>
            <?php if( $add_title || $add_caption ) { ?>
                <section class="aux-info-container">
                <?php if( $add_title ) { ?>
                    <div class="aux-slide-title">
                        <h3><?php echo auxin_get_trimmed_string( get_the_title( $attachment_id ), 70, '...'); ?></h3>
                    </div>
                <?php } if ( $add_caption ) { ?>
                    <div class="aux-slide-info">
                        <?php auxin_the_attachment_caption( $attachment_id ); ?>
                    </div>
                <?php } ?>
                </section>
            <?php } ?>
        </div>

<?php
    }
    if ( $arrows ) {
?>
        <div class="aux-next-arrow aux-arrow-nav aux-white aux-round aux-hover-slide">
            <span class="aux-overlay"></span>
            <span class="aux-svg-arrow aux-medium-right"></span>
            <span class="aux-hover-arrow aux-svg-arrow aux-medium-right"></span>
        </div>
        <div class="aux-prev-arrow aux-arrow-nav aux-white aux-round aux-hover-slide">
            <span class="aux-overlay"></span>
            <span class="aux-svg-arrow aux-medium-left"></span>
            <span class="aux-hover-arrow aux-svg-arrow aux-medium-left"></span>
        </div>
<?php
    }

    // widget footer ------------------------------
    echo '</div><!-- aux-col-wrapper -->';

    echo $result['widget_footer'];

    return ob_get_clean();
}
