<?php
use Cena\Cena\Utils\HtmlForms;
use Demo\Factory as DemoFactory;
use Demo\Legacy\PageView;
use Demo\Models\PostList;

require_once( dirname(__DIR__) . '/autoload.php' );

try {

    $action = isset( $_GET['act'] ) ? $_GET['act'] : null;

    $view = call_user_func( function($action) {

        if( $action == 'sample' ) {
            include( dirname(__DIR__).'/config/sample-db.php' );
            header( "Location: index.php" );
            exit;
        }
        if( $action == 'setup' ) {
            include( dirname(__DIR__).'/config/setup-db.php' );
            header( "Location: index.php" );
            exit;
        }
        $view = new PageView();
        $em = DemoFactory::getEntityManager();
        $query = $em->createQuery( 'SELECT p FROM Demo\Models\PostList p' );
        $view[ 'posts' ] = $query->getResult();
        $view[ 'form'  ]  = DemoFactory::getHtmlForms();

        return $view;

    }, $action );

} catch ( Exception $e ) {

    $view = new PageView();
    $view->critical( $e->getMessage() );

}

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
    <div class="col-md-4" style="float: right;background-color: #f0f0f0; border: 2px solid #e0e0e0;">
        <h2>utilities</h2>
        <ul>
            <li>to initialize database, click <a href="index.php?act=setup" >here</a>.</li>
            <li>to add sample posts, click <a href="index.php?act=sample" >here</a>.</li>
        </ul>
    </div>
<?php
$form = $view['form'];
$posts = $view['posts'];
foreach ( $posts as $post ) {
    /** @var PostList|HtmlForms $form */
    $form->setEntity( $post );
    ?>
    <div class="col-md-4">
        <h2><a href="post.php?id=<?= $form->getPostId(); ?>" class="title"><?= $form->getTitle(); ?></a></h2>
        <span class="date" >
            [<?= $form->getPublishAt()->format('Y.m.d'); ?>]
            [<?= $form->getTagsList() ?>]
            [# of comments:<?= $form->getCountComments() ?>]
        </span><br/>
        <span><?= mb_substr( $form->getContent(), 0, 100 ) ?>... 
            [<a href="post.php?id=<?= $form->getPostId(); ?>">read more</a>]</span>
    </div>
<?php } ?>
</div>

<?php include( __DIR__.'/menu/footer.php' ); ?>
