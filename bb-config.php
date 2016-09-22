<?php
/** 
 * The base configurations of bbPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys and bbPress Language. You can get the MySQL settings from your
 * web host.
 *
 * This file is used by the installer during installation.
 *
 * @package bbPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for bbPress */
define( 'BBDB_NAME', 'woohelps' );

/** MySQL database username */
define( 'BBDB_USER', 'root' );

/** MySQL database password */
define( 'BBDB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'BBDB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'BBDB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'BBDB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/bbpress/ WordPress.org secret-key service}
 *
 * @since 1.0
 */
define( 'BB_AUTH_KEY', 'z?4k&v%vIBr4m]uM4Q}Jt);QR:<u:y#l83UBiZsA+8sLV,hjV[I[M|pf^d:~<&1f' );
define( 'BB_SECURE_AUTH_KEY', '4yO%ZC.T}Wq05@iCS#YbWNO6U.l?dM30YbM c.M-/U3!{2GURK>SMp`0U8*-+2>i' );
define( 'BB_LOGGED_IN_KEY', '/_I765k%avj_e%t]> YU8?!bN&tB4q&pa|i7u?AsS>?N%1GUm)ez=Kkgja}oP.bg' );
define( 'BB_NONCE_KEY', 'D:?C9^43iilKjtl(Y+KR%xeI//xz:%8!r}mO}EvvKS?xHB1M87!z_[-r2(`PCw`)' );
/**#@-*/

/**
 * bbPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$bb_table_prefix = 'woo_bb_';

/**
 * bbPress Localized Language, defaults to English.
 *
 * Change this to localize bbPress. A corresponding MO file for the chosen
 * language must be installed to a directory called "my-languages" in the root
 * directory of bbPress. For example, install de.mo to "my-languages" and set
 * BB_LANG to 'de' to enable German language support.
 */
define( 'BB_LANG', 'zh_CN' );
$bb->custom_user_table = 'woo_users';
$bb->custom_user_meta_table = 'woo_usermeta';

$bb->uri = 'http://woohelps.dev/wp-content/plugins/buddypress//bp-forums/bbpress/';
$bb->name = 'Woohelps 论坛';

define('BB_AUTH_SALT', '(e5S;0 G*/gM:)0x(1O>}v_yJX6. 1>h[&q]60,Ra9;GPc}YO9p&vq<yw6,r+[/l');
define('BB_LOGGED_IN_SALT', 'p]YvBzy#Fz(Eq&~+$a=WpMrh=v1ihq~$~&7$%Bog|Y]?V!grOu^kURoQiaWx)e@_');
define('BB_SECURE_AUTH_SALT', ')66$)w=eflc!zxLdg`!_LlKERP{lXAiF7hcrNi7s[_3GqJTR;|3B>tZ9AAt3#(Um');

define('WP_AUTH_COOKIE_VERSION', 2);

?>