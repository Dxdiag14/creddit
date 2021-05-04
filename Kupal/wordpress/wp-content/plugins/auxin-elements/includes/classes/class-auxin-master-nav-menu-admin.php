<?php
/**
 * Main class for adding configurations and custom menu item fields in admin area
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
class Auxin_Master_Nav_Menu_Admin {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance  = null;


    function __construct(){

        // Back-end modification hooks

        // Add extra fields to menu items in back-end menu editor
        add_filter( 'wp_setup_nav_menu_item' , array( $this, 'add_custom_nav_item_fields'     ) );

        // Save and update the value of custom menu fields
        add_action( 'wp_update_nav_menu_item', array( $this, 'update_backend_nav_menu_fields' ), 9, 3 );

        // Change the walker class for back-end  menu editor
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'change_nav_menu_backend_walker' ), 1000000000, 1 ); // the high priority is a fix for "popup-maker" plugin


        // Register stylesheet and javascript for edit menu page
        add_action( 'admin_menu'             , array( $this, 'enqueue_edit_menu_assests'      ) );

    }

    /* Back End
    ==========================================================================*/

    /**
     * Adds all custom fields to menu item object in back-end
     *
     * @param object $menu_item The menu item object
     */
    public function add_custom_nav_item_fields( $menu_item ){

        // Loop through all custom fields and add them to menu item object
        foreach ( $this->menu_item_fields as $field_id => $field_info ) {
            $menu_item->{$field_id} = get_post_meta( $menu_item->ID, '_menu_item_' . $field_id , true );
        }

        return $menu_item;
    }

    /**
     * Saves and updates the value of custom menu item fields
     */
    public function update_backend_nav_menu_fields( $menu_id, $menu_item_db_id, $args ){

        foreach ( $this->menu_item_fields as $field_id => $field_info ){

            // considering exception for checkbox field
            if( 'switch' == $field_info['type'] ) {

                if( ! isset( $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] ) ){
                    $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] = '0';
                } else {
                    $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] = '1';
                }

            } elseif( ! isset( $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] ) ){
                $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] = '';
            }

            // save custom style in custom css file
            if( 'custom_style' == $field_id ){

                if( $custom_style = $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] ){
                    auxin_add_custom_css( ".menu-item-$menu_item_db_id { $custom_style }", 'menu-item-' . $menu_item_db_id );
                } else {
                    auxin_remove_custom_css( 'menu-item-' . $menu_item_db_id );
                }

            }

            update_post_meta( $menu_item_db_id, '_menu_item_'. $field_id , $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] );
        }

    }

    /**
     * Modifies the walker class of back-end menu editor
     */
    public function change_nav_menu_backend_walker( $walker ){
        return 'Auxin_Walker_Nav_Menu_Back';
    }

    /**
     * Loads specific asset files only on edit menu page
     */
    public function enqueue_edit_menu_assests(){
        global $pagenow;

        if( 'nav-menus.php' == $pagenow ){
            wp_enqueue_style ( 'auxin-edit-menus-css' , ADMIN_CSS_URL . 'other/edit-menus.css' , NULL, '1.1' );
            wp_enqueue_script( 'auxin-edit-menus-js'  , ADMIN_JS_URL  . 'solo/edit-menus.js'   , array('jquery'), '1.1', true );
        }
    }

    /* Get methods
    ==========================================================================*/

    /**
     * Magic method to get the value of accessible properties
     *
     * @param   string   The property name
     * @return  string  The value of property
     */
    public function __get( $name ){

        // Retrieve the menu item fields from Master Menu class
        if( 'menu_item_fields' ==  $name ){
            return Auxin()->Master_Menu->menu_item_fields;
        }

        if( property_exists( $this, $name ) ){
            return $this->$name;
        }

        return null;
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
