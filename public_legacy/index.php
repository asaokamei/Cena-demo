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
<div class="post col-md-12">
    <h1>Post Lists</h1>
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
