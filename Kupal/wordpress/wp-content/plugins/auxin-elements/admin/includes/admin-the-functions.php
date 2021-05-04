<?php
// admin related functions

// Include advanced metabox tab
require_once( 'metaboxes/metabox-fields-general-advanced.php' );


/**
 * Searchs and removes unexpected fields and sections from metabox hub models
 *
 * @param  array  $models The list of metabox models
 * @param  array  $args   The metabox field and sections which should be dropped
 * @return        List of models
 */
function auxin_remove_from_metabox_hub( $models, $args = array() ){

    if( empty( $models ) ){
        return;
    }

    $defaults = array(
        'model_ids'  => array(), // the list of model IDs to be dropped
        'field_ids'  => array()  // the list of field IDs to be dropped
    );

    $args = wp_parse_args( $args, $defaults );

    $args['model_ids' ] = (array) $args['model_ids'];
    $args['field_ids' ] = (array) $args['field_ids'];

    foreach ( $models as $model_info_index => $model_info ) {
        // if similar field id detected, drop it
        if( in_array( $model_info['model']->id, $args['model_ids' ] )  ){
            unset( $models[ $model_info_index ] );
            continue;
        }

        $fields = $model_info['model']->fields;

        if( ! empty( $fields ) ){
            foreach ( $fields as $field_index => $field ) {
                if( empty( $field["id"] ) ){
                    continue;
                }
                if( in_array( $field["id"], $args['field_ids' ] ) ){
                    unset( $fields[ $field_index ] );
                    $models[ $model_info_index ]['model']->fields = $fields;
                }
            }
        }
    }

    return $models;
}



/*----------------------------------------------------------------------------*/
/*  TGMPA plugin update functions
/*----------------------------------------------------------------------------*/

/**
 * Count the number of bundled plugins having new updates
 *
 * @return int|bool  The umber of plugins having update
 */
function auxin_count_bundled_plugins_have_update(){
    // Check transient
    if ( false === ( $tgmpa_counter = auxin_get_transient( 'auxin_count_bundled_plugins_have_update' ) ) ) {
        // Get instance
        $tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
        // Reset Counter
        $tgmpa_counter  = 0;
        // Check plugins list
        foreach ( $tgmpa_instance->plugins as $slug => $plugin ) {
            if ( $plugin['source_type'] === 'bundled' && $tgmpa_instance->is_plugin_active( $slug ) && $tgmpa_instance->does_plugin_have_update( $slug ) ) {
                $tgmpa_counter++;
            }
        }
        auxin_set_transient( 'auxin_count_bundled_plugins_have_update', $tgmpa_counter, 12 * HOUR_IN_SECONDS );
    }

    return $tgmpa_counter;
}

/**
 * Get tgmpa install plugins page
 */
function auxin_get_tgmpa_plugins_page(){
    // Get instance
    $tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
    return $tgmpa_instance->install_plugins_page();
}

/**
 * Get our custom updates list
 *
 * @return array
 */
function auxin_get_update_list(){
    // General objects
    $bulk_list    = new stdClass;
    $last_checked = new stdClass;

    // Get plugin updates info
    $bulk_list->plugins = new stdClass;
    $last_checked->plugins_time = 0;
    if (  current_user_can( 'update_plugins' ) ) {
        $update_plugins = auxin_get_transient( 'auxin_update_plugins' );
        if ( isset( $update_plugins->response  ) && ! empty( $update_plugins->response ) ){
            $bulk_list->plugins = $update_plugins->response;
        }
        if(  isset( $update_plugins->last_checked ) ){
            $last_checked->plugins_time = $update_plugins->last_checked;
        }
    }

    // Get theme updates info
    $bulk_list->themes = new stdClass;
    $last_checked->themes_time = 0;
    if ( current_user_can( 'update_themes' ) ) {
        $update_themes = auxin_get_transient( 'auxin_update_themes' );
        if ( isset( $update_themes->response  ) && ! empty( $update_themes->response ) ){
            $bulk_list->themes = $update_themes->response;
        }
        if(  isset( $update_themes->last_checked ) ){
            $last_checked->themes_time = $update_themes->last_checked;
        }
    }

    // Set last checked in human diff time
    $get_last_checked_item    = $last_checked->themes_time >= $last_checked->plugins_time ? $last_checked->themes_time : $last_checked->plugins_time;
    $bulk_list->last_checked  = $get_last_checked_item !== 0 ?  human_time_diff( $get_last_checked_item ) : __( 'a long time', 'auxin-elements' );
    // Set total updates count
    $bulk_list->total_updates = count( (array) $bulk_list->plugins ) + count( (array) $bulk_list ->themes );

    return apply_filters( 'auxin_get_total_updates_list', $bulk_list );
}

/**
 * Get total updates number
 *
 * @return integer
 */
function auxin_get_total_updates(){
    $last_update  = auxin_get_update_list();
    return isset( $last_update->total_updates ) ? $last_update->total_updates : 0;
}

/**
 * Set The Default Category for post type
 *
 * @return integer
 */
function auxin_set_uncategorized_term ( $post_id, $post ) {
    $taxonomies = get_object_taxonomies( $post->post_type );
    foreach ( $taxonomies as $taxonomy ) {
        $terms = wp_get_post_terms( $post_id, $taxonomy );
        if ( empty( $terms ) ) {
            wp_set_object_terms( $post_id, 'uncategorized', $taxonomy );
        }
    }
}

/**
 * Import requested template from server
 *
 */
if ( ! function_exists('auxin_template_importer') ) {
    function auxin_template_importer( $template_ID = '', $template_type = '', $action = '' ) {

        $template_ID = sanitize_text_field( trim( $template_ID ) );
        $template_type = sanitize_text_field( trim( $template_type ) );

        if ( empty( $template_ID ) || empty( $template_type ) )
            return [
                'success'   => false,
                'data'      => [
                    'message' => __( 'Template ID or type is required.', 'auxin-elements')
                ]
            ];

        // Get All Templates data
        $templates_data = Auxin_Welcome::get_instance()->get_demo_list('templates');

        if ( empty( $templates_data ) ) {
            return [
                'success'   => false,
                'data'      => [
                    'message' => __( "An error occurred while updating templates library.", 'auxin-elements' )
                ]
            ];
        }

        // Find data of selected template
        $has_template = false;
        foreach ( $templates_data['templates'] as $key => $template_info ) {
            if ( $template_info['id'] == $template_ID && $template_info['type'] == $template_type ) {
                $has_template = true;
                $template = $template_info;
                break;
            }
        }

        if ( ! $has_template ){
            return [
                'success'   => false,
                'data'      => [
                    'message' => __( 'Template Not Found.', 'auxin-elements' )
                ]
            ];
        }

        // Import Template
        $template_data_key = sanitize_key( "auxin_template_kit_{$template_type}_data_for_origin_id_{$template_ID}" );
        $template_imported_id_key = sanitize_key( "auxin_template_kit_new_id_for_origin_{$template_ID}" );

        $template_imported_new_id = auxin_get_transient( $template_imported_id_key );

        // Check if the template is already imported and was not deleted
        if ( false !== $template_imported_new_id && ! in_array( get_post_status( $template_imported_new_id ), [ false, 'trash' ] ) ) {
            return [
                'success'   => true,
                'data'      => [
                    'message'       => __( 'Template is already imported.', 'auxin-elements' ),
                    'data'          => false,
                    'isImported'    => true,
                ]
            ];
        }

        if( false === $template_data = auxin_get_transient( $template_data_key ) ){

            // Retrieve the template data
            $template_data = Auxin_Demo_Importer::get_instance()->fetch_template_data( $template_ID );

            if( ! $template_data ){
                return [
                    'success' => false,
                    'data' => [
                        'message' => __( 'Connection error, please check your connection.', 'auxin-elements' )
                    ]
                ];
            }

            // Set transient for 48h
            auxin_set_transient( $template_data_key, $template_data, WEEK_IN_SECONDS );
        }


        $post_type = "elementor_library";
        $args = [
            'post_title'    => wp_strip_all_tags( $template['title'] ),
            'post_status'   => 'publish',
            'post_type'     => $post_type
        ];

        // Inserting template into database
        $post_id = wp_insert_post( $args );

        if( ! is_wp_error( $post_id ) ){

            // update menu name to use menu created by user
            if ( $action == 'update_menu' ) {
                if ( has_nav_menu('header-primary') && $template_type == 'header'){
                    $location = 'header-primary';
                } else if ( has_nav_menu('footer') && $template_type == 'footer' ){
                    $location = 'footer';
                } else {
                    $location = '';
                }

                $header_menu = ( ! empty( $location ) ) ? get_term( get_nav_menu_locations()[$location], 'nav_menu' ) : '';
                $template_data = ( ! empty( $header_menu ) ) ? preg_replace( '#"menu_slug":".+?(?=")"#', '"menu_slug":"'.$header_menu->name.'"', $template_data ) : $template_data;
            }

            $json_content = json_decode( $template_data , true );
            $elementor_version = defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '2.9.0';

            update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
            update_post_meta( $post_id, '_elementor_data', $json_content['content'] );
            update_post_meta( $post_id, '_elementor_version', $elementor_version );

            // Set page template
            if( ! empty( $template['page_tmpl'] ) ){
                update_post_meta( $post_id, '_wp_page_template', $template['page_tmpl'] );
            }

            // Set template type
            if( $post_type === 'elementor_library' ) {
                update_post_meta( $post_id, '_elementor_template_type', $template_type );
            }

            auxin_set_transient( $template_imported_id_key, $post_id, MONTH_IN_SECONDS );

            // Force to generate CSS file for this template
            Auxin_Demo_Importer::get_instance()->maybe_flush_post( $post_id );

            // NOTE: Here we can set new header or footer template az main header or footer
            return [
                'success'   => true,
                'data'      => [
                    'message' => __( 'Template Imported Successfully', 'auxin-elements'),
                    'postId' => $post_id,
                    'isImported' => false,
                    'postTitle'  => get_the_title( $post_id )
                ]
            ];

        } else {

            return [
                'success'   => false,
                'data'      => [
                    'message' => __( 'Error while saving the template.', 'auxin-elements')
                ]
            ];
        }
    }
}