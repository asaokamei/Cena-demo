<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

/*
 * boot cli-manager for Doctrine2.
 */


$entityManager = include( __DIR__ . "/bootEmDc2.php" );

return ConsoleRunner::createHelperSet($entityManager);