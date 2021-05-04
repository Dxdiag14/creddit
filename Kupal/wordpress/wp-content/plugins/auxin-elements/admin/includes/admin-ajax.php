<?php

function auxin_ajax_send_feedback(){

    // skip if the form data is not receiced
    if( empty( $_POST['form'] ) ){
        wp_send_json_error( __( 'Data cannot be delivered, please try again.', 'auxin-elements' ) );
    }

    $form_data = $_POST['form'];

    // extract the form data
    $rate     = ( ! empty( $form_data['theme_rate'] ) || $form_data['theme_rate'] === '0' ) ? $form_data['theme_rate'] : '';
    $feedback = ! empty( $form_data['feedback']   ) ? $form_data['feedback']   : '';
    $email    = ! empty( $form_data['email']      ) ? $form_data['email']      : '';
    $nonce    = ! empty( $form_data['_wpnonce']   ) ? $form_data['_wpnonce']   : '';

    if( ! wp_verify_nonce( $nonce, 'phlox_feedback' ) ){
        wp_send_json_error( __( 'Authorization failed!', 'auxin-elements' ) );
    }

    if( $rate || $rate === '0' ){

        global $wp_version;

        $passed_diff_time = auxin_get_passed_installed_time();
        $installed_days = isset( $passed_diff_time->days ) ? $passed_diff_time->days : 1;

        $args = array(
            'user-agent' => 'WordPress/'.$wp_version.'; '. get_home_url(),
            'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 5),
            'body'       => array(
                'cat'         => 'rating',
                'action'      => 'submit',
                'item-slug'   => 'phlox',
                'rate'        => $rate,
                'client_key'  => get_theme_mod( 'client_key', ''),
                'item_version'=> THEME_VERSION,
                'theme_slug'  => THEME_ID,
                'feedback'    => $feedback,
                'is_active'   => function_exists('auxin_is_activated') &&  auxin_is_activated(),
                'installed_days' => $installed_days
            )
        );
        // send the rating through the api
        $request = wp_remote_post( 'http://api.averta.net/envato/items/', $args );
        update_option( 'auxin_show_rate_scale_notice', 0 );
        set_theme_mod( 'rate_scale_notice_remind_later_date', 0 );
        // if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {}

        // store the user rating on the website
        auxin_update_option( 'user_rating', $rate );

        // send the feedback via email
        $message = 'Rate: '. $rate . "\r\n" . 'Email: <' . $email . ">\r\n\r\n" . $feedback;
        wp_mail( 'feedbacks'.'@'.'averta.net', 'Feedback from phlox dashboard:', $message );

        wp_send_json_success( __( 'Sent Successfully. Thanks for your feedback!', 'auxin-elements' ) );

    } else{
        wp_send_json_error( __( 'An error occurred. Feedback could not be delivered, please try again.', 'auxin-elements' ) );
    }

}

add_action( 'wp_ajax_send_feedback', 'auxin_ajax_send_feedback' );

/**
 * Hide Feedback notice
 */
function auxin_remove_feedback_notice() {
    // skip if the form data is not receiced
    if( empty( $_POST['form'] ) ){
        wp_send_json_error( __( 'Data cannot be delivered, please try again.', 'auxin-elements' ) );
    }

    $form_data = $_POST['form'];

    $nonce    = ! empty( $form_data['_wpnonce']   ) ? $form_data['_wpnonce']   : '';

    if( ! wp_verify_nonce( $nonce, 'phlox_feedback' ) ){
        wp_send_json_error( __( 'Authorization failed!', 'auxin-elements' ) );
    }

    update_option( 'auxin_show_rate_scale_notice', 0 );
    set_theme_mod( 'rate_scale_notice_remind_later_date', 0 );

    wp_send_json_success();
}

add_action( 'wp_ajax_aux-remove-feedback-notice', 'auxin_remove_feedback_notice' );


/**
 * Remind feedback
 */
function auxin_ajax_remind_feedback() {
    // skip if the form data is not receiced
    if( empty( $_POST['form'] ) ){
        wp_send_json_error( __( 'Data cannot be delivered, please try again.', 'auxin-elements' ) );
    }

    $form_data = $_POST['form'];

    $nonce     = ! empty( $form_data['_wpnonce'] ) ? $form_data['_wpnonce'] : '';

    if( ! wp_verify_nonce( $nonce, 'phlox_feedback' ) ){
        wp_send_json_error( __( 'Authorization failed!', 'auxin-elements' ) );
    }

    // reset feedback notice viewer
    update_option( 'auxin_show_rate_scale_notice', 0 );
    set_theme_mod( 'rate_scale_notice_remind_later_date', time() + DAY_IN_SECONDS * 3 );

    wp_send_json_success();
}

add_action( 'wp_ajax_remind_feedback', 'auxin_ajax_remind_feedback' );



function auxin_ajax_isotope_filter_group(){
    // Check nonce
    if ( ! isset( $_POST['group'] ) ||! isset( $_POST['key'] ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aux-iso-group' ) ) {
        wp_send_json_error( __( 'Token Error.', 'auxin-elements' ) );
    }

    if( false !== update_option( 'aux_isotope_group_' . $_POST['key'] , $_POST['group'] ) ) {
        wp_send_json_success( __( 'It\'s Done.', 'auxin-elements' ) );
    }

    wp_send_json_error( __( 'An error occurred.', 'auxin-elements' ) );
}
add_action( 'wp_ajax_aux_isotope_group', 'auxin_ajax_isotope_filter_group' );


function auxin_ajax_filter_get_content() {

    // Check nonce
    if ( ! isset( $_POST['n'] ) || ! wp_verify_nonce( $_POST['n'], 'aux_ajax_filter_request' ) ) {
        wp_send_json_error( 'Nonce check failed!', 403 );
    }

    $num         = $_POST['num'];
    $post_type   = 'product';
    $tax         = $_POST['taxonomy'];
    $term        = $_POST['term'];
    $image_class = 'aux-img-dynamic-dropshadow';
    $width       = $_POST['width'];
    $height      = $_POST['height'];
    $order       = $_POST['order'];
    $orderby     = $_POST['orderby'];
    $size        = array( 'width' => $width, 'height' => $height );

    /*
     * The WordPress Query class.
     *
     * @link http://codex.wordpress.org/Function_Reference/WP_Query
     */
    $args = array(
        // Type & Status Parameters
        'post_type'   => $post_type,
        'post_status' => 'publish',
        // Pagination Parameters
        'posts_per_page' => $num,
        'nopaging'       => false,
        'order'          => $order,
        'orderby'        => $orderby,
    );

    if ( 'all' !== $term ) {
        // Taxonomy Parameters
        $args['tax_query'] = array(
            array(
                'taxonomy'         => $tax,
                'field'            => 'slug',
                'terms'            => $term,
                'include_children' => true,
                'operator'         => 'IN',
            )
        );
    }

    $posts = get_posts( $args );

    foreach ( $posts as $post ) {

        $image_id = get_post_thumbnail_id( $post );
        $product = wc_get_product( $post->ID );

        $post->thumb = auxin_get_the_responsive_attachment(
            $image_id,
            array(
                'quality'      => 100,
                'upscale'      => true,
                'crop'         => true,
                'add_hw'       => true, // whether add width and height attr or not
                'attr'         => array(
                    'class'                => 'auxshp-product-image auxshp-attachment ' . $image_class,
                    'data-original-width'  => $width,
                    'data-original-height' => $height,
                    'data-original-src'    => wp_get_attachment_image_src( $image_id, 'full' )[0]
                ),
                'size'         => $size,
                'image_sizes'  => 'auto',
                'srcset_sizes' => 'auto',
                'original_src' => true
            )
        );

        $post->price = $product->get_price_html();
        $post->meta = wc_get_product_category_list( $product->get_id(), ', ', '<em class="auxshp-meta-terms">', '</em>' );
        $post->badge = $product->is_on_sale() ? true : false;

        if( auxin_get_option( 'product_index_ajax_add_to_cart', '0' ) ) {
            $class = 'button aux-ajax-add-to-cart add_to_cart_button';
        }

        $post->cart = apply_filters( 'woocommerce_loop_add_to_cart_link',
                    sprintf( '<a rel="nofollow" href="%s" data-quantity="1" data-product_id="%s" data-product_sku="%s" data-verify_nonce="%s" class="%s"><i class="aux-ico auxicon-handbag"></i><span>%s</span></a>',
                        esc_url( $product->add_to_cart_url() ),
                        esc_attr( $product->get_id() ),
                        esc_attr( $product->get_sku() ),
                        esc_attr( wp_create_nonce( 'aux_add_to_cart-' . $product->get_id() ) ),
                        esc_attr( isset( $class ) ? $class : 'button add_to_cart_button' ),
                        esc_html( $product->add_to_cart_text() )
                    ),
                $product );
    }

    wp_send_json_success( $posts );

}

add_action( 'wp_ajax_filter_get_content', 'auxin_ajax_filter_get_content' );
add_action( 'wp_ajax_noprive_filter_get_content', 'auxin_ajax_filter_get_content' );

/**
 * wordpress ajax for dismissed notice
 *
 * @return json
 */
function auxin_dismissed_notice(){
    // Store it in the options table
	if ( ! isset( $_POST['id'] ) ||  ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], '_notice_nonce' ) ) {
		wp_send_json_error(  __( 'Token Error.', 'auxin-elements' ) );
	} else {
		auxin_set_transient( 'auxin-notice-' . $_POST['id'], 1, $_POST['expiration'] );
		wp_send_json_success( __( 'It\'s OK.', 'auxin-elements' ) );
	}
}
add_action( 'wp_ajax_auxin_dismissed_notice', 'auxin_dismissed_notice' );

/**
 * WordPress ajax to display activation form
 *
 * @return html
 */
function auxin_display_actvation_form(){
    if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'aux-activation-form' ) ) {
        // This nonce is not valid.
        wp_die( esc_html__( 'Security Token Error!', 'auxin-elements' ) );
    }
    ob_start();
?>
    <div class="aux-license-popup">
        <button class="featherlight-close-icon featherlight-close" aria-label="Close">✕</button>
        <img class="aux-popup-image" src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/activation.svg' ); ?>" />
        <h2 class="aux-popup-title"><?php esc_html_e( 'License Activation', 'auxin-elements' ); ?></h2>
        <p class="aux-popup-desc"><?php esc_html_e( 'Please activate your license to get automatic updates, premium support, and unlimited access to the template library and demo importer.', 'auxin-elements' ); printf('&nbsp;<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">%s</a>', esc_html( 'how to find purchase code?', 'auxin-elements' ) ); ?></p>
        <form class="auxin-form auxin-check-purchase" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post">
            <div class="form-group">
                <label class="form-label" for="aux-usermail"><?php esc_html_e( 'E-mail address', 'auxin-elements' ); ?></label>
                <input class="form-control" type="text" name="usermail" value="<?php echo esc_attr( get_option('admin_email') ); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="aux-purchase"><?php esc_html_e( 'Purchase code', 'auxin-elements' ); ?></label>
                <input class="form-control" type="text" name="purchase" required>
            </div>

            <?php wp_nonce_field( 'auxin-purchase-activation', 'security' ); ?>
            <input type="hidden" name="action" value="auxin_purchase_activation">
            <div class="button-group">
                <button type="submit" class="aux-button aux-primary aux-medium aux-activate-license" value="submit">
                    <span><?php esc_html_e( 'Activate', 'auxin-elements' ); ?></span>
                </button>
            </div>
        </form>
    </div>
<?php
    wp_die( ob_get_clean() );
}
add_action( 'wp_ajax_auxin_display_actvation_form', 'auxin_display_actvation_form' );

/**
 * wordpress ajax for auxin purchase activation
 *
 * @return json
 */
function auxin_purchase_activation(){

    if ( ! isset( $_POST['usermail'] ) ||  ! isset( $_POST['purchase'] ) || ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'auxin-purchase-activation' ) ) {
		wp_send_json_error( array(
			'message' 	 => __( 'Token Error.', 'auxin-elements' ),
			'buttonText' => __( 'Retry', 'auxin-elements' ),
		) );
    }

    $usermail      = $_POST['usermail'];
    $purchase_code = $_POST['purchase'];
    $action        = 'activate';

    $result = Auxin_License_Activation::get_instance()->license_action( $usermail, $purchase_code, $action );

    if( isset( $result['success'] ) && $result['success'] ){
        $result['buttonText'] = __( 'Close', 'auxin-elements' );
        wp_send_json_success( $result );
    }

    $result['buttonText'] = __( 'Retry', 'auxin-elements' );
    wp_send_json_error( $result );

}
add_action( 'wp_ajax_auxin_purchase_activation', 'auxin_purchase_activation' );

/**
 * wordpress ajax for auxin upgrader
 *
 * @return json
 */
function auxin_ajax_upgrader(){
    // Check ajax nonce field
    check_ajax_referer( 'auxin-start-upgrading', 'nonce' );

    if ( ! isset( $_POST['key'] ) || ! isset( $_POST['type'] ) ) {
		wp_send_json_error(  array(
            'slug'         => '',
            'errorCode'    => 'no_token_specified',
            'errorMessage' => __( 'Token Error.', 'auxin-elements' )
        ) );
    }

    $handler = new Auxin_Upgrader_Ajax_Handlers;
    $handler->run( $_POST['key'], $_POST['type'] );
}
add_action( 'wp_ajax_auxin_start_upgrading', 'auxin_ajax_upgrader' );


/**
 * wordpress ajax for auxin customizer export
 *
 * @return json
 */
function auxin_customizer_export(){

    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'auxin-export-control' ) ) {
		wp_send_json_error( __( 'Token Error.', 'auxin-elements' ) );
    }

    // Get theme options
    $theme_options = auxin_options();

    // Get theme mods
    $theme_mods = get_theme_mods();
    $filters    = array( 0, 'nav_menu_locations', 'custom_css_post_id', 'last_checked_version' );
    foreach ( $filters as $filter ) {
        if ( isset( $theme_mods[ $filter ] ) ) {
            unset( $theme_mods[ $filter ] );
        }
    }

    if( empty( $theme_options ) && empty( $theme_mods ) ){
        wp_send_json_error( __( 'No data found!', 'auxin-elements' ) );
    }

    $b64_content = base64_encode( maybe_serialize( array(
        'theme_options' => $theme_options,
        'theme_mods'    => $theme_mods
    ) ) );

    wp_send_json_success( array(
        'content'  => $b64_content,
        'fileName' => THEME_ID . '_export_' . current_time('timestamp') . '.txt'
    ) );

}
add_action( 'wp_ajax_auxin_customizer_export', 'auxin_customizer_export' );


/**
 * wordpress ajax for auxin customizer import
 *
 * @return json
 */
function auxin_customizer_import(){
    // Check security
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'auxin-import-control' ) ) {
		wp_send_json_error( __( 'Token Error.', 'auxin-elements' ) );
    }

    // Check input file
    if ( ! isset( $_FILES['file'] ) || 0 < $_FILES['file']['error'] ) {
        wp_send_json_error( __( 'Please upload a valid file.', 'auxin-elements' ) );
    }

    // Get and decode file content
    global $wp_filesystem;
    if ( empty($wp_filesystem) ) {
        require_once( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();
    }
    $json_content  = $wp_filesystem->get_contents( $_FILES['file']['tmp_name'] );
    $array_content = maybe_unserialize( base64_decode( $json_content ) );

    // Check array empty
    if ( empty( $array_content ) || ! is_array( $array_content ) ) {
        wp_send_json_error( __( 'Invalid or Empty Data.', 'auxin-elements' ) );
    }

    if( isset( $array_content['theme_options'] ) ){
        // Get image options names
        $get_options    = auxin_get_defined_options();
        $custom_images  = array();
        foreach ( $get_options['fields'] as $key => $value ) {
            if ( ! array_search(  'image', $value ) ) {
                continue;
            }
            $custom_images[]   = $value['id'];
        }
        // Update options
        foreach ( $array_content['theme_options'] as $auxin_key => $auxin_value ) {
            if ( in_array( $auxin_key, $custom_images ) && ! empty( $auxin_value ) ) {
                continue;
            }
            // Update exclusive auxin options
            auxin_update_option( $auxin_key , $auxin_value );
        }
    }

    if( isset( $array_content['theme_mods'] ) ){
        foreach ( $array_content['theme_mods'] as $theme_mods_key => $theme_mods_value ) {
            // Start theme mods loop:
            if( $theme_mods_key === 'custom_logo' ) {
                continue;
            }
            // Update theme mods
            set_theme_mod( $theme_mods_key , $theme_mods_value );
        }
    }

    // force to flush dynamic asset files
    delete_transient( 'auxin_' . AUXELS_SLUG . '_version' );

    wp_send_json_success( __( 'Successfully Imported.', 'auxin-elements' ) );

}
add_action( 'wp_ajax_auxin_customizer_import', 'auxin_customizer_import' );


/**
 * Ajax handler for auxin_template_library control to import template
 *
 * @return json
 */
function auxin_template_control_importer() {
    wp_send_json( auxin_template_importer( $_POST['id'], $_POST['template_type'], 'update_menu' ) );
}
add_action( 'wp_ajax_auxin_template_control_importer', 'auxin_template_control_importer' );
