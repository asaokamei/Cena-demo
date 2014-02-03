<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/*
 * boot EntityManager for Doctrine2.
 */

require_once( dirname(__DIR__) . '/autoload.php' );

// Create a simple "default" Doctrine ORM configuration for Annotations
$paths = array(
    dirname(__DIR__) ."/src/Demo/Models"
);
$isDevMode = false;

$dbParams = include( __DIR__ . '/dbParam.php' );

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
return $entityManager = EntityManager::create($dbParams, $config);
