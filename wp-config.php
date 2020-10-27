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
define('DB_NAME', "lida");

/** MySQL database username */
define('DB_USER', "root");

/** MySQL database password */
define('DB_PASSWORD', "Abril1221");

/** MySQL hostname */
define('DB_HOST', "localhost");

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
define('AUTH_KEY',         'stuaxfqpumihghpj15nchvd1zlhryvjm5cikjxkryr1qceq4wemntp1feuw4t0tq');
define('SECURE_AUTH_KEY',  'kyd41wl5pwgxde3qgzpqz6uuhidioe9dh0brcsigqldojghknxmtwcgj51dnvjfx');
define('LOGGED_IN_KEY',    'qzfigknq3mcgh6was0i3hmc2glw4xay2ps6dmkpm3nzw8zlfxylq9app02qd79m5');
define('NONCE_KEY',        'nbgyhejtsn67jrhtkv2osofd8b2appf38ahhxdsapqdh9p6sjxpwbx1acxwtqw7p');
define('AUTH_SALT',        'pa57dontkd523dh645lqctqt8f28riyuuwciybhklzlnhhnhxrl0tue4ieuzfdzc');
define('SECURE_AUTH_SALT', 'vvf3hvhsvast9o7bu5uazqizllfdpyw4bodl6wxc57rclh7jysk1ary0cppdihkf');
define('LOGGED_IN_SALT',   'srrcczktvm8jqzgaz2qkqcwyvuw7f9bk8tejywy8yca9ecyrwuas6b6flxeqxahf');
define('NONCE_SALT',       'y4gtgbw4y4kbsommiakb2cis0vawzlrdxvosiockngzu1ox3mduezgec88polrtp');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpmu_';

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
