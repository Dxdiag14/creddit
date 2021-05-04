<?php

class Auxin_Upgrader_Ajax_Handlers {

    protected $skin;

    function __construct(){
        // Include upgrader class
        include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        $this->skin = new WP_Ajax_Upgrader_Skin();
    }

    public function run( $key, $type ){
        switch ( $type ) {
            case 'themes':
                $this->update_theme( $key );
                break;

            case 'plugins':
                $this->update_plugin( $key );
                break;
            default:
                wp_send_json_error(  array(
                    'slug'         => '',
                    'errorCode'    => 'invalid_update_type',
                    'errorMessage' => __( 'Please enter a valid update type.', 'auxin-elements' )
                ) );
        }
    }

    /**
     * Ajax handler for updating a plugin.
     *
     * @see Auxin_Upgrader_Plugin
     *
     * @global WP_Filesystem_Base $wp_filesystem Subclass
     */
    private function update_plugin( $plugin ) {
        $plugin = plugin_basename( sanitize_text_field( wp_unslash( $plugin ) ) );

        $status = array(
            'update'     => 'plugin',
            'slug'       => sanitize_key( wp_unslash( dirname( $plugin ) ) )
        );

        if ( ! current_user_can( 'update_plugins' ) || 0 !== validate_file( $plugin ) ) {
            $status['errorMessage'] = __( 'Sorry, you are not allowed to update plugins for this site.' );
            wp_send_json_error( $status );
        }

        $upgrader = new Auxin_Upgrader_Plugin( $this->skin );
        $result   = $upgrader->start_upgrade( array( $plugin ), array(
            'clear_update_cache' => 'last_checked'
        ) );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $status['debug'] = $this->skin->get_upgrade_messages();
        }

        if ( is_wp_error( $this->skin->result ) ) {
            $status['errorCode']    = $this->skin->result->get_error_code();
            $status['errorMessage'] = $this->skin->result->get_error_message();
            wp_send_json_error( $status );
        } elseif ( $this->skin->get_errors()->get_error_code() ) {
            $status['errorMessage'] = $this->skin->get_error_messages();
            wp_send_json_error( $status );
        } elseif ( is_array( $result ) && ! empty( $result[ $plugin ] ) ) {
            $plugin_update_data = current( $result );

            /*
             * If the `update_plugins` site transient is empty (e.g. when you update
             * two plugins in quick succession before the transient repopulates),
             * this may be the return.
             *
             * Preferably something can be done to ensure `update_plugins` isn't empty.
             * For now, surface some sort of error here.
             */
            if ( true === $plugin_update_data ) {
                $status['errorMessage'] = __( 'Plugin update failed.' );
                wp_send_json_error( $status );
            }

            $status['successMessage'] = __( 'Plugin updated successfully.' );
            wp_send_json_success( $status );
        } elseif ( false === $result ) {
            global $wp_filesystem;

            $status['errorCode']    = 'unable_to_connect_to_filesystem';
            $status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.' );

            // Pass through the error from WP_Filesystem if one was raised.
            if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
                $status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
            }

            wp_send_json_error( $status );
        }

        // An unhandled error occurred.
        $status['errorMessage'] = __( 'Plugin update failed.' );
        wp_send_json_error( $status );
    }

    /**
     * Ajax handler for updating a theme.
     *
     * @see Auxin_Upgrader_Theme
     *
     * @global WP_Filesystem_Base $wp_filesystem Subclass
     */
    private function update_theme( $stylesheet ) {
        $stylesheet = preg_replace( '/[^A-z0-9_\-]/', '', wp_unslash( $stylesheet ) );

        $status = array(
            'update'     => 'theme',
            'slug'       => sanitize_key( wp_unslash( dirname( $stylesheet ) ) )
        );

        if ( ! current_user_can( 'update_themes' ) ) {
            $status['errorMessage'] = __( 'Sorry, you are not allowed to update themes for this site.' );
            wp_send_json_error( $status );
        }

        $upgrader = new Auxin_Upgrader_Theme( $this->skin );
        $result   = $upgrader->start_upgrade( array( $stylesheet ), array(
            'clear_update_cache' => 'last_checked'
        ) );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $status['debug'] = $this->skin->get_upgrade_messages();
        }

        if ( is_wp_error( $this->skin->result ) ) {
            $status['errorCode']    = $this->skin->result->get_error_code();
            $status['errorMessage'] = $this->skin->result->get_error_message();
            wp_send_json_error( $status );
        } elseif ( $this->skin->get_errors()->get_error_code() ) {
            $status['errorMessage'] = $this->skin->get_error_messages();
            wp_send_json_error( $status );
        } elseif ( is_array( $result ) && ! empty( $result[ $stylesheet ] ) ) {

            // Theme is already at the latest version.
            if ( true === $result[ $stylesheet ] ) {
                $status['errorMessage'] = $upgrader->strings['up_to_date'];
                wp_send_json_error( $status );
            }

            $status['successMessage'] = __( 'Theme updated successfully.' );
            wp_send_json_success( $status );
        } elseif ( false === $result ) {
            global $wp_filesystem;

            $status['errorCode']    = 'unable_to_connect_to_filesystem';
            $status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.' );

            // Pass through the error from WP_Filesystem if one was raised.
            if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
                $status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
            }

            wp_send_json_error( $status );
        }

        // An unhandled error occurred.
        $status['errorMessage'] = __( 'Update failed.' );
        wp_send_json_error( $status );
    }

}