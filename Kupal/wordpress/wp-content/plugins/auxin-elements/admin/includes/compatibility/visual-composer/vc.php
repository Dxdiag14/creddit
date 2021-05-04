<?php 
/**
 * Visual Composer related functions and hooks
 *
 */

// Add a separator between "Edit" and "Edit With Visual Composer" links in frontend.
function auxin_vc_edit_post_link( $link ) {
    if ( class_exists('Vc_Frontend_Editor') ) {
        $link .= '<i> | </i>'   ;
    }
    return $link;
}
add_filter( 'edit_post_link', 'auxin_vc_edit_post_link' );