<?php
use Cena\Doctrine2\Factory;

// Create a simple "default" Doctrine ORM configuration for Annotations
$paths = array( __DIR__ ."/CenaDemo/Entity");
$isDevMode = false;

$dbParams = include( dirname( __DIR__ ) . '/config/dbParam.php' );

return Factory::getEntityManager($dbParams, $paths);
