<?php
use Demo\Models\Post;
use Doctrine\ORM\EntityManager;

/** @var EntityManager $em */

$em = include( dirname( __DIR__ ) . '/config/bootEmDc2.php' );
$query = $em->createQuery( 'SELECT p FROM Demo\Models\Post p' );
/** @var Post[] $posts */
$posts = $query->getResult();

//var_dump( $posts );

?>
<?php include( __DIR__.'/menu/header.php' ); ?>
<h1>Post Lists</h1>
<ul>
    <?php
    foreach ( $posts as $post ) {
        ?>
        <li><a href="post.php?id=<?= $post->getPostId(); ?>" ><?= $post->getTitle(); ?></a> [<?= $post->getCreatedAt()->format('Y.m.d'); ?>]<br/>
            <?= $post->getContent(); ?></li>
    <?php } ?>
</ul>
<?php include( __DIR__.'/menu/footer.php' ); ?>
