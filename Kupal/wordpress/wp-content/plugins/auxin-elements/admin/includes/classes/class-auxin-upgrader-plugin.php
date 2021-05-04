<?php

class Auxin_Upgrader_Plugin extends Plugin_Upgrader {

    protected $update_list;

	/**
	 * Bulk upgrade several plugins at once.
	 *
	 * @param array $plugins Array of the basename paths of the plugins' main files.
	 * @param array $args {
	 *     Optional. Other arguments for upgrading several plugins at once. Default empty array.
	 *
	 *     @type bool $clear_update_cache Whether to clear the plugin updates cache if successful.
	 *                                    Default true.
	 * }
	 * @return array|false An array of results indexed by plugin file, or false if unable to connect to the filesystem.
	 */
    public function start_upgrade( $plugins, $args = array() ) {

        $defaults = array(
            'clear_update_cache' => true,
        );
        $parsed_args = wp_parse_args( $args, $defaults );

        $this->init();
        $this->bulk = true;
        $this->upgrade_strings();

        $this->update_list = auxin_get_transient( 'auxin_update_plugins' );

        add_filter('upgrader_clear_destination', array($this, 'delete_old_plugin'), 10, 4);

        $this->skin->header();

        // Connect to the Filesystem first.
        $res = $this->fs_connect( array(WP_CONTENT_DIR, WP_PLUGIN_DIR) );
        if ( ! $res ) {
            $this->skin->footer();
            return false;
        }

        $this->skin->bulk_header();

        /*
         * Only start maintenance mode if:
         * - running Multisite and there are one or more plugins specified, OR
         * - a plugin with an update available is currently active.
         * @TODO: For multisite, maintenance mode should only kick in for individual sites if at all possible.
         */
        $maintenance = ( is_multisite() && ! empty( $plugins ) );
        foreach ( $plugins as $plugin )
            $maintenance = $maintenance || ( is_plugin_active( $plugin ) && isset( $this->update_list->response[ $plugin ] ) );
        if ( $maintenance )
            $this->maintenance_mode(true);

        $results     = array();
        $api_request = new Auxin_Upgrader_Http_Api;

        $this->update_count = count($plugins);
        $this->update_current = 0;
        foreach ( $plugins as $plugin ) {
            $this->update_current++;
            $this->skin->plugin_info = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin, false, true);

            // Fix VC issue with auto update
            if( $plugin === 'js_composer/js_composer.php' ) {
                add_filter( 'upgrader_pre_download',  '__return_false', 9999);
            }

            if ( !isset( $this->update_list->response[ $plugin ] ) ) {
                $this->skin->set_result('up_to_date');
                $this->skin->before();
                $this->skin->feedback('up_to_date');
                $this->skin->after();
                $results[$plugin] = true;
                continue;
            }

            // Get the URL to the zip file.
            $r = $this->update_list->response[ $plugin ];

            $this->skin->plugin_active = is_plugin_active($plugin);

            if( ! wp_http_validate_url( $r['package'] ) && $r['package'] == 'AUXIN_GET_DOWNLOAD_URL' ){
                $r['slug'] =  ( $r['slug'] == 'masterslider' ) ? 'masterslider-wp' : $r['slug'];
                $downlaod_link = $api_request->get_download_link( $r['slug'] );
                if( ! is_wp_error( $downlaod_link ) ){
                    $r['package'] = $downlaod_link;
                }
            }

            $result = $this->run( array(
                'package'           => $r['package'],
                'destination'       => WP_PLUGIN_DIR,
                'clear_destination' => true,
                'clear_working'     => true,
                'is_multi'          => true,
                'hook_extra'        => array(
                    'plugin' => $plugin
                )
            ) );

            $results[$plugin] = $this->result;

            // Prevent credentials auth screen from displaying multiple times
            if ( false === $result )
                break;
        } //end foreach $plugins

        $this->maintenance_mode(false);

        // Force refresh of plugin update information.
        $this->clean_plugins_cache( $parsed_args['clear_update_cache'] );

        $this->skin->bulk_footer();

        $this->skin->footer();

        // Cleanup our hooks, in case something else does a upgrade on this connection.
        remove_filter('upgrader_clear_destination', array($this, 'delete_old_plugin'));

        return $results;
    }

    /**
     * Clears the Plugins cache used by get_plugins() and by default, the Plugin Update cache.
     *
     * @param bool $clear_update_cache Whether to clear the Plugin updates cache
     */
    private function clean_plugins_cache( $clear_update_cache = true ) {
        if ( $clear_update_cache === true ){
            auxin_delete_transient( 'auxin_update_plugins' );
        } elseif( $clear_update_cache === 'last_checked' ){
            // This will remove 'last_checked' value for list refresh
            if( isset( $this->update_list->last_checked ) ) {
                unset( $this->update_list->last_checked );
            }
            auxin_set_transient( 'auxin_update_plugins', $this->update_list );
        }
        wp_cache_delete( 'plugins', 'plugins' );
    }

}