<?php
/**
 * Main Admin Class
 *
 * @echo HEADER
 */

// no direct access allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}


/**
 * This class is used to work with the
 * administrative side of the plugin
 */
class AUXELS_Admin {

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

   /**
    * Slug of the plugin screen.
    *
    * @since    1.0.0
    *
    * @var      string
    */
    protected $screen_hook_suffix = null;


    /**
     * Initialize the plugin by loading admin classes and functions
     *
     */
    private function __construct() {

        // include admin files
        $this->includes();

        $this->enqueue_admin_scripts();
    }


    /**
     * Include admin essential classes and functions
     *
     * @return void
     */
    private function includes(){
        include_once( AUXELS_ADMIN_DIR . '/includes/index.php' );
    }



  /**
   * Return an instance of this class.
   *
   * @since     1.0.0
   *
   * @return    object    A single instance of this class.
   */
  public static function get_instance() {

    if( apply_filters( 'auxels_access_only_for_super_admins' , 0 ) && ! is_super_admin() ) {
      return;
    }

    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }


  /**
   * Register and enqueue admin-specific JavaScript & Stylesheet.
   *
   * @since     1.0.0
   *
   * @return    null    Return early if no settings page is registered.
   */
  public function enqueue_admin_scripts() {

    $admin_assets = new Auxels_Admin_Assets();

    if ( ! isset( $this->screen_hook_suffix ) )
      return;

    $screen = get_current_screen();
    if ( $this->screen_hook_suffix == $screen->id ) {
        // enqueue styles on the pages that belongs to plugin
    }

  }



}

return AUXELS_Admin::get_instance();
