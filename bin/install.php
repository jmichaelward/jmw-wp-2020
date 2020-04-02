#!/usr/bin/env php
<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Installation script.
 */
$root = dirname( __FILE__, 2 );

require_once "{$root}/public/vendor/autoload.php";

if ( ! is_readable( "{$root}/config/.env" ) ) {
	$process = new Process( [ 'cp', '.env.example', '.env' ], "{$root}/config" );
	$process->run();
}

try {
	$dotenv = new Dotenv();
	$dotenv->load( "{$root}/config/.env" );
} catch ( Throwable $e ) {
	echo "No .env file found in configuration. Could not complete installation.\n";
	exit( 1 );
}
$theme     = $_ENV['CURRENT_THEME'];
$theme_dir = "{$root}/public/wp-content/themes/{$theme}";

$processes = [
	'install_yarn'   => [
		[ 'yarn', 'install ' ],
		$theme_dir,
	],
	'build_theme'    => [
		[ 'yarn', 'run', 'build' ],
		$theme_dir,
	],
	'activate_theme' => [
		[ 'wp', 'theme', 'activate', $theme ],
		'./public/vendor/bin',
	],
];

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
