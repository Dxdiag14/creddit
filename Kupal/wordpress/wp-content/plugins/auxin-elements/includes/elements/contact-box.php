<?php
/**
 * Contact box element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function  get_auxin_contact_box( $master_array ) {

    $master_array['aux_contact_box'] = array(
        'name'                          => __("Contact Box", 'auxin-elements' ),
        'auxin_output_callback'         => 'auxin_widget_contact_box',
        'base'                          => 'aux_contact_box',
        'description'                   => __('It adds a contact box element.', 'auxin-elements'),
        'class'                         => 'aux-widget-contact-box',
        'show_settings_on_create'       => true,
        'weight'                        => 1,
        'category'                      => THEME_NAME,
        'group'                         => '',
        'admin_enqueue_js'              => '',
        'admin_enqueue_css'             => '',
        'front_enqueue_js'              => '',
        'front_enqueue_css'             => '',
        'icon'                          => 'aux-element aux-pb-icons-message-box',
        'custom_markup'                 => '',
        'js_view'                       => '',
        'html_template'                 => '',
        'deprecated'                    => '',
        'content_element'               => '',
        'as_parent'                     => '',
        'as_child'                      => '',
        'params' => array(
            array(
                'heading'           => __('Title','auxin-elements'),
                'description'       => __('Contact box title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'id',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Email','auxin-elements'),
                'description'       => __('Contact box email address.', 'auxin-elements'),
                'param_name'        => 'email',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Telephone','auxin-elements'),
                'description'       => __('Contact box phone number.', 'auxin-elements'),
                'param_name'        => 'telephone',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'telephone',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Address','auxin-elements'),
                'description'       => __('Contact box address.', 'auxin-elements'),
                'param_name'        => 'address',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Show socials','auxin-elements'),
                'description'       => __('Show site socials below the info.','auxin-elements'),
                'param_name'        => 'show_socials',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'show_socials',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Show map','auxin-elements'),
                'description'       => __('Show map above the info.','auxin-elements'),
                'param_name'        => 'show_map',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '1',
                'holder'            => '',
                'class'             => 'show_map',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Map position','auxin-elements'),
                'description'       => __('Whether to show map above the contact details or below them.', 'auxin-elements'),
                'param_name'        => 'map_position',
                'type'              => 'dropdown',
                'value'             => array(
                    'down'          => __('Below the contact details.', 'auxin-elements' ),
                    'up'            => __('Above the contact details.', 'auxin-elements' )
                ),
                'def_value'         => 'down',
                'holder'            => '',
                'class'             => 'map_position',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Map height','auxin-elements'),
                'description'       => __('Height of the map in pixels.', 'auxin-elements'),
                'param_name'        => 'height',
                'type'              => 'textfield',
                'value'             => '160',
                'def_value'         => '160',
                'holder'            => '',
                'class'             => 'height',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Latitude','auxin-elements'),
                'description'       => __('Latitude location over the map.', 'auxin-elements'),
                'param_name'        => 'latitude',
                'type'              => 'textfield',
                'value'             => '40.7',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Longitude','auxin-elements'),
                'description'       => __('Longitude location over the map.', 'auxin-elements'),
                'param_name'        => 'longitude',
                'type'              => 'textfield',
                'value'             => '-74',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Map type','auxin-elements'),
                'param_name'        => 'type',
                'type'              => 'dropdown',
                'def_value'         => 'ROADMAP',
                'value'             => array( 'ROADMAP' => __('ROADMAP', 'auxin-elements'), 'SATELLITE' => __('SATELLITE', 'auxin-elements') ),
                'holder'            => '',
                'class'             => 'type',
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'admin_label'       => false,
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
               array(
                'heading'           => __('Map style','auxin-elements'),
                'description'       => __('This feild allows you to customize the presentation of the standard Google base maps. You can find many preset styles in ', 'auxin-elements') .
                                            '<a href="https://snazzymaps.com/" target="_blank">' . __('this website.', 'auxin-elements') . '</a>' ,
                'param_name'        => 'style',
                'type'              => 'textarea_raw_html',
                'def_value'         => '',
                'value'             => '',
                'holder'            => '',
                'class'             => 'style',
                'admin_label'       => false,
                'dependency'        => array(
                    // array(
                        'element'   => 'show_map',
                        'value'     => '1'
                    // ),
                    // @TODO: this kind of dependency is not working we should fix it later
                    // array(
                    //     'element'  => 'type',
                    //     'value'    => 'ROADMAP'
                    // ),
                    // 'relation'=> 'and'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Marker info','auxin-elements'),
                'description'       => __('Marker popup text, leave it empty if you don\'t need it.', 'auxin-elements'),
                'param_name'        => 'marker_info',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => '',
                'class'             => 'marker_info',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Navigation control','auxin-elements'),
                'description'       => __('Show nacigation control on map.','auxin-elements'),
                'param_name'        => 'show_mapcontrols',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '0',
                'holder'            => '',
                'class'             => 'show_mapcontrols',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Zoom','auxin-elements'),
                'description'       => __('The initial resolution at which to display the map, between 1 to 20.', 'auxin-elements'),
                'param_name'        => 'zoom',
                'type'              => 'textfield',
                'value'             => '10',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Zoom with mouse wheel','auxin-elements'),
                'description'       => '',
                'param_name'        => 'zoom_wheel',
                'type'              => 'aux_switch',
                'def_value'         => '',
                'value'             => '0',
                'holder'            => '',
                'class'             => 'zoom_wheel',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'show_map',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => __( 'Map Options', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements'),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements'),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
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

add_filter( 'auxin_master_array_shortcodes', 'get_auxin_contact_box', 10, 1 );

/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_contact_box( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'             => '', // header title
        'style'             => '',
        'show_map'          => '1',
        'map_position'      => 'down',
        'show_socials'      => '1',
        'email'             => '',
        'telephone'         => '',
        'address'           => '',
        'type'              => 'ROADMAP',
        'zoom'              => '10',
        'zoom_wheel'        => '0',
        'marker_info'       => '',
        'show_mapcontrols'  => '0',
        'latitude'          => 40.7,
        'longitude'         => -74,
        'height'            => 160,

        'extra_classes'     => '', // custom css class names for this element
        'custom_el_id'      => '',
        'base_class'        => 'aux-widget-contact-box'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    // Validate the boolean variables
    $show_socials     = auxin_is_true( $show_socials );
    $zoom_wheel       = auxin_is_true( $zoom_wheel );
    $show_mapcontrols = auxin_is_true( $show_mapcontrols );

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    $contact_info =  '<div class="aux-contact-details"><ul>';
    if( ! empty( $telephone ) ){
        $contact_info .= '<li class="phone"><i class="auxicon-phone-classic-on"></i><span class="info-text">'. esc_html( $telephone ) .'</span></li>';
    }
    if( ! empty( $email ) ){
        $contact_info .= '<li class="email"><i class="auxicon-mail-3"></i><span class="info-text">'. antispambot( $email ) .'</span></li>';
    }
    if( ! empty( $address ) ){
        $contact_info .= '<li class="address"><i class="auxicon-map-pin-streamline"></i><span class="info-text">'. esc_html( $address ) .'</span></li>';
    }
    $contact_info .= '</ul>';
    if( $show_socials ) {
        $contact_info .= auxin_get_the_socials();
    }
    $contact_info .= '</div>';

    // Print the contact info above the map if the position option is set to 'up'
    if( 'down' !== $map_position ){
        echo $contact_info;
    }

    if( auxin_is_true( $show_map ) ) {
        $mapid        = uniqid("aux-map");
        $marker_title = '';

        if ( empty( $style ) ) {
            $style = auxin_get_gmap_style();
        } elseif ( base64_decode( $style, true ) === false) {

        } else {
            $style = rawurldecode( base64_decode( strip_tags( $style ) ) );
        }

?>
        <div id="<?php echo esc_attr( $mapid ); ?>" class="aux-map-wrapper <?php echo esc_attr( $extra_classes ); ?>" style="height:<?php echo esc_attr( $height ); ?>px" ></div>
<?php
    }

    if( 'down' === $map_position ){
        echo $contact_info;
    }

    if( auxin_is_true( $show_map ) ) {
        ?>
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
                    scrollwheel: <?php echo $zoom_wheel ? '1' : '0'; ?>,
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
                    lat: <?php echo esc_attr( $latitude  ); ?>,
                    lng: <?php echo esc_attr( $longitude ); ?>
                });
           });

        </script>
        <?php
    }


    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
