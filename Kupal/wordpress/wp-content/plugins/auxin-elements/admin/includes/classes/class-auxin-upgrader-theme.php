<?php

class Auxin_Upgrader_Theme extends Theme_Upgrader {

    protected $update_list;

	/**
	 * Upgrade several themes at once.
	 *
	 * @param array $themes The theme slugs.
	 * @param array $args {
	 *     Optional. Other arguments for upgrading several themes at once. Default empty array.
	 *
	 *     @type bool $clear_update_cache Whether to clear the update cache if successful.
	 *                                    Default true.
	 * }
	 * @return array[]|false An array of results, or false if unable to connect to the filesystem.
	 */
    public function start_upgrade( $themes, $args = array() ) {

        $defaults = array(
            'clear_update_cache' => true,
        );
        $parsed_args = wp_parse_args( $args, $defaults );

        $this->init();
        $this->bulk = true;
        $this->upgrade_strings();

        $this->update_list = auxin_get_transient( 'auxin_update_themes' );

        add_filter('upgrader_pre_install', array($this, 'current_before'), 10, 2);
        add_filter('upgrader_post_install', array($this, 'current_after'), 10, 2);
        add_filter('upgrader_clear_destination', array($this, 'delete_old_theme'), 10, 4);

        $this->skin->header();

        // Connect to the Filesystem first.
        $res = $this->fs_connect( array(WP_CONTENT_DIR) );
        if ( ! $res ) {
            $this->skin->footer();
            return false;
        }

        $this->skin->bulk_header();

        // Only start maintenance mode if:
        // - running Multisite and there are one or more themes specified, OR
        // - a theme with an update available is currently in use.
        // @TODO: For multisite, maintenance mode should only kick in for individual sites if at all possible.
        $maintenance = ( is_multisite() && ! empty( $themes ) );
        foreach ( $themes as $theme )
            $maintenance = $maintenance || $theme == get_stylesheet() || $theme == get_template();
        if ( $maintenance )
            $this->maintenance_mode(true);

        $results      = array();
        $api_request  = new Auxin_Upgrader_Http_Api;

        $this->update_count = count($themes);
        $this->update_current = 0;
        foreach ( $themes as $theme ) {
            $this->update_current++;

            $this->skin->theme_info = $this->theme_info($theme);

            if ( !isset( $this->update_list->response[ $theme ] ) ) {
                $this->skin->set_result(true);
                $this->skin->before();
                $this->skin->feedback( 'up_to_date' );
                $this->skin->after();
                $results[$theme] = true;
                continue;
            }

            // Get the URL to the zip file
            $r = $this->update_list->response[ $theme ];

            if( ! wp_http_validate_url( $r['package'] ) && $r['package'] == 'AUXIN_GET_DOWNLOAD_URL' ){
                $downlaod_link = $api_request->get_download_link( $r['slug'] );
                if( ! is_wp_error( $downlaod_link ) ){
                    $r['package'] = $downlaod_link;
                }
            }

            $result = $this->run( array(
                'package'           => $r['package'],
                'destination'       => get_theme_root( $theme ),
                'clear_destination' => true,
                'clear_working'     => true,
                'is_multi'          => true,
                'hook_extra'        => array(
                    'theme' => $theme
                ),
            ) );

            $results[$theme] = $this->result;

            // Prevent credentials auth screen from displaying multiple times
            if ( false === $result )
                break;
        } //end foreach $themes

        $this->maintenance_mode(false);

        // Refresh the Theme Update information
        $this->clean_themes_cache( $parsed_args['clear_update_cache'] );

        $this->skin->bulk_footer();

        $this->skin->footer();

        // Cleanup our hooks, in case something else does a upgrade on this connection.
        remove_filter('upgrader_pre_install', array($this, 'current_before'));
        remove_filter('upgrader_post_install', array($this, 'current_after'));
        remove_filter('upgrader_clear_destination', array($this, 'delete_old_theme'));

        return $results;
    }

    /**
     * Clears the cache held by get_theme_roots() and WP_Theme.
     *
     * @param bool $clear_update_cache Whether to clear the Theme updates cache
     */
    private function clean_themes_cache( $clear_update_cache = true ) {
        if ( $clear_update_cache === true ){
            auxin_delete_transient( 'auxin_update_themes' );
        } elseif( $clear_update_cache === 'last_checked' ){
            // This will remove 'last_checked' value for list refresh
            if( isset( $this->update_list->last_checked ) ) {
                unset( $this->update_list->last_checked );
            }
            auxin_set_transient( 'auxin_update_themes', $this->update_list );
        }
        search_theme_directories( true );
        foreach ( wp_get_themes( array( 'errors' => null ) ) as $theme ){
            $theme->cache_delete();
        }
    }

}