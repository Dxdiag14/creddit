<?php
/**
 * Main class for comunicating with envato elements
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

/*--------------------------------*/


class Auxels_Envato_Elements {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    public $extension_id_endpoint = 'https://wp.envatoextensions.com/wp-json/elements-content/v1/activate';

    public $token_verification_endpoint = 'https://api.extensions.envato.com/extensions/user_info';

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

    public function __construct() {
        add_action( 'wp_ajax_aux_verify_envato_elements_email', array( $this, 'ajax_get_Extension_ID' ) );
        add_action( 'wp_ajax_aux_verify_envato_elements_token', array( $this, 'ajax_verify_token' ) );
    }

    /**
     * Return Extension ID for communicating with envato elements
     */
    public function ajax_get_Extension_ID() {

        if ( ! empty( get_option( 'phlox_envato_elements_license_code', '' ) ) ) {
            wp_send_json( array( 'status' => true, 'message' => __( 'Email verified.', 'auxin-elements') ) );
        }
        
        $admin_email = sanitize_email( $_POST['email'] );
        
        if ( empty( $admin_email ) ) {
            wp_send_json( array( 'status' => false, 'message' => __( "Email is required!", 'auxin-elements') ) );
        }

        $args['body'] = array(
            'email'             => $admin_email,
            'condition_terms'   => 1,
            'condition_emails'  => 0
        );

        $args['headers'] = array(
            'user-agent'        => 'Mozilla/5.0 ' . home_url(),
            'sslverify'         =>  1,
        );

        $response = wp_remote_post( $this->extension_id_endpoint, $args );
        if ( $response && ! is_wp_error( $response ) ) {
            $response = json_decode( $response['body'], true );
            if ( isset( $response['license_code'] ) ) {
                update_option( 'phlox_envato_elements_license_code', $response['license_code'] );
                wp_send_json( array( 'status' => true, 'message' => __( 'Email verified.', 'auxin-elements') ) );
            } elseif ( isset( $response['error'] ) ) {
                wp_send_json( array( 'status' => false, 'message' => $response['error'] ) );    
            } elseif ( isset( $response['message'] ) ) {
                wp_send_json( array( 'status' => false, 'message' => $response['message'] ) );    
            } else {
                wp_send_json( array( 'status' => false, 'message' => __( 'Something went wrong. Please try again later', 'auxin-elements' ) ) );    
            }
        } else {
            wp_send_json( array( 'status' => false, 'message' => $response->get_error_message() ) );
        }
    }

    /**
     * Verify Envato Elemnents token
     */
    public function ajax_verify_token() {
        wp_send_json( $this->verify_token() );
    }

    public function verify_token() {

        if ( ! empty( get_option( 'phlox_envato_elements_token', '' ) ) ) {
            return array( 'status' => true, 'message' => __( 'Token is valid.', 'auxin-elements') );
        }

        $extension_id = get_option( 'phlox_envato_elements_license_code', '' );
        $token = sanitize_text_field( $_POST['token'] );

        $args['headers'] = array(
            'Extensions-Extension-Id'   => $extension_id,
            'Extensions-Token'          => $token
        );

        $response = wp_remote_get( $this->token_verification_endpoint, $args );

        if ( $response && ! is_wp_error( $response ) ) {
            $response = json_decode( $response['body'], true );
            if ( isset( $response['error'] ) && isset( $response['error']['message'] ) ) {
                return array( 'status' => false, 'message' => $response['error']['message'] );
            }

            if ( isset( $response['subscription_status'] ) && $response['subscription_status'] == 'paid' ) {
                update_option( 'phlox_envato_elements_token', $token );
                return array( 'status' => true, 'message' => __( 'Token is valid.', 'auxin-elements' ) );
            } else {
                return array( 'status' => false, 'message' => __( ' Token is not valid.', 'auxin-elements' ) );
            }
        } else {
            if ( is_wp_error( $response ) ) {
                return array( 'status' => false, 'message' => $response->get_error_message() );
            } else {
                return array( 'status' => false, 'message' => __( 'Something went wrong. Please try again later', 'auxin-elements' ) );
            }
        }
    }

    /**
     * Check if envato elements token and extension id provided or not
     */
    public function is_envato_element_enabled() {
        return ( ! empty( get_option( 'phlox_envato_elements_license_code', '' ) ) && ! empty( get_option( 'phlox_envato_elements_token', '' ) ) ) ? true : false;
    }

} // end widget class

