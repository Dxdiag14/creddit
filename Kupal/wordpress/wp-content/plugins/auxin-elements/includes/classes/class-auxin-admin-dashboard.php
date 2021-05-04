<?php
/**
 * Admin Dashboard Widgets
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

// no direct access allowed
if ( ! defined( 'ABSPATH' ) )  exit;


if ( ! class_exists( 'Auxin_Admin_Dashboard' ) ) :

/**
 * Auxin_Admin_Dashboard Class
 */
class Auxin_Admin_Dashboard {

  /**
   * Hook in admin dashboards
   */
    public function __construct() {

        if ( current_user_can( 'manage_options' ) ) {
            add_action( 'wp_dashboard_setup', array( $this, 'init' ) );
        }
    }

  /**
   * Add dashboard widgets on dashboard setup
   */
    public function init() {
        wp_add_dashboard_widget( 'auxin_dashboard_status', sprintf( __( '%s Status', 'auxin-elements' ), THEME_NAME ), array( $this, 'status_widget' ) );
    }

   /**
    * Show theme status widget
    */
    public function status_widget() {
        $auxin_active_post_types = auxin_get_possible_post_types( true );

        $post_types = get_post_types( array( '_builtin' => false ), 'objects' );

        echo '<table>';
        foreach( $post_types as $pt => $args ) {
            if( isset( $auxin_active_post_types[ $pt ] ) && $auxin_active_post_types[$pt] ){
                $edit_url = 'edit.php?post_type='. $pt;
                echo '<tr><td class="t"><a href="'. $edit_url .'">'. $args->labels->name .'</a></td><td class="b">( '. wp_count_posts( $pt )->publish .' )</td></tr>';
            }
        }
        echo '</table>';
    }

}

endif;
