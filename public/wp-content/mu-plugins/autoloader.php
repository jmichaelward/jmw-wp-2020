<?php
/**
 * This plugin loads mu-plugins located in subdirectories.
 *
 * @package JMichaelWard\JmwWP2020
 */

namespace JMichaelWard\JmwWP2020;

foreach ( get_mu_plugins() as $mu_plugin_file ) {
	require_once $mu_plugin_file;
}

/**
 * Get all of the mu-plugins from this directory.
 *
 * @author Jeremy Ward <jeremy@jmichaelward.com>
 * @since  2020-12-12
 * @return string[]
 */
function get_mu_plugins() : array {
	return array_map(
		function ( $plugin ) {
			return __DIR__ . "/{$plugin}/{$plugin}.php";
		},
		array_filter(
			scandir( __DIR__ ),
			function ( $plugin ) {
				if ( in_array( $plugin, [ '.', '..' ], true ) ) {
					return false;
				}

				$plugin_dir = __DIR__ . "/{$plugin}/";

				return (
					is_dir( $plugin_dir )
					&& is_readable( "{$plugin}/{$plugin}.php" )
				);
			}
		)
	);
}
