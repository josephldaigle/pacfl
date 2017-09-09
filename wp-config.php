<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/kcrusepac/public_html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'jd_test');

/** MySQL database username */
define('DB_USER', 'josephldaigle');

/** MySQL database password */
define('DB_PASSWORD', 'P@ssw0rd1!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'oetDh05RjNBded6OQNez2oH5h6t06FgRbLtDLr7I5bFqTY8OQJznJmyOclufw1by');
define('SECURE_AUTH_KEY',  'JfM6bazpS2z4he2tLFjhh4sE64No2zA3nJ8a61i8a007wDApUA98RRQHBsUTlEBF');
define('LOGGED_IN_KEY',    'JEjpqwd8wkaHLi66ikG8nuRndIC0fQjBw7lbo4KVkBU1mb9Cjwcf8cDfrPjRZgKk');
define('NONCE_KEY',        '8W7j6k61jGYQaO22BVyRyK55S3JXuSw1ljas7xB06l5oXLJ6ZBnNtlCZ9QemGSPu');
define('AUTH_SALT',        'RHlS87KbGjn6hKpQBANUK9Yhogfyzk080MpsSbecfHiSXkqLOEZBCE9Wxg4qfHT7');
define('SECURE_AUTH_SALT', 'nFcFgjkrdRHwikCtV5bTFaxbtVIGZeX8s9G040m50rwXb9puxgs0fpkoBamhiiQX');
define('LOGGED_IN_SALT',   '5ij01xPE9NTKQNVES3jeln7jKD46gJxwDiRvVYtzX4aItnS7c3zjz8nPc17oxV8C');
define('NONCE_SALT',       'ls02pkQ3nA4GyItKuha49Ytg0pYTBeMIa5w8uk2jOnQbNURhOEkllQWu00IkgOON');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp2_';

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
