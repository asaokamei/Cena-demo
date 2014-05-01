<?php

use Cena\Cena\Utils\HtmlForms;
use Demo\Controller\EditController;
use Demo\Models\Comment;
use Demo\Models\Post;
use Demo\Resources\Posting;
use Demo\Resources\Tags;
use WScore\Pages\Dispatch;

include( dirname(__DIR__) . '/autoload.php' );

$controller = Dispatch::getInstance( EditController::getInstance() );
$view = $controller->execute();

$id = $view['id'];
/** @var Posting $posting */
$posting = $view['posting'];
/** @var HtmlForms $form */
$form = $view['form'];
/** @var Tags $tags */
$tags = $view['tags'];

$post = $posting->getPost();
$form->setEntity( $post );
$post_form_name = $form->getFormName();
$post_cena_id   = $form->getCenaId();
$form->setHtmlOptions( 'class', 'form-control' );

?>
<?php include( __DIR__ . '/menu/header.php' ); ?>
<?php
echo $view->alert();
if( $view->isCritical() ) goto Html_Page_footer;
?>
<form name="postForm" method="post" action="edit.php?id=<?= $id; ?>">
    
    <?= $view->getHidden('_token'); ?>
    <div class="post col-md-12">
        <h1><?= $view['title']; ?></h1>
        <span class="date">[<?= $form->get( 'createdAt' )->format( 'Y.m.d' ); ?>]</span>
        <dl>
            <dt>Title: <?= $form->getErrorMsg('title') ?></dt>
            <dd><?= $form->text('title',
                    ['placeholder'=>'title of this blog'] ); ?></dd>
            <dt>Status:</dt>
            <dd>
                <label><?= $form->radio('status', Post::STATUS_PREVIEW ); ?>Preview</label>
                <label><?= $form->radio('status', Post::STATUS_PUBLIC ); ?>Public</label>
                <label><?= $form->radio('status', Post::STATUS_HIDE ); ?>Hide this</label>
            </dd>
            
            <dt>Publish At:  <?= $form->getErrorMsg('publishAt') ?></dt>
            <dd><?= $form->dateTime( 'publishAt',
                    ['style'=>'width:250px'] ); ?></dd>
            
            <dt>Content:  <?= $form->getErrorMsg('content') ?></dt>
            <dd><?= $form->textArea('content',
                    [ 'rows'=>'10', 'placeholder'=>'content here in markdown'] ); ?></dd>
            <dt>Tags:</dt>
            <dd>
                <?php
                /*
                 * list all existing tags.
                 */
                $postTag = $posting->getTags();
                foreach( $tags as $t ) {

                    $form->setEntity( $t );
                    /** @noinspection PhpUnusedParameterInspection */
                    $checked = $postTag->exists(
                        function($k,$e) use($t){ return $t==$e;} ) ? true : false;
                    ?>
                    <label>
                        <?= $form->linkReverse( 'tags][', $post, ['checked'=>$checked], 'checkbox' ); ?>
                        <?= $form['tag'] ?>
                        <?= $form->getError('tag');?>
                    </label>
                <?php
                }
                // for the new tag
                $form->setEntity( $tags->getNewTag() );
                ?>
                <label>
                    <?= $form->linkReverse( 'tags][', $post, [], 'checkbox' ); ?>
                    <?= $form->text( 'tag', ['width'=>'200px','placeholder'=>'add new tag'] );?>
                    <?= $form->getErrorMsg('tag');?>
                </label>
            </dd>
        </dl>
        <button type="submit" class="btn btn-primary">submit post</button>
    </div>
    
    <?php
    /** @var Comment[] $comments */
    $comments = $posting->getComments();
    if ( count( $comments ) > 0 ) {
        ?>

        <div class="comments col-md-8">
            <h2>comments...</h2>
            <?php
            /*
             * list all comments. 
             */
            foreach ( $comments as $comment ) {

                $form->setEntity( $comment );
                if ( !$form->isRetrieved() ) continue;
                ?>
                <hr>
                <div class="comment">
                    <?= $form->getErrorMsg('comment') ?>
                    <?= $form->link( 'post', $post_cena_id ); ?>
                    <?= $form->textArea( 'comment',
                        ['rows'=>4, 'placeholder'=>'comment here...'] ); ?>

                    <label>
                        to delete, check this:
                        <?= $form->deleteMeCheck(); ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <br/>
        <div class="post col-md-12">
            <button type="submit" class="btn btn-primary">submit post</button>
        </div>
    <?php } ?>
    
</form>
<?php Html_Page_footer: ?>
<?php include( __DIR__ . '/menu/footer.php' ); ?>
