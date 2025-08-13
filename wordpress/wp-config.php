<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'natalie_mota' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'David3003' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'r`6SK{^mEQ0GMpYe+HhC?Gc47k?}QOZZb7J[1~=kl&3D_#_o>m R%O>9~EOA8NIw' );
define( 'SECURE_AUTH_KEY',  'posCFTxbEs[-O%}mMyb4(h1i8+&+JGJl*L]TDmd~^xU:4NR3xdR}[P!*_fs0{4oY' );
define( 'LOGGED_IN_KEY',    'lET!j&NR?EpQmuF;E[?Ig/AXLXM$>G_2*gC=J{MRLS(=Yymc|>; DdP;Zy[A1{*I' );
define( 'NONCE_KEY',        'HX@@#F8 %w4/$mN6jZb4Ix0rvX,YMCOLzk*rMvQ~CdITIq>-`]Wz-4F#(%^01(< ' );
define( 'AUTH_SALT',        'vdVSN4UvA!.$Ke6s&}g3W=O$[]Fl&:OGLM5&GvVCGKqcnUTj7iQ`[u.}]wkXX*EH' );
define( 'SECURE_AUTH_SALT', 'T30H>StPPdMso_($}ZJe4j0[DeJb<1Z9#gzEk$?^oUUfVP_L<7[Rg[& =Uc4UF3E' );
define( 'LOGGED_IN_SALT',   '0Gl.cm/3kNbT/G-1l;,9>nTH@w.;58^xF_%$tET/q?x$Pbqjive;:3`ZS+iw*6&&' );
define( 'NONCE_SALT',       'hYKOr5L>O#|JR+6_zew)Bxlx^=p&6AI9?~kz8z#cx8]x1r<U}C7w^4XsBel%GI.j' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true);
define ('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
