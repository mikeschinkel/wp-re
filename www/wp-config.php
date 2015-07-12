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

/**
 * Can the local configuration file
 * This file can define anything constants or public variables needed
 * but this file MUST define the following constants:
 *
 *    DB_NAME
 *    DB_USER
 *    DB_PASSWORD
 *
 *    @see https://api.wordpress.org/secret-key/1.1/salt/
 *    AUTH_KEY
 *    SECURE_AUTH_KEY
 *    LOGGED_IN_KEY
 *    NONCE_KEY
 *    AUTH_SALT
 *    SECURE_AUTH_SALT
 *    LOGGED_IN_SALT
 *    NONCE_SALT
 *
 */
require __DIR__ . '/wp-config-local.php';

/** MySQL hostname */
if ( ! defined( 'DB_HOST' ) ) {

	define( 'DB_HOST', 'localhost' );

}

/** Database Charset to use in creating database tables. */
if ( ! defined( 'DB_CHARSET' ) ) {

	define( 'DB_CHARSET', 'utf8' );

}

/** The Database Collate type. Don't change this if in doubt. */
if ( ! defined( 'DB_COLLATE' ) ) {

	define( 'DB_COLLATE', '' );

}

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
if ( ! isset( $table_prefix ) ) {

	$table_prefix  = 'wp_';

}

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
if ( ! defined( 'WP_DEBUG' ) ) {

	define( 'WP_DEBUG', false );

}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');



