<?php
use Cena\Doctrine2\Factory;

// Create a simple "default" Doctrine ORM configuration for Annotations
$paths = array( dirname( __DIR__ ) ."/src/Demo/Models");
$isDevMode = false;

$dbParams = include( dirname( __DIR__ ) . '/config/dbParam.php' );

return Factory::em($dbParams, $paths);
