<?php

/**
 * Hide yoast seo admin notice
 */
function auxin_hide_yoast_seo_admin_notice() {
    if ( class_exists( 'WPSEO_Options' ) ) {
        WPSEO_Options::set( 'ignore_indexation_warning', true );
    }
}
add_action( 'admin_init', 'auxin_hide_yoast_seo_admin_notice' );

?>