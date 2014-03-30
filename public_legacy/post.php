<?php

use Cena\Cena\Utils\HtmlForms;
use Demo\Factory as DemoFactory;
use Demo\Legacy\PageView;
use Demo\Models\Comment;
use Demo\Models\Post;

include( dirname(__DIR__) . '/autoload.php' );

try {

    $id = isset( $_GET[ 'id' ] )? $_GET[ 'id' ] : null;
    $view = call_user_func( function($id) {

        if( !isset( $id ) ) {
            throw new \InvalidArgumentException('please indicate post # to view. ');
        }
        $view = new PageView();
        $posting = DemoFactory::getPosting();
        if( empty($_POST) ) {
            $posting->onGet( $id );
            $posting->getNewComment();
        } else {
            $posting->with( $_POST );
            if( $posting->onPostComment( $id ) ) {
                header( "Location: post.php?id={$id}" );
                exit;
            }
            $view->error( 'failed to post comment' );
        }

        $view['post']     = $posting->getPost();
        $view['comments'] = $posting->getComments();
        $view['tag_list'] = $posting->getTagList();
        $view['form']     = DemoFactory::getHtmlForms();
        
        return $view;
    }, $id );
    

} catch ( Exception $e ) {

    $view = new PageView();
    $view->critical( $e->getMessage() );

}

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
                <form name="addPost" method="post" action="post.php?id=<?= $id; ?>" >
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
