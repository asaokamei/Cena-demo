<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// set up Composer's auto loader. 
/** @var \Composer\Autoload\ClassLoader $loader */
$loader = include( dirname( __DIR__ ) . '/vendor/autoload.php' );

$loader->addPsr4( 'CenaDemo\\', __DIR__ . '/CenaDemo/' );
$loader->register();

// Create a simple "default" Doctrine ORM configuration for Annotations
$paths = array( dirname( __DIR__ ) ."/src/Demo/Models");
$isDevMode = false;

$dbParams = include( dirname( __DIR__ ) . '/config/dbParam.php' );

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
return $entityManager = EntityManager::create($dbParams, $config);
