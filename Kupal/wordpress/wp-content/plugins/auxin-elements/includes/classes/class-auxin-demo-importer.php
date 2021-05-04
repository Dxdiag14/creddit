<?php
/**
 * Demo Importer for auxin framework
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

class Auxin_Demo_Importer {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Skip these options during demo import process
     */
    public $skip_options = [
        'initial_date_free',
        'initial_version_free',
        'initial_date_pro',
        'initial_version_pro',
        'client_key',
        'imported_demo_id'
    ];

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


    function __construct() {
        add_action( 'wp_ajax_auxin_demo_data'       , array( $this, 'import') );
        add_action( 'wp_ajax_auxin_templates_data'  , array( $this, 'templates') );
        add_action( 'wp_ajax_import_step'           , array( $this, 'import_step') );
    }

    public function templates(){

        // Check security issues
        if ( ! isset( $_POST['ID'] ) || ! isset( $_POST['verify'] ) || ! wp_verify_nonce( $_POST['verify'], 'aux-template-manager' ) ) {
            // This nonce is not valid.
            wp_send_json_error( $this->error_template() );
        }

        $data            = false;
        $template_ID     = isset( $_POST['ID'] ) ? $_POST['ID'] : '';
        $template_type   = isset( $_POST['type'] ) ? $_POST['type'] : '';
        $page_template   = isset( $_POST['tmpl'] ) ? $_POST['tmpl'] : '';
        $template_title  = isset( $_POST['title'] ) ? $_POST['title'] : 'PHLOX #' . $template_ID;
        $template_status = isset( $_POST['status'] ) ? $_POST['status'] : 'import';
        $template_data_key = sanitize_key( "auxin_template_kit_{$template_type}_data_for_origin_id_{$template_ID}" );

        if( $template_status === 'copy' && false !== ( $data = auxin_get_transient( $template_data_key ) ) ) {
            wp_send_json_success( array( 'status' => 'copy', 'result' => json_decode( $data , true ), 'label' => esc_html__( 'Import content', 'auxin-elements' ) ) );
        }

        ob_start();

        if( ( $template_status === 'create_my_template' || $template_status === 'create_new_page' ) && false !== ( $data = auxin_get_transient( $template_data_key ) ) ){
            $post_type = $template_status === 'create_my_template' ? 'elementor_library' : 'page';
            $args = array(
              'post_title'    => wp_strip_all_tags( $template_title ),
              'post_status'   => 'publish',
              'post_type'     => $post_type
            );
            $post_id = wp_insert_post( $args );
            if( ! is_wp_error( $post_id ) ){

                $json_content = json_decode( $data , true );
                $elementor_version = defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '2.9.0';

                update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
                update_post_meta( $post_id, '_elementor_data', $json_content['content'] );
                update_post_meta( $post_id, '_elementor_version', $elementor_version );

                if( ! empty( $page_template ) ){
                    update_post_meta( $post_id, '_wp_page_template', $page_template );
                }

                if( $post_type === 'elementor_library' ) {
                    update_post_meta( $post_id, '_elementor_template_type', $template_type );
                }
            ?>
                <div class="clearfix">
                    <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/success.svg' ); ?>" />
                    <h3><?php esc_html_e( 'Page has been successfully generated.', 'auxin-elements' ); ?></h3>
                    <div class="aux-template-actions">
                        <a href="<?php echo esc_url( get_edit_post_link( $post_id ) ) ;?>" target="_blank" class="aux-button aux-primary aux-medium">
                            <?php _e( 'Edit Page', 'auxin-elements' ); ?>
                        </a>
                        <a href="#" class="aux-button aux-medium aux-outline aux-transparent aux-pp-close">
                            <?php _e( 'Close', 'auxin-elements' ); ?>
                        </a>
                    </div>
                </div>
            <?php
                wp_send_json_success( array(
                    'status'  => 'insert',
                    'result'  => ob_get_clean(),
                ) );

            }else{
                //there was an error in the post insertion,
                $data = false;
            }
        } else {
            $data = $this->fetch_template_data( $template_ID );
        }

        if( $data ) {
            // Set transient for 48h
            auxin_set_transient( $template_data_key, $data, WEEK_IN_SECONDS );
        ?>
            <div class="clearfix">
                <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/success.svg' ); ?>" />
                <h3><?php esc_html_e( 'Template content has been successfully imported.', 'auxin-elements' ); ?></h3>
                <div class="aux-template-actions">
                    <a  href="#"
                        class="aux-button aux-orange aux-medium aux-copy-template aux-iconic-action"
                        data-template-id="<?php echo esc_attr( $template_ID ); ?>"
                        data-template-type="<?php echo esc_attr( $template_type ); ?>"
                        data-status-type="copy"
                        data-nonce="<?php echo wp_create_nonce( 'aux-template-manager' ); ?>"
                    ><span><?php _e( 'Copy to clipboard', 'auxin-elements' ); ?></span></a>
                    <a  href="<?php esc_url(  add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'type' => 'progress' , 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) ) ); ?>"
                        class="aux-button aux-green aux-import-template aux-open-modal aux-medium aux-copy-template aux-iconic-action"
                        data-template-id="<?php echo esc_attr( $template_ID ); ?>"
                        data-template-type="<?php echo esc_attr( $template_type ); ?>"
                        data-template-page-tmpl="<?php echo esc_attr( $page_template ); ?>"
                        data-template-title="<?php echo esc_attr( $template_title ); ?>"
                        data-status-type="create_my_template"
                        data-nonce="<?php echo wp_create_nonce( 'aux-template-manager' ); ?>"
                    ><span><?php _e( 'Import to my templates', 'auxin-elements' ); ?></span></a>
                    <a href="#" class="aux-button aux-medium aux-outline aux-transparent aux-pp-close">
                        <?php _e( 'Close', 'auxin-elements' ); ?>
                    </a>
                </div>
            </div>
        <?php
            wp_send_json_success( array(
                'status'  => 'import',
                'result'  => ob_get_clean(),
                'label'   => esc_html__( 'Copy to clipboard', 'auxin-elements' ),
            ) );
        }

        wp_send_json_error( $this->error_template( $template_ID, $template_type ) );
    }

    public function fetch_template_data( $template_ID ){
        $data = $this->parse( 'http://library.phlox.pro/templates/?id='. $template_ID, 'data' );
        return $this->update_elementor_data( $data, 'upload' );
    }

    public function error_template( $template_ID = '', $template_type = '' ){
        ob_start();
    ?>
        <div class="clearfix">
            <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/failed.svg' ); ?>" />
            <h3><?php esc_html_e( 'Process failed!.', 'auxin-elements' ); ?></h3>
            <div class="aux-template-actions">
                <a href="<?php esc_url(  add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'type' => 'progress' , 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) ) ); ?>"
                    class="aux-button aux-primary aux-medium aux-open-modal aux-import-template"
                    data-template-id="<?php echo esc_attr( $template_ID ); ?>"
                    data-template-type="<?php echo esc_attr( $template_type ); ?>"
                    data-status-type="import"
                    data-nonce="<?php echo wp_create_nonce( 'aux-template-manager' ); ?>"
                ><span><?php _e( 'Retry', 'auxin-elements' ); ?></span></a>
                <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-pp-close">
                    <?php _e( 'Close', 'auxin-elements' ); ?>
                </a>
            </div>
        </div>
    <?php
        return ob_get_clean();
    }

    /**
     * Main Import
     *
     *
     * @return  JSON
     */
    public function import() {

        if ( ! isset( $_POST['ID'] ) || ! wp_verify_nonce( $_POST['verify'], 'aux-import-demo-' . $_POST['ID'] ) ) {
            // This nonce is not valid.
            wp_send_json_error( array( 'message' => __( 'Invalid Inputs.', 'auxin-elements' ) ) );
        }

        // Put demo ID in a variable
        $demo_ID = $_POST['ID'];

        $data = json_decode( $this->parse( 'https://demo.phlox.pro/api/v2/data/' . $demo_ID, 'insert', 'post' ), true );

        if ( $data['success'] ) {

            $get_options = $_POST['options'];
            foreach ( $get_options as $key => $value ) {
                $options[ $value['name'] ] = $value['value'];
            }

            update_option( 'auxin_demo_options', $options, false );

            update_option( 'auxin_last_imported_demo', array( 'id' => $demo_ID, 'time' => current_time( 'mysql' ), 'status' => $options ), false );

            flush_rewrite_rules();

            wp_send_json_success();
        }

        wp_send_json_error(  array( 'message' => $data['data'] ) );

    }

    public function import_step() {

        if ( ! isset( $_POST['step'] ) ) {
            wp_send_json_error( array( 'message' => __( 'Step Failed!', 'auxin-elements' ) ) );
        }

        $index   = isset( $_POST['index'] ) ? $_POST['index'] : 0;
        $data    = $this->get_demo_data();

        if( ! is_array( $data ) ){
            wp_send_json_error( array( 'message' => __( 'Error in getting data!', 'auxin-elements' ) ) );
        }

        $options = get_option( 'auxin_demo_options', false );

        switch ( $_POST['step'] ) {
            case 'download':
                if ( 'complete' === $options['import']
                || ( 'custom' === $options['import'] && ( isset( $options['media'] ) && 'on' === $options['media'] ) ) ) {
                    // change to current node
                    $index++;
                    if( is_array( $data['attachments'] ) && $posts_number = count( $data['attachments'] ) ){

                        if( $index == 1 ){
                            $requests = $this->prepare_download( $data['attachments'] );
                        } else {
                            $requests = get_option( 'auxin_demo_media_requests' );
                        }

                        if( $index <= $posts_number ){
                            $this->download( array_slice( $requests, $index - 1, 1 ) );

                            if( $index < $posts_number ){
                                wp_send_json_success( array( 'message' => __( 'Downloading Medias', 'auxin-elements' ). ' ' . $index . '/' . $posts_number, 'next' => 'download', 'index' => $index ) );
                            }
                        }
                    }

                }
                wp_send_json_success( array( 'step' => 'download', 'next' => 'media', 'message' => __( 'Importing Media', 'auxin-elements' ) ) );

            case 'media':
                if ( 'complete' === $options['import']
                || ( 'custom' === $options['import'] && ( isset( $options['media'] ) && 'on' === $options['media'] ) ) ) {
                    return $this->import_media( $data['attachments'] );
                }

            case 'users':
                if ( isset( $data['users'] ) ) {
                    // change to current node
                    $index++;
                    if( is_array( $data['users'] ) && $users_number = count( $data['users'] ) ){
                        if( $index <= $users_number ){
                            $this->import_users( array_slice( $data['users'], $index - 1, 1 ) );
                            if( $index < $users_number ){
                                wp_send_json_success( array( 'message' => __( 'Importing Users', 'auxin-elements' ). ' '. $index . '/' . $users_number, 'next' => 'users', 'index' => $index ) );
                            }
                        }
                    }
                }

                // Trash the default WordPress Post, "Hello World," which has an ID of '1'.
                if ( get_post_type( 1 ) != 'elementor_library' ) {
                    wp_trash_post( 1 );
                }

                wp_send_json_success( array( 'step' => 'users', 'next' => 'content', 'message' => __( 'Importing Contents', 'auxin-elements' ) ) );

            case 'content':
                if ( 'complete' === $options['import']
                || ( 'custom' === $options['import'] && ( isset( $options['posts'] ) && 'on' === $options['posts'] ) ) ) {

                    // change to current node
                    $index++;
                    if( is_array( $data['contents'] ) && $posts_number = count( $data['contents'] ) ){
                        if( $index <= $posts_number ){
                            $this->import_posts( array_slice( $data['contents'], $index - 1, 1 ) );
                            if( $index < $posts_number ){
                                wp_send_json_success( array( 'message' => __( 'Importing Contents', 'auxin-elements' ). ' '. $index . '/' . $posts_number, 'next' => 'content', 'index' => $index ) );
                            }
                        }
                    }

                }

                $this->update_imported_ids();
                auxin_delete_transient( 'aux-old-products-id-transformation' );
                if ( ! empty( $data['terms-meta'] ) ) {
                    $this->add_demo_terms_meta( $data['terms-meta'] );
                }
                wp_send_json_success( array( 'step' => 'content', 'message' => __( 'Importing Options', 'auxin-elements' ), 'next' => 'auxin_options' ) );

            case 'auxin_options':
                if ( 'complete' === $options['import']
                || ( 'custom' === $options['import'] && ( isset( $options['options'] ) && 'on' === $options['options'] ) ) ) {
                    return $this->import_options( $data['options'] );
                }

            case 'menus':
                if ( 'complete' === $options['import']
                || ( 'custom' === $options['import'] && ( isset( $options['menus'] ) && 'on' === $options['menus'] ) ) ) {

                    $index++;
                    if( is_array( $data['menus'] ) && $menu_number = count( $data['menus'] ) ){
                        if( $index <= $menu_number ){
                            $this->import_menus( array_slice( $data['menus'], $index - 1, 1 ) );
                            if( $index < $menu_number ){
                                wp_send_json_success( array( 'message' => __( 'Importing Menus', 'auxin-elements' ). ' '. $index . '/' . $menu_number, 'next' => 'menus', 'index' => $index ) );
                            }
                        }
                    }

                }
                wp_send_json_success( array( 'step' => 'menus', 'next' => 'widgets', 'message' => __( 'Importing Widgets', 'auxin-elements' ) ) );

            case 'widgets':
                if ( 'complete' === $options['import']
                || ( 'custom' === $options['import'] && ( isset( $options['widgets'] ) && 'on' === $options['widgets'] ) ) ) {
                    return $this->import_widgets( $data['widgets'] );
                }

            case 'masterslider':
                if ( 'complete' === $options['import']
                || ( 'custom' === $options['import'] && ( isset( $options['masterslider'] ) && 'on' === $options['masterslider'] ) )
                && isset( $data['sliders'] ) ) {
                    return $this->import_sliders( $data['sliders'] );
                }

            case 'prepare':
                return $this->prepare_site();
        }
    }

    /**
     * Parse url
     *
     * @param   String $url
     *
     * @return  String
     */
    public function parse( $url, $action = 'insert', $method = 'get' ) {

        //Get JSON
        if( $method === 'get '){
            $request    = wp_remote_get( $url,
                array(
                    'timeout'     => 30,
                    'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0'
                )
            );
        } else {
            $getLicense = get_site_option( THEME_ID . '_license' );
            $getLicense = empty( $getLicense ) ? get_site_option( AUXELS_PURCHASE_KEY ) : $getLicense;
            $bearer     = ! empty( $getLicense['token'] ) ? $getLicense['token'] : '';
            $getToken   = 'Bearer ' . $bearer;

            $request    = wp_remote_post(
                $url,
                array(
                    'body'    => array(
                        'audit_token'   => base64_encode( auxin_get_site_key() ),
                        'item_slug'     => THEME_ID,
                        'item_version'  => THEME_VERSION,
                        'authorization' => $getToken
                    ),
                    'headers' => array(
                        'Authorization'                     => $getToken,
                        'Envato-Extensions-Token'           => get_option( 'phlox_envato_elements_token', '' ),
                        'Envato-Extensions-Extension-Id'    => get_option( 'phlox_envato_elements_license_code', '' ),
                    ),
                    'timeout' => 25
                )
            );
        }


        //If the remote request fails, wp_remote_get() will return a WP_Error
        if( is_wp_error( $request ) || ! current_user_can( 'import' ) ){

            // Increase the CURL timeout if required
            if( ! empty( $requst['errors']['http_request_failed'][0] ) ){
	        	if( false !== strpos( $requst['errors']['http_request_failed'][0], 'cURL error 28') ){
	            	set_theme_mod('increasing_curl_timeout_is_required', 15);
	            }
            }

            wp_send_json_error( array( 'message' => $request->get_error_message() ) );
        }

        //proceed to retrieving the data
        $body       = wp_remote_retrieve_body( $request );
        // Check for error
        if ( is_wp_error( $body ) || json_decode( $body ) == NULL ) {
            wp_send_json_error( array( 'message' => __( 'Retrieve Body Fails', 'auxin-elements' ) ) );
        }

        if( $action === 'insert' ){
            // Create local json from remote url
            return $this->insert_file( $url, $body, 'demo.json', 'json' );
        }

        return $body;
    }

    // Importers
    // =====================================================================

    /**
     * Import options ( Customizer & Site Options )
     *
     * @param   array $auxin_options
     * @param   array $site_options
     * @param   array $theme_mods
     *
     * @return  String
     */
    public function import_options( array $options ) {
        $auxin_custom_images   = $this->get_options_by_type( 'image' );
        extract( $options );

        $elementor_header_footer_options = array(
            'site_elementor_header_template' => 'site_elementor_header_edit_template',
            'site_elementor_footer_template' => 'site_elementor_footer_edit_template',
        );

        foreach ( $theme_options as $auxin_key => $auxin_value ) {
            if ( in_array( $auxin_key, $auxin_custom_images ) && ! empty( $auxin_value ) ) {
                // This line is for changing the old attachment ID with new one.
                $auxin_value    = $this->get_attachment_id( 'auxin_import_id', $auxin_value );
            }

            if ( in_array( $auxin_key, array_keys( $elementor_header_footer_options ) ) ) {
                $auxin_new_value = auxin_get_transient( "aux-elementor-library-{$auxin_value}-changs-to" );

                auxin_update_option( $elementor_header_footer_options[ $auxin_key ] , maybe_unserialize( $auxin_new_value ) );
                auxin_update_option( $auxin_key , maybe_unserialize( $auxin_new_value ) );

                auxin_delete_transient( "aux-elementor-library-{$auxin_value}-changs-to" );
                continue;
            }

            if ( ( $auxin_key == 'portfolio_custom_archive_link' || $auxin_key == 'news_custom_archive_link' ) && ! empty( $auxin_value ) ) {
                $auxin_value = auxin_get_transient( "aux-page-{$auxin_value}-changs-to" );
            }
            // Update exclusive auxin options
            auxin_update_option( $auxin_key , maybe_unserialize( $auxin_value ) );
        }

        foreach ( $site_options as $site_key => $site_value ) {
            // If option value is empty, continue...
            if ( empty( $site_value ) ) continue;
            // Else change some values :)
            if( $site_key === 'page_on_front' || $site_key === 'page_for_posts' ) {
                $site_value = $this->get_meta_post_id( 'auxin_import_post', $site_value );
            }
            // Finally update options :)
            update_option( $site_key, $site_value );
        }

        foreach ( $theme_mods as $theme_mods_key => $theme_mods_value ) {
            // Start theme mods loop:
            if( $theme_mods_key === 'custom_logo' ) {
                // This line is for changing the old attachment ID with new one.
                $theme_mods_value = $this->get_attachment_id( 'auxin_import_id', $theme_mods_value );
            }

            if ( in_array( $theme_mods_key, $this->skip_options ) ) {
                continue;
            }
            // Update theme mods
            set_theme_mod( $theme_mods_key , maybe_unserialize( $theme_mods_value ) );
        }

        foreach ( $plugins_options as $plugin => $options ) {
            if( empty( $options ) ){
                continue;
            }
            foreach ( $options as $option => $value) {
                if( strpos( $option, 'page_id' ) !== false ) {
                    $value = $this->get_meta_post_id( 'auxin_import_post', $value );
                }
                if ( $option == 'elementor_active_kit' ) {
                    $value = $this->get_meta_post_id( 'auxin_import_post', $value );
                }
                update_option( $option, maybe_unserialize( $value ) );
            }
        }

        // @deprecated A temporary fix for an issue with elementor typography scheme
        // $elementor_typo_scheme = [
        //     '1' => [
        //         'font_family' => 'Arial',
        //         'font_weight' => ''
        //     ],
        //     '2' => [
        //         'font_family' => 'Arial',
        //         'font_weight' => ''
        //     ],
        //     '3' => [
        //         'font_family' => 'Tahoma',
        //         'font_weight' => ''
        //     ],
        //     '4' => [
        //         'font_family' => 'Tahoma',
        //         'font_weight' => ''
        //     ]
        // ];
        // update_option( 'elementor_scheme_typography', $elementor_typo_scheme );

        // set_theme_mod( 'elementor_page_typography_scheme', 0 );

        // Stores css content in custom css file
        auxin_save_custom_css();
        // Stores JavaScript content in custom js file
        auxin_save_custom_js();

        wp_send_json_success( array( 'step' => 'options', 'next' => 'menus', 'message' => __( 'Importing Menus', 'auxin-elements' ) ) );
    }

    /**
     * Import widgets data
     *
     * @param   array $widgets
     * @param   array $widgets_data
     *
     * @return  String
     */
    public function import_widgets( array $widgets ) {

        if ( ! function_exists( 'wp_get_sidebars_widgets' ) ) {
            require_once ABSPATH . WPINC . '/widgets.php';
        }

        extract( $widgets );

        $default_widgets = array();

        $widgets_data_str = wp_json_encode( $options );

        preg_match_all( '/\s*"nav_menu"\s*:\s*(\d*)\s*/', $widgets_data_str, $matchs, PREG_SET_ORDER );

        foreach ( $matchs as $match ) {
            $new_menu_id = get_option( 'auxin_demo_importer_menu_origin_id_' . $match[1] );
            $new_widgets_data = str_replace( $match[1], $new_menu_id, $match[0] );
            $widgets_data_str = str_replace( $match[0], $new_widgets_data, $widgets_data_str );
        }

        preg_match_all( '/\s*"attach_id"\s*:\s*(\d*)\s*/', $widgets_data_str, $matchs, PREG_SET_ORDER );
        preg_match_all( '/\s*"attachment_id"\s*:\s*(\d*)\s*/', $widgets_data_str, $matchs, PREG_SET_ORDER );

        foreach ( $matchs as $match ) {
            $new_image_id = $this->get_attachment_id( 'auxin_import_id', $match[1] );
            if( $new_image_id ){
                $new_widgets_data = str_replace( $match[1], $new_image_id, $match[0] );
                $widgets_data_str = str_replace( $match[0], $new_widgets_data, $widgets_data_str );
            }
        }

        $options = json_decode( $widgets_data_str, true );


        // Import widgets
        foreach (  $sidebars as $key => $value ) {
            $default_widgets[$key]  = $value;
        }
        // Replace new widgets with old ones.
        wp_set_sidebars_widgets( $default_widgets );

        // Import widgets data
        foreach ( $options as $data_key => $data_values ) {

            foreach ( $data_values as $counter => $options ) {
                // This line is for changing the old attachment ID with new one.
                if( isset( $options['about_image'] ) ) {
                    $data_values[$counter]['about_image'] = $this->get_attachment_id( 'auxin_import_id', $options['about_image'] );
                }

            }
            // Finally update widgets data.
            update_option( $data_key, $data_values );
        }

        wp_send_json_success( array( 'step' => 'widgets', 'next' => 'masterslider', 'message' => __( 'Importing Sliders', 'auxin-elements' ) ) );

    }

    /**
     * Import menus data
     *
     * @param   array $args
     *
     * @return  Boolean
     */
    public function import_menus( array $args ) {

        foreach ( $args as $menu_name => $menu_data ) {

            $menu_exists = wp_get_nav_menu_object( $menu_name );
            update_option( 'auxin_demo_importer_menu_origin_id_' . $menu_data['id'], $menu_exists, false );

            // If it doesn't exist, let's create it.
            if( ! $menu_exists ) {

                $menu_id = wp_create_nav_menu( $menu_name );
                if( is_wp_error( $menu_id ) ) continue;

                update_option( 'auxin_demo_importer_menu_origin_id_' . $menu_data['id'], $menu_id, false );
                // Create menu items
                foreach ( $menu_data['items'] as $item_key => $item_value ) {
                    //Keep 'menu-meta' in a variable
                    $meta_data = $item_value['menu-meta'];
                    $old_item_ID = $item_value['menu-item-current-id'];
                    // $post_name = isset( $item_value['menu-item-object-id'] ) ? $item_value['menu-item-object-id'] : '';
                    //remove Non-standard items from nav_menu input array
                    unset( $item_value['menu-meta']             );
                    unset( $item_value['menu-item-current-id']  );
                    unset( $item_value['menu-item-attr-title']  );
                    unset( $item_value['menu-item-classes']     );
                    unset( $item_value['menu-item-description'] );

                    switch ( $item_value['menu-item-type'] ) {
                        case 'post_type':
                            $item_value['menu-item-object-id'] = $this->get_meta_post_id( 'auxin_import_post', $item_value['menu-item-object-id'] );
                            unset( $item_value['menu-item-url'] );
                            break;
                        case 'taxonomy':
                            $get_term  = get_term_by( 'name', $item_value['menu-item-title'], $item_value['menu-item-object'] );
                            if( $get_term === false ){
                                $item_value['menu-item-object-id'] = auxin_get_transient( 'auxin_category_new_id_of' . $item_value['menu-item-object-id'] ) ? auxin_get_transient( 'auxin_category_new_id_of' . $item_value['menu-item-object-id'] ) : 1 ;
                            } else {
                                $item_value['menu-item-object-id'] = is_object( $get_term ) ? (int) $get_term->term_id : 0;
                            }
                            unset( $item_value['menu-item-url'] );
                            break;
                        default:
                            if( strpos( $item_value['menu-item-url'], '{{demo_home_url}}' ) !== false ) {
                                $item_value['menu-item-url'] = esc_url( str_replace( "{{demo_home_url}}", get_site_url(), $item_value['menu-item-url'] ) );
                            }
                            $item_value['menu-item-object-id'] = 0;
                    }

                    // Import menu item
                    $item_id = wp_update_nav_menu_item( $menu_id, 0, $item_value );
                    $post_id = $this->get_meta_post_id( 'page_header_menu', strval( $menu_data['id'] ) );

                    // Create a flag transient
                    auxin_set_transient( 'auxin_menu_item_old_parent_id_' . $old_item_ID, $item_id );

                    update_post_meta( $post_id, 'page_header_menu', $menu_id );

                    if ( is_wp_error( $item_id ) ) {
                        continue;
                    }

                    //Add 'meta-data' options for menu items
                    foreach ($meta_data as $meta_key => $meta_value) {

                        switch ( $meta_key ) {
                            case '_menu_item_object_id':
                                // Change exporter's object ID value
                                switch ( $item_value['menu-item-type'] ) {
                                    case 'post_type':
                                    case 'taxonomy':
                                        $meta_value = $item_value['menu-item-object-id'];
                                        break;
                                }
                                break;

                            case '_menu_item_menu_item_parent':
                                if( (int) $meta_value != 0 ) {
                                    $meta_value     = auxin_get_transient( 'auxin_menu_item_old_parent_id_' . $meta_value );
                                }
                                break;
                            case '_menu_item_url':
                                if( ! empty( $meta_value ) ) {
                                    $meta_value     = $item_value['menu-item-url'];
                                }
                                break;
                        }

                        update_post_meta( $item_id, $meta_key, $meta_value );
                    }
                }

                if( is_array( $menu_data['location'] ) ) {
                    // Putting up menu locations on theme_mods_phlox
                    $locations = get_theme_mod( 'nav_menu_locations' );
                    foreach ( $menu_data['location'] as $location_id => $location_name ) {
                        $locations[$location_name] = $menu_id;
                    }
                    set_theme_mod( 'nav_menu_locations', $locations );
                }

            }

        }

        // wp_send_json_success( array( 'step' => 'menus', 'next' => 'widgets', 'message' => __( 'Importing Widgets', 'auxin-elements' ) ) );

    }

    /**
     * Flush post data
     *
     * @param   Integer $post_id
     *
     * @return  String
     */
    public function maybe_flush_post( $post_id ){

        if( class_exists( '\Elementor\Core\Files\CSS\Post' ) && get_post_meta( $post_id, '_elementor_version', true ) ){
            $post_css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
            $post_css_file->update();
        }
    }

    /**
     * Import posts data
     *
     * @param   array $args
     *
     * @return  String
     */
    public function import_posts( array $args ) {

        foreach ( $args as $slug => $post ) {

            // If there is no post_type, then continue loop...
            if ( ! post_type_exists( $post['post_type'] ) ) {
                continue;
            }

            // Check post existence
            if( $this->post_exists( $post['post_title'], $post['ID'] ) ) {
                continue;
            }

            $content = base64_decode( $post['post_content'] );

            // Update the custom_css post for a given theme.
            if( $post['post_type'] == 'custom_css' ) {
                if( ! isset( $post['post_meta']['auxin_import_post'] ) ){
                    wp_update_custom_css_post( $content );
                }
                continue;
            }

            $post_id = wp_insert_post(
                array(
                    'post_title'        => sanitize_text_field( $post['post_title'] ),
                    'post_content'      => $this->shortcode_process( $content ),
                    'post_excerpt'      => $post['post_excerpt'],
                    'post_date'         => $post['post_date'],
                    'post_password'     => $post['post_password'],
                    'post_parent'       => $post['post_parent'],
                    'post_type'         => $post['post_type'],
                    'post_author'       => $this->get_author_ID( $post['post_author'] ),
                    'post_status'       => 'publish',
                )
            );

            if ( ! is_wp_error( $post_id ) ) {

                // 1. If post type equals product Search all product variations with post parent id of this product id
                // and change the parent id to this new $post_id
                // 2. Save the new product id to database
                if ( $post['post_type'] == 'product' ) {

                    $all_product_variations = get_posts(
                        array(
                            'post_type'     => 'product_variations',
                            'posts_per_page'=> -1,
                            'post_parent'   => $post['ID']
                        )
                    );

                    if ($all_product_variations) {
                        foreach ($all_product_variations as $post ) {
                            wp_update_post(
                                array(
                                    'ID'            => $post->ID,
                                    'post_parent'   => $post_id
                                )
                            );
                        }
                    }

                    // Store product ids recieved from server and it's new id
                    $stored_products_id = maybe_unserialize( auxin_get_transient( 'aux-old-products-id-transformation' ) );
                    $stored_products_id[$post['ID']] = $post_id;
                    auxin_set_transient( 'aux-old-products-id-transformation', maybe_serialize($stored_products_id), 48 * HOUR_IN_SECONDS );
                }

                if ( $post['post_type'] == 'elementor_library' ) {
                    auxin_set_transient( "aux-elementor-library-{$post['ID']}-changs-to", $post_id, 48 * HOUR_IN_SECONDS );
                }

                if ( $post['post_type'] == 'post' ) {
                    auxin_set_transient( "aux-post-{$post['ID']}-changs-to", $post_id, 48 * HOUR_IN_SECONDS );
                }

                if ( $post['post_type'] == 'page' ) {
                    auxin_set_transient( "aux-page-{$post['ID']}-changs-to", $post_id, 48 * HOUR_IN_SECONDS );
                }

                //Check post terms existence
                if ( ! empty( $post['post_terms'] ) ){
                    // Start adding post terms
                    foreach ( $post['post_terms'] as $tax => $term ) {

                        if( $tax === 'post_format' ) {
                            // Get post_format key value
                            $term = array_keys( $term );
                            // Set post format (Video, Audio, Gallery, ...)
                            set_post_format( $post_id , preg_replace( '/post-format-/', '', $term[0] ) );

                        } else {

                            // If taxonomy not exists, then continue loop...
                            if( ! taxonomy_exists( $tax ) ){
                                // check if post type is product and has product attribute import it otherwise continue the loop
                                if ( $post['post_type'] == 'product' || $post['post_type'] == 'product_variation' ) {
                                    if ( strpos( $tax, 'pa_' ) == '0' && class_exists( 'WooCommerce' ) ) {
                                        $tax_name = str_replace('pa_','',$tax);
                                        $type = ! empty( $term['pa_attribute_type'] ) ? $term['pa_attribute_type'] : 'select';
                                        $data = array(
                                            'name'    => $tax_name,
                                            'slug'    => $tax_name,
                                            'type'    => $type,
                                            'orderby' => 'menu_order',
                                            'has_archive'  => 0, // Enable archives ==> true (or 1)
                                        );
                                        $term_id = wc_create_attribute($data);
                                        if ( is_wp_error( $term_id ) ) {
                                            error_log($term_id->get_error_message(),0);
                                        }
                                        register_taxonomy( $tax, 'product', array() );
                                    } else {
                                        continue;
                                    }
                                } else {
                                    continue;
                                }
                            }
                            $add_these_terms = array();

                            foreach ($term as $key => $value) {
                                if ( $key == 'pa_attribute_type' ) {
                                    continue;
                                }
                                $key = is_numeric( $key ) ? (string) $key : $key;
                                $term_obj               = term_exists( $key, $tax );

                                // If the taxonomy doesn't exist, then we create it
                                if ( ! $term_obj ) {

                                    // Get parent term
                                    // $parent_term    = $value != "0" ? get_term_by( 'name', $value, $tax ) : (object) array( 'term_id' => "0" );
                                    // $parent_term_ID = isset( $parent_term->term_id ) ? (int) $parent_term->term_id : 0 ;
                                    // $term_args      = $parent_term_ID ? array( 'parent' => $parent_term_ID ) : array();

                                    $term_obj = wp_insert_term(
                                        $key,
                                        $tax,
                                        array()
                                    );
                                    if ( is_wp_error( $term_obj ) ) {
                                        continue;
                                    }

                                    auxin_set_transient( 'auxin_category_new_id_of' . $value, $term_obj['term_id'] );

                                }

                                $add_these_terms[]  = intval($term_obj['term_id']);
                            }

                            // Add post terms
                            wp_set_post_terms( $post_id, $add_these_terms, $tax, true );
                        }

                    }

                }

                // Set default_form_id for mailchimp plugin
                if( $post['post_type'] == 'mc4wp-form' ){
                    // set default_form_id
                    update_option( 'mc4wp_default_form_id', $post_id, false );
                }

                if ( ! empty( $post['post_comments'] ) ){
                    // Add post comments
                    foreach ( $post['post_comments'] as $comment_key => $comment_values ) {
                        $comment_values['data']['comment_post_ID']      = $post_id;
                        $comment_old_ID                         = $comment_values['data']['comment_ID'];

                        if ( $comment_values['data']['comment_parent'] != 0 ) {
                            $comment_values['data']['comment_parent']   = auxin_get_transient( 'auxin_comment_new_comment_id_' . $comment_values['data']['comment_parent'] );
                        }

                        unset( $comment_values['data']['comment_ID'] );
                        $comment_ID = wp_insert_comment( $comment_values['data'] );
                        if ( is_wp_error( $comment_ID ) ) {
                            continue;
                        } else {
                            if( ! empty( $comment_values['meta'] ) ){
                                foreach ($comment_values['meta'] as $meta_key => $meta_value) {
                                    update_comment_meta( $comment_ID, $meta_key, $meta_value );
                                }
                            }
                            auxin_set_transient( 'auxin_comment_new_comment_id_' . $comment_old_ID, $comment_ID );
                        }
                    }
                }

                if ( ! empty( $post['post_meta'] ) ){
                    // Add post meta data
                    foreach ( $post['post_meta'] as $meta_key => $meta_value ) {
                        // Unserialize when data is serialized
                        $meta_value = maybe_unserialize( $meta_value );

                        switch ( $meta_key ) {
                            case '_panels_data_preview':
                            case 'panels_data'  :
                                $meta_value = $this->siteorigin_data_update( $meta_value );
                                $auxin_custom_images    = $this->get_widget_by_type( array('attach_image', 'attach_images', 'aux_select_video', 'aux_select_audio') );
                                foreach ( $meta_value['widgets'] as $widgets_key => $widgets_value ) {
                                    foreach ($widgets_value as $panel_key => $panel_value) {
                                        if ( in_array( $panel_key, $auxin_custom_images ) && ! empty( $panel_key ) ) {
                                            $meta_value['widgets'][$widgets_key][$panel_key] = $this->update_gallery_ids( $panel_value );
                                        } elseif( $panel_key == 'cf7_shortcode' ) {
                                            $meta_value['widgets'][$widgets_key][$panel_key] = $this->shortcode_process( $panel_value );
                                        }
                                    }
                                }
                                break;

                            case '_thumbnail_id' :
                            case '_thumbnail_id2':
                            case '_format_audio_attachment':
                            case '_format_video_attachment':
                            case '_format_video_attachment_poster':
                            case '_format_gallery_type':
                            case 'aux_custom_bg_image':
                            case 'aux_title_bar_bg_image':
                            case 'aux_title_bar_bg_video_mp4':
                            case 'aux_title_bar_bg_video_ogg':
                            case 'aux_title_bar_bg_video_webm':
                            case '_product_image_gallery':
                                $meta_value    = $this->update_gallery_ids( $meta_value );
                                break;
                            case '_elementor_data':
                                // Update elementor data
                                $meta_value = $this->update_elementor_data( $meta_value );
                                // We need the `wp_slash` in order to avoid the unslashing during the `update_post_meta`
                                $meta_value = wp_slash( $meta_value );
                                break;
                            case 'aux_custom_logo':
                            case 'aux_custom_logo2':
                            case 'page_secondary_logo_image':
                                $meta_value = $this->get_attachment_id( 'auxin_import_id', $meta_value );
                                break;
                        }

                        update_post_meta( $post_id, $meta_key, $meta_value );
                    }
                }

                //Add auxin meta flag
                add_post_meta( $post_id,  'auxin_import_post', $post['ID'] );

                if( $post['post_thumb'] != "" ){
                    /* Get Attachment ID */
                    $attachment_id    = $this->get_attachment_id( 'auxin_import_id', $post['post_thumb'] );

                    if ( $attachment_id ) {
                        set_post_thumbnail( $post_id, $attachment_id );
                    }

                }

                $this->maybe_flush_post( $post_id );

            } else {

                continue;
            }

        }

        //wp_send_json_success( array( 'step' => 'content', 'next' => 'auxin_options', 'message' => __( 'Importing Options' ) ) );
    }

    public function update_imported_ids() {
        
        $args = array(
            'post_type' => array(
                'post',
                'page',
                'portfolio',
                'product',
                'product_variation'
            ),
            'posts_per_page' => -1
        );

        $query = new WP_Query($args);

        if ( $query->have_posts() ) {
            while( $query->have_posts() ) {
                $query->the_post();
                $post_ID = get_the_ID();
                
                if ( get_post_type( $post_ID ) == 'product_variation' ) {
                    $old_products_id = maybe_unserialize( auxin_get_transient( 'aux-old-products-id-transformation' ) );
                    $parent_product = wp_get_post_parent_id( $post_ID );
                    if ( ! empty( $old_products_id[ $parent_product ] ) ) {
                        wp_update_post(
                            array(
                                'ID' => $post_ID, 
                                'post_parent' => $old_products_id[ $parent_product ]
                            )
                        );
                    }
                    continue;
                }
                
                $elementor_data = get_post_meta( $post_ID , '_elementor_data', true );
                
                // Change slide's id in flexible carousel element
                preg_match_all( '/template":"\d*/', $elementor_data, $templates, PREG_SET_ORDER );
                if ( ! empty( $templates ) ) {
                    foreach ( $templates as $key => $template ) {
                        $old_id         = str_replace( 'template":"', '', $template[0] );
                        if ( ! is_numeric( $old_id ) ) {
                            continue;
                        }
                        $new_template   = 'template":"'. auxin_get_transient( "aux-elementor-library-{$old_id}-changs-to" );
                        $elementor_data = str_replace( $template[0], $new_template, $elementor_data );
                    }
                }

                // Change contact form 7 old id
                preg_match_all( '/contact-form-7 id=\\\"(\d*)/', $elementor_data, $contact_forms, PREG_SET_ORDER );
                if ( ! empty( $contact_forms ) ) {
                    foreach ( $contact_forms as $key => $form ) {
                        $new_form         = str_replace( $form[1], $this->get_attachment_id( 'auxin_import_post', $form[1] ), $form[0] );

                        $elementor_data = str_replace( $form[0], $new_form, $elementor_data );
                    }
                }

                // Change template's id in flexible recent posts element
                preg_match_all( '/post_column":"\d*/', $elementor_data, $templates, PREG_SET_ORDER );
                if ( ! empty( $templates ) ) {
                    foreach ( $templates as $key => $template ) {
                        $old_id         = str_replace( 'post_column":"', '', $template[0] );
                        if ( ! is_numeric( $old_id ) ) {
                            continue;
                        }
                        $new_template   = 'post_column":"'. auxin_get_transient( "aux-elementor-library-{$old_id}-changs-to" );
                        $elementor_data = str_replace( $template[0], $new_template, $elementor_data );
                    }
                }

                // change hosted video url and id
                preg_match_all( '/"hosted_url":\{.*?\}/', $elementor_data, $hosted_vidoes, PREG_SET_ORDER );
                if ( ! empty( $hosted_vidoes ) ) {
                    foreach( $hosted_vidoes as $key => $video ) {
                        preg_match_all( '/"id":(["|\d]*)/', $video[0], $id, PREG_SET_ORDER );
                        if ( !empty( $id ) ) {
                            $new_id = auxin_get_transient( 'auxin_video_import_id' );
                            if ( empty( $new_id ) || empty( $id[0][1] ) ) {
                                continue;
                            }
                            $new_url = wp_get_attachment_url( $new_id );
                            $new_url = str_replace( '/', '\/', $new_url );
                            $new_video = '"hosted_url":{"url":"' . $new_url . '","id":"' . $new_id . '"}';
                            $elementor_data = str_replace( $video[0], $new_video, $elementor_data );
                        }
                    }
                }

                // Change products's id in flexible recent posts element
                preg_match_all( '/only_products__in":"[\d,]*/', $elementor_data, $product_id_strings, PREG_SET_ORDER );
                if ( ! empty( $product_id_strings ) ) {
                    $old_products_id = maybe_unserialize( auxin_get_transient( 'aux-old-products-id-transformation' ) );
                    foreach ( $product_id_strings as $key => $product_id_string ) {
                        $old_ids = str_replace( 'only_products__in":"', '', $product_id_string[0] );
                        $old_ids = explode( ',', $old_ids );
                        $new_id = [];
                        foreach( $old_ids as $key => $id ) {
                            $new_id[] =  $old_products_id[ $id ];
                        }
                        $new_id = implode(',', $new_id );
                        $new_product_id_string = 'only_products__in":"' . $new_id . '"';
                        $elementor_data = str_replace( $product_id_string[0].'"', $new_product_id_string, $elementor_data );
                    }
                }

                // Change posts's id in flexible recent posts element
                preg_match_all( '/only_posts__in":"[\d,]*/', $elementor_data, $post_id_strings, PREG_SET_ORDER );
                if ( ! empty( $post_id_strings ) ) {
                    foreach ( $post_id_strings as $key => $post_id_string ) {
                        $old_ids = str_replace( 'only_posts__in":"', '', $post_id_string[0] );
                        $old_ids = explode( ',', $old_ids );
                        $new_id = [];
                        foreach( $old_ids as $key => $id ) {
                            $new_id[] =  auxin_get_transient( "aux-post-{$id}-changs-to" );
                        }
                        $new_id = implode(',', $new_id );
                        $new_post_id_string = 'only_posts__in":"' . $new_id . '"';
                        $elementor_data = str_replace( $post_id_string[0].'"', $new_post_id_string, $elementor_data );
                    }
                }

                // check elementor tags for page url
                preg_match_all( '/\[elementor-tag.+?\]/', $elementor_data, $elementor_tags, PREG_SET_ORDER );
                if ( ! empty( $elementor_tags ) ) {
                    foreach( $elementor_tags as $key => $tag ) {
                        // tag is page url 
                        if ( strpos( $tag[0], 'aux-pages-url' ) ) {
                            preg_match_all( '/settings=\\\"(.+)?\\\"/', $tag[0], $key_values, PREG_SET_ORDER );
                            if ( ! empty( $key_values ) ) {
                                foreach ( $key_values as $tag_key => $key_value ) {
                                    $old_key_value = urldecode( $key_value[1] );
                                    $page_id = str_replace( '{"key":"', '', $old_key_value );
                                    $page_id = str_replace( '"}', '', $page_id );
                                    
                                    $new_page_id = auxin_get_transient( "aux-page-{$page_id}-changs-to" );
                                    $new_key_value = urlencode('{"key":"' . $new_page_id . '"}');
                                    $new_tag = str_replace( $key_value[1], $new_key_value, $tag[0] );
                                    $elementor_data = str_replace( $tag[0], $new_tag, $elementor_data );
                                }
                            }
                        }
                    }
                }

                update_post_meta( get_the_ID(), '_elementor_data', wp_slash( $elementor_data ) );

                $page_header_use_legacy = get_post_meta( get_the_ID(), 'page_header_use_legacy', true );
                if ( $page_header_use_legacy == 'no' ) {
                    $old_id = get_post_meta( get_the_ID(), 'page_elementor_header_template', true );
                    update_post_meta( get_the_ID(), 'page_elementor_header_edit_template', auxin_get_transient( "aux-elementor-library-{$old_id}-changs-to" ) );
                    update_post_meta( get_the_ID(), 'page_elementor_header_template', auxin_get_transient( "aux-elementor-library-{$old_id}-changs-to" ) );
                }

                $page_footer_use_legacy = get_post_meta( get_the_ID(), 'page_footer_use_legacy', true );
                if ( $page_footer_use_legacy == 'no' ) {
                    $old_id = get_post_meta( get_the_ID(), 'page_elementor_footer_template', true );
                    update_post_meta( get_the_ID(), 'page_elementor_footer_edit_template', auxin_get_transient( "aux-elementor-library-{$old_id}-changs-to" ) );
                    update_post_meta( get_the_ID(), 'page_elementor_footer_template', auxin_get_transient( "aux-elementor-library-{$old_id}-changs-to" ) );
                }
            }
        }
        wp_reset_postdata();
    }

    /**
     * Add Terms Meta Fields
     */
    public function add_demo_terms_meta( $terms_meta ) {
        foreach( $terms_meta as $meta_id => $term_options ) {
            $term_id = auxin_get_transient( 'auxin_category_new_id_of' . (int) $term_options['term_id'] );
            if ( empty( $term_id ) ) {
                continue;
            }
            $term_options['meta_value'] = ( $term_options['meta_key'] == 'image' && is_numeric( $term_options['meta_value'] ) ) ? $this->get_attachment_id( 'auxin_import_id', $term_options['meta_value'] ) : $term_options['meta_value'];
            add_term_meta( $term_id, $term_options['meta_key'], $term_options['meta_value'] );
        }
    }

    public function prepare_download( array $args ) {

        $tmpname = $responses = $requests = array();

        // Preparing requests
        foreach ( $args as $import_id => $import_url ) {

            if ( $this->attachment_exist( $import_id, pathinfo( $import_url['url'], PATHINFO_BASENAME ) ) ) {
                continue;
            }

            $url_filenames = basename( parse_url( $import_url['url'], PHP_URL_PATH ) );

            if ( ! isset( $tmpname[$import_url['url']] ) ) {
                $tmpname[$import_url['url']] = wp_tempnam( $url_filenames );
            }

            $requests[$import_url['url']] = array( 'url' => $import_url['url'], 'options' => array( 'timeout' => 300, 'stream' => true, 'filename' => $tmpname[$import_url['url']] ) );
            $args[$import_id]['tmp_name'] = $tmpname[$import_url['url']];

        }

        update_option( 'auxin_demo_media_args', $args, false );
        update_option( 'auxin_demo_media_requests', $requests, false );

        return $requests;
    }


    public function download( array $requests ) {

        if( ! empty( $requests ) ) {
            // Split requests
            return Requests::request_multiple( $requests );
        }

    }


    public function import_media() {
        $args = get_option( 'auxin_demo_media_args', false );
        // Process moving temp files and insert attachments
        foreach ( $args as $import_id => $import_url ) {
            if( ! isset( $import_url['tmp_name'] ) || empty( $import_url['tmp_name'] ) ) {
                continue;
            }
            $path = isset( $import_url['path'] ) ? $import_url['path'] : '';
            $this->insert_attachment( $import_id, $import_url['url'], $import_url['tmp_name'] , $path );
        }
        delete_option('auxin_demo_media_args');

        wp_send_json_success( array( 'step' => 'media', 'next' => 'users', 'message' => __( 'Importing Users', 'auxin-elements' ) ) );
    }

    public function import_users( array $args ) {

        foreach ( $args as $key => $user ) {
            $user_ID = wp_insert_user( array(
                'user_login'    => $user['user_login'],
                'user_email'    => $user['user_email'],
                'role'          => $user['role'],
                'user_nicename' => $user['user_nicename'],
                'display_name'  => $user['display_name'],
                'user_pass'     => md5( wp_generate_password( 8, false ) )
            ) ) ;
            // On success.
            if ( ! is_wp_error( $user_ID ) ) {
                // Keep the current user's old ID
                add_user_meta( $user_ID, 'imported_user_id', $user['ID'] );
                foreach ( $user['user_meta'] as $meta_key => $meta_value ) {
                    if( $meta_key === 'primary_blog' && is_multisite() ){
                        $meta_value = get_current_blog_id();
                    }
                    add_user_meta( $user_ID, $meta_key, maybe_unserialize( $meta_value ) );
                }
            } elseif( is_multisite() && isset( $user_ID->errors['existing_user_login'] ) ) {
                $user_ID = get_user_by( 'login', $user['user_login'] );
                if( isset( $user_ID->ID ) ){
                    add_user_to_blog( get_current_blog_id(), $user_ID->ID, $user['role'] );
                }
            }
        }

    }

    /**
     * Import master slider
     *
     * @param   array $args
     *
     * @return  String
     */
    public function import_sliders( $sliders ) {

        if ( class_exists( 'MSP_DB' ) && ! empty( $sliders ) ) {

            $ms_db = new MSP_DB;

            foreach ( $sliders as $slider ) {

                if ( isset( $slider['ID'] ) ) {
                    unset( $slider['ID'] );
                }

                $ms_db->add_slider( $slider );

            }

            if( function_exists( 'msp_save_custom_styles' ) ) {
                msp_save_custom_styles();
            }

        }

        wp_send_json_success( array( 'step' => 'masterslider', 'next' => 'prepare', 'message' => __( 'Preparing Site ...', 'auxin-elements' ) ) );

    }

    /**
     * Prepare site for final step
     *
     * @param   array $args
     *
     * @return  String
     */
    public function prepare_site() {
        // Clear elementor cache
        if ( class_exists( '\\Elementor\\Plugin' ) ) {
            \Elementor\Plugin::instance()->files_manager->clear_cache();
        }
        // Remove local demo file
        wp_delete_file( $this->get_theme_dir() . '/demo.json' );
        // Send final success message
        wp_send_json_success( array( 'step' => 'prepare_site', 'next' => 'final', 'message' => __( 'All steps are successful', 'auxin-elements' ) ) );
    }

    // Custom Functionalities
    // =====================================================================

    /**
     * This will changing the old attachment IDs with new ones
     *
     * @param   string  $value
     *
     * @return  string
     */
    public function update_gallery_ids( $value ) {
        // This line is for changing the old attachment ID with new one.
        if( strpos( $value, ',' ) !== false ) {
            $value   = explode( ",", $value );
            $gallery = array();
            foreach ( $value as $gallery_key => $gallery_value ) {
                if ( $get_new_attachment = $this->get_attachment_id( 'auxin_import_id', $gallery_value ) ) {
                    $gallery[]   = $get_new_attachment;
                }
            }
            return implode( ",", $gallery );
        } else {
            return $this->get_attachment_id( 'auxin_import_id', $value );
        }

    }

    /**
     * Get options (ID) by type
     *
     * @param   string  $type
     * @param   array   $output
     *
     * @return  array | empty array
     */
    public function get_options_by_type( $type, $output = array() ) {

        $get_options    = auxin_get_defined_options();

        foreach ( $get_options['fields'] as $key => $value ) {
            if ( ! array_search(  $type, $value ) ) {
                continue;
            }
            $output[]   = $value['id'];
        }

        return $output;

    }

    /**
     * Get page builder (param_name) by type
     *
     * @param   string  $type
     * @param   array   $output
     *
     * @return  array | empty array
     */
    public function get_widget_by_type( array $type, $output = array() ) {

        $get_widgets    = Auxin_Widget_Shortcode_Map::get_instance()->get_master_array();

        foreach ( $get_widgets as $key => $value ) {
            foreach ( $value['params'] as $params_key => $params_value ) {
                if ( ! in_array( $params_value['type'], $type ) ) {
                    continue;
                }
                $output[]   = $params_value['param_name'];
            }
        }

        return $output;

    }

    /**
     * An attractive function to change the values of old IDs in the shortcode attributes.
     *
     * @param   string $content
     *
     * @return  String
     */
    public function shortcode_process( $content ) {
        // Return if not contain Shortcode
        if ( false === strpos( $content, '[' ) ) {
            return $content;
        }

        // Make a copy of content
        $new_content   = $content;
        // Detect shortcode usage
        $wp_preg_match = preg_match_all( '/'. get_shortcode_regex() .'/s', $new_content, $matches );

        // Get old ID from cf7 shortcode
        preg_match( '/contact-form-7 id="([^\"]*?)\"/', $new_content, $get_old_cf7_id );

        if ( isset( $get_old_cf7_id[1] ) ) {
            // Update values
            $new_content = preg_replace( '/contact-form-7 id="([^"]*)"/', 'contact-form-7 id="'. $this->get_attachment_id( 'auxin_import_post', $get_old_cf7_id[1] ) .'"', $content );
        }

        // Parse our elements in visual composer
        if ( $wp_preg_match && array_key_exists( 2, $matches ) && stripos( $new_content, "vc_row" ) !== false  ) {
            // Get the list of attachment options attribute names
            $widget_attributes = $this->get_widget_by_type( array('attach_image', 'attach_images', 'aux_select_video', 'aux_select_audio') );

            if ( ! is_array($widget_attributes) ) {
                return $new_content;
            }

            foreach ($widget_attributes as $key => $param) {
                // Find all target attributes by the following pattern
                preg_match_all('/'.$param.'="([^"]*)"/', $content, $attributes);
                // Then start the revolution by result matches
                foreach ( $attributes[1] as $attr => $val ) {
                    // This line is for changing the old attachment ID with new one.
                    if( strpos( $val, ',' ) !== false ) {
                        $stack_values   = explode( ",", $val );
                        $gallery_widget = array();
                        foreach ( $stack_values as $gallery_key => $gallery_value ) {
                            $get_new_attachment     = $this->get_attachment_id( 'auxin_import_id', $gallery_value );
                            if ( $get_new_attachment ) {
                                $gallery_widget[]   = $get_new_attachment;
                            }
                        }
                        $new_val = implode( ",", $gallery_widget );
                    } else {
                        $new_val = $this->get_attachment_id( 'auxin_import_id', $val );
                    }
                    if ( 'src' !== $param ) {
                        // Finally replace old values with new ones. Bravo :))
                        $new_content = preg_replace('/'.$param.'="'.$val.'"/', $param.'="'.$new_val.'"', $new_content);
                    }

                }

            }
        }

        return $new_content;
    }

    /**
     * Get the attachment ID
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  ID | false
     */
    public function get_attachment_id( $key, $value ) {

        global $wpdb;

        $meta       =   $wpdb->get_results( "
                            SELECT *
                            FROM $wpdb->postmeta
                            WHERE
                            meta_key='".$key."'
                            AND
                            meta_value='".$value."'
                            OR
                            meta_key='auxin_attachment_has_duplicate_".$value."'
                        ");

        if ( is_array($meta) && !empty($meta) && isset($meta[0]) ) {
            $meta   =   $meta[0];
        }

        if ( is_object( $meta ) ) {
            return $meta->post_id;
        } else {
            return "";
        }

    }

    /**
     * check post existence
     *
     * @param   string  $title
     * @param   integer $post_ID
     * @param   string  $content
     * @param   string  $date
     *
     * @return  0 | post ID
     */
    public function post_exists( $title, $post_ID, $content = '', $date = '' ) {
        global $wpdb;

        $post_title   = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) );
        $post_content = wp_unslash( sanitize_post_field( 'post_content', $content, 0, 'db' ) );
        $post_date    = wp_unslash( sanitize_post_field( 'post_date', $date, 0, 'db' ) );

        $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
        $args = array();

        if ( !empty ( $date ) ) {
            $query .= ' AND post_date = %s';
            $args[] = $post_date;
        }

        if ( !empty ( $title ) ) {
            $query .= ' AND post_title = %s';
            $args[] = $post_title;
        }

        if ( !empty ( $content ) ) {
            $query .= ' AND post_content = %s';
            $args[] = $post_content;
        }

        if ( !empty ( $args ) ) {

            $results = $wpdb->get_results( $wpdb->prepare($query, $args) );

            if( $results != null ) {
                foreach ( $results as $key => $value ) {
                    if ( get_post_meta( $value->ID, 'auxin_import_post', true ) == $post_ID ) {
                        return $value->ID;
                    }
                }
            }

        }

        return 0;
    }

    /**
     * Get old id for posts, menus
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  ID | false
     */
    public function get_meta_post_id( $key, $value ) {

        global $wpdb;

        $sql = $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s", $key, $value );

        $meta = $wpdb->get_results( $sql );

        if ( is_array($meta) && !empty($meta) && isset($meta[0]) ) {
            $meta   =   $meta[0];
        }

        if ( is_object( $meta ) ) {
            return $meta->post_id;
        } else {
            return 0;
        }

    }

    /**
     * Get the attachment ID by PATHINFO_BASENAME
     *
     * @param   string $path
     *
     * @return  ID | false
     */
    public function get_attachment_id_by_basename( $path ) {

        global $wpdb;

        $post       =   $wpdb->get_results( "
                            SELECT *
                            FROM $wpdb->posts
                            WHERE
                            guid LIKE '%".$path."%'
                        ");

        if ( is_array($post) && !empty($post) && isset($post[0]) ) {
            $post   =   $post[0];
        }

        if ( is_object( $post ) ) {
            return $post->ID;
        } else {
            return null;
        }

    }

    /**
     * Insert file into local server
     *
     * @param   String $url
     * @param   String $content
     * @param   String $basename
     *
     * @return  String|Boolean
     */
    public function insert_file( $url, $content = '', $basename = '', $output = 'path' ) {

        if ( ! isset( $url ) ) {
            return false;
        }

        $basename     = empty( $basename ) ? basename( $url ) : $basename;
        $get_contents = empty( $content ) ? @file_get_contents( $url ) : $content;

        if( $get_contents && auxin_put_contents_dir( $get_contents, $basename ) ) {
            return $output !== 'path' ? $get_contents : pathinfo( $url, PATHINFO_FILENAME );
        } else {
            return false;
        }

    }

    public function get_author_ID( $author_ID ){
        $user_info = get_users( array( 'meta_key' => 'imported_user_id', 'meta_value' => $author_ID ) );
        if( ! empty( $user_info ) && isset( $user_info[0]->ID ) ){
            return $user_info[0]->ID;
        } else {
            return get_current_user_id();
        }
    }

    /**
     * Get data from local server
     *
     * @return  Array|Boolean
     */
    public function get_demo_data(){
        // Get & return json data from local server
        if( false !== ( $data = @file_get_contents( $this->get_theme_dir() . '/demo.json' ) ) ) {
            $data = json_decode( $data, true );
            return  $data['data'];
        }

        return false;
    }

    /**
     * Get theme custom directory
     *
     * @return  String
     */
    public function get_theme_dir(){

        if( defined( THEME_CUSTOM_DIR ) ) {
            return  THEME_CUSTOM_DIR;
        }

        $uploads = wp_get_upload_dir();
        return $uploads[ 'basedir' ] . '/' . THEME_ID;

    }


    /**
     * Insert attachment from url
     *
     * @param   integer $import_id
     * @param   string  $url
     * @param   integer $post_id
     *
     * @return  Integer
     */
    public function insert_attachment( $import_id, $url, $file_name, $path = '', $post_id = 0 ) {
        $base_file_name = pathinfo( $url, PATHINFO_BASENAME );
        // Check if media exist then get out
        if ( $this->attachment_exist( $import_id, $base_file_name ) ) {
            // Add meta data for duplicated videos
            if ( pathinfo( $url, PATHINFO_FILENAME ) == "video" ) {
                $imported_id    = $this->get_attachment_id_by_basename( $base_file_name );
                update_post_meta( $imported_id, 'auxin_attachment_has_duplicate_' . $import_id , $import_id );
            }

            return;
        }

        if ( ! function_exists('wp_handle_sideload') ) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }

        $file_array             = array();
        $file_array['name']     = basename( $url );
         // Download file to temp location.
        $file_array['tmp_name'] = $file_name;

        // If error storing temporarily, return the error.
        if ( is_wp_error( $file_array['tmp_name'] ) ) {
            return;
        }

        $overrides = array( 'test_form' => false );
        $time      = current_time( 'mysql' );
        $date      = explode( '/', $path );
        $year      = isset( $date[0] ) ? $date[0] : date("Y");
        $month     = isset( $date[1] ) ? $date[1] : date("n");

        if ( ! empty( $path ) ) {
            $time = date( "Y-m-d H:i:s", mktime( date("H"), date("i"), date("s"), (int) $month, date("j"), (int) $year ) );
        } elseif ( $post = get_post( $post_id ) ) {
                if ( substr( $post->post_date, 0, 4 ) > 0 )
                        $time = $post->post_date;
        }

        $file = wp_handle_sideload( $file_array, $overrides, $time );

        if ( isset( $file['error'] ) && ! empty( $file['error'] ) ) {
            return;
        }

        $url      = $file['url'];
        $type     = $file['type'];
        $file     = $file['file'];
        $title    = preg_replace('/\.[^.]+$/', '', 'demo-attachment-' . $import_id . '-' . $base_file_name );
        $content  = '';

        // Use image exif/iptc data for title and caption defaults if possible.
        if ( $image_meta = @wp_read_image_metadata($file) ) {
            if ( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) ) {
                $title = $image_meta['title'];
            }
            if ( trim( $image_meta['caption'] ) ) {
                $content = $image_meta['caption'];
            }
        }

        if ( isset( $desc ) ) {
            $title = $desc;
        }

        // Construct the attachment array.
        $attachment = array(
            'post_mime_type' => $type,
            'guid'           => $url,
            'post_parent'    => $post_id,
            'post_title'     => $title,
            'post_content'   => $content
        );

        // This should never be set as it would then overwrite an existing attachment.
        unset( $attachment['ID'] );

        // Save the attachment metadata
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

        $image_size = getimagesize( $file );

        if ( ! is_wp_error( $attach_id ) ) {
           
            $width = isset( $image_size[0] ) ? $image_size[0] : '';
            $height = isset( $image_size[1] ) ? $image_size[1] : '';

            wp_update_attachment_metadata( $attach_id, array( 'file' => $file, 'width' => $width, 'height' => $height, 'image_meta' => $image_meta ) );
        }

        //Add auxin meta flag on attachment
        if ( $type == 'video/mp4' ) {
            auxin_set_transient( 'auxin_video_import_id', $attach_id ); 
        }
        update_post_meta( $attach_id, 'auxin_import_id', $import_id );

        return $attach_id;

    }

    /**
     * Check media existence
     *
     * @param   string $filename
     *
     * @return  boolean
     */
    public function attachment_exist( $import_id, $filename ) {

        global $wpdb;
        $title = preg_replace('/\.[^.]+$/', '', 'demo-attachment-' . $import_id . '-' . $filename );

        return $wpdb->get_var( "
            SELECT COUNT(*)
            FROM
            $wpdb->posts    AS p,
            $wpdb->postmeta AS m
            WHERE
            p.ID = m.post_id
            AND p.post_type = 'attachment'
            AND m.meta_key  = 'auxin_import_id'
            AND p.post_title LIKE '$title'
        " );

    }



    public function update_elementor_data( $meta, $action = 'update' ) {

        $matches     = array();
        $attach_keys = array( 'image', 'img', 'photo', 'poster', 'media', 'src' );

        foreach ( $attach_keys as $attach_key ) {
            preg_match_all('/\s*"\b\w*'.$attach_key.'\w*\"\s*:\{.*?\}/', $meta, $image );
            if( isset( $image ) && ! empty( $image ) ){
                $matches = array_merge( $matches, $image );
            }
        }
        $patterns = array(
            '"wp_gallery":(\[.*?\])',
            '"carousel":(\[.*?\])',
            '"attach_id":\{.*?\}'
        );
        foreach( $patterns as $key => $pattern ) {
            preg_match_all('/' . $pattern . '/', $meta, $wp_gallery, PREG_SET_ORDER );
            if ( !empty( $wp_gallery ) ) {
                foreach ( $wp_gallery as $gallery_key => $gallery_val ) {
                    preg_match_all( '/\{\"id":.*?\}/' , $gallery_val[0], $gallery );
                    $matches = !empty( $gallery ) ? array_merge( $matches, $gallery ) : $matches;
                }
            }
        }

        preg_match_all('/"icon_list":(\[.*?\])/', $meta, $icon_lists, PREG_SET_ORDER );
        if ( !empty( $icon_lists ) ) {
            foreach ( $icon_lists as $list_key => $icon_list ) {
                preg_match_all( '/\{\"url":.*?\}/' , $icon_list[0], $list );
                $matches = !empty( $list ) ? array_merge( $matches, $list ) : $matches;
            }
        }

        $svg_icon_patterns = [
            'icon',
            'selected_icon',
            'prev_icon',
            'next_icon',
            'aux_new_icon'
        ];
        
        foreach( $svg_icon_patterns as $key => $pattern ) {
            preg_match_all('/"' . $pattern . '":(\{.*?\})/', $meta, $svg_urls, PREG_SET_ORDER );
            if ( !empty( $svg_urls ) ) {
                foreach ( $svg_urls as $svg_key => $svg_url ) {
                    preg_match_all( '/\{\"url":.*?\}/' , $svg_url[0], $svg );
                    $matches = !empty( $svg ) ? array_merge( $matches, $svg ) : $matches;
                }
            }
        }

        // remove empties
        $matches = array_filter( $matches );

        foreach ( $matches as $images ) {
            foreach ( $images as $image ) {


                $isIntegerValue = false;
                preg_match('/(?:"id":")(.*?)(?:")/', $image, $image_id );
                if( ! isset( $image_id[1] ) || empty( $image_id[1] ) ) {
                    // This is a fixup for integer values of elementor json data value.
                    preg_match('/\"id":(\d*)/', $image, $image_id );
                    if( ! isset( $image_id[1] ) || empty( $image_id[1] ) ) {
                        continue;
                    }
                    $isIntegerValue = true;
                }
                $image_id = strval($image_id[1]);

                preg_match('/(?:"url":")(.*?)(?:")/', $image, $image_url );
                if( ! isset( $image_url[1] ) || empty( $image_url[1] ) ) {
                    continue;
                }
                $image_url = $image_url[1];

                $new_image_id = $new_image_url = '';

                if( $action === 'upload' && class_exists( '\Elementor\TemplateLibrary\Classes\Import_Images' ) ){
                    $import_images  = new \Elementor\TemplateLibrary\Classes\Import_Images();
                    $new_attachment = $import_images->import( array(
                        'id'  => stripslashes( $image_id ),
                        'url' => stripslashes( $image_url )
                    ) );
                    $new_image_id  = isset( $new_attachment['id'] ) ? $new_attachment['id'] : $image_id;
                    $new_image_url = isset( $new_attachment['url'] ) ? $new_attachment['url'] : $image_url;

                } else {
                    $new_image_id  = $this->get_attachment_id( 'auxin_import_id', $image_id );
                    $new_image_url = wp_get_attachment_url( $new_image_id );
                }

                if( ! empty( $new_image_id ) && ! empty( $new_image_url ) ){
                    if( $isIntegerValue ){
                        $new_image = str_replace( '"id":'. $image_id, '"id":'. $new_image_id, $image );
                    } else {
                        $new_image = str_replace( '"id":"'. $image_id .'"', '"id":"'. $new_image_id . '"', $image );
                    }
                    $new_image = str_replace( '"url":"'. $image_url, '"url":"'. str_replace( '/', '\/', $new_image_url), $new_image );
                    $meta = str_replace( $image , $new_image, $meta );
                }
            }
        }

        // Replace old category ID's
        preg_match_all('/"cat":(\[.*?\])/', $meta, $cats, PREG_SET_ORDER );
        if( ! empty( $cats ) ) {
            foreach ( $cats as $key => $cat ) {
                // Check array existence
                if( ! isset( $cat[0] ) ){
                    continue;
                }
                $cat_array  = array();
                // Put json array into php array
                $categories = json_decode( $cat[1], true );
                if( is_array( $categories ) && ! empty( $categories ) ) {
                    foreach ( $categories as $cat_key => $cat_id ) {
                        $cat_old_id = auxin_get_transient( 'auxin_category_new_id_of' . $cat_id );
                        if( $cat_old_id !== false ){
                            $cat_array[ $cat_key ] = $cat_old_id;
                        }
                    }
                    // Remove duplicates of empty data
                    array_unique( $cat_array );
                    $meta = str_replace( $cat[0], '"cat":'. wp_json_encode( $cat_array ), $meta );
                }
            }
        }

        // Change structure of urls included in custom css
        $site_url = str_replace( '/', '\/', trailingslashit( get_site_url() ) );
        preg_match_all( '/"custom_css":".+?(?<!\\ )"/', $meta, $custom_css, PREG_SET_ORDER );
        if ( ! empty( $custom_css ) ) {
            foreach ( $custom_css as $key => $css ) {
                preg_match_all( '#[\w\\\/\-\.\:]+?([\w\-]+?)\\\/wp-content#', $css[0], $matches, PREG_SET_ORDER );
                if ( ! empty( $matches ) ) {
                    $new_css = $css[0];
                    foreach( $matches[0] as $key => $url ) {
                        if ( ! empty( $matches[1][$key] ) ) {
                            $new_url = str_replace( '\/' . $matches[1][$key], '', $url );
                            $new_url = str_replace( "https:\/\/demo.phlox.pro\/", $site_url, $new_url );
                            $new_css = str_replace( $url, $new_url, $new_css );
                        }
                        
                    }
                    $new_css = preg_replace( "#sites\\\/\d*\\\/#", '', $new_css );
                    $meta = str_replace( $css[0], $new_css, $meta );    
                }
            }
        }

        // change all site urls to imported url
        preg_match_all( '#https:\\\/\\\/demo.phlox.pro\\\/.*?\\\/#', $meta, $urls, PREG_SET_ORDER );
        if ( ! empty( $urls ) ) {
            foreach( $urls as $key => $url ) {
                $meta = str_replace( $url[0], $site_url, $meta );
            }
        }

        // remove network part of url from importing 
        // http:\/\/...\/\/wp-content\/uploads\/sites\/12\/2020\/09 => http:\/\/...\/\/wp-content\/uploads\/2020\/09
        if ( ! is_array( $meta ) ) {
            $meta = preg_replace( "#sites\\\/\d*\\\/#", '', $meta );
        }

        return $meta;
    }


    public function siteorigin_data_update( $meta ) {

        $meta_str = wp_json_encode( $meta );

        preg_match_all( '/\s*"background_image_attachment"\s*:\s*"(\d+?)\s*",?/', $meta_str, $matchs, PREG_SET_ORDER );

        foreach ( $matchs as $match ) {
            if ( sizeof( $match ) > 1 ) {
                $new_id = $this->get_attachment_id( 'auxin_import_id', $match[1] );
                $replaced_meta = str_replace( $match[1] , $new_id, $match[0]);
                $meta_str = str_replace( $match[0] , $replaced_meta, $meta_str );
            }
        }

        return json_decode( $meta_str, true );

    }



}//End class
