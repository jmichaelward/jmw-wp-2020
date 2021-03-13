<?php
/**
 * Collection of functions shared between scripts.
 *
 * @package JMichaelWard\JmwWP2020;
 */

namespace JMichaelWard\JmwWP2020;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Attemt to load the environment values if the configuration exists.
 *
 * @return Dotenv
 */
function load_environment() {
	/*
	 * Attempt to load the .env configuration. Bail if it can't be found.
	 */
	try {
		$dotenv = new Dotenv();
		$dotenv->load( dirname( __FILE__, 2 ) . '/config/.env' );
	} catch ( Throwable $e ) {
		echo "No .env file found in configuration. Could not complete installation.\n";
		exit( 1 );
	}

	return $dotenv;
}
