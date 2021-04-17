<?php
/**
 * Load all of the mu-plugins.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$plugins = array_filter(
	[
		'jmw-wp-requirements/jmw-wp-requirements.php',
	],
	function( $plugin ) {
		return is_readable( plugin_dir_path( __FILE__ ) . "/{$plugin}" );
	}
);

foreach( $plugins as $plugin ) {
	require_once $plugin;
}
