<?php

use Cena\Cena\Utils\HtmlForms;
use Demo\Factory as DemoFactory;
use Demo\Legacy\PageView;
use Demo\Models\Comment;

include( dirname(__DIR__) . '/autoload.php' );

try {

    $view = new PageView();
    if( !isset( $_GET[ 'id' ] ) ) {
        throw new \InvalidArgumentException('please indicate post # to view. ');
    }
    $id = $_GET[ 'id' ];
    $posting = DemoFactory::getPosting();
    if( empty($_POST) ) {
        $posting->onGet( $id );
        $newComment = $posting->getNewComment();
    } else {
        $posting->with( $_POST );
        $posting->onPostComment( $id );
    }

    $post = $posting->getPost();
    $comments = $posting->getComments();
    $tag_list = $posting->getTagList();
    $form = DemoFactory::getHtmlForms();

} catch ( Exception $e ) {

    $view->critical( $e->getMessage() );

}

?>
<?php include( __DIR__ . '/menu/header.php' ); ?>
<style>
</style>
<?php
echo $view->alert();
if( $view->isCritical() ) goto Html_Page_footer;
?>
<div class="post col-md-12">
    <?php $form->setEntity( $post ); ?>
    <h1><?= $form['title']; ?></h1>
    <span class="date">[<?= $form->get( 'publishAt' )->format( 'Y.m.d' ); ?>] [<?= implode( ', ', $tag_list ); ?>]</span>
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
    foreach ( $comments as $comment ) {
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
    <form name="addPost" method="post" action="post.php?id=<?= $id; ?>" >
        <input type="hidden" name="<?= $form->getFormName()?>[link][post]" value="<?= $post_cena_id; ?>">
        <textarea name="<?= $form->getFormName() ?>[prop][comment]" placeholder="comment here..." class="form-control"></textarea>
        <button type="submit" class="btn btn-info btn-sm">add comment</button>
    </form>
    <?php } ?>
    <?php } ?>
</div>
<?php Html_Page_footer: ?>
<?php include( __DIR__ . '/menu/footer.php' ); ?>
