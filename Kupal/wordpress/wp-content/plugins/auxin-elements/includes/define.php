<?php

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

// theme name
if( ! defined( 'THEME_NAME' ) ){
    $theme_data = wp_get_theme();
    define( 'THEME_NAME', $theme_data->Name );
}


define( 'AUXELS_VERSION'        , '2.7.12' );

define( 'AUXELS_SLUG'           , 'auxin-elements' );

define( 'AUXELS_PURCHASE_KEY'   , 'envato_purchase_code_3909293' );


define( 'AUXELS_DIR'            , dirname( plugin_dir_path( __FILE__ ) ) );
define( 'AUXELS_URL'            , plugins_url( '', plugin_dir_path( __FILE__ ) ) );
define( 'AUXELS_BASE_NAME'      , plugin_basename( AUXELS_DIR ) . '/auxin-elements.php' ); // auxin-elements/auxin-elements.php


define( 'AUXELS_ADMIN_DIR'      , AUXELS_DIR . '/admin' );
define( 'AUXELS_ADMIN_URL'      , AUXELS_URL . '/admin' );

define( 'AUXELS_INC_DIR'        , AUXELS_DIR . '/includes' );
define( 'AUXELS_INC_URL'        , AUXELS_URL . '/includes' );

define( 'AUXELS_PUB_DIR'        , AUXELS_DIR . '/public' );
define( 'AUXELS_PUB_URL'        , AUXELS_URL . '/public' );
