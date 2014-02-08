<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// Create a simple "default" Doctrine ORM configuration for Annotations
$paths = array( dirname( __DIR__ ) ."/src/Demo/Models");
$isDevMode = false;

$dbParams = include( dirname( __DIR__ ) . '/config/dbParam.php' );

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
return $entityManager = EntityManager::create($dbParams, $config);
