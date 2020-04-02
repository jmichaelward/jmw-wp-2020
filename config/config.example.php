<?php
// Setup autoloader and other configuration values.
$loadables = array_filter(
	[
		dirname( __FILE__, 2 ) . '/public/vendor/autoload.php',
		__DIR__ . '/local-config.php',
	],
	function ( $file_path ) {
		return is_readable( $file_path );
	}
);

foreach ( $loadables as $loadable ) {
	require_once $loadable;
}

try {
	$dotenv = new Dotenv();
	$dotenv->load( __DIR__ . '/.env' );
} catch ( Throwable $e ) {
	error_log( $e->getMessage() ); // @codingStandardsIgnoreLine
}

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'database_name_here' );

/** MySQL database username */
define( 'DB_USER', 'username_here' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password_here' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service} You can change these at any
 * point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', 'put your unique phrase here' );
define( 'SECURE_AUTH_KEY', 'put your unique phrase here' );
define( 'LOGGED_IN_KEY', 'put your unique phrase here' );
define( 'NONCE_KEY', 'put your unique phrase here' );
define( 'AUTH_SALT', 'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT', 'put your unique phrase here' );
define( 'NONCE_SALT', 'put your unique phrase here' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * Site-specific customizations.
 */
$url = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? getenv( 'SERVER_HOST' );

// Define the home and site URLs.
$protocol = ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) ) ? 'https' : 'http';
define( 'HOME_URL', "{$protocol}://{$url}" );
define( 'WP_SITEURL', "{$protocol}://{$url}/wp/" );

// Set the location of the wp-content directory.
define( 'WP_CONTENT_DIR', dirname( __FILE__, 2 ) . '/public/wp-content' );

if ( ! defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', "{$protocol}://{$url}/wp-content" );
}
