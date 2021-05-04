<?php 

function auxin_uvca_font_icons() {

	$uvca_icons = get_option( 'smile_fonts', array() );

	$phlox_icons[ THEME_ID . '-icons' ] = array( 
		'include' => 'smile_fonts/' . THEME_ID,
		'folder'  => 'smile_fonts/' . THEME_ID,
		'style'   => THEME_ID . '/auxin-front.css',
		'config'  => 'charmap.php'
	);

	$uvca_icons = $phlox_icons + $uvca_icons;

	update_option( 'smile_fonts', $uvca_icons );

	$upload_dir = wp_upload_dir();

	global $wp_filesystem;

	$path = trailingslashit( $upload_dir['basedir'] ) . 'smile_fonts/' . THEME_ID . '/';

	$charmap_file = trailingslashit( $path ) . 'charmap.php';
	$css_file = trailingslashit( $path ) . 'auxin-front.css';

    $charmap     = file_get_contents( AUXELS_ADMIN_DIR . '/includes/compatibility/uvca/auxicon/charmap.txt' );
    $css     = file_get_contents( AUXELS_ADMIN_DIR . '/includes/compatibility/uvca/auxicon/auxin-front.css' );

   	auxin_put_contents( $charmap, $charmap_file );
   	auxin_put_contents( $css, $css_file );


}

add_action( 'auxels_activated', 'auxin_uvca_font_icons' );

