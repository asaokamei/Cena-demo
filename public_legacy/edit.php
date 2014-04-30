<?php

use Cena\Cena\Utils\HtmlForms;
use Demo\Controller\EditController;
use Demo\Models\Comment;
use Demo\Models\Post;
use Demo\Resources\Posting;
use Demo\Resources\Tags;

include( dirname(__DIR__) . '/autoload.php' );

$controller = EditController::getInstance();
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
            <dd><input type="text" name="<?= $post_form_name ?>[prop][title]" class="form-control"
                       placeholder="title" value="<?= $form['title']; ?>"/></dd>
            <dt>Status:</dt>
            <dd>
                <label><input type="radio"
                              name="<?= $post_form_name ?>[prop][status]"
                              placeholder="status" value="<?= Post::STATUS_PREVIEW ?>"
                              <?= $form->checkIf( $form->isEqualTo('status', Post::STATUS_PREVIEW)) ?>>Preview</label>
                <label><input type="radio"
                              name="<?= $post_form_name ?>[prop][status]"
                              placeholder="status" value="<?= Post::STATUS_PUBLIC?>"
                              <?= $form->checkIf( $form->isEqualTo('status', Post::STATUS_PUBLIC)) ?>>Public</label>
                <label><input type="radio"
                              name="<?= $post_form_name ?>[prop][status]"
                              placeholder="status" value="<?= Post::STATUS_HIDE ?>"
                              <?= $form->checkIf( $form->isEqualTo('status', Post::STATUS_HIDE)) ?> >Hide this</label>
            </dd>
            
            <dt>Publish At:  <?= $form->getErrorMsg('publishAt') ?></dt>
            <dd><input type="datetime-local" name="<?= $post_form_name ?>[prop][publishAt]"
                       class="form-control" style="width: 250px"
                       placeholder="publish date"
                       value="<?= $form->get( 'publishAt' )->format('Y-m-d\TH:i:s') ?>" ></dd>
            
            <dt>Content:  <?= $form->getErrorMsg('content') ?></dt>
            <dd><textarea name="<?= $post_form_name ?>[prop][content]"
                          rows="10" class="form-control"
                          placeholder="content here..."><?= $form['content']; ?></textarea></dd>
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
                        function($k,$e) use($t){ return $t==$e;} ) ? ' checked="checked"' : '';
                    ?>
                    <label>
                        <input type="checkbox"
                               name="<?= $post_form_name ?>[link][tags][]"
                               value="<?= $form->getCenaId() ?>" <?= $checked ?> />
                        <?= $form['tag'] ?>
                        <?= $form->getError('tag');?>
                    </label>
                <?php
                }
                // for the new tag
                $form->setEntity( $tags->getNewTag() );
                ?>
                <label>
                    <input type="checkbox"
                           name="<?= $post_form_name ?>[link][tags][]"
                           value="<?= $form->getCenaId() ?>" />
                    <input type="text"
                           name="<?= $form->getFormName() ?>[prop][tag]" value=""
                           placeholder="new tag..." class="form-control" width="200px">
                    <?= $form->getError('tag');?>
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
                    <input type="hidden"
                           name="<?= $form->getFormName() ?>[link][post]"
                           class="form-control"
                           value="<?= $post_cena_id ?>">
                    
                    <textarea name="<?= $form->getFormName() ?>[prop][comment]"
                              rows="4" class="form-control"
                              placeholder="comment here..."><?= $form['comment']; ?></textarea>
                    <label>
                        to delete, check this:
                        <input type="checkbox"
                            <?= $form->checkIf( $form->isDeleted() ); ?>
                               name="<?= $form->getFormName() ?>[del]"
                               value="<?= $post_cena_id ?>">
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
