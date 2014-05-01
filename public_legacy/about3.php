<?php
use Demo\Controller\AboutController;

require_once( dirname(__DIR__) . '/autoload.php' );

$controller = AboutController::getInstance();
$view = $controller->execute();

?>

<?php include( __DIR__ . '/menu/header.php' ); ?>
<div class="post col-md-12">

    <h1>About this demo</h1>
    <p>This is a sample demo blog application to demonstrate Cena technology.</p>
    <p>It uses Cena and Doctrine2 as a base ORM. </p>

    <h2>Tables Structure</h2>
    <p>This demo is consisted of 4 tables:</p>
    <dl>
        <dt>post:</dt>
        <dd>main blog post containing title and content. </dd>
        <dt>comment:</dt>
        <dd>comments associated with a blog post.</dd>
        <dt>tag:</dt>
        <dd>tags associated with a blog post via post_tags join table. i.e. many-to-many relationship. </dd>
        <dt>post_tags:</dt>
        <dd>a join table for post and tag. </dd>
    </dl>
    <img src="tables.jpeg" />
</div>
<?php include( __DIR__ . '/menu/footer.php' ); ?>
