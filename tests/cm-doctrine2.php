<?php
use Cena\Cena\Factory as CenaFactory;
use Cena\Doctrine2\Factory as Dc2Factory;

/*
 * create cm (CenaManager) for doctrine2 ema. 
 */

$em  = include( __DIR__ . '/em-doctrine2.php' );
$ema = Dc2Factory::ema( $em );
$cm  = CenaFactory::cm( $ema );

return $cm;