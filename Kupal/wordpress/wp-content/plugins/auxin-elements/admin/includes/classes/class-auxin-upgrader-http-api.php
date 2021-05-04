<?php

class Auxin_Upgrader_Http_Api {

    protected $api = 'http://api.averta.net/envato/items/';

    function __construct(){}

    /**
     * Get single setting value
     *
     * @param string $option
     * @param string $section
     * @param string $default
     * @return string
     */
    private function get_setting( $option, $section, $default = '' ) {
        $options = get_site_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
        return $default;
    }

    /**
     * Make a wordpress remote post request on averta API
     *
     * @param array $args
     * @return void
     */
    private function remote_post( $args ){
        global $wp_version;

        $request_string = array(
            'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url'),
            'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30: 3),
            'body'       => $args
        );

        // Start checking for an update
        $request = wp_remote_post( $this->api, $request_string );

        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
            return false;
        }

        return $request;
    }

    /**
     * Get our non official items list
     *
     * @return array
     */
    private function get_non_official_items(){
        return apply_filters( 'auxin_set_non_official_items', array(
            '3909293'    => 'phlox-pro',
            '3909293-03' => 'auxin-shop',
            '3909293-04' => 'auxin-the-news',
            '3909293-05' => 'auxin-pro-tools',
            '3909293-06' => 'masterslider-wp',
            '3909293-07' => 'js_composer',
            '3909293-08' => 'bdthemes-element-pack',
            '3909293-09' => 'Ultimate_VC_Addons',
            '3909293-10' => 'waspthemes-yellow-pencil',
            '3909293-11' => 'LayerSlider',
            '3909293-12' => 'revslider',
            '3909293-13' => 'go_pricing',
            '3909293-14' => 'convertplug'
        ) );
    }

    /**
     * Get download link from averta API
     *
     * @return void
     */
    public function get_download_link( $key ){

        $token  = $this->get_setting( 'token' , AUXELS_PURCHASE_KEY );
        $token  = empty( $token ) ? $this->get_setting( 'token' , THEME_ID . '_license' ) : $token;

        if( empty( $token ) ) {
            return new WP_Error( 'no_credentials',
                __( 'Envato username, API key and your item purchase code are required for downloading updates from Envato marketplace.', 'auxin-elements' )
            );
        }

        $items  = $this->get_non_official_items();

        if( ! in_array( $key, $items ) ) {
            return new WP_Error( 'no_credentials',
                '"' . $key . '" ' .  __( 'Is not exist in our non official list. ', 'auxin-elements' )
            );
        }
        $item_ID = array_search( $key, $items );

        $request = $this->remote_post( array(
            'cat'     => 'download-purchase',
            'action'  => 'token',
            'item-id' => $item_ID,
            'token'   => $token,
            'url'     => get_site_url()
        ) );

        if( $request === false ){
            return new WP_Error( 'process_faild',
                __( 'Error in getting download link.', 'auxin-elements' )
            );
        }

        $response = wp_remote_retrieve_body( $request );
        $response = json_decode( $response, true );

        if( ! isset( $response['download_url'] ) || empty( $response['download_url'] ) ) {
            // Envato API error ..
            return new WP_Error( 'no_credentials',
                __( 'Error on connecting to download API...', 'auxin-elements' )
            );
        }

        return $response['download_url'];
    }

}