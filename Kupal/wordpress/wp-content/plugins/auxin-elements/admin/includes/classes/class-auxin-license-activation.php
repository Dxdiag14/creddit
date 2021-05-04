<?php

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

class Auxin_License_Activation {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance  = null;

	protected $api           = 'http://support.averta.net/en/api/?branch=envato&group=items&cat=verify-purchase';
    protected $usermail      = '';
    protected $purchase_code = '';
    protected $action        = 'activate';


	function __construct(){
		$this->option_prefix = AUXELS_PURCHASE_KEY;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
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
	 * The result of license validation request
	 *
	 * @return string|array   the server response
	 */
	private function get_license_result() {

	    if( empty( $this->usermail ) || empty( $this->purchase_code ) ) {
	        return false;
	    }

	    global $wp_version;

        $action = ( 'activate' === $this->action ) ? 'activate': 'deactivate';
	    $token  = $this->get_license_info( 'token' );

	    $args = array(
	        'user-agent' => 'WordPress/'.$wp_version.'; ' . get_site_url(),
	        'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3),
	        'body'       => array(
	            'action'	=> $action,
		    	'key'  		=> $this->purchase_code,
		    	'email' 	=> $this->usermail,
		    	'token'		=> $token,
		    	'url'  		=> get_site_url(),
		    	'item_id'  	=> '3909293',
				'ip'   		=> isset( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : ''
	        )
	    );

		$args = apply_filters( 'auxels_get_license_result_args', $args );
		
	    $request = wp_remote_post( $this->api, $args );

	    if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
	        return false;
	    }

	    return json_decode( $request['body'], true );
	}


	/**
	 * Activate or deactivate license if license info is correct
	 *
	 * @param  string $usermail      envato usermail
	 * @param  string $purchase_code item purchase code
	 * @param  string $action        activate or deactivate license
	 *
	 * @return array   An array containing result of activation or deactivation
	 */
	public function license_action( $usermail, $purchase_code, $action = 'activate' ){

		$output = array(
			'success' 	=> 0,
			'status'    => '',
			'message' 	=> '',
			'error' 	=> ''
		);

		if( empty( $usermail ) ){
	    	$output['message'] = __( 'Email address is required.', 'auxin-elements' );
	    	return $output;
	    } elseif( empty( $purchase_code ) ){
	    	$output['message'] = __( 'Purchase key is required.', 'auxin-elements' );
	    	return $output;
		}

		// Check email validation
		if( ! is_email( $usermail ) ){
	    	$output['message'] = __( 'Please enter a valid email address.', 'auxin-elements' );
	    	return $output;
		}

	    // get previous license info
        $license_info = $this->get_license_info();

        $this->usermail      = $usermail;
        $this->purchase_code = $purchase_code;
        $this->action        = $action;
	    // fetch license info
	    $response = $this->get_license_result();

	    if( false !== $response ){

	    	if( empty( $response['result'] ) ){
	    		$output['message'] = __( 'Bad request with wrong header ..', 'auxin-elements' );
	    		return $output;
	    	}


	    	if( 'success' == $response['result'] ){
				// Remove token transient
				auxin_delete_transient( 'auxin_check_token_validation_status' );

	    		if( 'active' == $response['status'] ){

	        		$token 		= isset( $response['token'] ) ? $response['token'] : '';
					$license_info['usermail'] 		= $usermail;
	        		$license_info['purchase_code']  = $purchase_code;
	        		$license_info['token']  		= $token;
	    			update_site_option( $this->option_prefix, $license_info );

	    		} elseif( 'deactive' == $response['status'] ){

					$license_info['token']  		= '';
					update_site_option( $this->option_prefix, $license_info );

	    		}

	    		$output['success'] = 1;
	    		$output['status' ] = $response['status'];

	    	// if an error occurred
	    	} else {
	    		$output['success'] = 0;
                $output['status' ] = $response['status'];
	    	}

	    	$output['message'] = $response['msg'] . sprintf( ' <sub>[%s]</sub>', $response['code'] );

	    } else {
	    	$output['message'] = __( 'Connection error...', 'auxin-elements' );
	    	$output['success'] = 0;
	    }

        do_action( 'auxin_on_license_action', $action, $output );

	    return $output;
	}


    private function get_token_validation_status() {

        global $wp_version;

        $token = $this->get_license_info( 'token' );

        $args = array(
            'user-agent' => 'WordPress/'.$wp_version.'; ' . get_site_url(),
            'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3),
            'body' => array(
                'action'    => 'token',
                'token'     => $token,
                'url'       => get_site_url()
            )
        );

        $request = wp_remote_post( $this->api, $args );


        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
            return false;
        }

        $response = json_decode( $request['body'], true );
        $response['token'] = $token;

        return $response;
    }


    public function maybe_invalid_token(){

        $status = $this->get_token_validation_status();

        if( false !== $status && isset( $status['allowed'] ) ){
            // if token is no longer valid to be used on this domain
            if ( ! $status['allowed'] ){
                $license_info = get_site_option( $this->option_prefix, array() );
                $license_info['token'] = '';
                update_site_option( $this->option_prefix, $license_info );
            }
        }

        return $status;
    }



	/**
	 * Retrieves license info or a specific license field
	 *
	 * @param  string $field 	Specific license field or empty string
	 * @return array|string     Returns all license info in array or a string containing license field value
	 */
	private function get_license_info( $field = '', $default = '' ){
		$license_info = get_site_option( $this->option_prefix, array() );

		if( empty( $field ) )
			return $license_info;

		return isset( $license_info[ $field ] ) ? $license_info[ $field ] : $default;
	}

}
