#!/usr/bin/env php
<?php
/**
 * Helper installer script for setting up the local dev environment.
 *
 * Note: This is not yet elegant enough to check for missing dependencies. For
 * instance, if Yarn is not installed on the local machine, this script will
 * fail to complete. That said, because it is run on the Composer post-install
 * event, all of the dependencies you'll need to set up the site should be present,
 * and the script can be manually re-triggered once the remaining build tools are in place.
 *
 * This script will get updated as the development processes are changed. For instance,
 * it may not be necessary in the future to run the theme build steps if the themes themselves
 * ship with built assets in tagged releases.
 */

namespace JMichaelWard\JmwWP2020;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Installation script.
 */
$root = dirname( __FILE__, 2 );

require_once "{$root}/public/vendor/autoload.php";

/*
 * Copy the example environment file into config if it's not set up to get
 * some initial values.
 */
$configs = [
	'.env.example'       => '.env',
	'config.example.php' => 'config.php',
];

foreach ( $configs as $example => $config ) {
	if ( ! is_readable( "{$root}/config/{$config}" ) ) {
		$process = new Process( [ 'cp', $example, $config ], "{$root}/config" );
		$process->run();
	}
}

load_environment();

// Get theme values.
$theme     = $_ENV['CURRENT_THEME'];
$theme_dir = "{$root}/public/wp-content/themes/{$theme}";

/*
 * Set up individual processes to execute.
 *
 * Each step is given a name so we can make particular checks within the foreach loop.
 * If there was ever a need to abstract this out, we could, but for now, it's fine just being
 * a simple array of parameters to pass into new Process objects.
 */
$processes = [
	'install_yarn'   => [
		[ 'yarn', 'install' ],
		$theme_dir,
	],
	'build_theme'    => [
		[ 'yarn', 'run', 'build' ],
		$theme_dir,
	],
];

// Loop through the parameters and run the individual processes with output.
foreach ( $processes as $name => $params ) {
	if ( 'install_yarn' === $name && is_dir( $theme_dir . '/node_modules' ) ) {
		continue;
	}

	$process = new Process( $params[0], $params[1] );
	$process->run();

	if ( ! $process->isSuccessful() ) {
		throw new ProcessFailedException( $process );
	}

	echo $process->getOutput(); // @codingStandardsIgnoreLine
}
