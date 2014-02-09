<?php
use Cena\Cena\CenaManager;
use Cena\Cena\Utils\ClassMap;
use Cena\Cena\Utils\Collection;
use Cena\Cena\Utils\Composition;
use Cena\Doctrine2\EmaDoctrine2;
use Doctrine\ORM\EntityManager;

/*
 * create cm (CenaManager) for doctrine2 ema. 
 */

/** @var EntityManager $em */
$em = include( __DIR__ . '/em-doctrine2.php' );

$ema = new EmaDoctrine2();
$ema->setEntityManager( $em );

$cm = new CenaManager(
    new Composition(),
    new Collection(),
    new ClassMap()
);
$cm->setEntityManager( $ema );

return $cm;