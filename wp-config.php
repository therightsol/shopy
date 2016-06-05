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
define('WP_MEMORY_LIMIT', '512M');
define('DB_NAME', 'shopy');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '1oyzb+@uF],70IB=Xl7L{i^2Y:!S`)!FdR-+#dkK6o[`&PjzXv,SGPiyqQ8>*FMU');
define('SECURE_AUTH_KEY',  'b!nug,S$(q5&4G<MTae@5|nAVak-yLP5?A}/t(ixCc<Se~mRR=|4>X|+Kgkav%0{');
define('LOGGED_IN_KEY',    'n#S,~1f7$`(uUmu?:+W05)}XHWNpiyavwdk9Tv=Oob2q5/0AC+p{$6+hU~{y}*{@');
define('NONCE_KEY',        'L(Tc##D1?,vu5iomBc8HiaVk3B-^@>^bw70 4ZE3^a>,av]cM`.@q#:HEfgP;5[A');
define('AUTH_SALT',        'zV(2b1E/s0T)UY6SdLM?7}Q(H`X(?%qQqc.6s+}{!{TvTx<N^U6<-f:UuT2jV}`+');
define('SECURE_AUTH_SALT', '.fzc.k:4Z^NM/?_1e,tzk9u`/vf nLWL[aqh@D+WU6). _xa8S)s,T$l4)g*ttfy');
define('LOGGED_IN_SALT',   '-#;?<`G@-sB?F$4$]/2:7Xeelcf[j[0+nq(eON>+^ED-a4ij@`N-rd6.#6}RBD-z');
define('NONCE_SALT',       ']AqFs,r#@aJM;[r ^!>g+}+[~`y8D8AqT3&6ZWJ_~12{]pD^3Q-.vpG(]jgGSKTW');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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