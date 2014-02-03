<?php

/*
 * creates tables.
 */

$em = include( __DIR__ . '/bootEmDc2.php' );
$tool = new \Doctrine\ORM\Tools\SchemaTool( $em );

$classes = array(
    $em->getClassMetadata( 'Demo\Models\Post' ),
    $em->getClassMetadata( 'Demo\Models\Comment' ),
);
$tool->dropSchema( $classes );
$tool->createSchema( $classes );
