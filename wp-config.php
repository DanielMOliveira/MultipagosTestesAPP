<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'bd__multicartoes30');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'aq1sw2');

/** nome do host do MySQL */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'OZ94.DZ92_P+&sZY$dD@[D<n-waBmw#f_aDz_sO!3Tn$s-^^Ok67B<5oGp||sm-<');
define('SECURE_AUTH_KEY',  'i7_XP@&z8sFg++kk(b6u<hr+gk|l)Hd1yQI.;nK sq`a}5u2AQPUJ-C:QA(l~%cm');
define('LOGGED_IN_KEY',    'w16{so-9s-FVl|&+?7seS;|VLvC-?2h1/#u:4*%V{lmsYnRk[+$K2yLd$-zP5J5b');
define('NONCE_KEY',        'Y|HK.Dhp9M%3Mv AvW/DrRx((H]^im;;& +^d +I}dRt}K|-flE-[|acqGCt}{-|');
define('AUTH_SALT',        'vEMcS>RK#kh<`!t295^iKLCtTKy2!x0kg$`9/NtT!:TZ+,NTS8LeZ{:m$uF@CJ@f');
define('SECURE_AUTH_SALT', 'eNc!X#bw}^]jm:@MzLReXD(AulxUcnn}<EBimku63J/ZoD6J]13#Z~CpvW|-(c8o');
define('LOGGED_IN_SALT',   'R/`@8a5|O?%V;&o3#=c&*;!pNtk9q<`C;5A*a7@vU@~FC(7hqvv@$8rV{`A|tuRN');
define('NONCE_SALT',       '|w_01RyM<MCaDbxP0eDoFT: D7h~hZ}li~?z.8/>s.g+?)c%$#bBKP~~!H3d_bM*');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'sug_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'pt_BR');
//define('WP_LANG_DIR', $_SERVER['DOCUMENT_ROOT'].'wordpress/languages');

define( 'WP_MEMORY_LIMIT', '96M' );
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
