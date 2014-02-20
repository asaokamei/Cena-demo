<?php
use Cena\Doctrine2\Factory;

/*
 * boot EntityManager for Doctrine2.
 */

require_once( dirname(__DIR__) . '/autoload.php' );

// Create a simple "default" Doctrine ORM configuration for Annotations
$paths = array(
    dirname(__DIR__) ."/src/Demo/Models"
);
$dbParams = include( __DIR__ . '/dbParam.php' );

return Factory::em($dbParams, $paths);
