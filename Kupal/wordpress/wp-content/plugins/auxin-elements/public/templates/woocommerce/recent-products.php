<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

    $cat_count = get_the_terms( $post->ID, 'product_cat' );
	$cat_count = is_array( $cat_count ) ? count( $cat_count ) : 0;

    $tag_count = get_the_terms( $post->ID, 'product_tag' );
	$tag_count = is_array( $tag_count ) ? count( $tag_count ) : 0;
?>

<div <?php post_class(); ?>>
	<?php

	woocommerce_template_loop_product_link_open();

	if ( auxin_is_true( $display_sale_badge ) ) {
		woocommerce_show_product_loop_sale_flash();
	}

	if ( auxin_is_true( $show_media ) ) {
		echo $the_media;
	}

	if ( auxin_is_true( $display_title ) ) {
		woocommerce_template_loop_product_title();
	}

	?>
	<div class="loop-meta-wrapper">
	    <div class="product_meta">
			<?php
			$rating_count = $product->get_rating_count();
			$average      = $product->get_average_rating();
			
			if ( $rating_count > 0 ) {
				echo wc_get_rating_html( $average, $rating_count );
			}

	        if ( auxin_is_true( $display_categories ) && $cat_count > 0 ) {
	            echo wc_get_product_category_list( $product->get_id(), ', ', '<em class="auxshp-meta-terms">', '</em>' );
	        } ?>
	    </div>
	</div>
	<?php
	if ( auxin_is_true( $display_price ) ) {
		woocommerce_template_loop_price();
	}

	 woocommerce_template_loop_product_link_close();

	if ( auxin_is_true( $display_button ) ) {
		woocommerce_template_loop_add_to_cart();
	}

	?>
</div>
