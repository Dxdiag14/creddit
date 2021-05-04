<?php

/**
 * Enqueue smush lazy load script in header
 */
function auxin_enqueue_smush_lazy_load_in_header() {
    if ( defined( 'WP_SMUSH_PREFIX' ) && empty( get_option( WP_SMUSH_PREFIX . 'lazy_load', '' ) ) ) {
        $default_lazy_load_config = maybe_unserialize( 'a:8:{s:6:"format";a:5:{s:4:"jpeg";b:1;s:3:"png";b:1;s:3:"gif";b:1;s:3:"svg";b:1;s:6:"iframe";b:1;}s:6:"output";a:4:{s:7:"content";b:1;s:7:"widgets";b:1;s:10:"thumbnails";b:1;s:9:"gravatars";b:1;}s:9:"animation";a:4:{s:8:"selected";s:6:"fadein";s:6:"fadein";a:2:{s:8:"duration";i:400;s:5:"delay";i:0;}s:7:"spinner";a:2:{s:8:"selected";i:1;s:6:"custom";a:0:{}}s:11:"placeholder";a:3:{s:8:"selected";i:1;s:6:"custom";a:0:{}s:5:"color";s:7:"#F3F3F3";}}s:7:"include";a:7:{s:9:"frontpage";b:1;s:4:"home";b:1;s:4:"page";b:1;s:6:"single";b:1;s:7:"archive";b:1;s:8:"category";b:1;s:3:"tag";b:1;}s:13:"exclude-pages";a:0:{}s:15:"exclude-classes";a:0:{}s:6:"footer";b:0;s:6:"native";b:0;}' );
        update_option( WP_SMUSH_PREFIX . 'lazy_load', $default_lazy_load_config );
        update_option( 'skip-smush-setup', true );
    }
}
add_action( 'admin_init', 'auxin_enqueue_smush_lazy_load_in_header' );

?>