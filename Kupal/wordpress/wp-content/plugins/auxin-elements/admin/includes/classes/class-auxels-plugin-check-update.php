<?php
/**
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}


class AUXELS_Plugin_Check_Update {
    /**
     * The plugin current version
     * @var string
     */
    public $current_version;

    /**
     * The plugin remote update path
     * @var string
     */
    public $update_path;

    /**
     * Plugin Slug (plugin_directory/plugin_file.php)
     * @var string
     */
    public $plugin_slug;

    /**
     * Plugin name (plugin_file)
     * @var string
     */
    public $slug;

    /**
     * The item name while requesting to update api
     * @var string
     */
    public $request_name;

    /**
     * The item ID in marketplace
     * @var string
     */
    public $plugin_id;


    /**
     * The item name while requesting to update api
     * @var string
     */
    public $plugin_file_path;

    /**
     * The item name while requesting to update api
     * @var string
     */
    public $banners;


    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $current_version
     * @param string $update_path
     * @param string $plugin_slug
     * @param string $slug
     */
    function __construct( $current_version, $update_path, $plugin_slug, $slug, $item_request_name = '' ) {
        // Set the class public variables
        $this->current_version  = $current_version;
        $this->update_path      = $update_path;
        $this->plugin_slug      = $plugin_slug;
        $this->slug             = $slug;

        $this->request_name     = empty( $item_request_name ) ? $this->slug : $item_request_name;

        // define the alternative API for checking for updates
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update') );
    }


    /**
     * Check the plugin version on update plugin transient expired
     *
     * @param $transient
     * @return object $ transient
     */
    public function check_update( $transient ) {
        // Get the remote version
        $remote_version = $this->get_remote_version();

        return $transient;
    }


    /**
     * Return the remote version
     * @return string $remote_version
     */
    public function get_remote_version() {
        global $wp_version;

        $theme_data = wp_get_theme();
        if( is_child_theme() ) {
            $theme_data = wp_get_theme( $theme_data->template );
        }

        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        if( ! isset( $all_plugins[ $this->plugin_slug ] ) || empty( $all_plugins[ $this->plugin_slug ] ) ){
            return;
        }

        $this_plugin = $all_plugins[ $this->plugin_slug ];
        if( ! is_array( $this_plugin ) ){
            $this_plugin = array();
        }
        $this_plugin['ID']        = $this->plugin_id;
        $this_plugin['Name']      = defined('THEME_NAME') ? THEME_NAME : 'Phlox';
        $this_plugin['Theme']     = $theme_data->Name;
        $this_plugin['Version']   = defined('THEME_VERSION') ? THEME_VERSION : $theme_data->Version;
        $this_plugin['Slug']      = defined('THEME_ID') ? THEME_ID : $this->slug;
        $this_plugin['Activated'] = 0;
        $this_plugin['client_key'] = get_theme_mod( 'client_key', '');

        $args = array(
            'user-agent' => 'WordPress/'.$wp_version.'; '. get_site_url(),
            'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3),
            'body'       => array(
                'cat'       => 'version-check',
                'action'    => 'final',
                'type'      => 'plugin',
                'item-name' => $this->request_name,
                'item-info' => $this_plugin
            )
        );
        $args = apply_filters( 'auxels_version_check_args', $args );
        $request = wp_remote_post( $this->update_path, $args );

        if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
            return $request['body'];
        }
        return false;
    }
}
