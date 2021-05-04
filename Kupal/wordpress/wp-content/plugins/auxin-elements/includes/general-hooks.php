<?php
/**
 * Before Single Products Summary Div
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */



/**
 * Adds a mian css class indicator to body tag
 *
 * @param  array $classes  List of body css classes
 * @return array           The modified list of body css classes
 */
function auxels_body_class( $classes ) {
  $classes[]      = '_auxels';

  if ( auxin_get_option('page_animation_nav_enable') && class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
    unset( $classes[ array_search( 'aux-page-animation', $classes ) ] );
    unset( $classes[ array_search( 'aux-page-animation-' . esc_attr( auxin_get_option('page_animation_nav_type', 'fade') ), $classes ) ] );
  }

  return $classes;
}
add_filter( 'body_class', 'auxels_body_class', 13 );


/**
 * Add meta custom field types for vc
 *
 * @return void
 */
function auxin_add_vc_field_types(){

    if ( defined( 'WPB_VC_VERSION' ) ) {

        // aux_iconpicker field type definition for VC
        vc_add_shortcode_param( 'aux_iconpicker', 'auxin_aux_iconpicker_settings_field', ADMIN_JS_URL . 'scripts.js' );
        function auxin_aux_iconpicker_settings_field( $settings, $value ) {

            $font_icons = Auxin()->Font_Icons->get_icons_list('fontastic');
            $output = '<div class="aux-element-field aux-iconpicker">';
            $output .= sprintf( '<select name="%1$s" id="%1$s" class="aux-fonticonpicker aux-select wpb_vc_param_value wpb-select  ' .
                    esc_attr( $settings['param_name'] ) . ' ' . $settings['type'] . '_field" >',  esc_attr($settings['param_name'])  );
            $output .= '<option value="">' . __('Choose ..', 'auxin-elements') . '</option>';

            if( is_array( $font_icons ) ) {
                foreach ( $font_icons as $icon ) {
                    $icon_id = trim( $icon->classname, '.' );
                    // $output .= '<option value="'. $icon_id .'" '. selected( $instance[$id], $icon_id, false ) .' >'. $icon->name . '</option>';
                    $output .= '<option value="'. $icon_id .'" '. selected( esc_attr( $value ) , $icon_id, false ) .' >'. $icon->name . '</option>';

                }
            }

            $output .= '</select>';
            $output .= '</div>';

           return   $output; // This is html markup that will be outputted in content elements edit form
        }

        // aux_visual_select field type definition for VC
        vc_add_shortcode_param( 'aux_visual_select', 'auxin_aux_visual_select_settings_field', ADMIN_JS_URL . 'scripts.js' );
        function auxin_aux_visual_select_settings_field( $settings, $value ) {

            $output = '<select name="' . esc_attr($settings['param_name']) .
            '" class="aux-element-field visual-select-wrapper wpb_vc_param_value wpb-select  ' .
            esc_attr( $settings['param_name'] ) . ' ' . $settings['type'] . '_field" '.
            ' id="' . esc_attr($settings['param_name']) .
            '" data-option="' . esc_attr( $value ) .  '" >';
            foreach ( $settings['choices'] as $id => $option_info ) {
                $active_attr = ( $id == esc_attr( $value ) ) ? 'selected' : '';
                $data_class  = isset( $option_info['css_class'] ) && ! empty( $option_info['css_class'] ) ? 'data-class="'. $option_info['css_class'].'"' : '';
                $data_symbol = empty( $data_class ) && isset( $option_info['image'] ) && ! empty( $option_info['image'] ) ? 'data-symbol="'. $option_info['image'].'"' : '';
                $data_video  = ! empty( $option_info['video_src'] ) ? 'data-video-src="'. esc_attr( $option_info['video_src'] ).'"' : '';
                $css_classs  =  'class="'. ($id) .'"';
                $output     .= sprintf( '<option value="%s" %s %s %s %s %s>%s</option>', $id,$css_classs, $active_attr, $data_symbol, $data_video, $data_class, $option_info['label']  );
            }
            $output .= '</select>';

            return   $output; // This is html markup that will be outputted in content elements edit form
        }

        // aux_select_audio field type definition for VC
        vc_add_shortcode_param( 'aux_select_audio', 'aux_select_audio_settings_field', ADMIN_JS_URL . 'scripts.js' );
        function aux_select_audio_settings_field( $settings, $value ) {

            // Store attachment src for avertaAttachMedia field
            if( !empty( $value) ) {
                $att_ids = explode( ',', $value );
                $attach_ids_list = auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
                    if(!empty($att_ids)) {
                        printf( "<script>auxin.attachmedia = jQuery.extend( auxin.attachmedia, %s );</script>", wp_json_encode( array_unique( $attach_ids_list ) ) );
                    }
            }
            $output = '';
            $output .= '<div class="aux-element-field av3_container aux_select_image axi-attachmedia-wrapper">'.
                                '<input type="text" class="wpb-multiselect wpb_vc_param_value ' . esc_sql($settings['param_name']) . ' ' .  $settings['type'] . '_field"  name="' . esc_attr($settings['param_name']) . '" ' . 'id="' . esc_attr($settings['param_name']) . '" '
                                . 'value="' . esc_attr( $value ) . '" data-media-type="audio" data-limit="1" data-multiple="0"'
                                .'data-add-to-list="'.__('Add Audio', 'auxin-elements').'" '
                                .'data-uploader-submit="'.__('Add Audio', 'auxin-elements').'"'
                                .'data-uploader-title="'.__('Select Audio', 'auxin-elements').'"> '
                        .'</div>';

            return   $output; // This is html markup that will be outputted in content elements edit form
        }

         // aux_select_video field type definition for VC
        vc_add_shortcode_param( 'aux_select_video', 'aux_select_video_settings_field', ADMIN_JS_URL . 'scripts.js' );
        function aux_select_video_settings_field( $settings, $value ) {

            // Store attachment src for avertaAttachMedia field
            if( !empty( $value) ) {
                $att_ids = explode( ',', $value );
                $attach_ids_list = auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
                if(!empty($att_ids)) {
                    printf( "<script>auxin.attachmedia = jQuery.extend( auxin.attachmedia, %s );</script>", wp_json_encode( array_unique( $attach_ids_list ) ) );
                }
            }
            $output = '';
            $output .= '<div class="aux-element-field av3_container aux_select_image axi-attachmedia-wrapper">'.
                                '<input type="text" class="wpb-multiselect wpb_vc_param_value ' . esc_sql($settings['param_name']) . ' ' .  $settings['type'] . '_field"  name="' . esc_attr($settings['param_name']) . '" ' . 'id="' . esc_attr($settings['param_name']) . '" '
                                . 'value="' . esc_attr( $value ) . '" data-media-type="video" data-limit="1" data-multiple="0"'
                                .'data-add-to-list="'.__('Add Video', 'auxin-elements').'" '
                                .'data-uploader-submit="'.__('Add Video', 'auxin-elements').'"'
                                .'data-uploader-title="'.__('Select Video', 'auxin-elements').'"> '
                        .'</div>';

           return   $output; // This is html markup that will be outputted in content elements edit form
        }

        // aux_select2_multiple field type definition for VC
        vc_add_shortcode_param( 'aux_select2_multiple', 'aux_multiple_selector_settings_field', ADMIN_JS_URL . 'scripts.js' );
        function aux_multiple_selector_settings_field( $settings, $value ) {

            if( gettype( $value ) === "string" ) {
                $value = explode( ",", $value);
            }
            $select = $value;
            $output = '';
            $output .= '<select multiple="multiple" name="' . esc_sql($settings['param_name']) . '"  style="width:100%" '  . ' class="wpb-multiselect wpb_vc_param_value aux-select2-multiple ' . esc_sql($settings['param_name']) . ' ' .  $settings['type'] . '_field" '. '>';
                    foreach ( $settings['value'] as $id => $option_info ) {
                       $active_attr = in_array( $id, $select) ? 'selected="selected"' : '';
                       $output     .= sprintf( '<option value="%s" %s >%s</option>', $id, $active_attr, $option_info  );
                    }
            $output.= '</select>';

            return   $output; // This is html markup that will be outputted in content elements edit form
        }

        // aux_taxonomy field type definition for VC
        vc_add_shortcode_param( 'aux_taxonomy', 'aux_taxonomy_selector_settings_field', ADMIN_JS_URL . 'scripts.js' );
        function aux_taxonomy_selector_settings_field( $settings, $value ) {

            $categories = get_terms(
                array( 'taxonomy'   => $settings['taxonomy'],
                        'orderby'    => 'count',
                        'hide_empty' => true
                ));

            $categories_list = array( ' ' => __('All Categories', 'auxin-elements' ) );
            foreach ( $categories as $key => $value_id ) {
                $categories_list[$value_id->term_id] = $value_id->name;
            }
            if( gettype( $value ) === "string" ) {
                $value = explode( ",", $value);
            }
            $selected = $value;
            $output = '';
            $output .= '<select multiple="multiple" name="' . $settings['param_name'] . '"  style="width:100%" '  . ' class="wpb-multiselect wpb_vc_param_value aux-select2-multiple ' . esc_sql($settings['param_name']) . ' '  . 'aux-admin-select2 ' . $settings['type'] . '_field" '. '>';
            foreach ( $categories_list as $id => $options_info ) {
                $active_attr = in_array( $id, $selected) ? 'selected="selected"' : '';
                $output     .= sprintf( '<option value="%s" %s >%s</option>', $id, $active_attr, $options_info  );
            }
            $output.= '</select>';

           return   $output; // This is html markup that will be outputted in content elements edit form
        }

        vc_add_shortcode_param( 'aux_switch', 'auxin_aux_switch_settings_field', ADMIN_JS_URL . 'scripts.js' );
        function auxin_aux_switch_settings_field( $settings, $value ) {
            $active_attr =  auxin_is_true( $value ) ? 'checked="checked"' : '';
            return  '<div class="av3_container aux_switch">'.
                         '<input type="checkbox" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb_checkbox checkbox '.
                            esc_attr( $settings['param_name'] ) . ' ' .
                            esc_attr( $settings['type'] ) . '_field' .
                         '" value="' . esc_attr( $value ) . '" ' . $active_attr . ' >' .
                    '</div>'; // This is html markup that will be outputted in content elements edit form
        }



    }

    /**
     * Enqueue all my widget's admin scripts
     */
    function auxin_widgets_enqueue_scripts(){
        wp_enqueue_script('auxin_widget');

    }
    add_action( 'admin_print_scripts-widgets.php', 'auxin_widgets_enqueue_scripts' );

    // Add this to enqueue your scripts on Page Builder too
    add_action('siteorigin_panel_enqueue_admin_scripts', 'auxin_widgets_enqueue_scripts');

    /**
     * This part is for adding Auxin font icon to Visual composer icon
     * this is just temporary and need to move and write in a better manner when it is compelete
     * TODO: just for now to see it is working
     */

        // Add Auxin icons to Visual Composer icons
        $settings = array(
          'name'     => __( 'Auxin Icons', 'auxin-elements' ),
          'category' => THEME_NAME,
          'params'   => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Icon library', 'auxin-elements' ),
                    'value' => array(
                        __( 'Font Awesome', 'auxin-elements' ) => 'fontawesome',
                        __( 'Open Iconic', 'auxin-elements' )  => 'openiconic',
                        __( 'Typicons', 'auxin-elements' )     => 'typicons',
                        __( 'Entypo', 'auxin-elements' )       => 'entypo',
                        __( 'Linecons', 'auxin-elements' )     => 'linecons',
                        __( 'Auxin', 'auxin-elements' )        => 'auxin'
                    ),
                    'admin_label' => true,
                    'param_name'  => 'type',
                    'description' => __( 'Select icon library.', 'auxin-elements' )
                ),
                array(
                    'type'       => 'iconpicker',
                    'heading'    => __( 'Icon', 'auxin-elements' ),
                    'param_name' => 'icon_auxin',
                    'value'      => 'auxin-icon-basket-1', // default value to backend editor admin_label
                    'settings'   => array(
                        'emptyIcon'    => false,
                        // default true, display an "EMPTY" icon?
                        'type'         => 'auxin',
                        'iconsPerPage' => 4000
                        // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value'   => 'auxin'
                    ),
                    'description' => __( 'Select icon from library.', 'auxin-elements' )
                ),
                array(
                    'type'       => 'iconpicker',
                    'heading'    => __( 'Icon', 'auxin-elements' ),
                    'param_name' => 'icon_fontawesome',
                    'value'      => 'fa fa-adjust', // default value to backend editor admin_label
                    'settings'   => array(
                        'emptyIcon' => false,
                        // default true, display an "EMPTY" icon?
                        'iconsPerPage' => 4000
                        // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value'   => 'fontawesome'
                    ),
                    'description' => __( 'Select icon from library.', 'auxin-elements' )
                ),
                array(
                    'type'       => 'iconpicker',
                    'heading'    => __( 'Icon', 'auxin-elements' ),
                    'param_name' => 'icon_openiconic',
                    'value'      => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
                    'settings'   => array(
                        'emptyIcon'    => false, // default true, display an "EMPTY" icon?
                        'type'         => 'openiconic',
                        'iconsPerPage' => 4000   // default 100, how many icons per/page to display
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value'   => 'openiconic'
                    ),
                    'description' => __( 'Select icon from library.', 'auxin-elements' )
                ),
                array(
                    'type'       => 'iconpicker',
                    'heading'    => __( 'Icon', 'auxin-elements' ),
                    'param_name' => 'icon_typicons',
                    'value'      => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
                    'settings'   => array(
                        'emptyIcon'    => false, // default true, display an "EMPTY" icon?
                        'type'         => 'typicons',
                        'iconsPerPage' => 4000 // default 100, how many icons per/page to display
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value'   => 'typicons'
                    ),
                    'description' => __( 'Select icon from library.', 'auxin-elements' )
                ),
                array(
                    'type'       => 'iconpicker',
                    'heading'    => __( 'Icon', 'auxin-elements' ),
                    'param_name' => 'icon_entypo',
                    'value'      => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
                    'settings'   => array(
                        'emptyIcon'    => false, // default true, display an "EMPTY" icon?
                        'type'         => 'entypo',
                        'iconsPerPage' => 4000 // default 100, how many icons per/page to display
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value'   => 'entypo'
                    ),
                ),
                array(
                    'type'       => 'iconpicker',
                    'heading'    => __( 'Icon', 'auxin-elements' ),
                    'param_name' => 'icon_linecons',
                    'value'      => 'vc_li vc_li-heart', // default value to backend editor admin_label
                    'settings'   => array(
                        'emptyIcon'    => false, // default true, display an "EMPTY" icon?
                        'type'         => 'linecons',
                        'iconsPerPage' => 4000   // default 100, how many icons per/page to display
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value'   => 'linecons'
                    ),
                    'description' => __( 'Select icon from library.', 'auxin-elements' ),
                ),

                array(
                    'type'        => 'colorpicker',
                    'heading'     => __( 'Custom color', 'auxin-elements' ),
                    'param_name'  => 'custom_color',
                    'description' => __( 'Select custom icon color.', 'auxin-elements' ),
                    'dependency'  => array(
                        'element' => 'color',
                        'value'   => 'custom'
                    ),
                ),
                array(
                    'type'       => 'dropdown',
                    'heading'    => __( 'Background shape', 'auxin-elements' ),
                    'param_name' => 'background_style',
                    'value'      => array(
                        __( 'None', 'auxin-elements' )            => '',
                        __( 'Circle', 'auxin-elements' )          => 'rounded',
                        __( 'Square', 'auxin-elements' )          => 'boxed',
                        __( 'Rounded', 'auxin-elements' )         => 'rounded-less',
                        __( 'Outline Circle', 'auxin-elements' )  => 'rounded-outline',
                        __( 'Outline Square', 'auxin-elements' )  => 'boxed-outline',
                        __( 'Outline Rounded', 'auxin-elements' ) => 'rounded-less-outline'
                    ),
                    'description' => __( 'Select background shape and style for icon.', 'auxin-elements' )
                ),

                array(
                    'type'        => 'colorpicker',
                    'heading'     => __( 'Custom background color', 'auxin-elements' ),
                    'param_name'  => 'custom_background_color',
                    'description' => __( 'Select custom icon background color.', 'auxin-elements' ),
                    'dependency'  => array(
                        'element' => 'background_color',
                        'value'   => 'custom'
                    ),
                ),

                array(
                    'type'       => 'dropdown',
                    'heading'    => __( 'Icon alignment', 'auxin-elements' ),
                    'param_name' => 'align',
                    'value'      => array(
                        __( 'Left', 'auxin-elements' )   => 'left',
                        __( 'Right', 'auxin-elements' )  => 'right',
                        __( 'Center', 'auxin-elements' ) => 'center'
                    ),
                    'description' => __( 'Select icon alignment.', 'auxin-elements' ),
                ),

            ),

        );

        if ( defined( 'WPB_VC_VERSION' ) ) {

        // vc_map_update('vc_icon', $settings );

        //  TODO: This is a sample we need to create an array for all the icons and also enque its css file
        // add_filter( 'vc_iconpicker-type-auxin', 'vc_iconpicker_type_auxin' );
        function vc_iconpicker_type_auxin( $icons ) {
            $auxin_icons = array(
                "Test" => array(
                    array( 'auxin-icon auxin-icon-2-arrows' => __( 'Arrow', 'auxin-elements' ) ),
                    array( 'auxin-icon auxin-icon-basket-1' => __( 'Arrow', 'auxin-elements' ) ),
                    array( 'auxin-icon auxin-icon-back-pack' => __( 'Back', 'auxin-elements' ) )
                )
            );

            return array_merge( $icons, $auxin_icons );
        }

        // add_action( 'vc_backend_editor_enqueue_js_css', 'auxin_vc_iconpicker_editor_jscss' );
        // @see Vc_Frontend_Editor::enqueueAdmin (wp-content/plugins/js_composer/include/classes/editors/class-vc-frontend-editor.php),
        // used to enqueue needed js/css files when frontend editor is rendering
        // add_action( 'vc_frontend_editor_enqueue_js_css', 'auxin_vc_iconpicker_editor_jscss' );
        function auxin_vc_iconpicker_editor_jscss () {
            wp_enqueue_style( 'auxin_font' );
        }

        // @see Vc_Base::frontCss, used to append actions when frontCss(frontend editor/and real view mode) method called
        // This action registers all styles(fonts) to be enqueue later
        add_action( 'vc_base_register_front_css', 'auxin_vc_iconpicker_base_register_css' );

        // @see Vc_Base::registerAdminCss, used to append action when registerAdminCss(backend editor) method called
        // This action registers all styles(fonts) to be enqueue later
        add_action( 'vc_base_register_admin_css', 'auxin_vc_iconpicker_base_register_css' );
        function auxin_vc_iconpicker_base_register_css () {
            wp_register_style( 'auxin_font', vc_asset_url( 'css/lib/auxin-font/auxin-font.css' ), false, WPB_VC_VERSION, 'screen' );
        }


    }

}
add_action( 'auxin_admin_loaded', 'auxin_add_vc_field_types' );


/**
 * load custom shortcodes, templates and element in visual composer start
 *
 * @return void
 */
function auxin_on_vc_plugin_loaded(){
    global $vc_manager;
    if( ! is_null( $vc_manager ) ) {
        $auxin_shortcodes_template_dir = AUXELS_PUB_DIR . '/templates/vcomposer';
        $vc_manager->setCustomUserShortcodesTemplateDir( $auxin_shortcodes_template_dir );
    }
}
add_action( 'plugins_loaded', 'auxin_on_vc_plugin_loaded' );



function auxin_add_theme_options_in_plugin( $fields_sections_list ){

    // Sub section - Custom JS ------------------------------------

    $fields_sections_list['sections'][] = array(
        'id'          => 'general-section-custom-js',
        'parent'      => 'general-section', // section parent's id
        'title'       => __( 'Custom JS Code', 'auxin-elements'),
        'description' => __( 'Your Custom Javascript', 'auxin-elements')
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Custom Javascript in Head', 'auxin-elements'),
        'description'   => sprintf( __('You can add your custom javascript code here.%s DO NOT use %s tag.', 'auxin-elements'), '<br />' , '<code>&lt;script&gt;</code>' )."<br />".
                           __('In order to save your custom javascript code, you are expected to execute the code prior to saving.', 'auxin-elements'),
        'id'            => 'auxin_user_custom_js_head',
        'section'       => 'general-section-custom-js',
        'dependency'    => array(),
        'default'       => '',
        'transport'     => 'postMessage',
        'button_labels' => array( 'label' => __('Execute', 'auxin-elements') ),
        'mode'          => 'javascript',
        'type'          => 'code'
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Custom Javascript in Footer', 'auxin-elements'),
        'description'   => sprintf( __('You can add your custom javascript code here.%s DO NOT use %s tag.', 'auxin-elements'), '<br />' , '<code>&lt;script&gt;</code>' )."<br />".
                           __('In order to save your custom javascript code, you are expected to execute the code prior to saving.', 'auxin-elements'),
        'id'            => 'auxin_user_custom_js',
        'section'       => 'general-section-custom-js',
        'dependency'    => array(),
        'default'       => '',
        'transport'     => 'postMessage',
        'button_labels' => array( 'label' => __('Execute', 'auxin-elements') ),
        'mode'          => 'javascript',
        'type'          => 'code'
    );


    // Sub section - SEO ----------------------------------

    $fields_sections_list['sections'][] = array(
        'id'          => 'general-section-seo',
        'parent'      => 'general-section', // section parent's id
        'title'       => __( 'Google API Keys & SEO', 'auxin-elements'),
        'description' => __( 'Google API Keys & SEO', 'auxin-elements')
    );


    $fields_sections_list['fields'][] = array(
        'title'         => __('Built in SEO', 'auxin-elements'),
        'description'   => __('In case of using SEO plugins like "WordPress SEO by Yoast" or "All in One SEO Pack" you can disable built-in SEO for maximum compatibility.',
                              'auxin-elements'),
        'id'            => 'enable_theme_seo',
        'section'       => 'general-section-seo',
        'dependency'    => array(),
        'default'       => '1',
        'type'          => 'switch'
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Google Analytics Code', 'auxin-elements'),
        'description'   => sprintf( __('You can %s set up Analytics tracking %s and add the tracking ID here.', 'auxin-elements'),
        '<a href="https://support.google.com/analytics/answer/1008080" target="_blank">',
        '</a>' ),
        'id'            => 'auxin_user_google_analytics',
        'section'       => 'general-section-seo',
        'dependency'    => array(),
        'default'       => '',
        'transport'     => 'postMessage',
        'mode'          => 'javascript',
        'button_labels' => array( 'label' => false ),
        'type'          => 'text'
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Google Maps API Key', 'auxin-elements'),
        'description'   => sprintf(
                            __( 'In order to use google maps on your website,  you have to %s create an api key %s and insert it in this field.', 'auxin-elements' ),
                            '<a href="https://developers.google.com/maps/documentation/javascript/" target="_blank">',
                            '</a>'
                        ),
        'id'            => 'auxin_google_map_api_key',
        'section'       => 'general-section-seo',
        'dependency'    => array(),
        'default'       => '',
        'transport'     => 'postMessage',
        'mode'          => 'javascript',
        'type'          => 'text'
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Google Marketing Code', 'auxin-elements'),
        'description'   => sprintf( __('You can add your Google marketing code here.%s DO NOT use %s tag.', 'auxin-elements'), '<br />' , '<code>&lt;script&gt;</code>' ),
        'id'            => 'auxin_user_google_marketing',
        'section'       => 'general-section-seo',
        'dependency'    => array(),
        'default'       => '',
        'transport'     => 'postMessage',
        'mode'          => 'javascript',
        'button_labels' => array( 'label' => false ),
        'type'          => 'code'
    );

    // Secondary logo for sticky header  ----------------------------------


    $custom_logo_args = get_theme_support( 'custom-logo' );

    $fields_sections_list['fields'][] = array(
        'title'          => __( 'Logo 2 (optional)', 'auxin-elements' ),
        'description'    => __( 'The secondary logo which appears when the header becomes sticky (optional).', 'auxin-elements' ),
        'id'             => 'custom_logo2',
        'section'        => 'title_tagline',
        'transport'      => 'postMessage',
        'default'        => '',
        'priority'       => 9,
        'type'           => 'image',
        'transport'      => 'refresh'
    );


    // Sub section - Button 1 in header -------------------------------

    $fields_sections_list['sections'][] = array(
        'id'            => 'header-section-action-button1',
        'parent'        => 'header-section',                                     // section parent's id
        'title'         => __( 'Header Button 1', 'auxin-elements' ),
        'description'   => __( 'Setting for Header Button 1', 'auxin-elements' ),
        'is_deprecated' => true
    );

    $fields_sections_list['fields'][] = array(
        'title'            => __( 'Use Legacy Header', 'auxin-elements' ),
        'description'      => __( 'Disable it to replace header section with an Elementor template', 'auxin-elements' ),
        'id'               => 'site_header_btn1_section_use_legacy',
        'section'          => 'header-section-action-button1',
        'type'             => 'switch',
        'default'          => '0',
        'related_controls' => ['site_header_use_legacy']
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Display Header Button 1','auxin-elements' ),
        'description'       => __('Enable this option to display a button in header.','auxin-elements' ),
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_show_btn1',
        'type'              => 'switch',
        'default'           => '0',
        'dependency'  => array(
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'partial'           => array(
            'selector'              => '.aux-btn1-box',
            'container_inclusive'   => true,
            'render_callback'       => function(){ echo auxin_get_header_button(1); }
        )
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Hide Button 1 on Tablet', 'auxin-elements' ),
        'description' => __( 'Enable it to hide header button 1 on tablet devices.', 'auxin-elements' ),
        'id'          => 'site_header_show_btn1_on_tablet',
        'section'     => 'header-section-action-button1',
        'dependency'  => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'default'     => '1',
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-btn1-box").toggleClass( "aux-tablet-off", to );',
        'type'        => 'switch'
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Hide Button 1 on Mobile', 'auxin-elements' ),
        'description' => __( 'Enable it to hide header button 1 on tablet devices.', 'auxin-elements' ),
        'id'          => 'site_header_show_btn1_on_phone',
        'section'     => 'header-section-action-button1',
        'dependency'  => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'default'     => '1',
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-btn1-box").toggleClass( "aux-phone-off", to );',
        'type'        => 'switch'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Button Label','auxin-elements' ),
        'description'       => __('Specifies the label of button.','auxin-elements' ),
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_btn1_label',
        'type'              => 'text',
        'default'           => __('Button', 'auxin-elements'),
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn1-box .aux-ac-btn1").html( to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Button Size','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_btn1_size',
        'type'              => 'select',
        'choices'           => array(
            'exlarge' => __('Exlarge', 'auxin-elements' ),
            'large'   => __('Large'  , 'auxin-elements' ),
            'medium'  => __('Medium' , 'auxin-elements' ),
            'small'   => __('Small'  , 'auxin-elements' ),
            'tiny'    => __('Tiny'   , 'auxin-elements' )
        ),
        'default'          => 'large',
        'dependency'       => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn1-box .aux-ac-btn1").removeClass( "aux-exlarge aux-large aux-medium aux-small aux-tiny" ).addClass( "aux-" + to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Button Shape','auxin-elements' ),
        'description'   => '',
        'section'       => 'header-section-action-button1',
        'id'            => 'site_header_btn1_shape',
        'type'          => 'radio-image',
        'choices'       => array(
            ''          => array(
                'label' => __('Sharp', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
            ),
            'round'     => array(
                'label' => __('Round', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-curved.svg'
            ),
            'curve'     => array(
                'label' => __('Curve', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-rounded.svg'
            )
        ),
        'default'          => 'curve',
        'dependency'       => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn1-box .aux-ac-btn1").removeClass( "aux-round aux-curve" ).addClass( "aux-" + to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Button Style','auxin-elements' ),
        'description'   => '',
        'section'       => 'header-section-action-button1',
        'id'            => 'site_header_btn1_style',
        'type'          => 'radio-image',
        'choices'       => array(
            ''          => array(
                'label' => __('Normal', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
            ),
            '3d'        => array(
                'label' => __('3D', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-3d.svg'
            ),
            'outline'   => array(
                'label' => __('Outline', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-outline.svg'
            )
        ),
        'default'          => '',
        'dependency'       => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn1-box .aux-ac-btn1").removeClass( "aux-3d aux-outline" ).addClass( "aux-" + to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'          => __( 'Button Typography', 'auxin-elements' ),
        'id'             => 'site_header_btn1_typography',
        'section'        => 'header-section-action-button1',
        'default'        => '',
        'type'           => 'group_typography',
        'selectors'      => '.site-header-section .aux-btn1-box .aux-button',
        'dependency'     => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'      => 'postMessage'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Icon for Button','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_btn1_icon',
        'type'              => 'icon',
        'default'           => '',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'refresh'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Icon Alignment','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_btn1_icon_align',
        'type'              => 'radio-image',
        'choices'           => array(
            'default'       => array(
                'label'     => __('Default' , 'auxin-elements'),
                'image'     => AUXELS_ADMIN_URL . '/assets/images/button.png'
            ),
            'left'     => array(
                'label'     => __('Left' , 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button2.webm webm'
            ),
            'right'       => array(
                'label'     => __('Right' , 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button1.webm webm'
            ),
            'over'       => array(
                'label'     => __('Over', 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button5.webm webm'
            ),
            'left-animate' => array(
                'label'     => __('Animate from Left', 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button4.webm webm'
            ),
            'right-animate' => array(
                'label'     => __('Animate from Righ', 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button3.webm webm'
            )
        ),
        'default'           => 'default',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn1-box .aux-ac-btn1").alterClass( "aux-icon-*", "aux-icon-" + to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Color of Button','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_btn1_color_name',
        'type'              => 'radio-image',
        'choices'           => auxin_get_famous_colors_list(),
        'default'           => 'ball-blue',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'refresh'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Color of Button on Sticky','auxin-elements' ),
        'description'       => __('Specifies the color of the button when the header sticky is enabled.', 'auxin-elements' ),
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_btn1_color_name_on_sticky',
        'type'              => 'radio-image',
        'choices'           => auxin_get_famous_colors_list(),
        'default'           => 'ball-blue',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'refresh'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Button Link','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_btn1_link',
        'type'              => 'text',
        'default'           => '#',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn1-box .aux-ac-btn1").prop( "href", to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Open Link in','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button1',
        'id'                => 'site_header_btn1_target',
        'type'              => 'select',
        'choices'           => array(
            '_self'  => __('Current page' , 'auxin-elements' ),
            '_blank' => __('New page', 'auxin-elements' )
        ),
        'default'           => '_self',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn1',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn1-box .aux-ac-btn1").prop( "target", to );'
    );



    // Sub section - Button 2 in header -------------------------------

    $fields_sections_list['sections'][] = array(
        'id'            => 'header-section-action-button2',
        'parent'        => 'header-section',                                     // section parent's id
        'title'         => __( 'Header Button 2', 'auxin-elements' ),
        'description'   => __( 'Setting for Header Button 2', 'auxin-elements' ),
        'is_deprecated' => true
    );

    $fields_sections_list['fields'][] = array(
        'title'            => __( 'Use Legacy Header', 'auxin-elements' ),
        'description'      => __( 'Disable it to replace header section with an Elementor template', 'auxin-elements' ),
        'id'               => 'site_header_btn2_section_use_legacy',
        'section'          => 'header-section-action-button2',
        'type'             => 'switch',
        'default'          => '0',
        'related_controls' => ['site_header_use_legacy']
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Display Header Button 2','auxin-elements' ),
        'description'       => __('Enable this option to display a button in header.','auxin-elements' ),
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_show_btn2',
        'type'              => 'switch',
        'default'           => '0',
        'dependency'        => array(
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'partial'           => array(
            'selector'              => '.aux-btn2-box',
            'container_inclusive'   => true,
            'render_callback'       => function(){ echo auxin_get_header_button(2); }
        )
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Hide Button 2 on Tablet', 'auxin-elements' ),
        'description' => __( 'Enable it to hide header button 2 on tablet devices.', 'auxin-elements' ),
        'id'          => 'site_header_show_btn2_on_tablet',
        'section'     => 'header-section-action-button2',
        'dependency'  => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'default'     => '1',
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-btn2-box").toggleClass( "aux-tablet-off", to );',
        'type'        => 'switch'
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Hide Button 2 on Mobile', 'auxin-elements' ),
        'description' => __( 'Enable it to hide header button 2 on tablet devices.', 'auxin-elements' ),
        'id'          => 'site_header_show_btn2_on_phone',
        'section'     => 'header-section-action-button2',
        'dependency'  => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'default'     => '1',
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-btn2-box").toggleClass( "aux-phone-off", to );',
        'type'        => 'switch'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Button Label','auxin-elements' ),
        'description'       => __('Specifies the label of button.','auxin-elements' ),
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_btn2_label',
        'type'              => 'text',
        'default'           => __('Button', 'auxin-elements'),
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn2-box .aux-ac-btn2").html( to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Button Size','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_btn2_size',
        'type'              => 'select',
        'choices'           => array(
            'exlarge' => __('Exlarge', 'auxin-elements' ),
            'large'   => __('Large'  , 'auxin-elements' ),
            'medium'  => __('Medium' , 'auxin-elements' ),
            'small'   => __('Small'  , 'auxin-elements' ),
            'tiny'    => __('Tiny'   , 'auxin-elements' )
        ),
        'default'          => 'large',
        'dependency'       => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn2-box .aux-ac-btn2").removeClass( "aux-exlarge aux-large aux-medium aux-small aux-tiny" ).addClass( "aux-" + to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Button Shape','auxin-elements' ),
        'description'   => '',
        'section'       => 'header-section-action-button2',
        'id'            => 'site_header_btn2_shape',
        'type'          => 'radio-image',
        'choices'       => array(
            ''          => array(
                'label' => __('Sharp', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
            ),
            'round'     => array(
                'label' => __('Round', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-curved.svg'
            ),
            'curve'     => array(
                'label' => __('Curve', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-rounded.svg'
            )
        ),
        'default'          => 'curve',
        'dependency'       => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn2-box .aux-ac-btn2").removeClass( "aux-round aux-curve" ).addClass( "aux-" + to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'         => __('Button Style','auxin-elements' ),
        'description'   => '',
        'section'       => 'header-section-action-button2',
        'id'            => 'site_header_btn2_style',
        'type'          => 'radio-image',
        'choices'       => array(
            ''          => array(
                'label' => __('Normal', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
            ),
            '3d'        => array(
                'label' => __('3D', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-3d.svg'
            ),
            'outline'   => array(
                'label' => __('Outline', 'auxin-elements' ),
                'image' => AUXIN_URL . 'images/visual-select/button-outline.svg'
            )
        ),
        'default'          => 'outline',
        'dependency'       => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn2-box .aux-ac-btn2").removeClass( "aux-3d aux-outline" ).addClass( "aux-" + to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'          => __( 'Button Typography', 'auxin-elements' ),
        'id'             => 'site_header_btn2_typography',
        'section'        => 'header-section-action-button2',
        'default'        => '',
        'type'           => 'group_typography',
        'selectors'      => '.site-header-section .aux-btn2-box .aux-button',
        'dependency'     => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'      => 'postMessage'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Icon for Button','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_btn2_icon',
        'type'              => 'icon',
        'default'           => '',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'refresh'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Icon Alignment','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_btn2_icon_align',
        'type'              => 'radio-image',
        'choices'           => array(
            'default'       => array(
                'label'     => __('Default' , 'auxin-elements'),
                'image'     => AUXELS_ADMIN_URL . '/assets/images/button.png'
            ),
            'left'     => array(
                'label'     => __('Left' , 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button2.webm webm'
            ),
            'right'       => array(
                'label'     => __('Right' , 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button2.webm webm'
            ),
            'over'       => array(
                'label'     => __('Over', 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button5.webm webm'
            ),
            'left-animate' => array(
                'label'     => __('Animate from Left', 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button4.webm webm'
            ),
            'right-animate' => array(
                'label'     => __('Animate from Righ', 'auxin-elements'),
                'video_src' => AUXELS_ADMIN_URL . '/assets/images/preview/Button3.webm webm'
            )
        ),
        'default'           => 'default',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn2-box .aux-ac-btn2").alterClass( "aux-icon-*", "aux-icon-" + to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Color of Button','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_btn2_color_name',
        'type'              => 'radio-image',
        'choices'           => auxin_get_famous_colors_list(),
        'default'           => 'emerald',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'refresh'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Color of Button on Sticky','auxin-elements' ),
        'description'       => __('Specifies the color of the button when the header sticky is enabled.', 'auxin-elements' ),
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_btn2_color_name_on_sticky',
        'type'              => 'radio-image',
        'choices'           => auxin_get_famous_colors_list(),
        'default'           => 'ball-blue',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'refresh'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Button Link','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_btn2_link',
        'type'              => 'text',
        'default'           => '',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn2-box .aux-ac-btn2").prop( "href", to );'
    );

    $fields_sections_list['fields'][] = array(
        'title'             => __('Open Link in','auxin-elements' ),
        'description'       => '',
        'section'           => 'header-section-action-button2',
        'id'                => 'site_header_btn2_target',
        'type'              => 'select',
        'choices'           => array(
            '_self'  => __('Current page' , 'auxin-elements' ),
            '_blank' => __('New page', 'auxin-elements' )
        ),
        'default'           => '_self',
        'dependency'        => array(
            array(
                 'id'      => 'site_header_show_btn2',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_header_use_legacy',
                'value'   => '1',
                'operator'=> '=='
            ),
        ),
        'transport'         => 'postMessage',
        'post_js'           => '$(".aux-btn2-box .aux-ac-btn2").prop( "target", to );'
    );


    // Sub section - footer  -------------------------------


    $fields_sections_list['fields'][] = array(
        'title'       => __('Footer Brand Image', 'auxin-elements'),
        'description' => __('This image appears as site brand image on footer section.', 'auxin-elements'),
        'id'          => 'site_secondary_logo_image',
        'section'     => 'footer-section-footer',
        'dependency'  => array(
            array(
                 'id'      => 'show_site_footer',
                 'value'   => array('1'),
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_footer_use_legacy',
                'value'   => array('1'),
                'operator'=> '=='
           )
        ),
        'default'     => '',
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-logo-footer .aux-logo-anchor',
            'container_inclusive'   => false,
            'render_callback'       => function(){ echo _auxin_get_footer_logo_image(); }
        ),
        'type'        => 'image'
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __('Footer Brand Height', 'auxin-elements'),
        'description' => __('Specifies maximum height of logo in footer.', 'auxin-elements'),
        'id'          => 'site_secondary_logo_max_height',
        'section'     => 'footer-section-footer',
        'dependency'  => array(
            array(
                 'id'      => 'show_site_footer',
                 'value'   => array('1'),
                 'operator'=> '=='
            ),
            array(
                'id'      => 'site_footer_use_legacy',
                'value'   => array('1'),
                'operator'=> '=='
           )
        ),
        'default'        => '50',
        'transport'      => 'postMessage',
        'post_js'        => '$(".aux-logo-footer .aux-logo-anchor img").css( "max-height", $.trim(to) + "px" );',
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_secondary_logo_max_height' );
            }
            $value = trim( $value, 'px');
            return $value ? ".aux-logo-footer .aux-logo-anchor img { max-height:{$value}px; }" : '';
        },
        'type'        => 'text'
    );




    // Sub section - Login page customizer -------------------------------

    $fields_sections_list['sections'][] = array(
        'id'            => 'tools-section-login',
        'parent'        => 'tools-section', // section parent's id
        'title'         => __( 'Login Page', 'auxin-elements' ),
        'description'   => __( 'Preview login page', 'auxin-elements' ),
        'preview_link'  =>  wp_login_url()
    );



    $fields_sections_list['fields'][] = array(
        'title'       => __('Login Skin', 'auxin-elements'),
        'description' => __('Specifies a skin for login page of your website.', 'auxin-elements'),
        'id'          => 'auxin_login_skin',
        'section'     => 'tools-section-login',
        'dependency'  => array(),
        'choices'     => array(
            'default'   =>  array(
                'label' => __('Default', 'auxin-elements'),
                'image' => AUXIN_URL . 'images/visual-select/login-skin-default.svg'
            ),
            'clean-white'   =>  array(
                'label' => __('Clean white', 'auxin-elements'),
                'image' => AUXIN_URL . 'images/visual-select/login-skin-light.svg'
            ),
            'simple-white'   =>  array(
                'label' => __('Simple white', 'auxin-elements'),
                'image' => AUXIN_URL . 'images/visual-select/login-skin-simple-light.svg'
            ),
            'simple-gray'   =>  array(
                'label' => __('Simple gray', 'auxin-elements'),
                'image' => AUXIN_URL . 'images/visual-select/login-skin-simple-gray.svg'
            )
        ),
        'transport' => 'refresh',
        'default'   => 'default',
        'type'      => 'radio-image'
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __('Login message', 'auxin-elements'),
        'description' => __('Enter a text to display above the login form.', 'auxin-elements'),
        'id'          => 'auxin_login_message',
        'section'     => 'tools-section-login',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'type'        => 'textarea',
        'default'     => ''
    );

    //--------------------------------

    $fields_sections_list['fields'][] = array(
        'title'       =>  __('Login Page Logo', 'auxin-elements'),
        'description' =>  __('Specifies a logo to display on login page.(width of logo image could be up to 320px)', 'auxin-elements'),
        'id'          =>  'auxin_login_logo_image',
        'section'     =>  'tools-section-login',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'default'     =>  '',
        'type'        =>  'image'
    );


    $fields_sections_list['fields'][] = array(
        'title'       => __('Logo Width', 'auxin-elements'),
        'description' => __('Specifies width of logo image in pixel.', 'auxin-elements'),
        'id'          => 'auxin_login_logo_width',
        'section'     => 'tools-section-login',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'default'     => '84',
        'type'        => 'text'
    );


    $fields_sections_list['fields'][] = array(
        'title'       => __('Logo Height', 'auxin-elements'),
        'description' => __('Specifies height of logo image in pixel.', 'auxin-elements'),
        'id'          => 'auxin_login_logo_height',
        'section'     => 'tools-section-login',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'default'     => '84',
        'type'        => 'text'
    );

    //--------------------------------

    $fields_sections_list['fields'][] = array(
        'title'         => __('Enable Background', 'auxin-elements'),
        'description'   => __('Enable it to display custom background on login page.', 'auxin-elements'),
        'id'            => 'auxin_login_bg_show',
        'section'       => 'tools-section-login',
        'type'          => 'switch',
        'transport'     => 'refresh',
        'wrapper_class' => 'collapse-head',
        'default'       => '0'
    );


    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Background Color', 'auxin-elements'),
        'description' => __( 'Specifies background color of website.', 'auxin-elements'),
        'id'          => 'auxin_login_bg_color',
        'section'     => 'tools-section-login',
        'type'        => 'color',
        'selectors'    => ' ',
        'dependency'  => array(
            array(
                'id' => 'auxin_login_bg_show',
                'value' => array( '1' )
            )
        ),
        'transport'   => 'postMessage',
        'default'     => ''
    );

    $fields_sections_list['fields'][] = array(
        'title'       =>  __('Background Image', 'auxin-elements'),
        'description' =>  __('You can upload custom image for background of login page', 'auxin-elements'),
        'id'          => 'auxin_login_bg_image',
        'section'     => 'tools-section-login',
        'type'        => 'image',
        'dependency'  => array(
            array(
                'id' => 'auxin_login_bg_show',
                'value' => array( '1' )
            )
        ),
        'transport'   => 'refresh',
        'default'     => ''
    );

    $fields_sections_list['fields'][] = array(
        'title'       =>  __('Background Size', 'auxin-elements'),
        'description' =>  __('Specifies background size on login page.', 'auxin-elements'),
        'id'          => 'auxin_login_bg_size',
        'section'     => 'tools-section-login',
        'type'        => 'radio-image',
        'choices'     => array(
            'auto' => array(
                'label'     => __('Auto', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-bg-size-1',
            ),
            'contain' => array(
                'label'     => __('Contain', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-bg-size-2'
            ),
            'cover' => array(
                'label'     => __('Cover', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-bg-size-3'
            )
        ),
        'dependency'  => array(
            array(
                'id' => 'auxin_login_bg_show',
                'value' => array( '1' )
            )
        ),
        'transport'  => 'refresh',
        'default'    => 'auto'
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __('Background Pattern', 'auxin-elements'),
        'description' => sprintf(__('You can select one of these patterns as login background image. %s Some of these can be used as a pattern over your background image.', 'auxin-elements'), '<br>'),
        'id'          => 'auxin_login_bg_pattern',
        'section'     => 'tools-section-login',
        'choices'     => auxin_get_background_patterns( array( 'none' => array( 'label' =>__('None', 'auxin-elements'), 'image' => AUXIN_URL . 'images/visual-select/none-pattern.svg' ) ), 'before' ),
        'type'        => 'radio-image',
        'dependency'  => array(
            array(
                'id' => 'auxin_login_bg_show',
                'value' => array( '1' )
            )
        ),
        'transport'   => 'refresh',
        'default'     => ''
    );

    $fields_sections_list['fields'][] = array(
        'title'       =>  __( 'Background Repeat', 'auxin-elements'),
        'description' =>  __( 'Specifies how background image repeats.', 'auxin-elements'),
        'id'          => 'auxin_login_bg_repeat',
        'section'     => 'tools-section-login',
        'choices'     =>  array(
            'no-repeat' => array(
                'label'     => __('No repeat', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-none',
            ),
            'repeat' => array(
                'label'     => __('Repeat horizontally and vertically', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-repeat-xy',
            ),
            'repeat-x' => array(
                'label'     => __('Repeat horizontally', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-repeat-x',
            ),
            'repeat-y' => array(
                'label'     => __('Repeat vertically', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-repeat-y',
            )
        ),
        'type'       => 'radio-image',
        'dependency'  => array(
            array(
                'id' => 'auxin_login_bg_show',
                'value' => array( '1' )
            )
        ),
        'transport'  => 'refresh',
        'default'    => 'no-repeat'
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Background Position', 'auxin-elements'),
        'description' => __('Specifies background image position.', 'auxin-elements'),
        'id'          => 'auxin_login_bg_position',
        'section'     => 'tools-section-login',
        'choices'     => array(
            'left top' => array(
                'label'     => __('Left top', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-top-left'
            ),
            'center top' => array(
                'label'     => __('Center top', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-top-center'
            ),
            'right top' => array(
                'label'     => __('Right top', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-top-right'
            ),

            'left center' => array(
                'label'     => __('Left center', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-center-left'
            ),
            'center center' => array(
                'label'     => __('Center center', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-center-center'
            ),
            'right center' => array(
                'label'     => __('Right center', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-center-right'
            ),

            'left bottom' => array(
                'label'     => __('Left bottom', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-bottom-left'
            ),
            'center bottom' => array(
                'label'     => __('Center bottom', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-bottom-center'
            ),
            'right bottom' => array(
                'label'     => __('Right bottom', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-bottom-right'
            )
        ),
        'type'       => 'radio-image',
        'dependency'  => array(
            array(
                'id' => 'auxin_login_bg_show',
                'value' => array( '1' )
            )
        ),
        'transport'  => 'refresh',
        'default'    => 'left top'
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __('Background Attachment', 'auxin-elements'),
        'description' => __('Specifies whether the background is fixed or scrollable as user scrolls the page.', 'auxin-elements'),
        'id'          => 'auxin_login_bg_attach',
        'section'     => 'tools-section-login',
        'type'        => 'radio-image',
        'choices'     => array(
            'scroll' => array(
                'label'     => __('Scroll', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-bg-attachment-scroll',
            ),
            'fixed' => array(
                'label'     => __('Fixed', 'auxin-elements'),
                'css_class' => 'axiAdminIcon-bg-attachment-fixed',
            )
        ),
        'dependency'  => array(
            array(
                'id' => 'auxin_login_bg_show',
                'value' => array( '1' )
            )
        ),
        'transport'  => 'refresh',
        'default'    => 'scroll'
    );

    //--------------------------------

    $fields_sections_list['fields'][] = array(
        'title'       => __('Custom CSS class name', 'auxin-elements'),
        'description' => __('In this field you can define custom CSS class name for login page.
                          This class name will be added to body classes in login page and is useful for advance custom styling purposes.', 'auxin-elements'),
        'id'         => 'auxin_login_body_class',
        'section'    => 'tools-section-login',
        'dependency' => array(),
        'transport'  => 'refresh',
        'default'    => '',
        'type'       => 'text'
    );

    // Sub section - 404 page customizer -------------------------------

    $fields_sections_list['sections'][] = array(
        'id'            => 'tools-section-404',
        'parent'        => 'tools-section', // section parent's id
        'title'         => __( '404 Page', 'auxin-elements' ),
        'description'   => __( '404 Page Options', 'auxin-elements' )
        //'description'   => __( 'Preview 404 page', 'auxin-elements' ),
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __('404 Page', 'auxin-elements'),
        'description' => __('Specifies a page to display on 404.', 'auxin-elements'),
        'id'         => 'auxin_404_page',
        'section'    => 'tools-section-404',
        'dependency' => array(),
        'transport'  => 'refresh',
        'default'    => 'default',
        'type'       => 'select',
        'choices'    => auxin_get_all_pages(),
    );

    // Sub section - Maintenance page customizer -------------------------------

    $fields_sections_list['sections'][] = array(
        'id'            => 'tools-section-maintenance',
        'parent'        => 'tools-section', // section parent's id
        'title'         => __( 'Maintenance or Comingsoon Page', 'auxin-elements' ),
        'description'   => __( 'Maintenance or Comingsoon Page Options', 'auxin-elements' )
        //'description'   => __( 'Preview maintenance page', 'auxin-elements' ),
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Enable Maintenance or Comingsoon Mode', 'auxin-elements' ),
        'description' => __( 'With this option you can manually enable Maintenance or Comingsoon mode', 'auxin-elements' ),
        'id'         => 'auxin_maintenance_enable',
        'section'    => 'tools-section-maintenance',
        'dependency' => array(),
        'transport'  => 'refresh',
        'default'    => '0',
        'type'       => 'switch',
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __('Maintenance or Comingsoon Page', 'auxin-elements'),
        'description' => __('In This Case You Can Set Your Specifc Page for Maintenance or Comingsoon Mode', 'auxin-elements'),
        'id'         => 'auxin_maintenance_page',
        'section'    => 'tools-section-maintenance',
        'dependency'  => array(
            array(
                'id' => 'auxin_maintenance_enable',
                'value' => array( '1' )
            )
        ),        'transport'  => 'refresh',
        'default'    => 'default',
        'type'       => 'select',
        'choices'    => auxin_get_all_pages(),
    );

    // Sub section - Custom Search -------------------------------

    $fields_sections_list['sections'][] = array(
        'id'            => 'tools-section-search-result',
        'parent'        => 'tools-section', // section parent's id
        'title'         => __( 'Search Results', 'auxin-elements' ),
        'description'   => __( 'Search Results Options', 'auxin-elements' )
    );

    //--------------------------------

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Exclude Posts Types', 'auxin-elements' ),
        'description' => __( 'The post types which should be excluded from search results.', 'auxin-elements' ),
        'id'         => 'auxin_search_exclude_post_types',
        'section'    => 'tools-section-search-result',
        'dependency' => array(),
        'transport'  => 'postMessage',
        'default'    => '',
        'type'       => 'select2-post-types',
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Exclude Posts Without Featured Image', 'auxin-elements' ),
        'description' => __( 'Exclude posts without featured image in search results.', 'auxin-elements' ),
        'id'         => 'auxin_search_exclude_no_media',
        'section'    => 'tools-section-search-result',
        'dependency' => array(),
        'transport'  => 'postMessage',
        'default'    => '',
        'type'       => 'switch',
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Include posts', 'auxin-elements' ),
        'description' => __( 'If you intend to include additional posts, you should specify the posts here.<br>You have to insert the Post IDs that are separated by camma (eg. 53,34,87,25)', 'auxin-elements' ),
        'id'         => 'auxin_search_pinned_contents',
        'section'    => 'tools-section-search-result',
        'dependency' => array(),
        'transport'  => 'postMessage',
        'default'    => '',
        'type'       => 'text',
    );

    // Sub section - Custom Search -------------------------------

    $fields_sections_list['sections'][] = array(
        'id'            => 'tools-section-import-export',
        'parent'        => 'tools-section', // section parent's id
        'title'         => __( 'Import/Export', 'auxin-elements' ),
        'description'   => __( 'Import or Export options', 'auxin-elements' )
    );


    //--------------------------------

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Export Data', 'auxin-elements' ),
        'description' => __( 'Your theme options code which you can import later.', 'auxin-elements' ),
        'id'         => 'auxin_customizer_export',
        'section'    => 'tools-section-import-export',
        'dependency' => array(),
        'transport'  => 'postMessage',
        'default'    => '',
        'type'       => 'export',
    );

    $fields_sections_list['fields'][] = array(
        'title'       => __( 'Import Data', 'auxin-elements' ),
        'description' => __( 'Paste the exported theme options code to import into theme.', 'auxin-elements' ),
        'id'         => 'auxin_customizer_import',
        'section'    => 'tools-section-import-export',
        'dependency' => array(),
        'transport'  => 'postMessage',
        'default'    => '',
        'type'       => 'import',
    );

    if( defined( 'AUX_WHITELABEL_DISPLAY' ) && AUX_WHITELABEL_DISPLAY ){
        // White Label section ==================================================================

        $fields_sections_list['sections'][] = array(
            'id'          => 'whitelabel-section',
            'parent'      => '', // section parent's id
            'title'       => __( 'White Label', 'auxin-elements'),
            'description' => __( 'White Label Settings', 'auxin-elements'),
            'icon'        => 'axicon-doc'
        );

        // Sub section - Custom Labels -------------------------------

        $fields_sections_list['sections'][] = array(
            'id'           => 'whitelabel-section-labels',
            'parent'       => 'whitelabel-section', // section parent's id
            'title'        => __( 'Settings', 'auxin-elements'),
            'description'  => __( 'Change PHLOX labels.', 'auxin-elements')
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Theme Name', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_theme_name',
            'section'    => 'whitelabel-section-labels',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => THEME_NAME_I18N,
            'type'       => 'text',
        );

        $fields_sections_list['fields'][] = array(
            'title'       =>  __('Theme Author Name', 'auxin-elements'),
            'id'          => 'auxin_whitelabel_theme_author_name',
            'section'     => 'whitelabel-section-labels',
            'type'        => 'text',
            'transport'   => 'postMessage',
            'default'     => ''
        );

        $fields_sections_list['fields'][] = array(
            'title'       =>  __('Theme Author URL', 'auxin-elements'),
            'id'          => 'auxin_whitelabel_theme_author_url',
            'section'     => 'whitelabel-section-labels',
            'type'        => 'url',
            'transport'   => 'postMessage',
            'default'     => ''
        );

        $fields_sections_list['fields'][] = array(
            'title'       =>  __('Theme Description', 'auxin-elements'),
            'id'          => 'auxin_whitelabel_theme_description',
            'section'     => 'whitelabel-section-labels',
            'type'        => 'textarea',
            'transport'   => 'postMessage',
            'default'     => ''
        );

        $fields_sections_list['fields'][] = array(
            'title'       =>  __('Theme Screenshot (1200x900)', 'auxin-elements'),
            'id'          => 'auxin_whitelabel_theme_screenshot',
            'section'     => 'whitelabel-section-labels',
            'type'        => 'image',
            'transport'   => 'postMessage',
            'default'     => ''
        );

        // Sub section - Custom Labels -------------------------------

        $fields_sections_list['sections'][] = array(
            'id'           => 'whitelabel-section-views',
            'parent'       => 'whitelabel-section', // section parent's id
            'title'        => __( 'Displays', 'auxin-elements'),
            'description'  => __( 'Change PHLOX admin views.', 'auxin-elements')
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Notifications', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_notices',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Theme Badge', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_theme_badge',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Phlox Menu', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_menu',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Dashboard Section', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_dashboard_section',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'dependency'  => array(
                array(
                     'id'      => 'auxin_whitelabel_hide_menu',
                     'value'   => array('1'),
                     'operator'=> '!='
                )
            ),
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Customization Section', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_customization_section',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'dependency'  => array(
                array(
                     'id'      => 'auxin_whitelabel_hide_menu',
                     'value'   => array('1'),
                     'operator'=> '!='
                )
            ),
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Demo Importer Section', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_demo_importer_section',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'dependency'  => array(
                array(
                     'id'      => 'auxin_whitelabel_hide_menu',
                     'value'   => array('1'),
                     'operator'=> '!='
                )
            ),
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Template Kits Section', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_template_kits_section',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'dependency'  => array(
                array(
                     'id'      => 'auxin_whitelabel_hide_menu',
                     'value'   => array('1'),
                     'operator'=> '!='
                )
            ),
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Plugins Section', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_plugins_section',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'dependency'  => array(
                array(
                     'id'      => 'auxin_whitelabel_hide_menu',
                     'value'   => array('1'),
                     'operator'=> '!='
                )
            ),
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Tutorials Section', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_tutorials_section',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'dependency'  => array(
                array(
                     'id'      => 'auxin_whitelabel_hide_menu',
                     'value'   => array('1'),
                     'operator'=> '!='
                )
            ),
            'type'       => 'switch',
        );

        $fields_sections_list['fields'][] = array(
            'title'       => __( 'Hide Feedback Section', 'auxin-elements' ),
            'description' => '',
            'id'         => 'auxin_whitelabel_hide_feedback_section',
            'section'    => 'whitelabel-section-views',
            'dependency' => array(),
            'transport'  => 'postMessage',
            'default'    => '0',
            'dependency'  => array(
                array(
                     'id'      => 'auxin_whitelabel_hide_menu',
                     'value'   => array('1'),
                     'operator'=> '!='
                )
            ),
            'type'       => 'switch',
        );
    }

    return $fields_sections_list;
}

add_filter( 'auxin_defined_option_fields_sections', 'auxin_add_theme_options_in_plugin', 12, 1 );





/*-----------------------------------------------------------------------------------*/
/*  Injects JavaScript codes from theme options in head
/*-----------------------------------------------------------------------------------*/

function auxin_ele_add_js_to_head() {
    if( $inline_js = auxin_get_option( 'auxin_user_custom_js_head' ) ){
        echo '<script>'. $inline_js .'</script>';
    }
    if( isset( $_GET['helper'] ) ){
        echo '<style>.elementor-section.elementor-section-boxed>.elementor-container{box-shadow:0 0 0 1px #2b83eb;}</style>';
    }
}
add_action( 'wp_head','auxin_ele_add_js_to_head' );


function auxin_ele_add_google_analytics_code() {
    if( $google_analytics_code = auxin_get_option( 'auxin_user_google_analytics' ) ){
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $google_analytics_code ); ?>"></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '<?php echo esc_attr( $google_analytics_code ); ?>');</script>
<?php
    }
}
add_action( 'wp_head','auxin_ele_add_google_analytics_code' );

/*-----------------------------------------------------------------------------------*/
/*  Injects JavaScript codes from theme options in JS file
/*-----------------------------------------------------------------------------------*/

function auxin_ele_add_theme_options_to_js_file( $js ){
    $js['theme_options_custom'] = auxin_get_option( 'auxin_user_custom_js' );

    $js['theme_options_google_marketing'] = auxin_get_option( 'auxin_user_google_marketing' );

    // @deprecated in version 2.5.0
    unset( $js['theme_options_google_analytics'] );

    return $js;
}
add_filter( 'auxin_custom_js_file_content', 'auxin_ele_add_theme_options_to_js_file' );


/*-----------------------------------------------------------------------------------*/
/*  Adds the custom CSS class of the login page to body element
/*-----------------------------------------------------------------------------------*/

function auxin_login_body_class( $classes ){

    if( $custom_class = auxin_get_option('auxin_login_body_class' ) ){
        $classes['auxin_custom'] = $custom_class;
    }

    if( $custom_skin = auxin_get_option('auxin_login_skin' ) ){
        $classes['auxin_skin'] = esc_attr( 'auxin-login-skin-' . $custom_skin );
    }

    return $classes;
}
add_action( 'auxin_functions_ready', function(){
    add_filter( 'login_body_class', 'auxin_login_body_class' );
});



/*-----------------------------------------------------------------------------------*/
/*  Adds proper styles for background and logo on login page
/*-----------------------------------------------------------------------------------*/

function auxin_login_head(){

    $styles     = '';

    if( $bg_image_id = auxin_get_option( 'auxin_login_logo_image' ) ){
        $bg_image = wp_get_attachment_url( $bg_image_id );
        $styles   .= "background-image: url( $bg_image ); ";

        $bg_width  = auxin_get_option( 'auxin_login_logo_width' , '84' );
        $bg_height = auxin_get_option( 'auxin_login_logo_height', '84' );

        $bg_width  = rtrim( $bg_width , 'px' ) . 'px';
        $bg_height = rtrim( $bg_height, 'px' ) . 'px';

        $styles   .= "background-size: $bg_width $bg_height; ";
        $styles   .= "width: $bg_width; height: $bg_height; ";

        echo "<style>#login h1 a { $styles }</style>";
    }

    if( auxin_get_option( 'auxin_login_bg_show' ) ){

        // get styles for background image
        $bg_styles = auxin_generate_styles_for_backgroud_fields( 'auxin_login_bg', 'option', array(
            'color'      => 'auxin_login_bg_color',
            'image'      => 'auxin_login_bg_image',
            'repeat'     => 'auxin_login_bg_repeat',
            'size'       => 'auxin_login_bg_size',
            'position'   => 'auxin_login_bg_position',
            'attachment' => 'auxin_login_bg_attachment',
            'clip'       => 'auxin_login_bg_clip'
        ) );

        $pattern_style = auxin_generate_styles_for_backgroud_fields( 'auxin_login_bg', 'option', array(
            'pattern'    => 'auxin_login_bg_pattern'
        ) );

        echo "<style>body.login { $bg_styles } body.login:before { $pattern_style }</style>";
    }

}
add_action( 'auxin_functions_ready', function(){
    add_action( 'login_head', 'auxin_login_head' );
});


/*-----------------------------------------------------------------------------------*/
/*  Changes the login header url to home url
/*-----------------------------------------------------------------------------------*/

function auxin_login_headerurl( $login_header_url ){

    if ( ! is_multisite() ) {
        $login_header_url   = home_url();
    }
    return $login_header_url;
}
add_action( 'auxin_functions_ready', function(){
    add_filter( 'login_headerurl', 'auxin_login_headerurl' );
});

/*-----------------------------------------------------------------------------------*/
/*  Changes the login header url to home url
/*-----------------------------------------------------------------------------------*/

function auxin_login_headertext( $login_header_title ){

    if ( ! is_multisite() ) {
        $login_header_title = get_bloginfo( 'name' );
    }
    return $login_header_title;
}
add_action( 'auxin_functions_ready', function(){
    add_filter( 'login_headertext', 'auxin_login_headertext' );
});

/*-----------------------------------------------------------------------------------*/
/*  Adds custom message above the login form
/*-----------------------------------------------------------------------------------*/

function auxin_login_message( $login_message ){

    if( $custom_message = auxin_get_option( 'auxin_login_message' ) ){

        $message_wrapper_start = '<div class="message">';
        $message_wrapper_end   = "</div>\n";

        $custom_message_markup = $message_wrapper_start . $custom_message . $message_wrapper_end;

        /**
         * Filter instructional messages displayed above the login form.
         *
         * @param string $custom_message Login message.
         */
        $login_message .=  apply_filters( 'auxin_login_message', $custom_message_markup, $custom_message, $message_wrapper_start, $message_wrapper_end );
    }

    return $login_message;
}
add_action( 'auxin_functions_ready', function(){
    add_filter( 'login_message', 'auxin_login_message' );
});


/*-----------------------------------------------------------------------------------*/
/*  Prints the custom js codes of a single page to the source page
/*-----------------------------------------------------------------------------------*/

function auxin_custom_js_for_pages( $js, $post ){
    // The custom JS code for specific page
    if( $post && ! is_404() && is_singular() ) {
        $js .= get_post_meta( $post->ID, 'aux_page_custom_js', true );
    }
    return $js;
}
add_filter( 'auxin_footer_inline_script', 'auxin_custom_js_for_pages', 15, 2 );


/*-----------------------------------------------------------------------------------*/
/*  Add preconnect for Google Fonts.
/*-----------------------------------------------------------------------------------*/

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function auxin_resource_hints( $urls, $relation_type ) {
        if ( wp_style_is( 'auxin-fonts-google', 'queue' ) && 'preconnect' === $relation_type ) {
                $urls[] = array(
                        'href' => 'https://fonts.gstatic.com',
                        'crossorigin',
                );
        }
        return $urls;
}
//add_filter( 'wp_resource_hints', 'auxin_resource_hints', 10, 2 );


/*-----------------------------------------------------------------------------------*/
/*  Setup Header
/*-----------------------------------------------------------------------------------*/

function auxin_after_setup_theme_extra(){
    // gererate shortcodes in widget text
    add_filter('widget_text', 'do_shortcode');
    // Remove wp ulike auto disaply filter
    remove_filter( 'the_content', 'wp_ulike_put_posts', 15 );
}
add_action( 'after_setup_theme', 'auxin_after_setup_theme_extra' );

/*-----------------------------------------------------------------------------------*/
/*  add excerpts to pages
/*-----------------------------------------------------------------------------------*/

function auxin_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'auxin_add_excerpts_to_pages' );


/*-----------------------------------------------------------------------------------*/
/*  Add some user contact fields
/*-----------------------------------------------------------------------------------*/

function auxin_user_contactmethods($user_contactmethods){
    $user_contactmethods['twitter']    = __('Twitter'    , 'auxin-elements');
    $user_contactmethods['facebook']   = __('Facebook'   , 'auxin-elements');
    $user_contactmethods['googleplus'] = __('Google Plus', 'auxin-elements');
    $user_contactmethods['flickr']     = __('Flickr'     , 'auxin-elements');
    $user_contactmethods['delicious']  = __('Delicious'  , 'auxin-elements');
    $user_contactmethods['pinterest']  = __('Pinterest'  , 'auxin-elements');
    $user_contactmethods['github']     = __('GitHub'     , 'auxin-elements');
    $user_contactmethods['skills']     = __('Skills'     , 'auxin-elements');

    return $user_contactmethods;
}
add_filter('user_contactmethods', 'auxin_user_contactmethods');


/*-----------------------------------------------------------------------------------*/
/*  Add home page menu arg to menu item list
/*-----------------------------------------------------------------------------------*/

function auxin_add_home_page_to_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'auxin_add_home_page_to_menu_args' );

/*-----------------------------------------------------------------------------------*/
/*  Print meta tags to preview post while sharing on facebook
/*-----------------------------------------------------------------------------------*/

if( ! defined('WPSEO_VERSION') && ! class_exists('All_in_One_SEO_Pack') ){

    function auxin_facebook_header_meta (){

        if( ! defined('AUXIN_VERSION') ){
            return;
        }

        // return if built-in seo is disabled or "SEO by yoast" is active
        if( ! auxin_get_option( 'enable_theme_seo', 1 ) ) return;

        global $post;
        if( ! isset( $post ) || ! is_singular() || is_search() || is_404() ) return;
        setup_postdata( $post );

        $featured_image = auxin_get_the_post_thumbnail_src( $post->ID, 90, 90, true, 90 );
        $post_excerpt   = get_the_excerpt();
        ?>
    <meta name="title"       content="<?php echo esc_attr( $post->post_title ); ?>" />
    <meta name="description" content="<?php echo esc_attr( $post_excerpt ); ?>" />
    <?php if( $featured_image) { ?>
    <link rel="image_src"    href="<?php echo $featured_image; ?>" />
    <?php }

    }

    add_action( 'wp_head', 'auxin_facebook_header_meta' );
}

/*-----------------------------------------------------------------------------------*/
/*  Add SiteOrigin class prefix and custom field classes path
/*-----------------------------------------------------------------------------------*/
if ( auxin_is_plugin_active( 'so-widgets-bundle/so-widgets-bundle.php') ) {

    function auxels_register_auxin_siteorigin_class_prefix( $class_prefixes ) {
        $class_prefixes[] = 'Auxin_SiteOrigin_Field_';
        return $class_prefixes;
    }

    add_filter( 'siteorigin_widgets_field_class_prefixes', 'auxels_register_auxin_siteorigin_class_prefix' );


    function auxels_register_custom_fields( $class_paths ) {
        $class_paths[] = AUXELS_ADMIN_DIR . '/includes/compatibility/siteorigin/fields/';
        return $class_paths;
    }

    add_filter( 'siteorigin_widgets_field_class_paths', 'auxels_register_custom_fields' );
}


/**
 * Replace WooCommerce Default Pagination with auxin pagination
 *
 */
remove_action( 'woocommerce_pagination' , 'woocommerce_pagination', 10 );
add_action   ( 'woocommerce_pagination', 'auxin_woocommerce_pagination' , 10 );

function auxin_woocommerce_pagination() {
    auxin_the_paginate_nav(
        array( 'css_class' => auxin_get_option('archive_pagination_skin') )
    );
}

/*-----------------------------------------------------------------------------------*/
/*  the function runs when auxin framework loaded
/*-----------------------------------------------------------------------------------*/

function auxin_on_auxin_fw_admin_loaded(){

    // assign theme custom capabilities to roles on first run
    if( ! auxin_get_theme_mod( 'are_auxin_caps_assigned', 0 ) ){
        add_action( 'admin_init'  , 'auxin_assign_default_caps_for_post_types' );
        set_theme_mod( 'are_auxin_caps_assigned', 1 );
    }

    if ( ! auxin_get_theme_mod( 'initial_date', 0 ) ) {
        set_theme_mod( 'initial_date', current_time( 'mysql' ) );
    }

    $slug = THEME_PRO ? 'pro' : 'free';
    if ( ! auxin_get_theme_mod( 'initial_version_' . $slug, 0 ) ) {
        set_theme_mod( 'initial_version_' . $slug, THEME_VERSION );
    }

    if ( ! auxin_get_theme_mod( 'initial_date_' . $slug, 0 ) ) {
        set_theme_mod( 'initial_date_' . $slug, current_time( 'mysql' ) );
    }

    if ( ! auxin_get_theme_mod( 'client_key', 0 ) ) {
        $client_key = base64_encode( get_site_url() ) . rand( 100000, 1000000 );
        set_theme_mod( 'client_key', str_shuffle( $client_key ) );
    }
}

add_action( 'auxin_admin_loaded', 'auxin_on_auxin_fw_admin_loaded' );


/**
 * Retrieves the passed time from first installation date of theme
 *
 * @return DataTimeInterface
 */
function auxin_get_passed_installed_time(){
    $slug = THEME_PRO ? 'pro' : 'free';
    $initial_time = auxin_get_theme_mod( 'initial_date_' . $slug, "now" );
    $initial_date = new DateTime( $initial_time );
    $passed_time = $initial_date->diff( new DateTime() );

    return $passed_time;
}


/*-------------------------------------------------------------------------------*/
/*  assigns theme custom post types capabilities to main roles
/*-------------------------------------------------------------------------------*/

function auxin_assign_default_caps_for_post_types() {
    $auxin_registered_post_types = auxin_registered_post_types(true);

    // the roles to add capabilities of custom post types to
    $roles = array('administrator', 'editor');

    foreach ( $roles as $role_name ) {

        $role = get_role( $role_name );

        // loop through custom post types and add custom capabilities to defined rules
        foreach ( $auxin_registered_post_types as $post_type ) {

            $post_type_object = get_post_type_object( $post_type );
            // add post type capabilities to role
            foreach ( $post_type_object->cap as $cap_key => $cap ) {
                if( ! in_array( $cap_key, array( 'edit_post', 'delete_post', 'read_post' ) ) )
                    $role->add_cap( $cap );
            }
        }

    }
}





function auxels_add_post_type_metafields(){

    $all_post_types = auxin_get_possible_post_types(true);

    $auxin_is_admin  = is_admin();

    foreach ( $all_post_types as $post_type => $is_post_type_allowed ) {

        if( ! $is_post_type_allowed ){
            continue;
        }

        // define metabox args
        $metabox_args = array( 'post_type' => $post_type );

        switch( $post_type ) {

            case 'page':

                $metabox_args['hub_id']        = 'axi_meta_hub_page';
                $metabox_args['hub_title']     = __('Page Options', 'auxin-elements');
                $metabox_args['to_post_types'] = array( $post_type );

                break;

            case 'post':

                $metabox_args['hub_id']        = 'axi_meta_hub_post';
                $metabox_args['hub_title']     = __('Post Options', 'auxin-elements');
                $metabox_args['to_post_types'] = array( $post_type );

            default:
                break;
        }

        // Load metabox fields on admin
        if( $auxin_is_admin ){
            auxin_maybe_render_metabox_hub_for_post_type( $metabox_args );
        }

    }

}

//add_action( 'init', 'auxels_add_post_type_metafields' );

/*-----------------------------------------------------------------------------------*/
/*  Add custom blog page tamplate
/*-----------------------------------------------------------------------------------*/

/**
 * Add custom page templates
 *
 * @param  string $result        The current custom blog page template markup
 * @param  string $page_template The name of page template
 *
 * @return string                The markup for current page template
 */
function auxels_blog_page_templates( $result, $page_template ){

    // page number
    $paged  = max( 1, get_query_var('paged'), get_query_var('page') );

    // posts perpage
    $per_page      = get_option( 'posts_per_page' );

    // if template type is masonry
    if( strpos( $page_template, 'blog-type-6' ) ){

        $args = array(
            'title'                         => '',
            'num'                           => $per_page,
            'paged'                         => $paged,
            'order_by'                      => 'menu_order date',
            'order'                         => 'desc',
            'show_media'                    => true,
            'exclude_without_media'         => 0,
            'exclude_custom_post_formats'   => 0,
            'exclude_quote_link'            => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 0 ) ),
            'loadmore_type'                 => esc_attr( auxin_get_option( 'post_index_loadmore_type', '' ) ),
            'show_title'                    => true,
            'show_info'                     => true,
            'show_readmore'                 => true,
            'show_author_footer'            => false,
            'tag'                           => '',
            'reset_query'                   => true
        );

        // get the shortcode base blog page
        $result = auxin_widget_recent_posts_masonry_callback( $args );
    }

    // if template type is tiles
    elseif( strpos( $page_template, 'blog-type-9' ) ){

        $args = array(
            'title'                         => '',
            'num'                           => $per_page,
            'paged'                         => $paged,
            'order_by'                      => 'menu_order date',
            'order'                         => 'desc',
            'show_media'                    => true,
            'exclude_without_media'         => 0,
            'exclude_custom_post_formats'   => 0,
            'exclude_quote_link'            => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 0 ) ),
            'loadmore_type'                 => esc_attr( auxin_get_option( 'post_index_loadmore_type', '' ) ),
            'show_title'                    => true,
            'show_info'                     => true,
            'show_readmore'                 => true,
            'show_author_footer'            => false,
            'tag'                           => '',
            'reset_query'                   => true
        );

        // get the shortcode base blog page
        $result = auxin_widget_recent_posts_tiles_callback( $args );
    }

    // if template type is land
    elseif( strpos( $page_template, 'blog-type-8' ) ){

        $args = array(
            'title'                         => '',
            'num'                           => $per_page,
            'paged'                         => $paged,
            'order_by'                      => 'menu_order date',
            'order'                         => 'desc',
            'show_media'                    => true,
            'exclude_without_media'         => 0,
            'exclude_custom_post_formats'   => 0,
            'exclude_quote_link'            => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 0 ) ),
            'loadmore_type'                 => esc_attr( auxin_get_option( 'post_index_loadmore_type', '' ) ),
            'show_excerpt'                  => true,
            'excerpt_len'                   => '160',
            'show_title'                    => true,
            'show_info'                     => true,
            'show_readmore'                 => true,
            'show_author_footer'            => false,
            'tag'                           => '',
            'reset_query'                   => true
        );

        // get the shortcode base blog page
        $result = auxin_widget_recent_posts_land_style_callback( $args );
    }

    // if template type is timeline
    elseif( strpos( $page_template, 'blog-type-7' ) ){

        $args = array(
            'title'              => '',
            'num'                => $per_page,
            'paged'              => $paged,
            'order_by'           => 'menu_order date',
            'order'              => 'desc',
            'exclude_quote_link' => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 1 ) ),
            'loadmore_type'      => esc_attr( auxin_get_option( 'post_index_loadmore_type', '' ) ),
            'show_media'         => true,
            'show_excerpt'       => true,
            'excerpt_len'        => '160',
            'show_title'         => true,
            'show_info'          => true,
            'show_readmore'      => true,
            'show_author_footer' => false,
            'timeline_alignment' => 'center',
            'tag'                => '',
            'reset_query'        => true
        );

        // get the shortcode base blog page
        $result = auxin_widget_recent_posts_timeline_callback( $args );
    }

    // if template type is grid
    elseif( strpos( $page_template, 'blog-type-5' ) ){

        $args = array(
            'title'              => '',
            'num'                => $per_page,
            'order_by'           => 'menu_order date',
            'order'              => 'desc',
            'exclude_quote_link' => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 1 ) ),
            'paged'              => $paged,
            'show_media'         => true,
            'display_like'       => esc_attr( auxin_get_option( 'show_blog_archive_like_button', 1 ) ),
            'loadmore_type'      => esc_attr( auxin_get_option( 'post_index_loadmore_type', '' ) ),
            'show_excerpt'       => true,
            'excerpt_len'        => '160',
            'show_title'         => true,
            'show_info'          => true,
            'show_readmore'      => true,
            'show_author_footer' => false,
            'desktop_cnum'       => esc_attr( auxin_get_option( 'post_index_column_number', 4 ) ),
            'tablet_cnum'        => esc_attr( auxin_get_option( 'post_index_column_number_tablet', 2 ) ),
            'phone_cnum'         => esc_attr( auxin_get_option( 'post_index_column_number_mobile', 1 ) ),
            'preview_mode'       => 'grid',
            'tag'                => '',
            'reset_query'        => true
        );

        // get the shortcode base blog page
        $result = auxin_widget_recent_posts_callback( $args );
    }

    return $result;
}

add_filter( 'auxin_blog_page_template_archive_content', 'auxels_blog_page_templates', 10, 2 );


/*-----------------------------------------------------------------------------------*/
/*  Add custom blog archive tamplate types
/*-----------------------------------------------------------------------------------*/

/**
 * Add custom page templates
 *
 * @param  string $result        The current custom blog loop template markup
 * @param  string $page_template The ID of template type option
 *
 * @return string                The markup for current blog archive page
 */
function auxels_add_blog_archive_custom_template_layouts( $result, $template_type_id ){

    // get template type id
    $post_loadmore_type = auxin_get_option( 'post_index_loadmore_type', '' );
    // get the length of content
    $excerpt_len = esc_attr( auxin_get_option( 'blog_content_on_listing_length' ) );

    // default value for showing info
    $show_post_info = $show_post_date = $show_post_author = $show_post_categories = true;

    $author_or_readmore      = 'readmore';
    $show_post_date          = true;
    $show_post_categories    = true;
    $blog_content_on_listing = 'excerpt';
    $display_comments        = true;
    $display_author_header   = true;
    $display_author_footer   = false;

    // Use taxonomy template option if is category or tag archive page

    if( is_category() || is_tag() ){
        $author_or_readmore      = auxin_get_option( 'display_post_taxonomy_author_readmore', 'readmore');
        $post_loadmore_type      = auxin_get_option( 'post_taxonomy_loadmore_type', '' );
        $excerpt_len             = auxin_get_option( 'post_taxonomy_archive_on_listing_length', '' );
        $show_post_info          = auxin_get_option( 'display_post_taxonomy_info', true );
        $show_post_date          = auxin_get_option( 'display_post_taxonomy_info_date', true );
        $show_post_categories    = auxin_get_option( 'display_post_taxonomy_info_categories', true );
        $blog_content_on_listing = auxin_get_option( 'post_taxonomy_archive_content_on_listing', 'excerpt' );
        $display_comments        = auxin_get_option( 'display_post_taxonomy_widget_comments', true);
        $display_author_header   = auxin_get_option( 'display_post_taxonomy_author_header', true);
        $display_author_footer   = auxin_get_option( 'display_post_taxonomy_author_footer', false);

    } elseif ( auxin_is_blog() ) {
        $author_or_readmore      = auxin_get_option( 'blog_display_author_readmore', 'readmore');
        $display_author_header   = auxin_get_option( 'blog_display_author_header', true);
        $display_author_footer   = auxin_get_option( 'blog_display_author_footer', false);
        $show_post_info          = auxin_get_option( 'display_post_info', true );
        $show_post_date          = auxin_get_option( 'display_post_info_date', true );
        $show_post_categories    = auxin_get_option( 'display_post_info_categories', true );
        $blog_content_on_listing = auxin_get_option( 'blog_content_on_listing', 'excerpt' );
        $excerpt_len             = auxin_get_option( 'blog_content_on_listing_length', '' );
        $display_comments        = auxin_get_option( 'display_post_comments_number', true);
    } else {
        $blog_content_on_listing = 'excerpt';
    }

    $show_post_author = $show_post_author ? 'author' : 'readmore';
    $show_excerpt     = 'none' === $blog_content_on_listing ? false : true ;
    $excerpt_len      = 'full' === $blog_content_on_listing ? null : $excerpt_len ;

    // page number
    $paged    = max( 1, get_query_var('paged'), get_query_var('page') );
    // posts perpage
    $per_page = get_option( 'posts_per_page' );

    if( 6 == $template_type_id ){
        $args = array(
            'num'                           => $per_page,
            'exclude_without_media'         => esc_attr( auxin_get_option( 'exclude_without_media' ) ),
            'exclude_custom_post_formats'   => 0,
            'exclude_quote_link'            => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 1 ) ),
            'display_like'                  => esc_attr( auxin_get_option( 'show_blog_archive_like_button', 1 ) ),
            'display_comments'              => $display_comments,
            'display_author_footer'         => $display_author_footer,
            'display_author_header'         => $display_author_header,
            'loadmore_type'                 => esc_attr( $post_loadmore_type ),
            'paged'                         => $paged,
            'show_media'                    => true,
            'show_excerpt'                  => $show_excerpt,
            'excerpt_len'                   => $excerpt_len,
            'show_info'                     => esc_attr( $show_post_info ),
            'show_date'                     => esc_attr( $show_post_date ),
            'display_categories'            => esc_attr( $show_post_categories ),
            'author_or_readmore'            => $author_or_readmore,
            'content_layout'                => esc_attr( auxin_get_option( 'post_index_column_content_layout', 'full' ) ),
            'desktop_cnum'                  => esc_attr( auxin_get_option( 'post_index_column_number' ) ),
            'tablet_cnum'                   => esc_attr( auxin_get_option( 'post_index_column_number_tablet' ) ),
            'phone_cnum'                    => esc_attr( auxin_get_option( 'post_index_column_number_mobile' ) ),
            'tag'                           => '',
            'extra_classes'                 => '',
            'custom_el_id'                  => '',
            'reset_query'                   => false,
            'use_wp_query'                  => true,
            'request_from'                  => 'archive'
        );

        $result = auxin_widget_recent_posts_masonry_callback( $args );

    // if template type is tiles
    } elseif( 9 == $template_type_id ){

        $args = array(
            'num'                           => $per_page,
            'exclude_without_media'         => esc_attr( auxin_get_option( 'exclude_without_media' ) ),
            'exclude_custom_post_formats'   => 0,
            'exclude_quote_link'            => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 1 ) ),
            'loadmore_type'                 => esc_attr( $post_loadmore_type ),
            'paged'                         => $paged,
            'show_media'                    => true,
            'show_excerpt'                  => $show_excerpt,
            'excerpt_len'                   => $excerpt_len,
            'display_title'                 => true,
            'display_comments'              => $display_comments,
            'show_info'                     => esc_attr( $show_post_info ),
            'show_date'                     => esc_attr( $show_post_date ),
            'display_categories'            => esc_attr( $show_post_categories ),
            'author_or_readmore'            => $author_or_readmore,
            'display_author_footer'         => $display_author_footer,
            'display_author_header'         => $display_author_header,
            'tag'                           => '',
            'extra_classes'                 => '',
            'custom_el_id'                  => '',
            'reset_query'                   => false,
            'use_wp_query'                  => true,
            'request_from'                  => 'archive'
        );

        $result = auxin_widget_recent_posts_tiles_callback( $args );

    // if template type is land
    } elseif( 8 == $template_type_id ){

        $args = array(
            'num'                           => $per_page,
            'exclude_without_media'         => esc_attr( auxin_get_option( 'exclude_without_media' ) ),
            'exclude_custom_post_formats'   => 0,
            'exclude_quote_link'            => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 1 ) ),
            'show_media'                    => true,
            'paged'                         => $paged,
            'display_like'                  => esc_attr( auxin_get_option( 'show_blog_archive_like_button', 1 ) ),
            'display_comments'              => $display_comments,
            'loadmore_type'                 => esc_attr( $post_loadmore_type ),
            'show_excerpt'                  => $show_excerpt,
            'excerpt_len'                   => $excerpt_len,
            'display_title'                 => true,
            'show_info'                     => esc_attr( $show_post_info ),
            'show_date'                     => esc_attr( $show_post_date ),
            'display_categories'            => esc_attr( $show_post_categories ),
            'author_or_readmore'            => $author_or_readmore,
            'display_author_footer'         => $display_author_footer,
            'display_author_header'         => $display_author_header,
            'image_aspect_ratio'            =>  esc_attr( auxin_get_option( 'post_image_aspect_ratio' ) ),
            'tag'                           => '',
            'extra_classes'                 => '',
            'custom_el_id'                  => '',
            'reset_query'                   => false,
            'use_wp_query'                  => true,
            'request_from'                  => 'archive'
        );

        $result = auxin_widget_recent_posts_land_style_callback( $args );

    // if template type is timeline
    } elseif( 7 == $template_type_id ){

        $args = array(
            'num'                           => $per_page,
            'exclude_without_media'         => esc_attr( auxin_get_option( 'exclude_without_media' ) ),
            'exclude_custom_post_formats'   => 0,
            'exclude_quote_link'            => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 1 ) ),
            'show_media'                    => true,
            'paged'                         => $paged,
            'display_like'                  => esc_attr( auxin_get_option( 'show_blog_archive_like_button', 1 ) ),
            'display_comments'              => $display_comments,
            'loadmore_type'                 => esc_attr( $post_loadmore_type ),
            'show_excerpt'                  => $show_excerpt,
            'excerpt_len'                   => $excerpt_len,
            'display_title'                 => true,
            'show_info'                     => esc_attr( $show_post_info ),
            'show_date'                     => esc_attr( $show_post_date ),
            'display_categories'            => esc_attr( $show_post_categories ),
            'author_or_readmore'            => $author_or_readmore,
            'display_author_footer'         => $display_author_footer,
            'display_author_header'         => $display_author_header,
            'image_aspect_ratio'            => esc_attr( auxin_get_option( 'post_image_aspect_ratio' ) ),
            'timeline_alignment'            => esc_attr( auxin_get_option( 'post_index_timeline_alignment', 'center' ) ),
            'tag'                           => '',
            'reset_query'                   => false,
            'use_wp_query'                  => true,
            'request_from'                  => 'archive'
        );

        $result = auxin_widget_recent_posts_timeline_callback( $args );

    // if template type is grid
    } elseif( 5 == $template_type_id ){

        $args = array(
            'num'                           => $per_page,
            'exclude_without_media'         => esc_attr( auxin_get_option( 'exclude_without_media' ) ),
            'exclude_custom_post_formats'   => 0,
            'exclude_quote_link'            => esc_attr( auxin_get_option( 'post_exclude_quote_link_formats', 1 ) ),
            'show_media'                    => true,
            'show_excerpt'                  => $show_excerpt,
            'paged'                         => $paged,
            'post_info_position'            => esc_attr( auxin_get_option( 'post_info_position', 'after-title' ) ),
            'show_info'                     => esc_attr( $show_post_info ),
            'show_date'                     => esc_attr( $show_post_date ),
            'display_categories'            => esc_attr( $show_post_categories ),
            'display_like'                  => esc_attr( auxin_get_option( 'show_blog_archive_like_button', 1 ) ),
            'display_comments'              => $display_comments,
            'loadmore_type'                 => esc_attr( $post_loadmore_type ),
            'content_layout'                => esc_attr( auxin_get_option( 'post_index_column_content_layout', 'full' ) ),
            'excerpt_len'                   => $excerpt_len,
            'display_title'                 => true,
            'author_or_readmore'            => $author_or_readmore,
            'display_author_footer'         => $display_author_footer,
            'display_author_header'         => $display_author_header,
            'image_aspect_ratio'            => esc_attr( auxin_get_option( 'post_image_aspect_ratio' ) ),
            'desktop_cnum'                  => esc_attr( auxin_get_option( 'post_index_column_number' ) ),
            'tablet_cnum'                   => esc_attr( auxin_get_option( 'post_index_column_number_tablet' ) ),
            'phone_cnum'                    => esc_attr( auxin_get_option( 'post_index_column_number_mobile' ) ),
            'preview_mode'                  => 'grid',
            'tag'                           => '',
            'reset_query'                   => false,
            'use_wp_query'                  => true,
            'request_from'                  => 'archive'
        );

        $result = auxin_widget_recent_posts_callback( $args );
    }

    return $result;
}

add_filter( 'auxin_blog_archive_custom_template_layouts', 'auxels_add_blog_archive_custom_template_layouts', 10, 2 );


/*-----------------------------------------------------------------------------------*/
/*  Add Boxed layout to siteorigin row layout types
/*-----------------------------------------------------------------------------------*/

function auxin_so_row_style_attributes( $attributes, $style ){

    if ( ! empty( $style['row_stretch'] ) && 'boxed' === $style['row_stretch'] ) {
        if( ( $key = array_search( 'siteorigin-panels-stretch', $attributes['class'] ) ) !== false ) {
            unset( $attributes['class'][ $key ] );
        }
        $attributes['class'][] = 'aux-fold';
    }

    return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'auxin_so_row_style_attributes', 12, 2 );

/*-----------------------------------------------------------------------------------*/
/*  Add Boxed layout to siteorigin row layout types
/*-----------------------------------------------------------------------------------*/

function auxin_so_row_style_fields( $fields ){
    $fields['row_stretch']['options']['boxed'] = __( 'Boxed', 'auxin-elements' );

    return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'auxin_so_row_style_fields', 15 );

/*-----------------------------------------------------------------------------------*/
/*  Filtering wp_title to improve seo and letting seo plugins to filter the output too
/*-----------------------------------------------------------------------------------*/

if( ! defined( 'WPSEO_VERSION') ){

    function auxin_wp_title($title, $sep, $seplocation) {
        global $page, $paged, $post;

        // Don't affect feeds
        if ( is_feed() )  return $title;

        // Add the blog name
        if ( 'right' == $seplocation )
            $title  .= get_bloginfo( 'name' );
        else
            $title   = get_bloginfo( 'name' ) . $title;

        // Add the blog description for the home/front page
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            $title .= " $sep $site_description";

        // Add a page number if necessary
        if ( $paged >= 2 || $page >= 2 )
            $title .= " $sep " . sprintf( __( 'Page %s', 'auxin-elements'), max( $paged, $page ) );

        return $title;
    }

    add_filter( 'wp_title', 'auxin_wp_title', 10, 3 );
}

/*-----------------------------------------------------------------------------------*/
/*  Add new functionality in wp default playlist
/*-----------------------------------------------------------------------------------*/

function auxin_underscore_playlist_templates(){
?>
<script type="text/html" id="tmpl-wp-playlist-current-item">
    <# if ( data.image ) { #>
    <img src="{{ data.thumb.src }}" alt="" />
    <# } #>
    <div class="wp-playlist-caption">
        <span class="wp-playlist-item-meta wp-playlist-item-title"><?php
            /* translators: playlist item title */
            printf( _x( '&#8220;%s&#8221;', 'playlist item title' ), '{{ data.title }}' );
        ?></span>
        <# if ( data.meta.album ) { #><span class="wp-playlist-item-meta wp-playlist-item-album">{{ data.meta.album }}</span><# } #>
        <# if ( data.meta.artist ) { #><span class="wp-playlist-item-meta wp-playlist-item-artist">{{ data.meta.artist }}</span><# } #>
    </div>
</script>
<script type="text/html" id="tmpl-wp-playlist-item">
    <div class="wp-playlist-item">
        <#
        var isThumbnailExist = data.thumb.src.indexOf("wp-includes/images/media") > 0 ? 'aux-has-no-thubmnail' : '';
        #>
        <a class="wp-playlist-caption {{ isThumbnailExist }}" href="{{ data.src }}">
            <# if ( data.image ) { #>
                <img class="wp-playlist-item-artist" src="{{ data.thumb.src }}" alt="{{ data.title }}" />
            <# } #>
            <# if ( data.meta.length_formatted ) { #>
            <span class="wp-playlist-item-length">{{ data.meta.length_formatted }}</span>
            <# } #>
        </a>
        <div class="wp-playlist-item-title" >
          <a href="{{ data.src }}">
            <h4>
            <# if ( data.caption ) { #>
                <?php
                    /* translators: playlist item title */
                    printf( _x( '%s', 'playlist item title' ), '{{{ data.caption }}}' );
                ?>
            <# } else { #>
                <?php
                    /* translators: playlist item title */
                    printf( _x( '%s', 'playlist item title' ), '{{{ data.title }}}' );
                ?>
            <# } #>
            </h4>
          </a>
        </div>
    </div>
</script>
<?php
}

function auxin_modify_wp_playlist_scripts(){
    remove_action   ( 'wp_footer'      , 'wp_underscore_playlist_templates'     , 0 );
    remove_action   ( 'admin_footer'   , 'wp_underscore_playlist_templates'     , 0 );
    add_action      ( 'wp_footer'      , 'auxin_underscore_playlist_templates'  , 0 );
    add_action      ( 'admin_footer'   , 'auxin_underscore_playlist_templates'  , 0 );
}
add_action( 'wp_playlist_scripts', 'auxin_modify_wp_playlist_scripts', 15 );

/*-----------------------------------------------------------------------------------*/
/*  Redirects a 404 page to the custom one if available
/*-----------------------------------------------------------------------------------*/

function auxin_redirect_custom_404_page() {

    if( 'default' !== $custom_404_page = auxin_get_option( 'auxin_404_page', 'default ') ) {
        if( is_404() ){
            wp_redirect( get_permalink( $custom_404_page ) );
            exit();
        }
        global $post;

        if( ! empty( $post->ID ) && $post->ID == $custom_404_page ){
            status_header(404);
            nocache_headers();
        }
    }

}
add_action( 'template_redirect', 'auxin_redirect_custom_404_page' );

/*-----------------------------------------------------------------------------------*/

/**
 * Loads a PHP file which includes special functionalities for a custom site
 * @return void
 */
function load_special_demo_functionality(){
    if( auxin_get_option( 'special_php_file_enabled', 0 ) ){
        $file_path = THEME_CUSTOM_DIR .'/'. auxin_get_option('special_php_file_name', 'functions') .'.php';
        if( file_exists( $file_path ) ){
            include_once $file_path;
        }
    }
}
add_action( 'auxin_loaded', 'load_special_demo_functionality' );


/**
 * Automatically clear autoptimizeCache if it goes beyond 256MB
 *
 * @return void
 */
function auxin_maybe_flush_autoptimize_big_cache(){
    // Check transient
    if ( false === auxin_get_transient( 'auxin_maybe_flush_autoptimize_cache' ) ) {

        if ( class_exists('autoptimizeCache') ) {
            $theMaxSize = 256000;
            $statArr = autoptimizeCache::stats();
            $cacheSize = round($statArr[1]/1024);

            if ( $cacheSize > $theMaxSize ){
               autoptimizeCache::clearall();
               # Refresh the page so that autoptimize can create new cache files and it does breaks the page after clearall.
               header("Refresh:0");
            }
        }
        auxin_set_transient( 'auxin_maybe_flush_autoptimize_cache', 2 * DAY_IN_SECONDS );
    }

}

add_action( 'auxin_loaded', 'auxin_maybe_flush_autoptimize_big_cache' );

/*-----------------------------------------------------------------------------------*/

/**
 * Replace the primary logo on the page if custom logo was specified
 *
 * @param  int    $logo_id The current primary logo ID
 * @param  array  $args    The primary logo args
 * @return int             The primary logo ID
 */
function auxin_page_custom_primary_logo_id( $logo_id ){
    global $post;

    if( empty( $post->ID ) ){
        return $logo_id;
    }

    // Check if the custom logo for page is enabled
    if( ! auxin_is_true( auxin_get_post_meta( $post, 'aux_use_custom_logo', 0 ) ) ){
        return $logo_id;
    }

    if( ( $custom_logo_id = auxin_get_post_meta( $post, 'aux_custom_logo' ) ) && is_numeric( $custom_logo_id ) ){
        return $custom_logo_id;
    }

    return $logo_id;
}

add_filter( 'theme_mod_custom_logo', 'auxin_page_custom_primary_logo_id' );


/**
 * Replace the custom logo on the page if custom logo was specified
 *
 * @param  int    $logo_id The current secondary logo ID
 * @param  array  $args    The secondary logo args
 * @return int             The secondary logo ID
 */
function auxin_page_custom_secondary_logo_id( $logo_id, $args ){
    global $post;

    if( empty( $post->ID ) ){
        return $logo_id;
    }

    // Check if the custom logo for page is enabled
    if( ! auxin_is_true( auxin_get_post_meta( $post, 'aux_use_custom_logo', 0 ) ) ){
        return $logo_id;
    }

    if( $custom_logo_id = auxin_get_post_meta( $post, 'aux_custom_logo2' ) ){
        return $custom_logo_id;
    }

    return $logo_id;
}

add_filter( 'auxin_secondary_logo_id', 'auxin_page_custom_secondary_logo_id', 10, 2 );

/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/*  Function For Checking is website on maintenance mode
/*-----------------------------------------------------------------------------------*/

function auxin_is_maintenance() {
    if ( ( function_exists('auxin_get_option') && auxin_get_option('auxin_maintenance_enable', '0') ) || file_exists( ABSPATH . '.maintenance' ) ){
        return true;
    } else {
        return false;
    }

}
add_action( 'get_header', 'auxin_is_maintenance' );

/*-----------------------------------------------------------------------------------*/
/*  Fixing a fatal error while saving the content with page builder enabled
/*-----------------------------------------------------------------------------------*/

function auxin_load_template_function_for_page_builders(){
    if( is_admin() ){
        locate_template( AUXIN_INC . 'include/templates/templates-header.php', true, true );
        locate_template( AUXIN_INC . 'include/templates/templates-post.php'  , true, true );
        locate_template( AUXIN_INC . 'include/templates/templates-footer.php', true, true );
    }
}
add_action('save_post', 'auxin_load_template_function_for_page_builders', 7, 1);
add_action('wp_ajax_wpseo_filter_shortcodes', 'auxin_load_template_function_for_page_builders', 7, 1);

/*-----------------------------------------------------------------------------------*/
/*  Function For Let the user To use custom page for Maintenance and Comingsoon
/*-----------------------------------------------------------------------------------*/
//
function auxin_custom_maintenance_page() {

    if( auxin_is_maintenance() && !current_user_can('manage_options') ){

        $page          = auxin_get_option( 'auxin_maintenance_page', 'default');
        $url           = get_permalink( $page );
        $url_protocols = array( 'http://', 'https://' );
        $url_str       = str_replace( $url_protocols, '', $url );
        $current_url   = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        /* Tell search engines that the site is temporarily unavailable */
        $protocol = $_SERVER['SERVER_PROTOCOL'];

        if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol ) {
            $protocol = 'HTTP/1.0';
        }

        header( "$protocol 503 Service Unavailable", true, 503 );
        header( 'Content-Type: text/html; charset=utf-8' );

        if ( 'default' !== $page && ( $current_url !== $url_str ) ){
            header( "Location: " .$url );
            exit();
        } else if ( 'default' === $page ) {
            include auxin_get_template_file( 'maintenance' , '', AUXELS()->template_path() );
        }
    }

}

add_action( 'wp', 'auxin_custom_maintenance_page');

/*-----------------------------------------------------------------------------------*/


/**
 * Add Subfooter and Subfooter bar to Wocommerce templates
 */

function auxin_display_shop_footer_sidebar() {
    get_sidebar('footer');
}

add_action( 'woocommerce_sidebar', 'auxin_display_shop_footer_sidebar', 10 );



/*-----------------------------------------------------------------------------------*/
/*  Star Rating Markup for WooCommerce
/*-----------------------------------------------------------------------------------*/

function auxin_get_star_rating_html( $rating_html, $rating ){

    if ( $rating > 0 ) {

        // Round Rating value to neareset value  1.5 => 1.5 ,  1.8 => 2 , 1.1 => 1
        $decimal_value = $rating - floor($rating) ;

        if ( 0.5 != $decimal_value ) {
            $rating = round( ( $rating * 2 ) / 2 ) ;
        }

        $rating_html  = '<div class="aux-rating-box aux-star-rating">';
        $rating_html .= '<span class="aux-star-rating-avg" style="width: ' . ( $rating / 5 ) * 100 .'%">';
        $rating_html .= '</span>';
        $rating_html .= '</div>';
    } else {
        $rating_html = '';
    }

    return $rating_html;
}

add_filter( 'woocommerce_product_get_rating_html', 'auxin_get_star_rating_html', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/*  Enable ajax add to cart on free version
/*-----------------------------------------------------------------------------------*/
function auxels_enable_woocommerce_ajax_add_to_cart( $args ){
    global $product;
    $args['class'] = implode( ' ', array_filter( array(
        'button',
        'aux-ajax-add-to-cart',
        'product_type_' . $product->get_type(),
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : ''
    ) ) );

    $args['attributes']['data-product-type'] = $product->get_type();
    $args['attributes']['data-verify_nonce'] = wp_create_nonce( 'aux_add_to_cart-' . $product->get_id() );

    return $args;
}

add_filter( 'woocommerce_loop_add_to_cart_args', 'auxels_enable_woocommerce_ajax_add_to_cart', 10 );

/*-----------------------------------------------------------------------------------*/
/*  Change Products Title Dom
/*-----------------------------------------------------------------------------------*/
add_action( 'init', 'auxin_remove_default_woocommerce_product_title' );

function auxin_remove_default_woocommerce_product_title() {
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    add_action( 'woocommerce_shop_loop_item_title', 'auxin_woocommerce_template_loop_product_title', 10 );
}

function auxin_woocommerce_template_loop_product_title() {
    global $product;
    $dom = '<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '"><h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h2></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo apply_filters( 'auxin_woocommerce_template_loop_product_title', $dom );
}

/**
 * Override inner body sections hooks for replace header&footer
 *
 * @return void
 */
function auxin_override_inner_body_sections(){

    global $post, $aux_main_post;
    $aux_main_post = $post;

    if( ! class_exists( '\Elementor\Plugin' ) ){
        return;
    }

    if ( 'default' === $use_legacy_header = auxin_get_post_meta( $post, 'page_header_use_legacy', 'default' ) ) {
        $use_legacy_header = auxin_get_option('site_header_use_legacy');
    }

    if( ! auxin_is_true( $use_legacy_header ) ) {
        remove_action( 'auxin_after_inner_body_open', 'auxin_the_top_header_section', 4 );
        remove_action( 'auxin_after_inner_body_open', 'auxin_the_main_header_section', 4 );
        if ( ! class_exists( '\ElementorPro\Plugin' ) || empty( ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( 'header' ) ) ) {
            add_action( 'auxin_after_inner_body_open', 'auxin_get_header_template', 4 );
        }
    }
    if ( 'default' === $use_legacy_footer = auxin_get_post_meta( $post, 'page_footer_use_legacy', 'default' ) ) {
        $use_legacy_footer = auxin_get_option('site_footer_use_legacy');
    }

    if( ! auxin_is_true( $use_legacy_footer ) ) {
        remove_action( 'auxin_before_the_footer', 'auxin_the_site_footer' );
        if ( ! class_exists( '\ElementorPro\Plugin' ) || empty( ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( 'footer' ) ) ) {
            add_action( 'auxin_before_the_footer', 'auxin_get_footer_template' );
        }
    }
}
add_action( 'wp', 'auxin_override_inner_body_sections' );

/**
 * Add canvas on elementor single template
 *
 * @param string $single_template
 * @return string
 */
function auxin_load_canvas_template( $single_template ) {
    global $post;

    if ( 'elementor_library' === $post->post_type && defined( 'ELEMENTOR_PATH' ) && defined( 'AUXIN_ELEMENTOR_TEMPLATE' ) ) {
        $template_type = get_post_meta( $post->ID, '_elementor_template_type', true );
        // Limit the template types
        if( ! in_array( $template_type, array( 'header', 'footer' ) ) ){
            return $single_template;
        }
        // Load elementor canvas template
        $elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';
        if ( file_exists( $elementor_2_0_canvas ) ) {
            return $elementor_2_0_canvas;
        } else {
            return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
        }
    }

    return $single_template;
}
add_filter( 'single_template', 'auxin_load_canvas_template' );


/*-----------------------------------------------------------------------------------*/
/* override the canvas template of elementor plugin
/*-----------------------------------------------------------------------------------*/

function auxin_override_elementor_canvas_template( $template ){

	if ( false !== strpos( $template, '/templates/canvas.php' ) ) {
		$template = AUXELS_PUB_DIR . '/templates/elementor/canvas.php';
    }

	return $template;
}
add_filter( 'template_include', 'auxin_override_elementor_canvas_template', 12 );

/* -------------------------------------------------------------------------- */
/*        override default wordpress archive link for custom post types       */
/* -------------------------------------------------------------------------- */

function auxin_override_post_types_archive_link( $link, $post_type ) {
    if ( $post_type == 'portfolio' && auxin_is_true( auxin_get_option('portfolio_show_custom_archive_link') && ! empty( auxin_get_option( 'portfolio_custom_archive_link' ) ) ) ) {
        return get_permalink( auxin_get_option( 'portfolio_custom_archive_link' ) );
    }

    if ( $post_type == 'news' && auxin_is_true( auxin_get_option('news_show_custom_archive_link') && ! empty( auxin_get_option( 'news_custom_archive_link' ) ) ) ) {
        return get_permalink( auxin_get_option( 'news_custom_archive_link' ) );
    }

    return $link;
}
add_filter( 'post_type_archive_link', 'auxin_override_post_types_archive_link', 10, 2 );

function auxels_improve_usage_feedback( $args ) {
    // collect theme name and version
    if ( false == $transient = auxin_get_transient( 'auxels_usage_trac' ) ) {
        $migrated = ( THEME_ID == 'phlox-pro' && ! empty( get_option( 'theme_mods_phlox' ) ) ) ? true : false;
        $args['body']['client_meta']['migrated'] = $migrated;

        $last_imported_demo = get_option( 'auxin_last_imported_demo', '' );
        if ( ! empty( $last_imported_demo ) && $last_imported_demo['id'] ) {
            $args['body']['client_meta'][ THEME_ID . '_imported_demo_id' ] = $last_imported_demo['id'];
        }

        // plugins usage
        $plugins = [
            'Auxin Portfolio'   => 'auxin-portfolio/auxin-portfolio.php',
            'Auxin Shop'        => 'auxin-shop/auxin-shop.php',
            'Auxin News'        => 'auxin-News/auxin-News.php',
            'WpBakery'          => 'js_composer/js_composer.php',
            'Revolution Slider' => 'revslider/revslider.php',
            'SiteOrigin'        => 'siteorigin-panels/siteorigin-panels.php',
            'Element Pack'      => 'bdthemes-element-pack/bdthemes-element-pack.php',
            'Yellow Pencil'     => 'waspthemes-yellow-pencil/yellow-pencil.php',
            'WooCommerce'       => 'woocommerce/woocommerce.php',
            'Elementor'         => 'elementor/elementor.php',
            'Elementor Pro'     => 'elementor-pro/elementor-pro.php'
        ];
        foreach ( $plugins as $name => $plugin ) {
            if ( ! is_plugin_active( $plugin ) ) {
                unset( $plugins[ $name ] );
            }
        }
        $args['body']['client_meta']['plugins'] = $plugins;

        // options usage
        $deprecated_options = [
            'header'    => 'site_header_use_legacy',
            'footer'    => 'site_footer_use_legacy'
        ];
        foreach( $deprecated_options as $key => $option ) {
            $args['body']['client_meta'][ 'has_dep_' . $key ] = auxin_is_true( auxin_get_option( $option ) ) ? 1 : 0;
        }

        // post-types and title bar settings usage
        $args['body']['client_meta']['post-types'] = [
            'post'      => [ 'num' => 0, 'title-bar' => 0 ],
            'page'      => [ 'num' => 0, 'title-bar' => 0 ],
            'portfolio' => [ 'num' => 0, 'title-bar' => 0 ],
            'product'   => [ 'num' => 0, 'title-bar' => 0 ],
            'news'      => [ 'num' => 0, 'title-bar' => 0 ],
            'faq'       => [ 'num' => 0, 'title-bar' => 0 ]
        ];
        foreach ( $args['body']['client_meta']['post-types'] as $key => $post_type ) {
            if ( ! post_type_exists( $post_type ) ) {
                continue;
            }

            $query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => -1 ) );
            $args['body']['client_meta']['post-types'][ $key ]['num'] = $query->found_posts;

            $title_bar_usage = 0;
            $title_bar_show = auxin_get_option( $post_type . '_show_title_bar' );

            if ( $query->have_posts() ) {
                while( $query->have_posts() ) {
                    $query->the_post();

                    if ( ( 'default' == $meta_title_bar = get_post_meta( get_the_ID(), 'aux_show_title_bar' ) ) && auxin_is_true( $title_bar_show ) ) {
                        ++$title_bar_usage;
                    } elseif ( auxin_is_true( $meta_title_bar ) ) {
                        ++$title_bar_usage;
                    }
                }
            }
            $args['body']['client_meta']['post-types'][ $key ]['title-bar'] = $title_bar_usage;
            wp_reset_postdata();
        }

        $slug = THEME_PRO ? 'pro' : 'free';
        $args['body']['client_meta']['init_date_' . $slug] = get_theme_mod( 'initial_date_' . $slug , current_time( 'mysql' ) );
        $args['body']['client_meta']['init_version_' . $slug] = get_theme_mod( 'initial_version_' . $slug, THEME_VERSION );

        auxin_set_transient( 'auxels_usage_trac', $args['body']['client_meta'], DAY_IN_SECONDS );

    } else {
        $args['body']['client_meta'] = $transient;
    }
    return $args;

}
add_filter( 'auxels_version_check_args', 'auxels_improve_usage_feedback' );

/*-----------------------------------------------------------------------------------*/
/* Add header and footer edit link in admin bar
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_bar_menu', 'auxin_add_admin_bar_header_footer_edit_link', 100);

function auxin_add_admin_bar_header_footer_edit_link() {
    global $wp_admin_bar, $post;

    if ( !is_super_admin() || !is_admin_bar_showing() || is_admin() )
        return;

    if ( 'default' === $use_legacy_header = auxin_get_post_meta( $post, 'page_header_use_legacy', 'default' ) ) {
        $use_legacy_header = auxin_get_option('site_header_use_legacy');
    }

    if ( 'default' === $use_legacy_footer = auxin_get_post_meta( $post, 'page_footer_use_legacy', 'default' ) ) {
        $use_legacy_footer = auxin_get_option('site_footer_use_legacy');
    }

    $template = [];

    if ( get_post_type( $post ) == 'page' ) {
        if ( ! auxin_is_true( $use_legacy_header) && ( $current_header = auxin_get_post_meta( $post, 'page_elementor_header_template' ) ) && is_numeric( $current_header ) ) {
            $template['current']['header'] = $current_header;
        }

        if ( ! auxin_is_true( $use_legacy_footer) && ( $current_footer = auxin_get_post_meta( $post, 'page_elementor_footer_template' ) ) && is_numeric( $current_footer ) ) {
            $template['current']['footer'] = $current_footer;
        }
    }

    if ( ! auxin_is_true( auxin_get_option('site_header_use_legacy') ) && $global_header = auxin_get_option('site_elementor_header_template', '' ) ) {
        $template['global']['header'] = $global_header;
    }

    if ( ! auxin_is_true( auxin_get_option('site_footer_use_legacy') ) && $global_footer = auxin_get_option('site_elementor_footer_template', '' ) ) {
        $template['global']['footer'] = $global_footer;
    }

    if ( ! empty( $template['current'] ) ) {

        foreach( $template['current'] as $key => $value ) {
            if ( empty( $value ) ) continue;
            $args[] = [
                'id'        => 'aux-current-' . $key ,
                'title'     => sprintf( '<span>%s</span><span class="aux-state">%s</span>', get_the_title( $value ), __( 'current ', 'auxin-elements' ) . $key ) ,
                'parent'    => 'aux-header-footer',
                'href'      => get_edit_post_link( $value ),
                'meta'      => [
                    'target' => '_blank'
                ]
            ];
        }
    }

    if ( ! empty( $template['global'] ) ) {

        foreach( $template['global'] as $key => $value ) {
            if ( empty( $value ) ) continue;
            $args[] = [
                'id'        => 'aux-global-' . $key ,
                'title'     => sprintf( '<span>%s</span><span class="aux-state">%s</span>', get_the_title( $value ), $key ) ,
                'parent'    => 'aux-header-footer',
                'href'      => get_edit_post_link( $value ),
                'meta'      => [
                    'target' => '_blank'
                ]
            ];
        }
    }

    if ( ! empty( $args ) ) {
        $wp_admin_bar->add_node(
            [
                'id'        => 'aux-header-footer',
                'title'     => sprintf( '<div class="aux-header-footer-edit-links">%s</div>', __( 'Edit Header & Footer', 'auxin-elements' ) ),
                'href'      => '',
            ]
        );

        foreach ( $args as $arg ) {
            $wp_admin_bar->add_node( $arg );
        }
    }
}

function auxels_add_svg_upload_permission( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'auxels_add_svg_upload_permission' );


/**
 * Add classes to wc product items
 *
 * @param array $classes
 * @return array $classes
 */ 
function auxels_add_product_item_classes( $classes ) {

    if( !auxin_is_true( auxin_get_option( 'product_archive_show_view_cart_link', false ) ) ) {
        $classes[] = 'aux-remove-view-cart';
    }

    return $classes;
}
add_filter( 'woocommerce_post_class', 'auxels_add_product_item_classes', 1, 1 );
