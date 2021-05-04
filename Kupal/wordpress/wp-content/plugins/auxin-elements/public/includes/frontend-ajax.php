<?php

/**
 * Load more ajax handler for "Recent Posts Grid" element
 *
 * @return void
 */
function auxels_ajax_handler_element_load_more(){
    if( ! defined( 'AUXIN_INC' ) ){
        wp_send_json_success("Phlox theme is required.");
    }
    if( empty( $_POST["handler"] ) ){
        wp_send_json_success("Please specify a handler.");
    }
    // Direct call is not alloweded
    if( empty( $_POST['action'] ) ){
        wp_send_json_error( __( 'Ajax action not found.', 'auxin-elements' ) );
    }
    if( empty( $_POST['args'] ) ){
        wp_send_json_error( __( 'Ajax args is required.', 'auxin-elements' ) );
    }
    // Authorize the call
    if( ! wp_verify_nonce( $_POST['nonce'], 'auxin_front_load_more' ) ){
        wp_send_json_error( __( 'Authorization failed.', 'auxin-elements' ) );
    }

    $ajax_args      = $_POST['args'];
    $element_markup = '';

    // include the required resources
    require_once( AUXELS_INC_DIR . '/general-functions.php' );
    require_once( THEME_DIR . AUXIN_INC . 'include/functions.php' );
    require_once( THEME_DIR . AUXIN_INC . 'include/templates/templates-post.php' );

    // take required actions based on custom handler (element base name)
    switch( $_POST['handler'] ) {

        case 'aux_recent_posts':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-grid-carousel.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_callback( $ajax_args );
            break;

        case 'aux_recent_posts_land_style':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-land-style.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_land_style_callback( $ajax_args );
            break;

        case 'aux_recent_posts_masonry':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-masonry.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_masonry_callback( $ajax_args );
            break;

        case 'aux_recent_posts_tiles':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-tiles.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_tiles_callback( $ajax_args );
            break;

        case 'aux_recent_posts_timeline':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-timeline.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_timeline_callback( $ajax_args );
            break;

        case 'aux_recent_news':
            require_once( AUXNEW_INC_DIR . '/elements/recent-news.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_news_callback( $ajax_args );
            break;

        case 'aux_recent_news_big_grid':
            require_once( AUXNEW_INC_DIR . '/elements/recent-news-big-grid.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_news_big_grid_callback( $ajax_args );
            break;

        case 'aux_recent_portfolios_grid':
            require_once( AUXPFO_INC_DIR . '/elements/recent-portfolios.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_portfolios_grid_callback( $ajax_args );
            break;

        case 'aux_flexible_recent_posts':
            require_once( AUXPRO_INC_DIR . '/elements/flexible-recent-posts.php'    );

            // Get the element markup
            $element_markup = auxin_widget_flexible_recent_posts_callback( $ajax_args );
            break;

        default:
            wp_send_json_error( __( 'Not a valid handler.', 'auxin-elements' ) );
            break;
    }

    // if the output is empty
    if( empty( $element_markup ) ){
        wp_send_json_error( __( 'No data received.', 'auxin-elements' ) );
    }

    wp_send_json_success( $element_markup );
}

add_action( 'wp_ajax_load_more_element', 'auxels_ajax_handler_element_load_more' );
add_action( 'wp_ajax_nopriv_load_more_element', 'auxels_ajax_handler_element_load_more' );

/**
 * Remove Product from Cart via Ajax
 *
 * @return void
 */
function auxels_remove_product_from_cart() {

	if ( !class_exists( 'WooCommerce' ) )
		return;

	global $woocommerce;

	try {

        $nonce 			  = $_POST['verify_nonce'];
        $id 			  = $_POST['product_id'];
        $cart_item_key    = $_POST['cart_item_key'];

	    if( ! isset( $_POST['product_id'] ) || ! wp_verify_nonce( $nonce, 'remove_cart-' . $id ) ){
	    	wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('Verification failed!', 'auxin-elements') ) );
	    }

		$cart 			= $woocommerce->cart;
        $cart->remove_cart_item( $cart_item_key );

		$cart->calculate_totals();

        $args  = isset( $_POST['args'] ) ? $_POST['args'] : array(
            'title'          => '',
            'css_class'      => '',
            'dropdown_class' => '',
            'color_class'    => 'aux-black',
            'action_on'      => 'click',
            'cart_url'       => '#',
            'dropdown_skin'  => '',
        );
        $items = auxin_get_cart_items( $args );
        
		$response = array(
            'fragments' => apply_filters(
                'woocommerce_add_to_cart_fragments',
                array(
                    '.aux-cart-wrapper .aux-card-dropdown' => '<div class="aux-card-dropdown aux-phone-off">' . $items . '</div>' ,
                )
            ),
            'cart_hash' => WC()->cart->get_cart_hash(),
            'items'     => $items,
			'total'		=> 	$woocommerce->cart->get_cart_subtotal(),
			'count'		=> 	$cart->cart_contents_count,
			'empty'		=>	sprintf( '<div class="aux-card-box">%s</div>',  __( 'Your cart is currently empty.', 'auxin-elements' ) ),
			'notif'		=>	sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-message">%s</div>',  __('Item has been removed from your shopping cart.', 'auxin-elements') )
		);

		wp_send_json_success( $response );

    } catch (Exception $e) {
        wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('An Error Occurred!', 'auxin-elements') ) );
    }

}
add_action( 'wp_ajax_auxels_remove_from_cart', 'auxels_remove_product_from_cart' );
add_action( 'wp_ajax_nopriv_auxels_remove_from_cart', 'auxels_remove_product_from_cart' );


/**
 * Add to Cart via Ajax
 */
function auxels_add_product_to_cart() {

	if ( ! class_exists( 'WooCommerce' ) )
		return;

	global $woocommerce;

	try {

		$product_id        = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : '';
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

		if( empty( $product_id ) ){
			wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('Verification failed!', 'auxin-elements') ) );
		} else {
			// Add item to cart
			if( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity ) ) {
				$args  = isset( $_POST['args'] ) ? $_POST['args'] : array(
		            'title'          => '',
		            'css_class'      => '',
		            'dropdown_class' => '',
		            'color_class'    => 'aux-black',
		            'action_on'      => 'click',
		            'cart_url'       => '#',
		            'dropdown_skin'  => '',
		        );
				$items = auxin_get_cart_items( $args );
				$count = $woocommerce->cart->cart_contents_count;
				$total = auxin_get_cart_basket( $args, $count );

				$data  = array(
                    'fragments' => apply_filters(
                        'woocommerce_add_to_cart_fragments',
                        array(
                            '.aux-cart-wrapper .aux-card-dropdown' => '<div class="aux-card-dropdown aux-phone-off">' . $items . '</div>' ,
                        )
                    ),
                    'cart_hash' => WC()->cart->get_cart_hash(),
					'items' => $items,
					'total' => $total,
					'notif' => sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-message"><a href="%s" class="button wc-forward">%s</a> "%s" %s</div>', esc_url( wc_get_cart_url() ) , __( 'View cart', 'auxin-elements' ), get_the_title( $product_id ) , __('has been added to your cart.', 'auxin-elements') )
				);
				// Send json success
				wp_send_json_success( $data );
			} else {
				wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('Sorry, this product cannot be purchased.', 'auxin-elements') ) );
			}

		}

    } catch( Exception $e ){
        wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('An Error Occurred!', 'auxin-elements') ) );
    }

}
add_action( 'wp_ajax_auxels_add_to_cart', 'auxels_add_product_to_cart' );
add_action( 'wp_ajax_nopriv_auxels_add_to_cart', 'auxels_add_product_to_cart' );

/**
 * Get refreshed cart fragments
 */
function auxels_get_refreshed_fragments() {
    global $woocommerce;

    $args  = isset( $_POST['args'] ) ? $_POST['args'] : array(
        'title'          => '',
        'css_class'      => '',
        'dropdown_class' => '',
        'color_class'    => 'aux-black',
        'action_on'      => 'click',
        'cart_url'       => '#',
        'dropdown_skin'  => '',
    );
    $items = auxin_get_cart_items( $args );
    $count = $woocommerce->cart->cart_contents_count;
    $total = auxin_get_cart_basket( $args, $count );

    $data  = array(
        'cart_hash' => WC()->cart->get_cart_hash(),
        'items' => $items,
        'total' => $total,
    );

    // Send json success
    wp_send_json_success( $data );
}
add_action( 'wp_ajax_auxels_get_refreshed_fragments', 'auxels_get_refreshed_fragments' );
add_action( 'wp_ajax_nopriv_auxels_get_refreshed_fragments', 'auxels_get_refreshed_fragments' );

/**
 * Ajax Search Handler
 *
 * @return void
 */
function auxels_ajax_search() {

    $available_search_post_types = auxin_get_available_post_types_for_search();
    
    if ( ! empty( $_GET['post_types'] ) ) {
        $post_types = $_GET['post_types'];
    } else {
        $post_types = array_keys( $available_search_post_types );
    }
    
    $s   = trim( sanitize_text_field( $_GET['s'] ) );
    if ( "0" == $cat = trim( sanitize_text_field( $_GET['cat'] ) ) ) {
        $cat = '';
    }

    // Start Searching First Post type
    $search_instance = new Auxels_Search_Post_Type($s,$cat,$post_types[0],'');
    
    if ( ! empty( $_GET['per_page'] ) && is_numeric( $_GET['per_page'] ) ) {
        $search_instance->set_query_args( array( 'posts_per_page' => sanitize_text_field( $_GET['per_page'] ) ) );
    }

    $results = array();
    foreach( $post_types as $key => $post_type ) {
        $search_instance->set_query_args( array( 'post_type' => $post_type ) );
        switch ($post_type) {
            case 'product':
                $result = $search_instance->search_products();
                break;
            case 'portfolio':
                $result = $search_instance->search_portfolio();
                break;
            default:
                $result = $search_instance->search_general_post_types();
                break;
        }
        $result['fromTitle'] = sprintf( esc_html__( 'From %s', 'auxin-elements' ), $available_search_post_types[ $post_type ]);
        $result['noResultMessage'] = sprintf( esc_html__( "Nothing found in %s", 'auxin-elements' ), $available_search_post_types[ $post_type ]);
        $results[] = $result;

    }

    wp_send_json_success( $results );
}

add_action( 'wp_ajax_aux_modern_search_handler', 'auxels_ajax_search' );
add_action( 'wp_ajax_nopriv_aux_modern_search_handler', 'auxels_ajax_search');