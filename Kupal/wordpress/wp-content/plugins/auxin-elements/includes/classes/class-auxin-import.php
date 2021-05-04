<?php
/**
 * Class for simplifying the demo import process
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
 * Master Slider Import/Export Class.
 *
 * @since 1.2.0
 */
class Auxin_Import {


    var $upload_baseurl = '';

    var $upload_basedir = '';

    /**
     * The ID of this importer
     * @var string
     */
    var $importer_id    = '';

    /**
     * The link to welcome page in admin
     * @var string
     */
    var $welcome_page_url  = '';

    /**
     * The link to demos page in admin
     * @var string
     */
    var $demo_page_url  = '';

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;



    function __construct() {

        $this->importer_id = 'auxin-importer';

        $welcome_root_page = ( defined( 'THEME_PRO' ) && THEME_PRO ) ? 'admin.php' : 'themes.php';

        $this->welcome_page_url = admin_url( $welcome_root_page . '?page=auxin-welcome' );
        $this->demo_page_url    = $this->welcome_page_url . '&tab=demos';

        add_action( 'admin_init', array( $this, 'admin_init' ) );

        // process and redirect the import page prior to rendering the header
        add_action( 'load-importer-'. $this->importer_id, array( $this, 'process_before_header_output' ) );
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


    /**
     * Print and process the output of auxin importer page
     * @return string     Importer page output
     */
    public function admin_init() {

        $upload = wp_upload_dir();
        $this->upload_baseurl = $upload['baseurl'];
        $this->upload_basedir = $upload['basedir'];


        register_importer( 'auxin-importer',
            __( 'Auxin Importer', 'auxin-elements' ),
            sprintf( __( 'Import demo data for %s theme.', 'auxin-elements' ), '<strong>' . THEME_NAME_I18N . '</strong>' ),
            array( $this, 'render_importer_page' )
        );
    }

    /**
     * Check if the request for importer page is valid and authorized, otherwise redirect client to demos page
     *
     * @return void
     */
    public function process_before_header_output(){

        // authorize the request and make sure it is from admin page, otherwise take the client back to demos page
        if ( ! $this->is_valid_request() ) {
            wp_redirect( $this->demo_page_url, 301 );
            exit();
        }

    }

    /**
     * Outputs the custom import page for auxin
     *
     */
    public function render_importer_page() {

        $this->header();

        $this->process_import_request();

        $this->footer();
    }


    /**
     * Display import page title
     */
    function header() {
        echo '<div class="wrap">';
        echo '<h2>' . __( 'Importing Demo Data', 'auxin-elements' ) . '</h2><br />';
        echo '<div class="aux-import-wrapper">';
    }

    /**
     * Close div.wrap
     */
    function footer() {
        echo '</div></div>';
    }


    /**
     * Process incoming requests for importing demo data
     * @return void
    */
    public function process_import_request(){

        // make sure the demo-id is specified
        if( ! empty( $_GET['demo-id'] ) ) {
            $demo_id   = sanitize_key( $_GET['demo-id'] );
            $demo_list = auxin_get_demo_info_list();

            // if demo-id is valid, start importing the data
            if( ! empty( $demo_list[ $demo_id ]['file'] ) ){
                $file = $demo_list[ $demo_id ]['file'];

                // if wordpress importer is not available, try to install it
                if ( ! class_exists( 'WP_Importer' ) ) {

                    printf( __( 'In order to import the demo data, you need to have "WordPress Importer" plugin installed. Please %s install and activate "WordPress Importer"%s and then try importing the demo data again.', 'auxin-elements' ),
                        '<a href="'. admin_url( 'plugin-install.php?s=WordPress+Importer+zourbuth&tab=search' ) .'&tab=plugins">',
                        '</a>'
                    );


                // start parsing and importing the data
                } else {
                    global $wp_import;
                    set_time_limit(0);
                    $wp_import->fetch_attachments = true;
                    $wp_import->import_file = $file;
                    $wp_import->import( $file );
                }


            } else {

                printf( __( 'The demo that you have requested is not valid. Please try to %s select a demo %s to import.', 'auxin-elements' ),
                    '<a href="'. $this->demo_page_url .'">',
                    '</a>'
                );

            }

        } else {
            printf( __( 'Please %s select a demo %s to import.', 'auxin-elements' ), '<a href="'. $this->demo_page_url .'">', '</a>' );
        }

    }

    /**
     * Makes sure that a user was referred from another admin page.
     *
     * @return boolean  True if user was referred from an admin page, false otherwise
     */
    private function is_valid_request(){
        $adminurl = strtolower( admin_url()      );
        $referer  = strtolower( wp_get_referer() );
        $result   = isset( $_REQUEST['_wpnonce'] ) ? wp_verify_nonce( $_REQUEST['_wpnonce'], 'auxin-import' ) : false;

        return $result && ( strpos( $referer, $adminurl ) === 0 );
    }

}

