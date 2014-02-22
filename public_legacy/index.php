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
<div class="jumbotron">
    <div class="container">
        <h1>This is Cena-demo!</h1>
        <p>This is a demo blog for Cena technology using Doctrine2 as 
            a base ORM with legacy PHP style (i.e. scripts per page). 
            Try edit one of the posts to see how Cena works. </p>
        <p><a href="https://github.com/asaokamei/Cena-demo" class="btn btn-primary btn-lg" role="button" target="_blank">See it on the github »</a></p>
    </div>
</div>
<div class="post col-md-12">
<?php
foreach ( $posts as $post ) {
    /** @var Post|HtmlForms $form */
    $form->setEntity( $post );
    ?>
    <div class="col-md-4">
        <h2><a href="post.php?id=<?= $form->getPostId(); ?>" class="title"><?= $form->getTitle(); ?></a></h2>
        <span class="date" >[<?= $form->getCreatedAt()->format('Y.m.d'); ?>]</span><br/>
    </div>
<?php } ?>
</div>

<?php include( __DIR__.'/menu/footer.php' ); ?>
