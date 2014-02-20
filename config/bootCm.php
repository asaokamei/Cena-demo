<?php
use Cena\Cena\Factory;
use Cena\Doctrine2\EmaDoctrine2;
use Doctrine\ORM\EntityManager;

/*
 * create cm (CenaManager) for doctrine2 ema. 
 */

/** @var EntityManager $em */
$em = include( __DIR__ . '/bootEmDc2.php' );

$ema = new EmaDoctrine2();
$ema->setEntityManager( $em );

$cm = Factory::cm( $ema );

$cm->setClass( 'Demo\Models\Post' );
$cm->setClass( 'Demo\Models\Comment' );

return $cm;