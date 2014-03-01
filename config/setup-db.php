<?php

/*
 * creates tables.
 */

/** @var EntityManager $em */
use Doctrine\ORM\EntityManager;

$em = include( __DIR__ . '/bootEmDc2.php' );
$tool = new \Doctrine\ORM\Tools\SchemaTool( $em );

$classes = array(
    $em->getClassMetadata( 'Demo\Models\Post' ),
    $em->getClassMetadata( 'Demo\Models\Comment' ),
    $em->getClassMetadata( 'Demo\Models\Tag' ),
);
$tool->dropSchema( $classes );
$tool->createSchema( $classes );

/*
 * create view, post_view, for Post with comments count and concatenated tag. 
 */

$dba = $em->getConnection();
$sql_view = "DROP VIEW IF EXISTS post_view;";
$dba->exec( $sql_view );

$sql_view = "
CREATE VIEW post_view AS 
SELECT *, 
  (SELECT COUNT(comment_id) FROM comment c WHERE c.post_id=p.post_id ) AS count_comments, 
  (SELECT GROUP_CONCAT( tag ORDER BY tag SEPARATOR ', ' ) FROM post_tags pt INNER JOIN tag t USING( tag_id ) WHERE pt.post_id=p.post_id  ) AS tags_list
FROM post p
";
$dba->exec( $sql_view );