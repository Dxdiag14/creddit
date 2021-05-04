<?php
/**
 * Adding options and capabilities while theme is installed
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;



class Auxin_Install {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance  = null;


    function __construct(){
        // Add theme capabilities on theme activation
        add_action( 'after_switch_theme', array( $this, 'install' ) );
        // will be @deprecated in version 2.0
        add_action( 'init', array( $this, 'install' ) );
    }


    /**
     * Install theme requirements
     */
    public function install(){
        $this->add_capabilities();
    }


    /**
     * Add theme custom capabilities
     */
    public function add_capabilities() {

        // check if custom capabilities are added before or not
        if( get_theme_mod( 'auxin_capabilities_added', 0 ) ){
            return;
        }

        global $wp_roles;

        if ( class_exists( 'WP_Roles' ) ) {
            if ( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
            }
        }

        if ( is_object( $wp_roles ) ) {

            $capabilities = $this->get_theme_capabilities();

            foreach ( $capabilities as $cap_group ) {
                foreach ( $cap_group as $cap ) {
                    $wp_roles->add_cap( 'administrator', $cap );
                    $wp_roles->add_cap( 'editor'       , $cap );
                }
            }
        }

        set_theme_mod( 'auxin_capabilities_added', 1 );
    }



    /**
     * Get capabilities for auxin - Will be assigned during theme activation
     *
     * @return array
     */
    public function get_theme_capabilities() {

        $capabilities      = array();
        $active_post_types = auxin_get_possible_post_types(true);

        foreach ( $active_post_types as $post_type => $is_active ) {

            $capabilities[ $post_type ] = array(
                // Post type
                "edit_{$post_type}",
                "read_{$post_type}",
                "delete_{$post_type}",
                "edit_{$post_type}s",
                "edit_others_{$post_type}s",
                "publish_{$post_type}s",
                "read_private_{$post_type}s",
                "delete_{$post_type}s",
                "delete_private_{$post_type}s",
                "delete_published_{$post_type}s",
                "delete_others_{$post_type}s",
                "edit_private_{$post_type}s",
                "edit_published_{$post_type}s",

                // Terms
                "manage_{$post_type}_terms",
                "edit_{$post_type}_terms",
                "delete_{$post_type}_terms",
                "assign_{$post_type}_terms"
            );

        }

        return $capabilities;
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

