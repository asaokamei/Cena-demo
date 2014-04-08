<?php

use Cena\Cena\Utils\HtmlForms;
use Demo\Controller\PostController;
use Demo\Models\Comment;
use Demo\Models\Post;

include( dirname(__DIR__) . '/autoload.php' );

$controller = PostController::factory();
$view = $controller->execute();

?>
<?php include( __DIR__ . '/menu/header.php' ); ?>
<style>
</style>
<?php
echo $view->alert();
if( $view->isCritical() ) goto Html_Page_footer;

/** @var Post $post */
/** @var Post|HtmlForms $form */
$post = $view['post'];
$form = $view['form'];
$form->setEntity( $post ); 
?>
<div class="post col-md-12">
    <h1><?= $form['title']; ?></h1>
    <span class="date">[<?= $form->get( 'publishAt' )->format( 'Y.m.d' ); ?>] [<?= implode( ', ', $view['tag_list'] ); ?>]</span>
    <div style="clear: both" ></div>
    <div class="content">
        <span><?= $post->getContentHtml(); ?></span>
    </div>
    <button class="btn btn-primary" onclick="location.href='edit.php?id=<?= $post->getPostId(); ?>'" >edit this post</button>
    <div style="clear: both" ></div>
</div>

<div class="comments col-md-8">
    <h2>comments...</h2>
    <?php
    /*
     * list all existing comments.
     */
    $post_cena_id = $form->getCenaId();
    foreach ( $view['comments'] as $comment ) {
        /** @var Comment|HtmlForms $form */
        $form->setEntity( $comment );
        if( $form->isRetrieved() ) {
        ?>
        <div class="comment">
            <span class="date">[<?= $form->getCreatedAt()->format( 'Y.m.d' ); ?>]</span>
            <span class="comment"><?= $form['comment']; ?></span>
        </div>
        <hr>
        <?php } else {  ?>
            <!-- show a form to add a new comment -->
            <div class="form-group<?php if( $form->isError() ) { echo ' has-error'; } ?>">
                <form name="addPost" method="post" action="post.php?id=<?= $view['id']; ?>" >
                    <input type="hidden" name="<?= $form->getFormName()?>[link][post]" value="<?= $post_cena_id; ?>">
                    <?php if( $msg = $form->getError('comment') ) { echo "<span class=\"error-msg\">$msg</span>"; } ?>
                    <textarea name="<?= $form->getFormName() ?>[prop][comment]" placeholder="comment here..." class="form-control"></textarea>
                    <button type="submit" class="btn btn-info btn-sm">add comment</button>
                </form>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<?php Html_Page_footer: ?>
<?php include( __DIR__ . '/menu/footer.php' ); ?>
