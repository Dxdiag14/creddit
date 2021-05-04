<?php

// Configuration common to all environments
include_once __DIR__ . '/wp-config.common.php';

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'H:RqEfkL<7CsPEYde5^0gHfm61]+2*/EH% wXb)#:%h-.Jds9p^>ApDEi$?DB?mX' );
define( 'SECURE_AUTH_KEY',  'I)n~{Q%YW-8J/IuB~ +y5mM5kPz$T@ W1:oS9_;8!l~n7x=b:`R~@gs+~)2B1y~.' );
define( 'LOGGED_IN_KEY',    '|<xd5W(M@nkd> M[@J(B|SWTVo}erN#@ QO6{e`ypmb@Xg:BLgs|pH)aqX()kt(S' );
define( 'NONCE_KEY',        '[v*%=%m|#mqyE:W{j(0(LQ&La!5jc?xoE=.8NPHp%7<:K! 9u?|FhZ!OUY9=~02{' );
define( 'AUTH_SALT',        'y&xNPXq{ouhkeR.h$Cc%XFMr;3~Q>rgA2+p1:FL</6IJ[`R(]q>c{8YTI::mnvA#' );
define( 'SECURE_AUTH_SALT', '/@pA[L<6E1cRcC8zM%ax$VN%>`_X%VT~$9/)k>Cl^.6s7kh|4z#p#iRxW15SJl_!' );
define( 'LOGGED_IN_SALT',   'x(nnZ7_Ww`l!s01FRMun B7fJj3mmX>`tQ(eacP[WD_VVwA>IfC8HRPi%RzN48Sr' );
define( 'NONCE_SALT',       ')la,~64.nLlh,UPoM]4D}c&IYK#^J9cZr^b,nifhw}q(sepoEk4>vMn,-Gc5aand' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

define('VP_ENVIRONMENT', 'default');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
