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

$dotenv = new Dotenv();
$dotenv->load( "{$root}/config/.env" );

$theme     = $_ENV['CURRENT_THEME'];
$theme_dir = "{$root}/public/wp-content/themes/{$theme}";

$process = new Process( [ 'yarn', 'install' ], $theme_dir );
$process->run();

$process = new Process( [ 'yarn', 'run', 'build' ], $theme_dir );
$process->run();

$process = new Process( [ 'wp', 'theme', 'activate', $theme ], './public/vendor/bin' );
$process->run();


if ( ! $process->isSuccessful() ) {
	throw new ProcessFailedException( $process );
}

echo $process->getOutput();
