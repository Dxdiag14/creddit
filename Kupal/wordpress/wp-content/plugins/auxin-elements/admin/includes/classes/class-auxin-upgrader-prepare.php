<?php

class Auxin_Upgrader_Prepare {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;
    protected $upgrade_types   = array(
        'plugins' => 'update_plugins',
        'themes'  => 'update_themes'
    );
    protected $api = 'http://api.averta.net/envato/items/';

    function __construct(){
        add_filter( 'site_transient_update_plugins', array( $this, 'disable_update_plugins' ) );
        add_filter( 'site_transient_update_themes',  array( $this, 'disable_update_themes'  ) );

        add_action( 'load-plugins.php',     array( $this, 'update_plugins'    ) );
        add_action( 'load-update.php',      array( $this, 'update_plugins'    ) );
        add_action( 'load-update-core.php', array( $this, 'update_plugins'    ) );
        add_action( 'wp_update_plugins',    array( $this, 'update_plugins'    ) );

        add_action( 'load-themes.php',      array( $this, 'update_themes'     ) );
        add_action( 'load-update.php',      array( $this, 'update_themes'     ) );
        add_action( 'load-update-core.php', array( $this, 'update_themes'     ) );
        add_action( 'wp_update_themes',     array( $this, 'update_themes'     ) );

        add_action( 'admin_init',           array( $this, 'maybe_update_list' ) );
    }

    /**
     * Remove auxin plugins from wp auto update
     *
     * @return object
     */
    public function disable_update_plugins( $transient ) {
        // Pass plugins list with their slug e.g. array( 'auxin-elements' )
        $plugins = apply_filters( 'auxin_disable_plugins_updates', array() );
        if ( isset($transient) && is_object($transient) && ! empty( $plugins ) ) {
            foreach ( $plugins as $key => $plugin ) {
                $pluginPath = $plugin . '/' . $plugin . '.php';
                if ( isset( $transient->response[$pluginPath] ) ) {
                    unset( $transient->response[$pluginPath] );
                }
            }
        }
        return $transient;
    }

    /**
     * Remove auxin themes from wp auto update
     *
     * @return object
     */
    public function disable_update_themes( $transient ) {
        // Pass themes list with their slug e.g. array( 'phlox' )
        $themes = apply_filters( 'auxin_disable_themes_updates', array() );
        if ( isset($transient) && is_object($transient) && ! empty( $themes  ) ) {
            foreach ( $themes as $theme ) {
                if ( isset( $transient->response[$theme] ) ) {
                    unset( $transient->response[$theme] );
                }
            }
        }
        return $transient;
    }

    /**
     * Check theme versions against the latest versions hosted on WordPress.org. & Averta API
     *
     * @return void
     */
    public function update_themes(){
        if ( wp_installing() ) {
            return;
        }

        $get_themes   = $this->get_themes();

        $last_update  = auxin_get_transient( 'auxin_update_themes' );
        if ( ! is_object($last_update) ) {
            $last_update = new stdClass;
        }

        $new_option = new stdClass;
        $new_option->last_checked = time();

        $doing_cron = wp_doing_cron();

        // Check for update on a different schedule, depending on the page.
        switch ( current_filter() ) {
            case 'upgrader_process_complete' :
                $timeout = 0;
                break;
            case 'load-update-core.php' :
                $timeout = MINUTE_IN_SECONDS;
                break;
            case 'load-themes.php' :
            case 'load-update.php' :
                $timeout = HOUR_IN_SECONDS;
                break;
            default :
                if ( $doing_cron ) {
                    $timeout = 2 * HOUR_IN_SECONDS;
                } else {
                    $timeout = 12 * HOUR_IN_SECONDS;
                }
        }

        $time_not_changed = isset( $last_update->last_checked ) && $timeout > ( time() - $last_update->last_checked );

        if ( $time_not_changed ) {
            $theme_changed = false;
            foreach ( $get_themes as $slug => $data ) {
                $new_option->checked[ $slug ] = $data->get( 'Version' );

                if ( !isset( $last_update->checked[ $slug ] ) || strval($last_update->checked[ $slug ]) !== strval($data->get( 'Version' )) ){
                    $theme_changed = true;
                }
            }

            if ( isset ( $last_update->response ) && is_array( $last_update->response ) ) {
                foreach ( $last_update->response as $slug => $update_details ) {
                    if ( ! isset($checked[ $slug ]) ) {
                        $theme_changed = true;
                        break;
                    }
                }
            }

            // Bail if we've checked recently and if nothing has changed
            if ( ! $theme_changed ) {
                return;
            }
        }

        // Update last_checked for current to prevent multiple blocking requests if request hangs
        $last_update->last_checked = time();
        auxin_set_transient( 'auxin_update_themes', $last_update );

        // Include plugins install
        if ( ! function_exists( 'themes_api' ) ){
            require_once ABSPATH . 'wp-admin/includes/theme.php';
        }

        foreach ( $get_themes as $slug => $data ) {

            if( $data->isOfficial ) {
                $response = themes_api( 'theme_information', array(
                    'slug'   => sanitize_key( $slug ),
                    'fields' => array(
                        'sections' => false,
                    ),
                ) );
                if ( ! is_wp_error( $response ) ) {
                    if( version_compare( $response->version, $data->get( 'Version' ), '>' ) ){
                        $new_option->response[ $data->get_stylesheet() ] = array(
                            'slug'    => esc_sql($slug),
                            'version' => esc_sql($response->version),
                            'package' => esc_url($response->download_link)
                        );
                    }
                }
            } else {
                // Get version number of our api
                $new_version = $this->remote_get( array(
                    'cat'       => 'version-check',
                    'action'    => 'final',
                    'item-name' => sanitize_key( $slug )
                ) );

                if( ! empty( $new_version ) && version_compare( $new_version, $data->get( 'Version' ), '>' ) ){
                    $new_option->response[ $data->get_stylesheet() ] = array(
                        'slug'    => esc_sql($slug),
                        'version' => esc_sql($new_version),
                        'package' => 'AUXIN_GET_DOWNLOAD_URL'
                    );
                }
            }

        }

        auxin_set_transient( 'auxin_update_themes', $new_option );
    }

    /**
     * Check plugin versions against the latest versions hosted on WordPress.org. & Averta API
     *
     * @return void
     */
    public function update_plugins(){
        if ( wp_installing() ) {
            return;
        }

        $get_plugins  = $this->get_plugins();

        $current    = auxin_get_transient( 'auxin_update_plugins' );
        if ( ! is_object($current) ) {
            $current = new stdClass;
        }

        $new_option = new stdClass;
        $new_option->last_checked = time();

        $doing_cron = wp_doing_cron();
        // Check for update on a different schedule, depending on the page.
        switch ( current_filter() ) {
            case 'upgrader_process_complete' :
                $timeout = 0;
                break;
            case 'load-update-core.php' :
                $timeout = MINUTE_IN_SECONDS;
                break;
            case 'load-plugins.php' :
            case 'load-update.php' :
                $timeout = HOUR_IN_SECONDS;
                break;
            default :
                if ( $doing_cron ) {
                    $timeout = 2 * HOUR_IN_SECONDS;
                } else {
                    $timeout = 12 * HOUR_IN_SECONDS;
                }
        }

        $time_not_changed = isset( $current->last_checked ) && $timeout > ( time() - $current->last_checked );
        if ( $time_not_changed ) {
            $plugin_changed = false;
            foreach ( $get_plugins as $path => $data ) {
                $new_option->checked[ $path ] = $data['Version'];

                if ( !isset( $current->checked[ $path ] ) || strval($current->checked[ $path ]) !== strval($data['Version']) ){
                    $plugin_changed = true;
                }
            }

            if ( isset ( $current->response ) && is_array( $current->response) ) {
                foreach ( $current->response as $plugin_file => $update_details ) {
                    if ( ! isset($get_plugins[ $plugin_file ]) ) {
                        $plugin_changed = true;
                        break;
                    }
                }
            }

            // Bail if we've checked recently and if nothing has changed
            if ( ! $plugin_changed ) {
                return;
            }
        }

        // Update last_checked for current to prevent multiple blocking requests if request hangs
        $current->last_checked = time();
        auxin_set_transient( 'auxin_update_plugins', $current  );

        // Include plugins install
        if ( ! function_exists( 'plugins_api' ) ){
            include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        }

        foreach ( $get_plugins as $path => $data ) {
            $slug = dirname( $path );

            if( $data['isOfficial'] ) {
                $response = plugins_api( 'plugin_information', array(
                    'slug'   => sanitize_key( $slug ),
                    'fields' => array(
                        'sections' => false,
                    ),
                ) );
                if ( ! is_wp_error( $response ) ) {
                    if( version_compare( $response->version, $data['Version'], '>' ) ){
                        $new_option->response[ $path ] = array(
                            'slug'    => esc_sql($slug),
                            'version' => esc_sql($response->version),
                            'package' => esc_url($response->download_link)
                        );
                    }
                }
            } else {
                // Get version number of our api
                $new_version = $this->remote_get( array(
                    'cat'       => 'version-check',
                    'action'    => 'final',
                    'item-name' => sanitize_key( $slug )
                ) );

                if( ! empty( $new_version ) && version_compare( $new_version, $data['Version'], '>' ) ){
                    $new_option->response[ $path ] = array(
                        'slug'    => esc_sql($slug),
                        'version' => esc_sql($new_version),
                        'package' => 'AUXIN_GET_DOWNLOAD_URL'
                    );
                }
            }
        }

        auxin_set_transient( 'auxin_update_plugins', $new_option  );
    }

    /**
     * Get averta group plugins list
     *
     * @return array
     */
    public function get_plugins(){
        // If running blog-side, bail unless we've not checked in the last 12 hours
        if ( ! function_exists( 'get_plugins' ) ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $plugins_list  = get_plugins();
        $regex_pattern = apply_filters( 'auxin_averta_plugins_regex', '(auxin|phlox)' );
        foreach ( $plugins_list as $path => $args ) {
            if( ! preg_match( $regex_pattern, $path ) ) {
                unset( $plugins_list[ $path ] );
                continue;
            }
            if( $this->is_official( 'plugins', $path ) ){
                $plugins_list[ $path ]['isOfficial'] = true;
            } else {
                if( defined('THEME_PRO' ) && THEME_PRO && auxin_is_activated() ) {
                    $plugins_list[ $path ]['isOfficial'] = false;
                } else {
                    unset( $plugins_list[ $path ] );
                    continue;
                }
            }
        }
        return apply_filters( 'auxin_get_plugins_list', $plugins_list );
    }

    /**
     * Get averta group themes list
     *
     * @return array
     */
    public function get_themes(){
        $themes_list   = wp_get_themes();
        $regex_pattern = apply_filters( 'auxin_averta_themes_regex', '(auxin|phlox)' );
        foreach ( $themes_list as $path => $args ) {
            if( ! preg_match( $regex_pattern, $path ) ) {
                unset( $themes_list[ $path ] );
                continue;
            }
            if( $this->is_official( 'themes', $path ) ){
                $themes_list[ $path ]->isOfficial = true;
            } else {
                if( defined('THEME_PRO' ) && THEME_PRO && auxin_is_activated() ) {
                    $themes_list[ $path ]->isOfficial = false;
                } else {
                    unset( $themes_list[ $path ] );
                    continue;
                }
            }
        }
        return apply_filters( 'auxin_get_themes_list', $themes_list );
    }

    /**
     * Check official plugins
     *
     * @param string $type
     * @param string $slug
     * @return boolean
     */
    private function is_official( $type, $slug ){
        switch ( $type ) {
            case 'themes':
                $official = apply_filters( 'auxin_official_themes', array() );
                if ( in_array ( $slug, $official ) ) {
                    return true;
                }
                break;

            case 'plugins':
                $official = apply_filters( 'auxin_official_plugins', array() );
                // Convert path to slug
                if( strpos( $slug, '.php' ) ) {
                    $slug = dirname( $slug );
                }
                if( in_array ( $slug, $official ) ) {
                    return true;
                }
                break;

            default:
                return false;
                break;
        }

        return false;
    }

    /**
     * General remote get function
     *
     * @param array $request_args
     * @return void
     */
    public function remote_get( $request_args ){
        $url = add_query_arg(
            $request_args,
            $this->api
        );

        $request = wp_remote_get( $url );

        if ( is_wp_error( $request ) ) {
            return false;
        }

        return wp_remote_retrieve_body( $request );
    }

    /**
     * Check the last time upgrades were run before checking plugin versions.
     *
     * @return void
     */
    public function maybe_update_list() {

        foreach ( $this->upgrade_types as $key => $type ) {
            // Set transient key name
            $transient_key = 'auxin_' . $type;
            // This will remove update data transient
            if( isset( $_GET['force-check'] ) && $_GET['force-check'] == 1 ){
                auxin_delete_transient( $transient_key );
            } else {
                // Otherwise check for last time upgrades
                $current = auxin_get_transient( $transient_key );
                if ( isset( $current->last_checked ) && 12 * HOUR_IN_SECONDS > ( time() - $current->last_checked ) ){
                    continue;
                }
            }
            // Any update? So run the upgrade callbacks
            $this->$type();
        }

        return;
    }

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


}