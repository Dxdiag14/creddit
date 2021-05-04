<?php
	$message = __( 'Apologies, we are busy updating our website.', 'auxin-elements' ) ;
	$title   = __( 'Maintenance Mode Is Enable', 'auxin-elements' ) ;
	wp_die( $message, $title );
