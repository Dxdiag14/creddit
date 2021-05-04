<?php
/**
 * Add new post type and corresponding taxonomies
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


if( ! class_exists( 'Auxin_Post_Type_Base' ) ){


/**
 * Register custom post type and taxonomies
 *
 */
class Auxin_Post_Type_Base {

    /**
     * The custom post type name
     *
     * @var string
     */
    protected $post_type = '';

    /**
     * Prefix for theme mods
     *
     * @var string
     */
    protected $prefix = 'auxin_';

    /**
     * The instance of WP_Post_Type class
     *
     * @var WP_Post_Type
     */
    private $wp_post_type;


    function __construct( $post_type = '' ) {

        if( ! empty( $post_type ) ){
            $this->post_type = $post_type;
        }
        if( ! $this->post_type ){
            return;
        }

        // Filter the list of columns to print on the manage posts screen
        add_filter( "manage_edit-{$this->post_type}_columns", array( $this, 'manage_edit_columns' ) );

        // Filter the list of columns shown when listing posts of the post type
        add_action( "manage_{$this->post_type}_posts_custom_column",  array( $this, 'manage_posttype_custom_columns' ) );

        // Add post type capabilities
        add_action( 'admin_init', array( $this, 'assign_post_type_capabilities' ) );
    }


    /**
     * Retrieves/Returns the instance of WP_Post_Type class instead of current class
     *
     * @return void
     */
    public function __toString(){
        return $this->wp_post_type;
    }

    /**
     * Register post type & taxonomies instantly
     *
     * @return void
     */
    public function register() {
        // Register the post type and get corresponding WP_Post_Type instance
        $this->wp_post_type = $this->register_post_type();

        // Register the taxonomies
        $this->register_taxonomies();
    }

    /**
     * Register post type & taxonomies via init hook
     *
     * @return void
     */
    public function register_hooks() {
        // Add post types
        add_action( 'init', array( $this, 'register_post_type' ), 0 );

        // Add taxonomies
        add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
    }

    /**
     * Register post type
     *
     * @return void
     */
    public function register_post_type() { }


    /**
     * Register taxonomies
     *
     * @return void
     */
    public function register_taxonomies() { }


    /**
     * Customizing post type list Columns
     *
     * @param  array $column  An array of column name => label
     * @return array          List of columns shown when listing posts of the post type
     */
    public function manage_edit_columns( $columns ){ }


    /**
     * Applied to the list of columns to print on the manage posts screen for current post type
     *
     * @param  array $column  An array of column name => label
     * @return array          List of columns shown when listing posts of the post type
     */
    function manage_posttype_custom_columns( $column ){ }


    /**
     * Remove featured image box
     *
     * @return void
     */
    public function remove_thumbnail_box(){
        remove_meta_box( 'postimagediv', $this->post_type, 'side' );
    }

    /**
     * Auto assign custom post type capabilities
     *
     * @return void
     */
    public function assign_post_type_capabilities(){

        // check if custom capabilities are already added
        if( get_option( "auxin_{$this->post_type}_capabilities_added", 0 ) ){
            return;
        }

        global $wp_roles;

        if ( class_exists( 'WP_Roles' ) ) {
            if ( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
            }
        }

        if ( is_object( $wp_roles ) ) {

            $capabilities = array(
                "edit_{$this->post_type}",
                "read_{$this->post_type}",
                "delete_{$this->post_type}",
                "edit_{$this->post_type}s",
                "edit_others_{$this->post_type}s",
                "publish_{$this->post_type}s",
                "read_private_{$this->post_type}s",
                "delete_{$this->post_type}s",
                "delete_private_{$this->post_type}s",
                "delete_published_{$this->post_type}s",
                "delete_others_{$this->post_type}s",
                "edit_private_{$this->post_type}s",
                "edit_published_{$this->post_type}s",
                // Terms
                "manage_{$this->post_type}_terms",
                "edit_{$this->post_type}_terms",
                "delete_{$this->post_type}_terms",
                "assign_{$this->post_type}_terms"
            );

            foreach ( $capabilities as $cap ) {
                $wp_roles->add_cap( 'administrator', $cap );
                $wp_roles->add_cap( 'editor'       , $cap );
            }

            update_option( "auxin_{$this->post_type}_capabilities_added", 1, false );
        }

    }

}


}
