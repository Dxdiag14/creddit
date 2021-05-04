<?php
/**
 * Master Slider Admin Scripts Class.
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

/**
 *  Class to load and print master slider panel scripts
 */
class Auxels_Admin_Assets {

    /**
     * __construct
     */
    function __construct() {
        // general assets
        add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
    }

    /**
     * Styles for admin
     *
     * @return void
     */
    public function load_styles() {
        // wp_enqueue_style( AUXELS_SLUG .'-admin-styles',   AUXELS_ADMIN_URL . '/assets/css/msp-general.css',  array(), AUXELS_VERSION );
    }

    /**
     * Scripts for admin
     *
     * @return void
     */
    public function load_scripts( $hook ) {

        wp_enqueue_script( AUXELS_SLUG .'-admin-global', AUXELS_ADMIN_URL . '/assets/js/solo/global.js', array( 'jquery' ), AUXELS_VERSION, true );

        if( strpos( $hook, "auxin-welcome" ) === false ) {
            return;
        }

        wp_enqueue_script( AUXELS_SLUG .'-admin-plugins', AUXELS_ADMIN_URL . '/assets/js/plugins.min.js', array('jquery'), AUXELS_VERSION, true );

        wp_enqueue_script( AUXELS_SLUG .'-admin-scripts', AUXELS_ADMIN_URL . '/assets/js/scripts.js', array( 'jquery', 'jquery-masonry', 'auxin_plugins', AUXELS_SLUG .'-admin-plugins' ), AUXELS_VERSION, true );

        wp_localize_script( AUXELS_SLUG .'-admin-plugins', 'aux_setup_params', array(
            'tgm_plugin_nonce' => array(
                'update'  => wp_create_nonce( 'tgmpa-update' ),
                'install' => wp_create_nonce( 'tgmpa-install' ),
            ),
            'ajaxurl'          => admin_url( 'admin-ajax.php' ),
            'wpnonce'          => wp_create_nonce( 'aux_setup_nonce' ),
            'imported_done'    => esc_html__( 'This demo has been successfully imported.', 'auxin-elements' ),
            'imported_fail'    => esc_html__( 'Whoops! There was a problem in demo importing.', 'auxin-elements' ),
            'progress_text'    => esc_html__( 'Processing: Download', 'auxin-elements' ),
            'nextstep_text'    => esc_html__( 'Continue', 'auxin-elements' ),
            'activate_text'    => esc_html__( 'Install Plugins', 'auxin-elements' ),
            'makedemo_text'    => esc_html__( 'Import Content', 'auxin-elements' ),
            'btnworks_text'    => esc_html__( 'Installing...', 'auxin-elements' ),
            'onbefore_text'    => esc_html__( 'Please do not refresh or leave the page during the wizard\'s process.', 'auxin-elements' ),
            'svg_loader'       => '<svg width="90" height="30" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="#505050"><circle cx="10" cy="10" r="10"><animate attributeName="r" from="10" to="10" begin="0s" dur="0.8s" values="10;9;10" calcMode="linear" repeatCount="indefinite" /><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite" /></circle><circle cx="50" cy="10" r="9" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;10;9" calcMode="linear" repeatCount="indefinite" /><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite" /></circle><circle cx="90" cy="10" r="10"><animate attributeName="r" from="10" to="10" begin="0s" dur="0.8s" values="10;9;10" calcMode="linear" repeatCount="indefinite" /><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite" /></circle></svg>'
        ) );
    }

}
