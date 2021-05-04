<?php
/**
 * EkitePack Themes Updater.
 *
 * Please note that this is a drop-in library for a theme or plugin.
 * The authors of this library are NOT responsible
 * for the support of your plugin or theme. Please contact the plugin
 * or theme author for support.
 *
 * @version 0.0.4
 * @date    16/04/2020
 * @link    http://elitepack.io/
 */

/**
	0.0.4
		- Fixed: return $response['package']; in get_download_link()
**/

if ( ! class_exists( 'ElitePack_Theme_Updater_Admin' ) ) {

	/**
	 * ElitePack_Theme_Updater_Admin.
	 */
	class ElitePack_Theme_Updater_Admin {

		protected $name               = null;
		protected $slug               = null;
		protected $item_id            = null;
		protected $version            = null;
		protected $capability         = null;
		protected $redirect_url       = null;
		protected $prefix             = null;
		protected $notice_screens     = null;
		protected $license_key        = null;
		protected $cached_data_key    = null;
		protected $license_db_key     = null;
		protected $license_status_key = null;
		protected $connect_error_key  = null;
		protected $cached_demos_key   = null;
		protected $cached_plugins_key = null;
		protected $is_plugin          = null;
		protected $plugin_file        = null;

		protected $allowed_html = [
			'p'      => [],
			'a'      => [
				'href'  => [],
				'rel'   => [],
				'class' => [],
			],
			'em'     => [],
			'strong' => [],
			'br'     => [],
		];


		/**
		 * Class Constructor.
		 *
		 * @param array $config configuration args.
		 * @param array $strings Text for the different elements.
		 */
		function __construct( $config = array(), $strings = array() ) {

			// Setup variables
			$this->setup_config( $config );

			// Strings passed in from the config file
			$this->strings = $strings;

			// Allowed HTML in the notice message.
			$this->allowed_html = apply_filters( "{$this->prefix}/ep/allowed_html", $this->allowed_html );

			// Options and Transient keys
			$this->license_db_key     = "{$this->prefix}_ep_license_key";
			$this->license_status_key = "{$this->prefix}_ep_license_status";
			$this->connect_error_key  = "{$this->prefix}_ep_license_error";

			// Cached Data Keys
			$this->cached_data_key    = "{$this->prefix}_ep_cached_data";
			$this->cached_demos_key   = "{$this->prefix}_ep_cached_demos";
			$this->cached_plugins_key = "{$this->prefix}_ep_cached_plugins";

			// Stored License Key
			$this->license_key = get_option( $this->license_db_key );

			// Actions and Filters
			add_action( 'init', array( $this, 'init' ) );
		}


		/**
		 * Setup global variables
		 *
		 * @since 1.0.0
		 */
		function setup_config( $config ){

			// Set config arguments.
			$config = wp_parse_args(
				$config, array(
					'api_url'        => false,
					'theme_folder'   => false,
					'item_id'        => false,
					'name'           => '',
					'version'        => false,
					'prefix'         => false,
					'notice_screens' => false,
					'is_plugin'      => false,
					'plugin_file'    => false,
					'redirect_url'   => admin_url(),
					'capability'     => 'manage_options',
				)
			);

			$this->name           = $config['name'];
			$this->item_id        = $config['item_id'];
			$this->api_url        = $config['api_url'];
			$this->version        = $config['version'];
			$this->capability     = $config['capability'];
			$this->redirect_url   = $config['redirect_url'];
			$this->prefix         = $config['prefix'];
			$this->notice_screens = $config['notice_screens'];
			$this->is_plugin      = $config['is_plugin'];

			/**
			 * Set sLug and prefix
			 */
			// Plugin
			if( $this->is_plugin ){
				if( ! empty( $config['plugin_file'] ) ){

					$this->plugin_file = $config['plugin_file'];
					$this->slug = plugin_basename( $this->plugin_file );

					// If there is no prefix, set a default the main file name as prefix
					$this->prefix = ! empty( $this->prefix ) ? $this->prefix : basename( $config['plugin_file'], '.php' );

					// Some plugins use index.php file as the main plugin file, that may cause conflicts.
					if( $this->prefix == 'index' ){
						$plugin_folder = explode( '/', $this->slug );
						if( ! empty( $plugin_folder[0] ) ){
							$plugin_folder = sanitize_key( strtolower( $plugin_folder[0] ) );
							if( $plugin_folder != 'plugins' ){
								$this->prefix = $plugin_folder;
							}
						}
					}
				}
			}

			// Theme
			else{
				if( ! empty( $config['theme_folder'] ) ){

					$this->slug = sanitize_key( $config['theme_folder'] );

					// If there is no prefix, set a default the theme folder name as prefix
					$this->prefix = ! empty( $this->prefix ) ? $this->prefix : $this->slug;

					// If theme name or version is missing, get it from the wp_get_theme
					if( empty( $this->name ) || empty( $this->version ) ){
						$theme_data    = wp_get_theme( $this->slug );
						$this->name    = ! empty( $this->name)    ? $this->name    : $theme_data->name;
						$this->version = ! empty( $this->version) ? $this->version : $theme_data->version;
					}
				}
			}

			// API URL, Slug and the Envato Item ID are required
			if( ! $this->item_id || ! $this->api_url || ! $this->slug ){
				wp_die( 'ElitePack Error: item_id, api_url and slug are required' );
			}

			// Fires after the $config is setup.
			do_action( "{$this->prefix}/ep/after_config_setup", $config );
		}


		/**
		 * Actions and Filters
		 * We hook these action and filters via the init to be able to the use current_user_can()
		 * @since 1.0.0
		 */
		function init(){

			if ( ! current_user_can( $this->capability ) || apply_filters( "{$this->prefix}/ep/disable", false ) ) {
				return;
			}

			add_action( 'admin_init',    array( $this, 'updater' ) );
			add_action( 'admin_init',    array( $this, 'activate_license'   ) );
			add_action( 'admin_init',    array( $this, 'deactivate_license' ) );
			add_action( 'admin_init',    array( $this, 'refresh_support'    ) );
			add_action( 'admin_notices', array( $this, 'redirected_notices'      ), 5 );
			add_action( 'admin_notices', array( $this, 'expiring_support_notice' ), 3 );
			add_action( 'admin_notices', array( $this, 'live_notices' ), 40 );
			add_action( 'admin_enqueue_scripts', array( $this, 'inline_scripts' ), 25 );

			// Deactivation Feedback
			add_action( 'admin_footer', array( $this, 'insert_deactivation_form' ) );
			add_action( "wp_ajax_{$this->slug}_send_feedback", array( $this, 'send_deactivation_feedback' ) );

			// If there is a new version we need to force run WordPress updates checker
			add_action( "{$this->prefix}/ep/after_saving_cache", array( $this, 'force_refresh_updates_data' ) );

			// Clear cached data when the item switched/deactivated
			if( ! $this->is_plugin ){
				add_action( 'switch_theme', array( $this, 'clear_cache' ), 1 );
			}

			// Debugging
			//$this->clear_stored_data();
			//$this->clear_cache();
			//update_user_meta( get_current_user_id(), 'dismissed_wp_pointers', '' );

			/*
			echo '<pre>';
			var_dump( $this->get() );
			echo '</pre>';
			*/
		}


		/**
		 * Strings
		 * Avoid any error messages by returning empty string, if the author removed any of the default texts
		 *
		 * @since 1.0.0
		 */
		function strings( $text ){

			if( ! empty( $this->strings[ $text ] ) ){
				return $this->strings[ $text ];
			}

			return $text;
		}


		/**
		 * Cache the data
		 *
		 * @since 1.0.0
		 */
		function save_cache( $item_data ){

			if( ! is_array( $item_data ) ){
				return;
			}

			set_transient( $this->cached_data_key, $item_data, apply_filters( "{$this->prefix}/ep/cache_expiration", DAY_IN_SECONDS ) );

			// Delete the Error Message
			delete_transient( $this->connect_error_key );

			do_action( "{$this->prefix}/ep/after_saving_cache", $item_data );
		}


		/**
		 * Clear Cache
		 *
		 * @since 1.0.0
		 */
		function clear_cache(){
			delete_transient( $this->cached_data_key    );
			delete_transient( $this->connect_error_key  );
			delete_transient( $this->cached_demos_key   );
			delete_transient( $this->cached_plugins_key );
		}


		/**
		 * Get the item remote data
		 *
		 * @since 1.0.0
		 */
		function get( $key = false ){

			// If there is an error return false
			if( false !== get_transient( $this->connect_error_key ) ){
				return false;
			}

			// Check the cached data, Refresh the Stored Data if not exists
			if( false === ( $item_data = get_transient( $this->cached_data_key ) ) ){

				// Item registered
				if( $this->is_active() ){
					$item_data = $this->api_get_data();
				}

				// Non Registered Item
				else{

					// Allow theme to disable checking if the license is not active, useful for mega themes to avoid many requests.
					if( apply_filters( "{$this->prefix}/ep/disable_non_active", false ) ){
						return false;
					}

					$item_data = $this->api_request( $this->api_endpoint_url('get_version') );
				}

				// No Response
				if ( empty( $item_data ) ){
					return false;
				}

				// Error
				if( is_wp_error( $item_data ) ){

					// Licensing Error
					if( $item_data->get_error_code() == 'licensing_error' ){
						$this->clear_stored_data();
					}

					set_transient( $this->connect_error_key, wp_strip_all_tags( $item_data->get_error_message() ), DAY_IN_SECONDS );
					return false;
				}

				// Cache the data
				$this->save_cache( $item_data );
			}

			// Single key
			if( ! empty( $key ) ){

				// Key Exists
				if( ! empty( $item_data[ $key ] ) ){
					return $item_data[ $key ];
				}

				return false;
			}

			// All cached data
			return $item_data;
		}


		/**
		 * Get the Purchase URL
		 *
		 * @since 1.0.0
		 */
		function purchase_url(){

			$purchase_url = $this->get( 'url' ) ? $this->get( 'url' ) : 'https://themeforest.net/item/i/'.$this->item_id;
			return apply_filters( "{$this->prefix}/ep/purchase_url", $purchase_url );
		}


		/**
		 * Get the activation link
		 *
		 * @since 1.0.0
		 */
		function activate_link(){

			return apply_filters( "{$this->prefix}/ep/api/activate_link", add_query_arg(
				array(
					'item'         => $this->item_id,
					'blog'         => esc_url( home_url() ),
					'redirect_url' => esc_url( $this->redirect_url ),
					'envato_verify_purchase' => true,
				),
				$this->api_url
			));
		}


		/**
		 * Get the Deactivate License link
		 *
		 * @since 1.0.0
		 */
		function deactivate_license_link(){

			return apply_filters( "{$this->prefix}/ep/api/deactivate_license_link", add_query_arg(
				array(
					'item' => $this->item_id,
					'deactivate-license' => true,
				),
				$this->redirect_url
			));
		}


		/**
		 * Get the Refresh Support Expiration
		 *
		 * @since 1.0.0
		 */
		function refresh_support_expiration_link(){

			return apply_filters( "{$this->prefix}/ep/api/refresh_support_expiration_link", add_query_arg(
				array(
					'item' => $this->item_id,
					'refresh-support-expiration' => true,
				),
				$this->redirect_url
			));
		}


		/**
		 * Get the API Request Endpoint URL
		 *
		 * @since 1.0.0
		 */
		function api_endpoint_url( $type ){

			$url = add_query_arg(
				array(
					'ep_api' => $type,
				),
				$this->api_url
			);

			return apply_filters( "{$this->prefix}/ep/api/endpoint_url", $url, $type, $this->api_url );
		}


		/**
		 * Make request to the Api endpoints
		 *
		 * @since 1.0.0
		 */
		function api_request( $url, $params = false, $blocking = true ){

			// No wp_filter here, to avoid nulled versions from chnage the required params.
			$api_params = array(
				'license' => trim( $this->license_key ),
				'item_id' => $this->item_id,
				'url'     => esc_url( home_url() ),
			);

			// Merge the
			if( ! empty( $params ) && is_array( $params ) ){
				$api_params = array_merge( $params, $api_params );
			}

			$response = wp_remote_post( $url, array(
				'body'      => $api_params,
				'blocking'  => $blocking,
				'sslverify' => (bool) apply_filters( "{$this->prefix}/ep/api/sslverify", true ),
				'timeout'   => apply_filters( "{$this->prefix}/ep/api/timeout", 15 ),
			));

			return $this->api_response( $response );
		}


		/**
		 * Check the Api response
		 *
		 * @since 1.0.0
		 */
		function api_response( $response ){
			return $response;
			//var_dump( $response );

			// Check the response code.
			$response_code    = wp_remote_retrieve_response_code( $response );
			$response_message = wp_remote_retrieve_response_message( $response );

			$debugging_information['response_code']   = $response_code;
			$debugging_information['response_cf_ray'] = wp_remote_retrieve_header( $response, 'cf-ray' );
			$debugging_information['response_server'] = wp_remote_retrieve_header( $response, 'server' );

			if ( ! empty( $response->errors ) && isset( $response->errors['http_request_failed'] ) ) {
				return new WP_Error( 'http_error', esc_html( current( $response->errors['http_request_failed'] ) ), $debugging_information );
			}

			if ( 200 !== $response_code && ! empty( $response_message ) ) {
				return new WP_Error( $response_code, $response_message, $debugging_information );
			}
			elseif ( 200 !== $response_code ) {
				return new WP_Error( $response_code, $this->strings('api-error'), $debugging_information );
			}

			$response = json_decode( wp_remote_retrieve_body( $response ), true );

			// Clean all fields
			$response = map_deep( $response, array( $this, 'kses' ) );

			// Success
			if( ! empty( $response['status'] ) && $response['status'] == 1 ){
				return $response;
			}
			// Error
			elseif ( ! empty( $response['error'] ) ) {
				return new WP_Error( 'licensing_error', $response['error'], $debugging_information );
			}

			return new WP_Error( 'api_error', $this->strings('api-error'), $debugging_information );
		}


		/**
		 * Prepare the get_data Endpoint request
		 *
		 * @since 1.0.0
		 */
		function api_get_data(){

			$params = array(
				'item_version' => $this->version,
				'php_version'  => phpversion(),
				'wp_version'   => get_bloginfo('version'),
				'local'        => get_locale(),
			);

			if( $this->is_plugin ){
				$params['installed_theme'] = get_template(); // Parent theme name
			}

			// Make the API request
			return $this->api_request( $this->api_endpoint_url('get_data'), apply_filters( "{$this->prefix}/ep/api/get_data/body", $params ) );
		}



		/**
		 * Get the Download URL via get_version Endpoint request
		 *
		 * @since 1.0.0
		 */
		function get_download_link(){

			// Check if we have cached download link
			if( false !== get_transient( "{$this->prefix}_ep_download_link" ) ){
				return get_transient( "{$this->prefix}_ep_download_link" );
			}

			// There is no cached link, Make new API request
			$response = $this->api_request( $this->api_endpoint_url('get_version') );

			// No Errors
			if( ! is_wp_error( $response ) && ! empty( $response['package'] ) && empty( $response['package_error'] ) ){

				// Default cache time - 1 Hour
				$expiration = apply_filters( "{$this->prefix}/ep/api/download_expiration", HOUR_IN_SECONDS );

				// File Generated Via Envato API is valid for 10 Minutes only, we will cache the response for 10 min.
				if ( false !== strrpos( $response['package'], 'marketplace.envato.com' ) || false !== strrpos( $response['package'], 'marketplace-downloads.customer.envatousercontent.com' ) ) {
					$expiration = 10 * MINUTE_IN_SECONDS;
				}

				set_transient( "{$this->prefix}_ep_download_link", $response['package'], $expiration );

				return $response['package'];
			}

			return false;
		}


		/**
		 * Deactivate License API request
		 *
		 * @since 1.0.0
		 */
		function api_deactivate_license(){

			if( ! $this->is_active() || ! empty( $_GET[ "{$this->prefix}_ep_notice" ] ) ){
				return;
			}

			$deactivate_request = $this->api_request( $this->api_endpoint_url('deactivate_license') );

			if( ! empty( $deactivate_request ) ){

				if( is_wp_error( $deactivate_request ) && $deactivate_request->get_error_code() != 'licensing_error' ){
					$type    = 'error';
					$message = $deactivate_request->get_error_message();
				}
				else{
					$type    = 'success';
					$message = $this->strings('revoke-license-success');

					$this->clear_stored_data();
				}

				$redirect = add_query_arg( array( "{$this->prefix}_ep_notice" => $type, 'notice-message' => urlencode( $message ) ), $this->redirect_url );
				wp_redirect( $redirect );

				exit;
			}
		}


		/**
		 * Activate the License
		 *
		 * @since 1.0.0
		 */
		function activate_license(){

			

				// If we are in the authorization process show the notices on all screens
				$this->notice_screens = false;

				// We Got the License Code - We need to make a request to verify it
				

					// Update the global $license_key
					$this->license_key = '34e47125-35fb-41d7-a259-004d27a35017';

					// Make the API request
					$response = $this->api_get_data();

					

						// Update the Stored Key and License Status
						update_option( $this->license_status_key, 'valid', false );
						update_option( $this->license_db_key, trim( '34e47125-35fb-41d7-a259-004d27a35017' ), false );

						// Cache the data
						$this->save_cache( $response );

						// Show the Success Message
						add_action( 'admin_notices', array( $this, 'notice_success' ), 5 );

						return;
					
				

				// If There is an Error, store the error and display the error notice
				
				
		

			// Show the Activate Message
		add_action( 'admin_notices', array( $this, 'notice_activate' ), 1  );
		}


		/**
		 * Dectivate License
		 *
		 * @since 1.0.0
		 */
		function deactivate_license(){

			if( isset( $_GET['deactivate-license'] ) && isset( $_GET['item'] ) && $_GET['item'] == $this->item_id ){
				$this->api_deactivate_license();
			}
		}


		/**
		 * Refresh Support Expiration date
		 *
		 * @since 1.0.0
		 */
		function refresh_support(){

			if( isset( $_GET['refresh-support-expiration'] ) && isset( $_GET['item'] ) && $_GET['item'] == $this->item_id ){

				$current_date = $this->get( 'supported_until' );

				// Delete the cached data to make the new request
				$this->clear_cache();

				// Add the required force_update param
				add_filter( "{$this->prefix}/ep/api/get_data/body", array( $this, 'refresh_support_expiration_remote_args' ) );

				// Once we call the get() function it will make a new connection and return the new updated support expiration date
				$updated_date = $this->get( 'supported_until' );

				// Prepare the message
				if( empty( $updated_date ) ){
					$message = $this->strings('support-update-failed');
					$type    = 'error';
				}
				else{

					// Convert the date to human readable format
					$human_date = sprintf( $this->strings('date-at-time'), wp_date( 'F j, Y', strtotime( $updated_date ) ), wp_date( 'g:i a', strtotime( $updated_date ) ) );

					if( $current_date == $updated_date ){
						$message = sprintf( $this->strings('support-not-updated'), $human_date );
						$type    = 'warning';
					}
					elseif( strtotime( $current_date ) < strtotime( $updated_date ) ){
						$message = sprintf( $this->strings('support-updated'), $human_date );
						$type    = 'success';
					}
					else{
						$message = $this->strings('support-update-failed');
						$type    = 'warning';
					}
				}

				$redirect = add_query_arg( array( "{$this->prefix}_ep_notice" => $type, 'notice-message' => urlencode( $message ) ), $this->redirect_url );
				wp_redirect( $redirect );
				exit;
			}
		}


		/**
		 * Refresh Support Expiration date
		 *
		 * @since 1.0.0
		 */
		function refresh_support_expiration_remote_args( $params ){
			$params['force_update'] = true;
			return $params;
		}


		/**
		 * Check if the current item has been validated
		 */
		function is_active(){
			
				return true;
			
		}


		/**
		 * Check if the domain is localhost or online dev install
		 *
		 * @access public
		 * @since 1.0
		 * @return bolean
		 */
		function is_dev() {

			//return false;

			$url = esc_url( home_url() );

			// Need to get the host...so let's add the scheme so we can use parse_url
			if ( false === strpos( $url, 'http://' ) && false === strpos( $url, 'https://' ) ) {
				$url = 'http://' . $url;
			}

			$info = parse_url( $url );
			$host = ! empty( $info['host'] ) ? $info['host'] : $url;

			if( $host == 'localhost' ){
				return true;
			}

			$dev_paths = apply_filters( "{$this->prefix}/ep/dev_paths", array(
				'dev', 'test', 'staging'
			) );

			// Check Subdomains and Folders
			foreach ( $dev_paths as $path ){
				if( strpos( $url, '.'.$path ) !== false || strpos( $url, '/'.$path ) !== false ){
					return true;
				}
			}

			return false;
		}


		/**
		 * Check if support is active
		 */
		function is_support_active(){

			$support_info = $this->support_period_info();

			if( ! empty( $support_info['status'] ) && $support_info['status'] != 'expired' ){
				return true;
			}

			return false;
		}


		/**
		 * Get Support period info
		 */
		function support_period_info(){

			

			$support_info    = array();
			$today_date      = time();
			$supported_until = '01.01.2030';
			$supported_until = strtotime( '+1200 days' );

			// Support is active
			

				$support_info['status'] = 'active';

				// Check if it less than 2 months
				$diff = (int) abs( $supported_until - $today_date );

				

				// Get the date and the remaning period
				$support_info['period'] = human_time_diff( $supported_until );
			

			// Opps expired
			

			$date_format = get_option( 'date_format' ) ? get_option( 'date_format' ) : 'F j, Y' ;

			$support_info['date'] = wp_date( $date_format, $supported_until );

			return $support_info;
		}


		/**
		 * Activated Successfully
		 *
		 * @since 1.0.0
		 */
		function notice_success(){

			$this->notice(
				'activated',
				$this->strings('register-success-title'),
				$this->strings('register-success-text'),
				apply_filters( "{$this->prefix}/ep/notice/success_args", array( 'type'=> 'success' ) )
			);
		}


		/**
		 * Activate Item Notice
		 *
		 * @since 1.0.0
		 */
		function notice_activate(){

			// Check if the theme is active
			
				return;
			

			// The Error message stored for 24 hours, we need to check if an error message exists
			if( false !== get_transient( $this->connect_error_key ) ){
				$this->notice_error();
				return;
			}

			// Authorze Message
			$this->notice(
				'activation-notice',
				sprintf( $this->strings('register-item'), $this->name ),
				sprintf( $this->strings('register-message'), $this->name ),
				apply_filters( "{$this->prefix}/ep/notice/active_args", array(
					'type'        => 'warning',
					'dismissible' => false,
					'buttons'     => array(
						array(
							'text'  => $this->strings('register-button'),
							'url'   => $this->activate_link(),
							'class' => 'button button-primary',
						),
						array(
							'text'    => $this->strings('purchase-license'),
							'url'     => $this->purchase_url(),
							'new_tab' => true,
							'class'   => 'button',
						),
					),
				)
			));
		}


		/**
		 * Error Notice
		 *
		 * @since 1.0.0
		 */
		function notice_error(){

			// No Errors stored
		
				return;
			

			$this->notice(
				'activation-error',
				'Error',
				$error,
				apply_filters( "{$this->prefix}/ep/notice/error_args", array(
					'type'        => 'error',
					'dismissible' => false,
					'buttons'     => array(
						array(
							'text'  => $this->strings('try-again'),
							'url'   => $this->activate_link(),
							'class' => 'button button-primary',
						),
						array(
							'text'    => $this->strings('purchase-license'),
							'url'     => $this->purchase_url(),
							'new_tab' => true,
							'class'   => 'button',
						),
					),
				)
			));
		}


		/**
		 * Show the Notice
		 *
		 * @since 1.0.0
		 */
		function redirected_notices(){

			if( ! empty( $_GET[ "{$this->prefix}_ep_notice" ] ) && ! empty( $_GET['notice-message'] ) ){

				$this->notice(
					false,
					false,
					urldecode( $_GET['notice-message'] ),
					apply_filters( "{$this->prefix}/ep/notice/redirected_args", array(
						'type' => $_GET[ "{$this->prefix}_ep_notice" ],
						'dismissible' => false,
					))
				);

				// Remove All other notices
				remove_action( 'admin_notices', array( $this, 'notice_error'    ) );
				remove_action( 'admin_notices', array( $this, 'notice_success'  ) );
				remove_action( 'admin_notices', array( $this, 'notice_activate' ) );
			}
		}


		/**
		 * Updater filters
		 *
		 * @since 1.0.0
		 */
		function updater() {

			// debug
			//set_site_transient( 'update_themes', null );
			//set_site_transient( 'update_plugins', null );

			/* Check if current version is < the latset version */
			if( version_compare( $this->get( 'new_version' ), $this->version, '<=' ) ){
				return;
			}

			// Deferred Download.
			add_action( 'upgrader_package_options', array( $this, 'maybe_deferred_download' ), 9 );

			// New Update Notice
			add_thickbox();
			add_action( 'admin_notices', array( $this, 'update_nag' ), 5 );

			// Plugin Update actions and filters
			if( $this->is_plugin ){

				// Plugins info screen
				add_filter( 'plugins_api', array( $this, 'plugin_info' ), 20, 3);

				// Inject the plugin update into the response array.
				add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_plugins' ) );
				add_filter( 'pre_set_transient_update_plugins',      array( $this, 'update_plugins' ) );

				// Update notice message in the plugins page
				add_action( "in_plugin_update_message-{$this->slug}", array( $this, 'in_plugin_update_message' ), 10, 2 );
			}

			// Theme Update actions and filters
			else{

				// Inject the theme update into the response array.
				add_filter( 'pre_set_site_transient_update_themes', array( $this, 'update_themes' ), 1 );
				add_filter( 'pre_set_transient_update_themes',      array( $this, 'update_themes' ), 1 );

				// Chnage default theme update texts
				add_action( 'wp_prepare_themes_for_js',              array( $this, 'update_theme_inline_notice' ), 999 );
				add_action( 'admin_print_footer_scripts-themes.php', array( $this, 'update_theme_inline_notice_js' ) );
			}
		}


		/**
		 * If there is a new version we need to force run WordPress updates checker
		 *
		 * @since 1.0.0
		 */
		function force_refresh_updates_data( $item_data = false ){

			if( empty( $item_data['new_version'] ) || version_compare( $item_data['new_version'], $this->version, '<=' ) ){
				return;
			}

			$update_type = $this->is_plugin ? 'update_plugins' : 'update_themes';

			set_site_transient( $update_type, null );
		}


		/**
		 * Plugin info section
		 *
		 * @since 1.0.0
		 */
		function plugin_info( $res, $action, $args ){

			if( $action !== 'plugin_information' || $this->slug !== $args->slug ){
				return $res;
			}

			// Parses the plugin contents to retrieve plugin’s metadata.
			$plugin_data = get_plugin_data( $this->plugin_file );

			// Plugin Info
			$res = new stdClass;
			$res->name         = $this->name;
			$res->slug         = $this->slug;
			$res->version      = $this->get( 'new_version' );
			$res->tested       = $this->get( 'tested' ) ? $this->get( 'tested' ) : get_bloginfo( 'version' );
			$res->requires_php = $this->get( 'requires_php' );
			$res->last_updated = $this->get( 'last_updated' );
			$res->homepage     = $this->purchase_url();
			$res->rating       = $this->get( 'item_rating' );
			$res->num_ratings  = $this->get( 'num_ratings' );
			$res->author       = ! empty( $plugin_data['Author'] ) ? $plugin_data['Author'] : false;

			// Check if license is active and customer can update the theme
			if( $this->is_active() && $this->get( 'can_update' ) ){
				$res->download_link = add_query_arg( array( 'elitepack_deferred' => true ), admin_url() );
			}

			if( $banners = $this->get( 'banners' ) ){
				$res->banners['high'] = ! empty( $banners['2x'] ) ? $banners['2x'] : false;
				$res->banners['low']  = ! empty( $banners['1x'] ) ? $banners['1x'] : false;
			}

			// Sections
			$res->sections = array();

			// Description Section
			if( $description = $this->get( 'description' ) ){
				$res->sections['description'] = wpautop( $description );
			}
			elseif( ! empty( $plugin_data['Description'] ) ){
				$res->sections['description'] = $plugin_data['Description'];
			}

			// ChnageLog Section
			if( $changelog_url = $this->get( 'changelog_url' ) ){
				$response = wp_remote_get( esc_url( $changelog_url  ) );
				if( ! is_wp_error( $response ) ){
					$res->sections['changelog'] = wpautop( wp_remote_retrieve_body( $response ) );
				}
			}

			return $res;
		}


		/**
		 * Defers building the API download url until the last responsible moment to limit file requests.
		 *
		 * Filter the package options before running an update.
		 *
		 * @since 1.0.0
		 *
		 * credit Envato_Market Plugin
		 */
		function maybe_deferred_download( $options ) {

			if ( false !== strrpos( $options['package'], 'elitepack_deferred' ) ) {
				$options['package'] = $this->get_download_link();
			}

			return $options;
		}


		/**
		 * Inject plugin update data
		 *
		 * @since 1.0.0
		 *
		 * @param object $transient The pre-saved value of the `update_plugins` site transient.
		 * @return object
		 */
		function update_plugins( $transient ) {

			if ( ! is_object( $transient ) ) {
				$transient = new stdClass;
			}

			if ( ! empty( $transient->response ) && ! empty( $transient->response[ $this->slug ] ) ) {
				return $transient;
			}

			$data = new stdClass;
			$data->slug           = $this->slug;
			$data->plugin         = $this->slug;
			$data->new_version    = $this->get( 'new_version' );
			$data->tested         = $this->get( 'tested' ) ? $this->get( 'tested' ) : get_bloginfo( 'version' );
			$data->requires_php   = $this->get( 'requires_php' );
			$data->upgrade_notice = $this->get( 'upgrade_notice' );

			if( $icons = $this->get( 'icons' ) ){
				$data->icons = $icons;
			}

			if( $banners = $this->get( 'banners' ) ){
				$data->banners = $banners;
			}

			// Check if license is active and customer can update the theme
			if( $this->is_active() && $this->get( 'can_update' ) ){
				$data->package = add_query_arg( array( 'elitepack_deferred' => true ), admin_url() );
			}

			if( ! empty( $data ) ){
				$transient->response[ $this->slug ] = $data;
			}

			return $transient;
		}


		/**
		 * Add an update notice in the pluigns page
		 *
		 * @since 1.0.0
		 */
		function in_plugin_update_message( $data, $response ) {

			if( empty( $data['package'] ) && current_user_can( 'update_plugins' ) ){

				if( ! $this->is_active() ){
					$new_notice = $this->strings('inline-register-item-notice');
				}
				elseif( ! $this->get( 'can_update' ) && $this->get( 'is_support_required' ) ){
					$new_notice = $this->strings('inline-renew-support-notice');
				}

				if( ! empty( $new_notice ) ){
					echo ' <strong>'. $new_notice .'</strong>';
				}
			}

			// Upgrade Extra Notice
			if( ! empty( $data['upgrade_notice'] ) ) {

				echo '</p>';

				// Upgrade notice CSS
				$upgrade_notice_css = apply_filters( "{$this->prefix}/ep/in_plugin_update_message/css", "
					#{$this->prefix}-in-plugin-update-message p{
						margin: 0 -12px;
						padding: 8px 14px;
					}
					#{$this->prefix}-in-plugin-update-message > p:first-child{
						border-top: 1px solid #ffb900;
					}
					#{$this->prefix}-in-plugin-update-message p:before,
					#{$this->prefix}-in-plugin-update-message p[aria-label],
					#{$this->prefix}-in-plugin-update-message-dummy-p{
						display:none !important;
					}
				");

				if( ! empty( $upgrade_notice_css ) ){
					echo '<style type="text/css">'. $upgrade_notice_css .'</style>';
				}

				$notice_html = apply_filters( "{$this->prefix}/ep/in_plugin_update_message/html", '<div id="'. $this->prefix .'-in-plugin-update-message">%s</div>', $this->prefix );
				$notice_text = apply_filters( "{$this->prefix}/ep/in_plugin_update_message/html", wpautop( $data['upgrade_notice'] ), $data['upgrade_notice']);

				printf( $notice_html, $notice_text );

				echo '<p id="'. $this->prefix .'-in-plugin-update-message-dummy-p">';
			}
		}


		/**
		 * Inject theme update data
		 *
		 * @since 1.0.0
		 *
		 * @param object $transient The pre-saved value of the `update_themes` site transient.
		 * @return object
		 */
		function update_themes( $transient ) {

			if ( isset( $transient->checked ) ) {

				$transient->response[ $this->slug ] = array(
					'theme'       => $this->slug,
					'new_version' => $this->get( 'new_version' ),
					'url'         => $this->get( 'changelog_url' ),
				);

				// Check if license is active and customer can update the theme
				if( $this->is_active() && $this->get( 'can_update' ) ){
					$transient->response[ $this->slug ]['package'] = add_query_arg( array( 'elitepack_deferred' => true ), admin_url() );
				}
			}

			return $transient;
		}


		/**
		 * Change the inline update message
		 *
		 * @since 1.0.0
		 */
		function update_theme_inline_notice( $themes ){

			if( ! empty( $themes[ $this->slug ]['update'] ) && current_user_can( 'update_themes' ) ){

				$message = $themes[ $this->slug ]['update'];

				if( ! $this->is_active() ){
					$new_notice = $this->strings('inline-register-item-notice');
				}
				elseif( ! $this->get( 'can_update' ) && $this->get( 'is_support_required' ) ){
					$new_notice = $this->strings('inline-renew-support-notice');
				}

				if( ! empty( $new_notice ) ){
					$themes[ $this->slug ]['update'] = preg_replace('/<em>.+?<\/em>/', $new_notice, $message );
				}
			}

			// Description Section
			if( $description = $this->get( 'description' ) ){
				$themes[ $this->slug ]['description'] = wpautop( $description );
			}

			return $themes;
		}


		/**
		 * Change the inline update message
		 *
		 * @since 1.0.0
		 */
		function update_theme_inline_notice_js(){

			if( ! $this->is_active() ){
				$new_notice = $this->strings('inline-register-item-notice');
			}
			elseif( ! $this->get( 'can_update' ) && $this->get( 'is_support_required' ) ){
				$new_notice = $this->strings('inline-renew-support-notice');
			}

			if( ! empty( $new_notice ) ){
				?>
				<script type="text/javascript">
					jQuery(window).load(function() {
						var $theme = jQuery('*[data-slug="<?php echo $this->slug ?>"]').find('.update-message p');
						if( typeof $theme != 'undefined') {
							$theme.append( ' <strong><?php echo $new_notice; ?></strong>' );
						}
					});
				</script>
				<?php
			}
		}


		/**
		 * Display the update notifications
		 *
		 * @return void
		 */
		function update_nag() {

			/**
			 * show_notice() contains a filter to disable notices depending on the notice ID,
			 * We use a dynamic ID here depends on the version number.
			 * Use this filter to disable ALL epiring support notices.
			 */
			if( apply_filters( "{$this->prefix}/ep/notice/new_update/disable", false ) ){
				return false;
			}

			$version_number = $this->get( 'new_version' );

			$notice_args = array(
				'type'        => 'warning',
				'dismissible' => true,
			);

			$details_url = add_query_arg(
				array(
					'TB_iframe' => 'true',
					'width'     => 1024,
					'height'    => 800,
				),
				$this->get( 'changelog_url' )
			);

			$message = sprintf(
				$this->strings('update-available'),
				$this->name,
				'<a href="'. $details_url .'" class="thickbox">',
				$version_number,
				'</a>'
			);

			if( ! $this->is_active() ){
				$take_action = $this->strings('inline-register-item-notice');
			}
			elseif( ! $this->get( 'can_update' ) && $this->get( 'is_support_required' ) ){
				$take_action = $this->strings('inline-renew-support-notice');

				$notice_args['buttons'] = array(
					array(
						'text'    => $this->strings('renew-support'),
						'url'     => $this->purchase_url(),
						'new_tab' => true,
						'class'   => 'button button-primary',
					),
				);
			}
			else{

				// Update URL
				if( $this->is_plugin ){
					$update_url = array(
						'action'   => 'upgrade-plugin',
						'plugin'   => urlencode( $this->slug ),
						'_wpnonce' => wp_create_nonce( 'upgrade-plugin_' . $this->slug ),
					);
				}
				else{
					$update_url = array(
						'action'   => 'upgrade-theme',
						'theme'    => urlencode( $this->slug ),
						'_wpnonce' => wp_create_nonce( 'upgrade-theme_' . $this->slug ),
					);
				}

				$take_action = '<a href="'. add_query_arg( $update_url, self_admin_url( 'update.php' ) )  .'">'. $this->strings('update-now') .'</a>';
			}

			if( ! empty( $take_action ) ){
				$message .= ' <strong>'. $take_action .'</strong>';
			}

			$this->notice(
				'new-update-'.sanitize_key( $version_number ),
				apply_filters( "{$this->prefix}/ep/notice/new_update/title", false ),
				$message,
				apply_filters( "{$this->prefix}/ep/notice/new_pdate_args", $notice_args )
			);

		}


		/**
		 * Epiring support notice
		 *
		 * @return void
		 */
		function expiring_support_notice() {

			/**
			 * show_notice() contains a filter to disable notices depending on the notice ID,
			 * We use a dynamic ID here depends on the support expiration date.
			 * Use this filter to disable ALL epiring support notices.
			 */
			if( apply_filters( "{$this->prefix}/ep/notice/support_expiring/disable", false ) ){
				return false;
			}

			// Get Support info
			$support_info = $this->support_period_info();

			// Check if the support is expiring
			if( empty( $support_info['status'] ) || $support_info['status'] != 'expiring' ){
				return;
			}

			$notice_args = apply_filters( "{$this->prefix}/ep/notice/support_expiring_args", array(
				'type'        => 'error',
				'dismissible' => true,

				'buttons' => array(
					array(
						'text'    => $this->strings('renew-support'),
						'url'     => $this->purchase_url(),
						'new_tab' => true,
						'class'   => 'button button-primary',
					)
				)
			));

			$message = sprintf(
				$this->strings('support-expiring-notice'),
				'<strong>'. $support_info['period'] .'</strong>'
			);

			$support_expiration_slug = ! empty( $support_info['date'] ) ? wp_date( 'ynj', strtotime( $support_info['date'] ) ) : '';

			$this->notice(
				"support-expiring-{$support_expiration_slug}",
				apply_filters( "{$this->prefix}/ep/notice/support_expiring/title", false ),
				$message,
				$notice_args
			);
		}


		/**
		 * Display live notices
		 *
		 * @return void
		 */
		function live_notices() {

			/**
			 * Use this filter to disable ALL live notices.
			 */
			if( apply_filters( "{$this->prefix}/ep/notice/live_notice/disable", false ) ){
				return false;
			}

			$messages = $this->get( 'messages' );

			if( empty( $messages ) || ! is_array( $messages ) ){
				return;
			}

			$count = 0;
			
			$today = date('U');
			$dismissed_notices = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ));

			foreach ( $messages as $message ) {

				// Max allowed number of live notices that can be shown in the same time
				if( $count == apply_filters( "{$this->prefix}/ep/notice/live_notice/max_number", 1 ) ){
					break;
				}

				//--
				$message = wp_parse_args(
					$message, array(
						'id'          => false,
						'dismissible' => false,
						'type'        => false,
						'title'       => false,
						'message'     => false,
						'buttons'     => false,
					)
				);

				// Message content is required, we check it here to avoid counting an empty message against the total max number
				if( empty( $message['message'] ) ){
					continue;
				}

				// This notice is dismissible and it already dismissed
				if( ! empty( $message['id'] ) && $message['dismissible'] ){
					if( in_array( sanitize_key( $this->prefix . '-' . $message['id'] ), $dismissed_notices ) ){
						continue;
					}
				}

				// Start date
				if( ! empty( $message['start_date'] )){
					$start_date = strtotime( $message['start_date'] );
					if( $start_date > $today ){
						continue;
					}
				}

				// Expire date
				if( ! empty( $message['expire_date'] )){
					$expire_date = strtotime( $message['expire_date'] );
					if( $expire_date < $today ){
						continue;
					}
				}

				// Args
				$message_args = array(
					'type'        => $message['type'],
					'dismissible' => $message['dismissible']
				);

				// Buttons
				if( ! empty( $message['buttons'] ) ){

					foreach ( $message['buttons'] as $key => $button ) {
						$message['buttons'][$key]['new_tab'] = true;

						if( ! empty( $button['type'] ) ){
							if( $button['type'] == 'primary' ){
								$message['buttons'][$key]['class'] = 'button button-primary';
							}
							elseif( $button['type'] == 'secondary' ){
								$message['buttons'][$key]['class'] = 'button';
							}
						}
					}

					$message_args['buttons'] = $message['buttons'];
				}

				// Notice
				$this->notice(
					$message['id'],
					$message['title'],
					$message['message'],
					$message_args
				);

				$count++;
			}
		}


		/**
		 * Filters text content and strips out disallowed HTML.
		 *
		 * @since 1.0.0
		 */
		function kses( $string ){

			// wp_kses uses wp_kses_normalize_entities which breacks urls
			$string = wp_kses_no_null( $string, array( 'slash_zero' => 'keep' ) );
			$string = wp_kses_hook(  $string, $this->allowed_html, wp_allowed_protocols() );
			$string = wp_kses_split( $string, $this->allowed_html, wp_allowed_protocols() );
			return $string;
		}


		/**
		 * Show the Notice
		 *
		 * @since 1.0.0
		 */
		function notice( $id = false, $title = false, $message, $args = array() ){

			// ID
			$id = ! empty( $id ) ? sanitize_key( $this->prefix . '-' . $id ) : '';

			// show notice?
			if( ! $this->show_notice( $id ) ){
				return;
			}

			// Message is required
			if( empty( $message ) ){
				return;
			}

			$args = wp_parse_args(
				$args, array(
					'class'       => false,
					'type'        => '',  // 'success', 'warning', 'error', 'info'
					'dismissible' => true,
					'title_tag'   => 'h3',
					'buttons'     => false
				)
			);

			// Classes
			$classes = array( 'notice', "{$this->prefix}-notice" );

			if( $args['class'] ){
				$classes[] = $args['class'];
			}

			if( $args['dismissible'] ){
				$classes[] = 'is-dismissible';
			}

			if( $args['type'] && in_array( $args['type'], array( 'success', 'warning', 'error', 'info' ) ) ){
				$classes[] = 'notice-'. $args['type'];
			}

			// Message
			if( strpos( $message, '<p>' ) === false ){
				$message = '<p>'. $message .'</p>';
			}

			?>

			<div id="<?php echo esc_attr( $id ) ?>" class="<?php echo esc_attr( join( ' ', $classes ) ) ?>">
				<?php

					// Title
					if( ! empty( $title ) ){

						$the_title = wp_strip_all_tags( $title );

						if( ! empty( $args['title_tag'] ) ){
							$the_title = sprintf( '<%1$s class="notice-title">%2$s</%1$s>', $args['title_tag'], $the_title );
						}

						echo apply_filters( "{$this->prefix}/ep/notice/title", $the_title, $title, $args );
					}

					// Content
					$message = apply_filters( "{$this->prefix}/ep/notice/message", $message, $args );
					echo wpautop( $this->kses( $message ) );

					// Buttons
					if( ! empty( $args['buttons'] ) ){

						echo apply_filters( "{$this->prefix}/ep/notice/before_buttons_tag", '<p>', $args );

						foreach ( $args['buttons'] as $button ) {

							if( ! empty( $button['text'] ) && ! empty( $button['url'] ) ){

								$class  = ! empty( $button['class'] )   ? $button['class'] : 'plain-button';
								$target = ! isset( $button['new_tab'] ) ? '_self' : '_blank';

								echo '<a target="'. esc_attr( $target ) .'" class="'. esc_attr( $class ) .'" href="'. esc_url( $button['url'] ) .'">'. wp_strip_all_tags( $button['text'] ) .'</a> ';
							}
						}

						echo apply_filters( "{$this->prefix}/ep/notice/after_buttons_tag", '</p>', $args );
					}
				?>
			</div>
			<?php
		}


		/**
		 * Check if the current page
		 *
		 * @since 1.0.0
		 */
		function show_notice( $id = false ){

			// Filter to disable notices
			if( apply_filters( "{$this->prefix}/ep/notice/show", false, $id ) ){
				return false;
			}

			// Check if the notice is dismissed before
			if( ! empty( $id ) ){
				$dismissed_notices = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
				if( in_array( $id, $dismissed_notices ) ){
					return false;
				}
			}

			// If screen is empty we want this shown on all screens.
			if ( ! $this->notice_screens || empty( $this->notice_screens ) ) {
				return true;
			}

			// Make sure the get_current_screen function exists.
			if ( ! function_exists( 'get_current_screen' ) ) {
				require_once ABSPATH . 'wp-admin/includes/screen.php';
			}

			// Check if we're on one of the defined screens.
			return ( in_array( get_current_screen()->id, $this->notice_screens, true ) );
		}


		/**
		 * Check if the current page
		 *
		 * @since 1.0.0
		 */
		function inline_scripts(){

			$scripts = "
				jQuery(document).on('click', '.$this->prefix-notice .notice-dismiss', function(){
					var ID = jQuery(this).closest('.notice').attr('id');
					if(typeof ID != 'undefined') {
						jQuery.ajax({
							url : ajaxurl,
							type: 'post',
							data: {
								pointer: ID,
								action : 'dismiss-wp-pointer',
							},
						});
					}
				});
			";

			wp_add_inline_script( 'jquery', apply_filters( "{$this->prefix}/ep/notice/js", $scripts ) );
		}


		/**
		 * Get Demos
		 *
		 * @since 1.0.0
		 */
		function get_demos(){

			if( $this->get('demos') ){

				if( false === ( $demos = get_transient( $this->cached_demos_key ) ) ){
					$demos = $this->api_request( $this->api_endpoint_url('get_demos') );
					set_transient( $this->cached_demos_key, $demos, apply_filters( "{$this->prefix}/ep/demos/cache_expiration", DAY_IN_SECONDS ) );
				}

				if( ! is_wp_error( $demos ) ){
					return $demos;
				}
			}

			return false;
		}


		/**
		 * Get Plugins
		 *
		 * @since 1.0.0
		 */
		function get_plugins(){

			if( $this->get('plugins') ){

				if( false === ( $plugins = get_transient( $this->cached_plugins_key ) ) ){
					$plugins = $this->api_request( $this->api_endpoint_url('get_plugins') );
					set_transient( $this->cached_plugins_key, $plugins, apply_filters( "{$this->prefix}/ep/plugins/cache_expiration", DAY_IN_SECONDS ) );
				}

				if( ! is_wp_error( $plugins ) ){
					return $plugins;
				}
			}

			return false;
		}


		/**
		 * Clear Cached data
		 *
		 * @since 1.0.0
		 */
		function clear_stored_data(){
			$this->clear_cache();
			delete_option( $this->license_status_key );
			delete_option( $this->license_db_key );
			delete_transient( "{$this->prefix}_ep_download_link" );
		}


		/**
		 * Inserts the deactivation form on plugins/Themes page
		 *
		 * @since 1.0.0
		 */
		function is_deactivation_feedback_on(){

			$status = true;

			// Check if the item is registered
			if( ! $this->is_active() ){
				$status = false;
			}
			else{

				// Check if the Deactivation Feedback is active
				$reasons = apply_filters( "{$this->prefix}/ep/deactivation_feedback/reasons", $this->get( 'deactivation_reasons' ) );
				if( ! $reasons || ! is_array( $reasons ) ){
					$status = false;
				}

				// Check if feedback is active for Dev. sites
				elseif( $this->is_dev() && ! $this->get( 'deactivation_on_dev' ) ){
					$status = false;
				}

			}

			return apply_filters( "{$this->prefix}/ep/deactivation_feedback/status", $status );
		}


		/**
		 * Inserts the deactivation form on plugins/Themes page
		 *
		 * @since 1.0.0
		 */
		function insert_deactivation_form(){

			// Check if the Deactivation Feedback is enabled
			if( ! $this->is_deactivation_feedback_on() ){
				return;
			}

			// Only insert in the deactivation pages
			$dashboared_screens = $this->is_plugin ? array( 'plugins', 'plugins-network' ) : array( 'themes', 'themes-network' );

			if ( ! in_array( get_current_screen()->id, $dashboared_screens ) ) {
				return;
			}

			// Get the Deactivation Reasons, we already checked it is an array in the is_deactivation_feedback_on()
			$reasons = apply_filters( "{$this->prefix}/ep/deactivation_feedback/reasons", $this->get( 'deactivation_reasons' ) );

			// Deactivation Modal ID
			$modal_id = apply_filters( "{$this->prefix}/ep/deactivation_feedback/modal/id", "{$this->prefix}-ep-deactivate-modal" );

			// Deactivation Modal CSS
			$modal_css = apply_filters( "{$this->prefix}/ep/deactivation_feedback/modal/css", "
				#{$modal_id}-overlay{
					display: none;
					position: fixed;
					width: 100%;
					height: 100%;
					top: 0;
					left: 0;
					background: rgba(0, 0, 0, 0.7);
					z-index: 99999
				}
				#{$modal_id}{
					display: none;
					position: fixed;
					width: 550px;
					height: auto;
					top: 50%;
					left: 50%;
					background: #fff;
					color: #444;
					transform: translateX(-50%) translateY(-50%);
					box-shadow: 0 1px 20px 5px rgba(0,0,0,0.3);
					z-index: 100000;
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
					-ms-interpolation-mode: nearest-neighbor;
					image-rendering: optimizeQuality;
					text-rendering: optimizeLegibility
				}
				#{$modal_id} .ep-modal-header {
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 0 16px 0 32px;
					border-bottom: 1px solid #EEE
				}
				#{$modal_id} .ep-modal-header > div {
					display: flex
				}
				#{$modal_id} .ep-modal-footer {
					display: flex;
					align-items: center;
					justify-content: space-between;
					height: 64px;
					padding: 0 32px;
					background: #F5F5F5
				}
				#{$modal_id} .ep-modal-close {
					font-size: 0;
					color: #AAA;
					border: none;
					background: none;
					cursor: pointer
				}
				#{$modal_id} .ep-modal-close:hover {
					color: #008ec2
				}
				#{$modal_id} .ep-modal-close:before {
					font-size: 1.25rem;
					line-height: 1
				}
				#{$modal_id} .ep-modal-cancel {
					color: #0073aa;
					text-decoration: underline;
					margin-left: 8px;
					line-height: 28px;
					border: none;
					background: none;
					cursor: pointer;
					font-weight: 500
				}
				#{$modal_id} .ep-modal-cancel:hover {
					color: #008ec2
				}
				#{$modal_id} .ep-modal-content {
					padding: 8px 32px;
					font-weight: 500;
				}
				#{$modal_id} .button-primary.ep-modal-disabled {
					opacity: 0.2;
					color: #fff !important;
					cursor: not-allowed;
					pointer-events: none
				}
				#{$modal_id} h2,
				#{$modal_id} h3 {
					display: inline-block;
					font-size: 1rem;
				}
				#{$modal_id} h2 {
					max-width: 430px;
					margin: 12px 0
				}
				#{$modal_id} h3 {
					margin: 8px 0
				}
				#{$modal_id} ul li {
					padding: 1px 0
				}
				#{$modal_id} input[type=radio] {
					margin-top: 1px;
					margin-right: 8px
				}
				#{$modal_id} .ep-modal-reason-details {
					display: none;
					padding-left: 26px;
					margin-top: 8px
				}
				#{$modal_id} .ep-modal-reason-details input[type=text],
				#{$modal_id} .ep-modal-reason-details textarea {
					font-size: 12px;
				}
				#{$modal_id} .ep-modal-reason-details textarea {
					width: 100%;
					height: 60px;
					padding: 5px
				}
			" );

			if( ! empty( $modal_css ) ){
				echo '<style type="text/css">'. $modal_css .'</style>';
			}

			$button_trigger = $this->is_plugin ? '.active[data-plugin="'. $this->slug .'"] .deactivate a' : 'a.activate';

			// Modal JS
			$modal_js = apply_filters( "{$this->prefix}/ep/deactivation_feedback/modal/js", "
				jQuery(document).ready(function(){

					// Deactivate/Switch button
					jQuery(document).on('click', '{$button_trigger}', function() {
						jQuery('#{$modal_id}').show();
						jQuery('#{$modal_id}-overlay').show();
						jQuery('#{$modal_id}-send-deactivation, #{$modal_id}-deactivation-skip').attr( 'href', jQuery(this).attr('href') );
						return false;
					});

					// Escape key
					jQuery(document).bind('keyup', function(event){
						if(event.keyCode == 27){
							jQuery('#{$modal_id}').hide();
							jQuery('#{$modal_id}-overlay').hide();
							return false;
						}
					});

					// Cancel Button
					jQuery(document).on('click', '.{$modal_id}-cancel, #{$modal_id}-overlay', function() {
						jQuery('#{$modal_id}').hide();
						jQuery('#{$modal_id}-overlay').hide();
						return false;
					});

					// Select reason
					jQuery('.{$modal_id}-reason').change(function(){
						jQuery('#{$modal_id}').find('.ep-modal-reason-details').find('input, textarea').val('');
						jQuery('#{$modal_id}-the-details').val('');
						jQuery('#{$modal_id}').find('.ep-modal-reason-details').hide();
						jQuery('#{$modal_id}-the-reason').val( jQuery(this).val() );

						jQuery('.{$modal_id}-reason-'+ jQuery(this).val() +'-details').show();

						if( jQuery('.{$modal_id}-reason-'+ jQuery(this).val() +'-details').find('input, textarea').length > 0 ){
							jQuery('.{$modal_id}-reason-'+ jQuery(this).val() +'-details').find('input, textarea').focus();
							jQuery('#{$modal_id}-send-deactivation').addClass('ep-modal-disabled').attr('disabled','disabled');
						}
						else{
							jQuery('#{$modal_id}-send-deactivation').removeClass('ep-modal-disabled').removeAttr('disabled');
						}
					});

					// Write text
					jQuery('#{$modal_id}').find('.ep-modal-reason-details').find('input, textarea').keyup(function() {
						jQuery('#{$modal_id}-the-details').val( jQuery(this).val() );
						if( jQuery('#{$modal_id}-the-details').val() != '' ){
							jQuery('#{$modal_id}-send-deactivation').removeClass('ep-modal-disabled').removeAttr('disabled');
						}
						else{
							jQuery('#{$modal_id}-send-deactivation').addClass('ep-modal-disabled').attr('disabled','disabled');
						}
					});

					/**
					 * AJAX Send Feedback
					 */
					jQuery(document).on('click', '#{$modal_id}-send-deactivation', function() {

						var link = jQuery(this).attr('href');

						jQuery.post(
							ajaxurl,
							{
								action: '{$this->slug}_send_feedback',
								reason: jQuery('#{$modal_id}-the-reason').val(),
								details: jQuery('#{$modal_id}-the-details').val(),
								_ajax_nonce: '". wp_create_nonce( "{$this->prefix}_ep_feedback_nonce" ) ."',
							},
							function(response) {
								window.location.href = link;
							}
						);

						return false;
					});

				});
			");

			if( ! empty( $modal_js ) ){
				echo '<script type="text/javascript">'. $modal_js .'</script>';
			}

			?>

			<div id="<?php echo $modal_id ?>" class="ep-modal <?php echo "{$this->prefix}-ep-modal" ?>">
				<div class="ep-modal-header">
					<div>
						<h2><?php printf( $this->strings('feedback'), $this->name ); ?></h2>
					</div>
					<button class="ep-modal-close <?php echo $modal_id ?>-cancel dashicons dashicons-no"><span class="screen-reader-text"><?php echo $this->strings('cancel'); ?></span></button>
				</div>
				<div class="ep-modal-content">
					<h3><?php echo $this->strings('deactivation-share-reason'); ?></h3>
					<ul>
						<?php

							foreach ( $reasons as $reason_id => $the_reason ) {
								?>
								<li>
									<?php do_action( "{$this->prefix}/ep/deactivation_feedback/modal/before_reason", $reason_id ); ?>

									<input type="radio" class="<?php echo $modal_id ?>-reason" name="<?php echo $modal_id ?>-reason" id="<?php echo esc_attr( $modal_id .'-reason-'. $reason_id ) ?>" value="<?php echo esc_attr( $reason_id ) ?>">
									<label for="<?php echo esc_attr( $modal_id .'-reason-'. $reason_id ) ?>"><?php echo $the_reason['title']; ?></label>

									<?php

									$description = ($this->is_support_active() ? $the_reason['desc_active_support'] : (isset($the_reason['desc_expired_support'])?$the_reason['desc_expired_support']:''));

									if( ! empty( $description ) || ! empty( $the_reason['details_title'] ) ) { ?>
										<div class="ep-modal-reason-details <?php echo esc_attr( $modal_id .'-reason-'. $reason_id .'-details' ) ?>">
											<?php

												do_action( "{$this->prefix}/ep/deactivation_feedback/modal/before_reason_details", $reason_id );

												if( ! empty( $description ) ) {
													echo '<p>'. $description .'</p>';
												}

												if( ! empty( $the_reason['details_title'] ) ) {

													do_action( "{$this->prefix}/ep/deactivation_feedback/modal/before_reason_details_field", $reason_id );

													if( ! empty( $the_reason['details_type'] ) && $the_reason['details_type'] == 1 ) {
														?>
															<textarea placeholder="<?php echo esc_attr( $the_reason['details_title'] ); ?>"></textarea>
														<?php
													}
													else{
														?>
															<input class="regular-text" type="text" placeholder="<?php echo esc_attr( $the_reason['details_title'] ); ?>">
														<?php
													}

													do_action( "{$this->prefix}/ep/deactivation_feedback/modal/after_reason_details_field", $reason_id );
												}

												do_action( "{$this->prefix}/ep/deactivation_feedback/modal/after_reason_details", $reason_id );
											?>
										</div>
									<?php } ?>
								</li>
								<?php

								do_action( "{$this->prefix}/ep/deactivation_feedback/modal/after_reason", $reason_id );
							}
						?>
					</ul>

					<input id="<?php echo $modal_id ?>-the-reason" type="hidden" value="">
					<input id="<?php echo $modal_id ?>-the-details" type="hidden" value="">
				</div>
				<div class="ep-modal-footer">
					<div>
						<a id="<?php echo $modal_id ?>-send-deactivation" href="#" class="button button-primary ep-modal-disabled ep-send-deactivation" disabled><?php echo $this->strings('send'); ?></a>
						<button class="button button-secondary <?php echo $modal_id ?>-cancel"><?php echo $this->strings('cancel'); ?></button>
					</div>
					<a href="#" id="<?php echo $modal_id ?>-deactivation-skip" class="button button-secondary ep-deactivate-skip"><?php echo $this->strings('skip'); ?></a>
				</div>
			</div>

			<div id="<?php echo $modal_id ?>-overlay" class="ep-mpdal-overlay"></div>
			<?php
		}


		/**
		 * Send the deactivation feedback
		 *
		 * @since 1.0.0
		 */
		public function send_deactivation_feedback() {

			check_ajax_referer( "{$this->prefix}_ep_feedback_nonce" );

			if( $this->is_deactivation_feedback_on() && ! empty( $_POST['reason'] ) ){

				do_action( "{$this->prefix}/ep/deactivation_feedback/before_send", $data, $request );

				$data = array(
					'uninstall_reason' => wp_strip_all_tags( $_POST['reason'] ),
				);

				if( ! empty( $_POST['details'] ) ) {
					$data['uninstall_details'] = wp_strip_all_tags( $_POST['details'] );
				}

				$request = $this->api_request( $this->api_endpoint_url('uninstall_feedback'), apply_filters( "{$this->prefix}/ep/api/deactivation_feedback/body", $data ), false );

				do_action( "{$this->prefix}/ep/deactivation_feedback/after_send", $data, $request );
			}

			return wp_send_json_success();
		}


		/**
		 * Return customer review
		 *
		 * @since 1.0.0
		 */
		public function customer_review() {
			return $this->get('review');
		}

	}

}

// END OF TEXT
