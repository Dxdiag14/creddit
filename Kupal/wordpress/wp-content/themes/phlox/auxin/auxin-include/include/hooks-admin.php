<?php
/**
 * Admin hooks
 *
 * 
 * @package    Auxin
 * @author     averta (c) 2014-2021
 * @link       http://averta.net
*/

function auxin_update_font_icons_list(){
    // parse and cache the list of fonts
    $fonts = Auxin()->Font_Icons;
    $fonts->update();
}
add_action( 'after_switch_theme', 'auxin_update_font_icons_list' );


// make the customizer avaialble while requesting via ajax
if ( defined('DOING_AJAX') && DOING_AJAX && version_compare( PHP_VERSION, '5.3.0', '>=') ){
    Auxin_Customizer::get_instance();
}



/**
 * Include the Welcome page admin menu
 *
 * @return void
 */
function auxin_setup_admin_welcome_page() {
    if( class_exists('Auxin_Welcome') ){
        Auxin_Welcome::get_instance();
    } else {
        Auxin_Welcome_Base::get_instance();
    }
}
add_action( 'auxin_admin_loaded', 'auxin_setup_admin_welcome_page' );


/*------------------------------------------------------------------------*/

/**
 * Update the deprecated option ids
 */
function auxin_update_last_checked_version(){

    $last_checked_version = auxin_get_theme_mod( 'last_checked_version', '1.0.0' );

    if( version_compare( $last_checked_version, THEME_VERSION, '>=') ){
        return;
    }

    do_action( 'auxin_theme_updated', $last_checked_version );
    do_action( 'auxin_updated'      , 'theme', THEME_ID, THEME_VERSION, THEME_ID );

    set_theme_mod( 'last_checked_version', THEME_VERSION );
}
add_action( 'auxin_loaded', 'auxin_update_last_checked_version' );




/**
 * Skip the notice for core plugin if skip btn clicked
 *
 * @return void
 */
function auxin_hide_required_plugin_notice() {

    if ( isset( $_GET['aux-hide-core-plugin-notice'] ) && isset( $_GET['_notice_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_GET['_notice_nonce'], 'auxin_hide_notices_nonce' ) ) {
            wp_die( __( 'Authorization failed. Please refresh the page and try again.', 'phlox' ) );
        }
        auxin_set_transient( 'auxin_hide_the_core_plugin_notice_' . THEME_ID, 1, 4 * YEAR_IN_SECONDS );
    }

    if ( isset( $_GET['aux-hide-phlox-pro-tools-plugin-notice'] ) && isset( $_GET['_notice_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_GET['_notice_nonce'], 'auxin_hide_notices_nonce' ) ) {
            wp_die( __( 'Authorization failed. Please refresh the page and try again.', 'phlox' ) );
        }
        auxin_set_transient( 'auxin_hide_phlox_pro_tools_plugin_notice_' . THEME_ID, 1, 4 * YEAR_IN_SECONDS );
    }

    if ( isset( $_GET['aux-show-install-core-plugin-notice'] ) && isset( $_GET['_notice_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_GET['_notice_nonce'], 'auxin_hide_notices_nonce' ) ) {
            wp_die( __( 'Authorization failed. Please refresh the page and try again.', 'phlox' ) );
        }
        auxin_delete_transient( 'auxin_hide_phlox_pro_tools_plugin_notice_' . THEME_ID );
        auxin_delete_transient( 'auxin_hide_the_core_plugin_notice_' . THEME_ID );
    }
}
add_action( 'wp_loaded', 'auxin_hide_required_plugin_notice' );


/**
 * Display a notice for installing theme core plugin
 *
 * @return void
 */
function auxin_core_plugin_notice(){
    if ( auxin_get_transient( 'auxin_hide_the_core_plugin_notice_' . THEME_ID ) ) {
        return;
    }
    if( defined( 'AUXELS_VERSION' ) ) {
        if( class_exists( '\Elementor\Plugin' ) ){
            return;
        }
    }

    $plugins_base_name = array(
        'auxin-elements/auxin-elements.php',
        'elementor/elementor.php'
    );
    $plugins_slug      = array(
        'auxin-elements',
        'elementor'
    );
    $plugins_filename  = array(
        'auxin-elements.php',
        'elementor.php'
    );
    $plugins_title     = array(
        __('Phlox Core Plugin', 'phlox'),
        __('Elementor', 'phlox')
    );
    // Classess to check if plugins are active or not
    $class_check = array(
        'AUXELS',
        '\Elementor\Plugin'
    );


    $installed_plugins  = get_plugins();

    // find required plugins which is not installed or active
    $not_installed_or_activated_plugins_id = array();
    foreach ( $plugins_base_name as $key => $plugin_base_name ) {
        if( ! isset( $installed_plugins[ $plugin_base_name ] ) || ! class_exists( $class_check[$key] ) ){
            $not_installed_or_activated_plugins_id[] = $key;
        }
    }

    // get information of required plugins which is not installed or not activated
    foreach ( $not_installed_or_activated_plugins_id as $key => $value ) {

        $not_installed_plugins_number = count( $not_installed_or_activated_plugins_id );
        $progress_text = $not_installed_plugins_number > 1 ? ( $key + 1 ). " / {$not_installed_plugins_number}" : "";
        $progress_text_and_title = $progress_text . ' - ' .$plugins_title[ $value ];

        $links_attrs[$key] = array(
            'data-plugin-slug'      => $plugins_slug[$value],

            'data-activating-label' => sprintf( __( 'Activating %s', 'phlox' ), $progress_text_and_title ),
            'data-installing-label' => sprintf( __( 'Installing %s', 'phlox' ), $progress_text_and_title ),
            'data-activate-label'   => sprintf( __( 'Activate %s'  , 'phlox' ), $progress_text_and_title ),
            'data-install-label'    => sprintf( __( 'Install %s'   , 'phlox' ), $progress_text_and_title ),

            'data-activate-url'     => auxin_get_plugin_activation_link( $plugins_base_name[$value], $plugins_slug[$value], $plugins_filename[$value] ),
            'data-install-url'      => auxin_get_plugin_install_link( $plugins_slug[$value] ),

            'data-redirect-url'     => self_admin_url( 'admin.php?page=auxin-welcome' ),
            'data-num-of-required-plugins' => $not_installed_plugins_number,
            'data-plugin-order'     => $key + 1,
            'data-wpnonce'          => wp_create_nonce( 'aux_setup_nonce' )
        );

        if( ! isset( $installed_plugins[ $plugins_base_name[$value] ] ) ){
            $links_attrs[$key]['data-action'] = 'install';
            $links_attrs[$key]['href'] = $links_attrs[ $key ]['data-install-url'];
            $links_attrs[$key]['button_label'] = sprintf( esc_html__( 'Install %s', 'phlox' ), $progress_text_and_title );
        } elseif( ! class_exists( $class_check[ $value ] ) ) {
            $links_attrs[$key]['data-action'] = 'activate';
            $links_attrs[$key]['href'] = $links_attrs[ $key ]['data-activate-url'];
            $links_attrs[$key]['button_label'] = sprintf( esc_html__( 'Activate %s', 'phlox' ), $progress_text_and_title );
        }
    }
?>
    <div class="updated auxin-message aux-notice-wrapper aux-notice-install-now">
        <h3 class="aux-notice-title"><?php printf( __( 'Thanks for choosing %s', 'phlox' ), THEME_NAME_I18N ); ?></h3>
        <p class="aux-notice-description"><?php echo __( 'To take full advantages of Phlox theme and enabling demo importer, please install core plugins.', 'phlox' ); ?></p>
        <p class="submit">
            <a class="button button-primary auxin-install-now aux-not-installed" data-info='<?php echo wp_json_encode( $links_attrs);?>' ><?php echo $links_attrs[0]['button_label']; ?></a>
            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'aux-hide-core-plugin-notice', 'install' ), 'auxin_hide_notices_nonce', '_notice_nonce' ) ); ?>" class="notice-dismiss aux-close-notice"><span class="screen-reader-text"><?php _e( 'Skip', 'phlox' ); ?></span></a>
        </p>
    </div>
<?php
}
add_action( 'admin_notices', 'auxin_core_plugin_notice' );




function auxin_customizer_device_options( $obj ) {
    if ( isset( $obj->devices ) && is_array( $obj->devices ) && ! empty( $obj->devices ) ): ?>
        <div class="axi-devices-option-wrapper" data-option-id="<?php echo esc_attr( $obj->id ); ?>">
            <span class="axi-devices-option axi-devices-option-desktop axi-selected" data-select-device="desktop">
                <img src="<?php echo esc_url( AUXIN_URL . 'images/visual-select/desktop.svg' ); ?>">
            </span>
            <?php foreach ( $obj->devices as $device => $title ): ?>
            <span class="axi-devices-option axi-devices-option-<?php echo esc_attr( $device ); ?>" data-select-device="<?php echo esc_attr( $device ); ?>">
                <img src="<?php echo esc_url( AUXIN_URL . 'images/visual-select/' . $device . '.svg' ); ?>" >
            </span>
            <?php endforeach ?>
        </div>
    <?php endif;
}

add_action( 'customize_render_control', 'auxin_customizer_device_options' );

/*-----------------------------------------------------------------------------------*/
/*  Auxin Admin notices
/*-----------------------------------------------------------------------------------*/
function auxin_admin_theme_lite_notices( $notice_list ){

    $notice_list[ 'rate_phlox_free' ] = new Auxin_Notices(array(
        'id'        => 'rate_phlox_free',
        'title'     => 'Hi! Thank you so much for using Phlox theme.',
        'desc'      => 'Could you please do us a HUGE favor? If you could take 2 min of your time, we would be really thankful if you give Phlox theme a 5-star rating on WordPress. By spreading the love, we can push Phlox forward and create even greater free stuff in the future!',
        'skin'      => 'default', // 'success', 'info', 'error'
        'has_close' => false,
        'initial_snooze' => DAY_IN_SECONDS * 2,
        'image'     => array(
            'width' => '105',
            'src'   => esc_url( AUXIN_URL ) . 'css/images/welcome/rating.svg'
        ),
        'buttons'   => array(
            array(
                'label'      => __('Sure, I like Phlox', 'phlox'),
                'link'       => 'https://wordpress.org/support/theme/phlox/reviews/?filter=5#new-post'
            ),
            array(
                'label'      => __('Maybe Later', 'phlox'),
                'type'       => 'skip',
                'expiration' => DAY_IN_SECONDS * 3
            ),
            array(
                'label'      => __('I Already Did :)', 'phlox'),
                'type'       => 'skip'
            )
        )
    ));

    if( version_compare( PHP_VERSION, '5.6.0', '<=' ) ){
        $notice_list[ 'php_phlox_requirement' ] = new Auxin_Notices( array(
            'id'        => 'php_phlox_requirement',
            'title'     => __( 'Please update your PHP version', 'phlox' ),
            'desc'      => sprintf( __( 'This theme is perfectly optimized for latest version of PHP. Your current PHP version is %s, we highly recommend you to upgrade PHP to version 7.0 or higher.', 'phlox' ), '<strong>' . PHP_VERSION . '</strong>' ),
            'skin'      => 'error', // 'success', 'info', 'error'
            'has_close' => false,
            // 'image'     =>[
            //     'width' => '105',
            //     'src'   => esc_url( AUXIN_URL ) . 'css/images/welcome/rating.svg'
            // ]
            'buttons'   => array(
                array(
                    'label'      => __('Remind me later', 'phlox'),
                    'type'       => 'skip',
                    'expiration' => DAY_IN_SECONDS * 3
                ),
                array(
                    'label'      => __('Dismiss notice', 'phlox'),
                    'type'       => 'skip'
                )
            )
        ));
    }


    return $notice_list;
}
add_action( 'auxin_admin_notices_instances', 'auxin_admin_theme_lite_notices' );
