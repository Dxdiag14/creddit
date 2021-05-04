<?php


// commeon functions
include_once( 'general-functions.php' );
include_once( 'general-hooks.php' );

// load shortcode files
include_once( 'general-shortcodes.php' );

Auxin_WhiteLabel::get_instance();
Auxin_Widget_Shortcode_Map::get_instance();
Auxels_WC_Attribute_Nav_Menu::get_instance();

Auxin_Import::get_instance();
if ( wp_doing_ajax() ) {
    Auxels_Envato_Elements::get_instance();
}

// load elements
include_once( 'elements/about-widget.php' );
include_once( 'elements/recent-posts-widget.php' );
include_once( 'elements/popular-posts-widget.php' );
include_once( 'elements/recent-posts-grid-carousel.php' );
include_once( 'elements/recent-posts-timeline.php' );
include_once( 'elements/recent-posts-masonry.php' );
include_once( 'elements/recent-posts-land-style.php' );
include_once( 'elements/general-element-fields.php' );
// if siteorigin bundle plugin is active
if( auxin_is_plugin_active( 'so-widgets-bundle/so-widgets-bundle.php' ) ||
    auxin_is_plugin_active( 'elementor/elementor.php' ) ) {
	include_once( 'elements/accordion.php' );
	include_once( 'elements/tabs.php' );
}

include_once( 'elements/testimonial.php' );
include_once( 'elements/staff.php' );
include_once( 'elements/text.php' );
include_once( 'elements/recent-posts-tiles.php' );
include_once( 'elements/recent-posts-tiles-carousel.php' );
include_once( 'elements/attachment-url.php' );
include_once( 'elements/audio.php' );
include_once( 'elements/button.php' );
include_once( 'elements/code.php' );
include_once( 'elements/contact-form.php' );
include_once( 'elements/divider.php' );
include_once( 'elements/dropcap.php' );
include_once( 'elements/gallery.php' );
include_once( 'elements/gmap.php' ); // check
include_once( 'elements/highlight.php' );
include_once( 'elements/image.php' );
include_once( 'elements/touch-slider.php' );
include_once( 'elements/related-posts.php' );
include_once( 'elements/before-after.php' );
include_once( 'elements/accordion-widget.php' );
include_once( 'elements/tab-widget.php' );
include_once( 'elements/custom-list.php' );

// check if instagram-feed is activated then it adds its element to page builder and widget
if( auxin_is_plugin_active( 'instagram-feed/instagram-feed.php' ) ) {
    include_once( 'elements/instagram-feed.php' );
}
// check if flickr-justified-gallery is activated then it adds its element to page builder and widget
if( auxin_is_plugin_active( 'flickr-justified-gallery/flickr-justified-gallery.php' ) ) {
    include_once( 'elements/flickr.php' );
}
// check if flickr-justified-gallery is activated then it adds its element to page builder and widget
if( auxin_is_plugin_active( 'custom-facebook-feed/custom-facebook-feed.php' ) ) {
    include_once( 'elements/facebook.php' );
}

// check if woocommerce is activated then it adds its element to page builder and widget
if( auxin_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    include_once( 'elements/recent-products.php' );
}

include_once( 'elements/latest-posts-slider.php' );
include_once( 'elements/quote.php' );
include_once( 'elements/search.php' );
include_once( 'elements/socials-list.php' );
include_once( 'elements/video.php' );
include_once( 'elements/contact-box.php' );

include_once( 'elementor/class-auxin-elementor-core-elements.php' );


