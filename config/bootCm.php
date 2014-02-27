<?php
use Cena\Cena\Factory as CenaFactory;
use Cena\Doctrine2\Factory as Dc2Factory;

/*
 * create cm (CenaManager) for doctrine2 ema. 
 */

$em  = include( __DIR__ . '/bootEmDc2.php' );
$ema = Dc2Factory::getEmaDoctrine2( $em );
$cm  = CenaFactory::getCenaManager( $ema );

$cm->setClass( 'Demo\Models\Post' );
$cm->setClass( 'Demo\Models\Comment' );
$cm->setClass( 'Demo\Models\Tag' );

return $cm;