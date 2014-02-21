<?php
use Cena\Cena\Utils\HtmlForms;
use Demo\Models\Post;
use Doctrine\ORM\EntityManager;

/** @var EntityManager $em */

$em = include( dirname( __DIR__ ) . '/config/bootEmDc2.php' );
$query = $em->createQuery( 'SELECT p FROM Demo\Models\Post p' );
/** @var Post[] $posts */
$posts = $query->getResult();
$form  = \Cena\Cena\Factory::form('dummy');
//var_dump( $posts );

?>
<?php include( __DIR__.'/menu/header.php' ); ?>
<style>
    span.date {
        font-size: 0.8em;
        color: #999999;
    }
    a.title {
        text-decoration: none;
        font-weight: bold;
        color: darkgreen;
        border-left: 3px solid darkgreen;
        padding-left: 4px;
    }
    ul {
        margin: 1em 0 1em 0;
        padding: 0;
    }
    li {
        list-style: none;
        margin: 15px 0 10px 0;
        padding: 0 0 5px 0;
        border-bottom: 1px dotted gray;
    }
</style>
<div class="post">
    <h1>Post Lists</h1>
</div>
<ul>
    <?php
    foreach ( $posts as $post ) {
        /** @var Post|HtmlForms $form */
        $form->setEntity( $post );
        ?>
        <li>
            <span class="date" >[<?= $form->getCreatedAt()->format('Y.m.d'); ?>]</span><br/>
            <a href="post.php?id=<?= $form->getPostId(); ?>" class="title"><?= $form->getTitle(); ?></a>
        </li>
    <?php } ?>
</ul>
<?php include( __DIR__.'/menu/footer.php' ); ?>
