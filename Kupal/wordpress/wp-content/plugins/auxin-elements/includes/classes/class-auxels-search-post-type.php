<?php
/**
 * Search Post Type Class.
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
 * Class to handle searching post types
 */
class Auxels_Search_Post_Type {
    
    /**
     * Search Phrase
     *
     * @var      string
     */
    public $s;
    
    /**
     * Search category
     *
     * @var      string
     */
    public $cat;

    /**
     * Search Post Type
     *
     * @var      string
     */
    public $post_type;
    
    /**
     * Number of serach Items per page
     *
     * @var      string
     */
    public $per_page;

    /**
     * Search query args
     *
     * @var      array
     */
    public $query_args = array();

    public $search_item_wrapper_start = "<div class='aux-search-item %s'>";
    public $search_item_container_start = "<a href='%s' class='aux-item-container'>";
    public $search_item_description_start = "<span class='aux-item-desc'>";
    public $search_item_title_start = "<span class='aux-item-title'>";
    public $search_item_details_start = "<span class='aux-item-details'>";

    public $search_item_details_end = "</span>";
    public $search_item_title_end = "</span>";
    public $search_item_description_end = "</span>";
    public $search_item_container_end = "</a>";
    public $search_item_wrapper_end = "</div>";

    /**
     * __construct
     */
    function __construct( $search_phrase = '', $search_category = '', $search_post_type = '', $number_per_page = '' ) {

        $this->s = $search_phrase;
        $this->cat = $search_category;
        $this->post_type = $search_post_type;
        $this->per_page = $number_per_page;

        $query_args = array (
            's'                 => $this->s,
            'post_type'         => $this->post_type,
            'no_found_rows'     => 1,
            'posts_per_page'    => $this->per_page
        );

        // Get category slug for each post type
    
        $categories_slug = auxin_general_post_types_category_slug();
        if ( !empty( $this->cat ) ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy'          => $categories_slug[$this->post_type],
                    'field'             => 'slug',
                    'terms'             => array( $this->cat )
                )
            ); 
        }       
        $this->set_query_args($query_args);        
    }
    
    /**
     * Change Query Args - append or modify query args parameter
     *
     * @return void
     */
    public function set_query_args ($args = array()) {
        if ( !empty( $args ) ) {
            foreach ( $args as $key => $value ) {
                $this->query_args[$key] = $value;
            }
        }
    }

    /**
     * Search Products Post type
     *
     * @return string
     */
    public function search_products() {
        
        if ( !class_exists( 'WooCommerce' ) )
            return;
        
        $product_args = array( 'post_type' => 'product' );
        $product_variation_args = array( 'post_type' => 'product_variation' );

        // search all products 
        $search_products_for_name = $this->search();

        // search all product variations
        $this->set_query_args($product_variation_args);
        $search_variations_for_name = $this->search();
        
        $this->set_query_args($product_args);
        // Search all product for sku meta fields
        $sku_args = array(
            's'                => '',
            'meta_query'       => array(
                array(
                    'key'     => '_sku',
                    'value'   => $this->s,
                    'compare' => 'like',
                ),
            ),
            'suppress_filters' => 0,
        );
        
        $this->set_query_args( $sku_args );
        $search_products_for_sku = $this->search();

        // Search All Variations for sku meta field
        $this->set_query_args($product_variation_args);
        $search_variations_for_sku = $this->search();
        
        //set search phrase query argument after searching for sku without search phrase 
        $this->set_query_args( array( 's' => $this->s ) );

        // Merge All search results and remove the same results
        $all_search_results = array_merge(
            $search_products_for_sku->posts, 
            $search_variations_for_sku->posts,
            $search_products_for_name->posts,
            $search_variations_for_name->posts
        );
        
        $products_id = array();
        foreach ($all_search_results as $key => $product) {
            $id = $product->ID;
            if ( in_array( $id, $products_id) ) {
                unset( $all_search_results[$key] );
            } else {
                $products_id[] = $id;
            }
        }

        // Generate output dom
        if ( !empty( $all_search_results ) ) {
            global $post;
            foreach ( $all_search_results as $post ) {
                setup_postdata( $post );
                $product = wc_get_product( get_the_ID() );
                $categories = get_the_terms( $product->get_id(), 'product_cat' );
                $cat = $categories[0]->name;
                $results[] = sprintf(
                    $this->search_item_wrapper_start.
                        $this->search_item_container_start.
                            '%s'.
                            $this->search_item_description_start.
                                $this->search_item_title_start.
                                    '%s'.
                                $this->search_item_title_end.
                                $this->search_item_details_start.
                                    '%s'.
                                $this->search_item_details_end.
                                '%s'.
                            $this->search_item_description_end.
                        $this->search_item_container_end.
                    $this->search_item_wrapper_end,
                    'product',
                    get_permalink(),
                    ( ( has_post_thumbnail() ) ? woocommerce_get_product_thumbnail( 'shop_thumbnail' ) : '' ),
                    $product->get_title(),
                    $cat, 
                    "<span class='aux-price'>".$product->get_price_html()."</span>"
                );
            }
            unset($this->query_args['meta_query']);
            unset($this->query_args['suppress_filters']);
            wp_reset_postdata();
            
            return $this->search_results( 'product', $results);

        } else {
            
            unset($this->query_args['meta_query']);
            unset($this->query_args['suppress_filters']);

            return $this->search_results( 'product' );
        }
    }

    /**
     * Search General Post types
     *
     * @return string
     */
    public function search_general_post_types() {
        $search_result = $this->search();
        if ($search_result->have_posts()) {
            global $post;
            while($search_result->have_posts()) {
                $search_result->the_post();
                $results[] = sprintf(
                    $this->search_item_wrapper_start.
                        $this->search_item_container_start.
                            '<img src="%s">'.
                            $this->search_item_description_start.
                                $this->search_item_title_start.
                                    '%s'.
                                $this->search_item_title_end.
                                $this->search_item_details_start.                                    
                                '%s'.
                                $this->search_item_details_end.
                            $this->search_item_description_end.
                        $this->search_item_container_end.
                    $this->search_item_wrapper_end,
                    'blog',
                    get_permalink(),
                    get_the_post_thumbnail_url(),
                    get_the_title(),
                    get_the_date()
                );
            }
            wp_reset_postdata();
            return $this->search_results( $this->query_args['post_type'], $results );
        } else {
            return $this->search_results( $this->query_args['post_type'] );
        }
    }
    
    /**
     * Search Portfolio Post type
     *
     * @return string
     */
    public function search_portfolio() {
        $search_result = $this->search();

        if ( $search_result->have_posts() ) {
            global $post;
            while ( $search_result->have_posts() ) {
                $search_result->the_post();
                $results[] = sprintf(
                    $this->search_item_wrapper_start.
                        $this->search_item_container_start.
                            '<img src="%s">'.
                        $this->search_item_container_end.
                    $this->search_item_wrapper_end,
                    'portfolio',
                    get_permalink(),
                    get_the_post_thumbnail_url()
                );
            }
            wp_reset_postdata();

            return $this->search_results( 'portfloio', $results );
        } else {
            return $this->search_results( 'portfolio' );
        }
    }

    /**
     * Main Query Method
     */
    public function search() {
        $search_result = new WP_Query( $this->query_args );
        return $search_result;
    }

    /**
     * Return Search results as array
     * 
     * @param   string $post_type
     * @param   array $results
     * 
     * @return  array
     */
    public function search_results( $post_type = 'post', $results = array() ) {
        return array(
            'postType' => $post_type, 
            'results' => $results, 
            'searchLink' => add_query_arg(
                array(
                    's' => $this->s,
                    'post_type' => $post_type
                ),
                get_site_url()
            )
        );
    }

}
