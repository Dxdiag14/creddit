<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package enternews
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function enternews_woocommerce_setup()
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

add_action('after_setup_theme', 'enternews_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function enternews_woocommerce_scripts()
{


    $font_path = WC()->plugin_url() . '/assets/fonts/';
    $inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

    wp_add_inline_style('enternews-woocommerce-style', $inline_font);
}

add_action('wp_enqueue_scripts', 'enternews_woocommerce_scripts');

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function enternews_woocommerce_active_body_class($classes)
{
    $classes[] = 'woocommerce-active';

    return $classes;
}

add_filter('body_class', 'enternews_woocommerce_active_body_class');

function enternews_product_archive_title($title)
{
    if (is_shop() && $shop_id = wc_get_page_id('shop')) {
        $title = get_the_title($shop_id);
    }
    return $title;
}

add_filter('get_the_archive_title', 'enternews_product_archive_title');

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function enternews_woocommerce_products_per_page()
{
    return 12;
}

add_filter('loop_shop_per_page', 'enternews_woocommerce_products_per_page');

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function enternews_woocommerce_thumbnail_columns()
{
    return 4;
}

add_filter('woocommerce_product_thumbnails_columns', 'enternews_woocommerce_thumbnail_columns');

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function enternews_woocommerce_loop_columns()
{
    return 3;
}

add_filter('loop_shop_columns', 'enternews_woocommerce_loop_columns');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function enternews_woocommerce_related_products_args($args)
{
    $defaults = array(
        'posts_per_page' => 3,
        'columns' => 3,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}

add_filter('woocommerce_output_related_products_args', 'enternews_woocommerce_related_products_args');

if (!function_exists('enternews_woocommerce_product_columns_wrapper')) {
    /**
     * Product columns wrapper.
     *
     * @return  void
     */
    function enternews_woocommerce_product_columns_wrapper()
    {
        $columns = enternews_woocommerce_loop_columns();
        echo '<div class="columns-' . absint($columns) . '">';
    }
}
add_action('woocommerce_before_shop_loop', 'enternews_woocommerce_product_columns_wrapper', 40);

if (!function_exists('enternews_woocommerce_product_columns_wrapper_close')) {
    /**
     * Product columns wrapper close.
     *
     * @return  void
     */
    function enternews_woocommerce_product_columns_wrapper_close()
    {
        echo '</div>';
    }
}
add_action('woocommerce_after_shop_loop', 'enternews_woocommerce_product_columns_wrapper_close', 40);

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('wp_footer', 'woocommerce_demo_store', 10);

/**
 * Remove default WooCommerce breadcrumbs.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

if (!function_exists('enternews_woocommerce_wrapper_before')) {
    /**
     * Before Content.
     *
     * Wraps all WooCommerce content in wrappers which match the theme markup.
     *
     * @return void
     */
    function enternews_woocommerce_wrapper_before()
    {
        ?>
        <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
        <?php
    }
}
add_action('woocommerce_before_main_content', 'enternews_woocommerce_wrapper_before');

if (!function_exists('enternews_woocommerce_wrapper_after')) {
    /**
     * After Content.
     *
     * Closes the wrapping divs.
     *
     * @return void
     */
    function enternews_woocommerce_wrapper_after()
    {
        ?>
        </main><!-- #main -->
        </div><!-- #primary -->
        <?php
    }
}
add_action('woocommerce_after_main_content', 'enternews_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'enternews_woocommerce_header_cart' ) ) {
 * enternews_woocommerce_header_cart();
 * }
 * ?>
 */

if (!function_exists('enternews_woocommerce_cart_link_fragment')) {
    /**
     * Cart Fragments.
     *
     * Ensure cart contents update when products are added to the cart via AJAX.
     *
     * @param array $fragments Fragments to refresh via AJAX.
     * @return array Fragments to refresh via AJAX.
     */
    function enternews_woocommerce_cart_link_fragment($fragments)
    {
        ob_start();
        enternews_woocommerce_cart_icon();
        $fragments['.af-cart-icon-and-count'] = ob_get_clean();

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'enternews_woocommerce_cart_link_fragment');

if (!function_exists('enternews_woocommerce_cart_link')) {
    /**
     * Cart Link.
     *
     * Displayed a link to the cart including the number of items present and the cart total.
     *
     * @return void
     */
    function enternews_woocommerce_cart_link()
    {
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'enternews'); ?>">
            <?php
            $item_count_text = sprintf(
            /* translators: number of items in the mini cart. */
                _n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'enternews'),
                WC()->cart->get_cart_contents_count()
            );
            ?>
            <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span> <span
                    class="count"><?php echo esc_html($item_count_text); ?></span>
        </a>
        <?php
    }
}


if (!function_exists('enternews_woocommerce_cart_icon')) {
    /**
     * Cart Link.
     *
     * Displayed a link to the cart including the number of items present and the cart total.
     *
     * @return void
     */
    function enternews_woocommerce_cart_icon()
    {
        ?>
        <div class="af-cart-icon-and-count dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-shopping-cart"></i>
            <span class="item-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
        </div>
        <?php
    }
}

if (!function_exists('enternews_woocommerce_header_cart')) {
    /**
     * Display Header Cart.
     *
     * @return void
     */
    function enternews_woocommerce_header_cart()
    {
        if (is_cart()) {
            $class = 'current-menu-item';
        } else {
            $class = '';
        }
        ?>

        <div class="af-cart-wrap">
        <div class="af-cart-icon-and-count dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-shopping-cart"></i>
            <span class="item-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
        </div>
        <div class="top-cart-content primary-bgcolor dropdown-menu">
            <ul class="site-header-cart">

                <li>
                    <?php
                    $instance = array(
                        'title' => '',
                    );

                    the_widget('WC_Widget_Cart', $instance);
                    ?>
                </li>
            </ul>
        </div>
        </div>

        <?php
    }
}



function enternews_get_products($number_of_products, $category=0, $show, $orderby='date', $order="DESC" ){


    $product_visibility_term_ids = wc_get_product_visibility_term_ids();

    $query_args = array(
        'posts_per_page' => $number_of_products,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'no_found_rows'  => 1,
        'order'          => $order,
        'meta_query'     => array(),
        'tax_query'      => array(
            'relation' => 'AND',
        ),
    );

    if ( absint($category) > 0 ) {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_taxonomy_id',
            'terms'    => $category

        );

    }

    switch ( $show ) {
        case 'featured' :
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $product_visibility_term_ids['featured'],
            );
            break;
        case 'onsale' :
            $product_ids_on_sale    = wc_get_product_ids_on_sale();
            $product_ids_on_sale[]  = 0;
            $query_args['post__in'] = $product_ids_on_sale;
            break;

    }

    switch ( $orderby ) {
        case 'price' :
            $query_args['meta_key'] = '_price';
            $query_args['orderby']  = 'meta_value_num';
            break;
        case 'rand' :
            $query_args['orderby']  = 'rand';
            break;
        case 'sales' :
            $query_args['meta_key'] = 'total_sales';
            $query_args['orderby']  = 'meta_value_num';
            break;
        default :
            $query_args['orderby']  = 'date';
    }

    return new WP_Query( apply_filters( 'enternews_product_query_args', $query_args ) );
}
