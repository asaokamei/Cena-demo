<?php

/*
 * autoload for setting Composer's AutoLoader.
 */

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = include( __DIR__ . '/vendor/autoload.php' );

$loader->add( 'Demo\\', __DIR__ .'/src' );
$loader->register();

