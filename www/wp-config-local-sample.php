<?php
/**
 * wp-config-local.php
 *
 * The local configuration file for a WordPress install.
 *
 * DO NOT COMMIT wp-config-local.php TO SOURCE CONTROL
 *
 * This file MUST define the following constants:has the following configurations: MySQL settings, Table Prefix,
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
 * This file may also define any other constants or global variables needed
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */
define( 'DB_NAME', 'database_name_here' );

/** MySQL database username */
define( 'DB_USER', 'username_here' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password_here' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

