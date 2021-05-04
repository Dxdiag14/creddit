<?php
/**
 * Google Map element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_gmap_master_array( $master_array ) {

    $master_array['aux_gmaps'] = array(
        'name'                    => __('Map ', 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_widget_gmaps_callback',
        'base'                    => 'aux_gmaps',
        'description'             => __('Google map block', 'auxin-elements' ),
        'class'                   => 'aux-widget-gmaps',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-google-maps',
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
                'description'       => __('Map title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Height','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'height',
                'type'              => 'textfield',
                'value'             => '700',
                'holder'            => '',
                'class'             => 'height',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Latitude','auxin-elements' ),
                'description'       => __('Latitude location over the map.','auxin-elements' ),
                'param_name'        => 'latitude',
                'type'              => 'textfield',
                'value'             => '52',
                'holder'            => '',
                'class'             => 'latitude',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Longitude','auxin-elements' ),
                'description'       => __('Longitude location over the map.','auxin-elements' ),
                'param_name'        => 'longitude',
                'type'              => 'textfield',
                'value'             => '14',
                'holder'            => '',
                'class'             => 'longitude',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Map type','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'type',
                'type'              => 'dropdown',
                'def_value'         => 'ROADMAP',
                'value'             => array( 'ROADMAP' => __('ROADMAP', 'auxin-elements' ), 'SATELLITE' => __('SATELLITE', 'auxin-elements' ) ),
                'holder'            => '',
                'class'             => 'type',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
             array(
                'heading'           => __('Map style','auxin-elements' ),
                'description'       => __('This feild allow you to customize the presentation of the standard Google base maps. You can find many preset styles in ', 'auxin-elements' ) .
                                            '<a href="https://snazzymaps.com/" target="_blank">' . __('this website.', 'auxin-elements' ) . '</a>' ,
                'param_name'        => 'style',
                'type'              => 'textarea_raw_html',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'style',
                'admin_label'       => false,
                'dependency'        => array(
                        'element'   => 'type',
                        'value'     => 'ROADMAP'
                )
            ),
            array(
                'heading'           => __('Marker info','auxin-elements' ),
                'description'       => __('Marker popup text, leave it empty if you don\'t need it.', 'auxin-elements' ),
                'param_name'        => 'marker_info',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'marker_info',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Marker Icon','auxin-elements' ),
                'description'       => __('Pick a small icon for gmaps marker.', 'auxin-elements' ),
                'param_name'        => 'attach_id',
                'type'              => 'attach_image',
                'value'             => '',
                'def_value'         => '',
                'holder'            => '',
                'class'             => 'attach_id',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),            
            array(
                'heading'           => __('Navigation control','auxin-elements' ),
                'description'       => __('Show navigation control on map.','auxin-elements' ),
                'param_name'        => 'show_mapcontrols',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'show_mapcontrols',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Zoom','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'zoom',
                'type'              => 'textfield',
                'value'             => '4',
                'holder'            => '',
                'class'             => 'zoom',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Zoom with mouse wheel','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'zoom_wheel',
                'type'              => 'aux_switch',
                'value'             => '0',
                'holder'            => '',
                'class'             => 'zoom_wheel',
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
                'holder'            => '',
                'class'             => 'extra_classes',
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

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_gmap_master_array', 10, 1 );


/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_gmaps_callback( $atts, $shortcode_content = null ){


    // Defining default attributes
    $default_atts = array(
        'title'            => '', // header title
        'type'             => 'ROADMAP',
        'style'            => '',
        'height'           => 700,
        'latitude'         => 40.7,
        'longitude'        => -74,
        'attach_id'        => '', // popup conetent
        'marker_info'      => '', // popup conetent
        'show_mapcontrols' => 1,
        'zoom'             => 10,
        'zoom_wheel'       => 0,

        'extra_classes'    => '', // custom css class names for this element
        'custom_el_id'     => '', // custom id attribute for this element
        'base_class'       => 'aux-widget-gmaps'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    ob_start();
    
    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    $mapid = uniqid("aux-map");

    if ( class_exists('WeDevs_Dokan') ) {

        if ( dokan_is_store_page() ) {
            $store_info   = dokan_get_store_info( get_query_var( 'author' ) );
            $map_location = isset( $store_info['location'] ) ? esc_attr( $store_info['location'] ): '';
            $latitude     = isset( $locations[0] ) ? $locations[0] : $latitude;
            $longitude    = isset( $locations[1] ) ? $locations[1] : $longitude;
        }

    }


    if ( empty( $style ) ) {
        $style = auxin_get_gmap_style();
    } elseif ( base64_decode( $style, true ) === false) {
    } else {
        $style = rawurldecode( base64_decode( strip_tags( $style ) ) );
    }
    
    $zoom_wheel = auxin_is_true( $zoom_wheel ) ? 'true' : 'false';

?>

    <div class="aux-col-wrapper aux-no-gutter">
        <div id="<?php echo esc_attr( $mapid ); ?>" class="aux-map-wrapper <?php echo esc_attr( $extra_classes ); ?>" style="height:<?php echo esc_attr( $height ); ?>px" ></div>

        <script>
            jQuery( function($) {
                if(typeof GMaps != "function" || typeof google === "undefined"){
                    console.info( "Please add google map API key in theme options. https://developers.google.com/maps/documentation/javascript/" );
                    return;
                }
                var map = new GMaps({
                    el: "#<?php echo esc_attr( $mapid     ); ?>",
                    lat:  <?php echo esc_attr( $latitude  ); ?>,
                    lng:  <?php echo esc_attr( $longitude ); ?>,
                    zoom: <?php echo esc_attr( $zoom      ); ?>,
                    scrollwheel: <?php echo esc_attr( $zoom_wheel ) ;?>,
                    <?php if( $type == "SATELLITE" ){ ?>
                    mapTypeId: google.maps.MapTypeId.SATELLITE,
                    <?php } else { ?>
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    <?php } if ( $show_mapcontrols == false ) { ?>
                    disableDefaultUI: true,
                    <?php } ?>
                    panControl : true
                });

                <?php if( $type == "ROADMAP" ){ ?>
                map.addStyle({
                    styledMapName:"Auxin custom style map",
                    styles: <?php echo $style; ?>,
                    mapTypeId: "aux_map_style"
                });

                map.setStyle("aux_map_style");
                <?php } ?>
                map.addMarker({
                    <?php if ( ! empty( $marker_info ) ) { ?>
                        infoWindow: { content: "<?php echo esc_html( $marker_info ); ?>" },
                    <?php } ?>
                    lat : <?php echo esc_attr( $latitude  ); ?>,
                    lng : <?php echo esc_attr( $longitude ); ?>,
                    <?php if ( ! empty( $attach_id ) ) { ?>
                        icon: "<?php echo esc_attr( auxin_get_attachment_url( $attach_id, 'full' ) ); ?>"
                    <?php } ?>
                    
                });
           });

        </script>

    </div><!-- aux-col-wrapper -->

<?php
    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
