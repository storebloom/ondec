<?php
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'odwp2016');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '~RnY3[fjlP:a(0nxjAPvg}2kRD52E9r.Ngi<+poX6s%<`sm1KBaoQ-pGOH 45JW|');
define('SECURE_AUTH_KEY',  'bQ}FlW$l)h36(N~J{U^-TMKDLmGxW,y833jIv3ESIz:bJ8pxW;fB!?Nc,&ZC]<(/');
define('LOGGED_IN_KEY',    ';O!g<ygT X ad@gJrQW6XQ_lUGQc@aX=piHy|baYe$c?X75CVvU[,WexO7]iSH 4');
define('NONCE_KEY',        'TJ/`wZMUx4Ia].wdUid.wd@@xy-=rRkLEZ(~YPelK07GLQ`|T:OaoO0X~!SqC7Ln');
define('AUTH_SALT',        'Mrv5>0JuJ[UwJiUMd+F )f{YU |v N.udb9Tcq:1|28W<t4=/WPEkY7`w+oD-pcm');
define('SECURE_AUTH_SALT', '{A0ymY.zISkb[gTC I[3pAE9P.#fusiHOFc6:utd-TZuV-EOtPqIN8$^h3?=L*|1');
define('LOGGED_IN_SALT',   ':`We;Slp8DY+Pvd;mllX?W{%fav%r+%0y=WA9CP$yE{InOw 12PgY[ z`b%KXtt&');
define('NONCE_SALT',       'i46@2]KF3rD]ICrbB)a(,S!NjmGiyuG6snDeBh,p/2dBn4122:<B>Z7+`^udL$++');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'od_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
