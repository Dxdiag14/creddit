<?php
/**
 * Defining Constants.
 *
 * 
 * @package    Auxin
 * @author     averta (c) 2014-2021
 * @link       http://averta.net
 */

/*-----------------------------------------------------------------------------------*/
/*  Define Global Vars
/*-----------------------------------------------------------------------------------*/

if( ! defined('THEME_NAME_I18N') ) define( 'THEME_NAME_I18N' , esc_attr__( 'Phlox', 'phlox' ) );
if( ! defined('THEME_PRO' )      ) define( 'THEME_PRO'       ,  false   );
if( ! defined('THEME_PRO_NAME_I18N') ) define( 'THEME_PRO_NAME_I18N' , esc_attr__( 'Phlox Pro', 'phlox' ) );


// dummy gettext call to translate theme name
__( 'PHLOX', 'phlox' );

/*-----------------------------------------------------------------------------------*/
