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
define('DB_NAME', 'woohelps');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'uUxoZjrIH3');

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
define('AUTH_KEY',         'z?4k&v%vIBr4m]uM4Q}Jt);QR:<u:y#l83UBiZsA+8sLV,hjV[I[M|pf^d:~<&1f');
define('SECURE_AUTH_KEY',  '4yO%ZC.T}Wq05@iCS#YbWNO6U.l?dM30YbM c.M-/U3!{2GURK>SMp`0U8*-+2>i');
define('LOGGED_IN_KEY',    '/_I765k%avj_e%t]> YU8?!bN&tB4q&pa|i7u?AsS>?N%1GUm)ez=Kkgja}oP.bg');
define('NONCE_KEY',        'D:?C9^43iilKjtl(Y+KR%xeI//xz:%8!r}mO}EvvKS?xHB1M87!z_[-r2(`PCw`)');
define('AUTH_SALT',        '(e5S;0 G*/gM:)0x(1O>}v_yJX6. 1>h[&q]60,Ra9;GPc}YO9p&vq<yw6,r+[/l');
define('SECURE_AUTH_SALT', ')66$)w=eflc!zxLdg`!_LlKERP{lXAiF7hcrNi7s[_3GqJTR;|3B>tZ9AAt3#(Um');
define('LOGGED_IN_SALT',   'p]YvBzy#Fz(Eq&~+$a=WpMrh=v1ihq~$~&7$%Bog|Y]?V!grOu^kURoQiaWx)e@_');
define('NONCE_SALT',       '^Owq$vGmwIng3h&/*rONv<}w{qUxebbvl{@gneS3L~qG)<iWbM6>BP( 4GZV5t0n');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'woo_';

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
