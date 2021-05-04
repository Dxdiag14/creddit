<?php

//
// ─── DISABLE AUTOMATIC REQUIRED PAGE CREATION ───────────────────────────────────
//

add_filter( 'woocommerce_create_pages', 'auxin_disable_automatic_page_creation', 1, 1 );
function auxin_disable_automatic_page_creation( $pages ) {
    if ( ! empty( $_GET['action'] ) && $_GET['action'] == 'install_pages' ) {
        return $pages;
    }
    return[];
}

?>