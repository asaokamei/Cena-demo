<?php

/** @var CenaManager $cm */
use Cena\Cena\CenaManager;
use Cena\Cena\Factory;
use Demo\Models\Post;
use Doctrine\ORM\EntityManager;

$cm = include( dirname( __DIR__ ) . '/config/bootCm.php' );
$process = new \Cena\Cena\Process( $cm );
$posting = new \Demo\Resources\Posting( $cm, $process );

$id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : '';
if ( $id ) { // edit an existing posting. 
    $posting->onGet( $id );
}
else { // form for a new posting. 
    $posting->onNew();
}

$post = $posting->getPost();
$comments = $posting->getComments();

/*
 * preparing tags.
 */

// get associated tags, and a new tag
$posting->getTags();
$newTag = $posting->getNewTag();
$postTag = $posting->getTags();

// get all the tags.
/** @var EntityManager $em */
$em = $cm->getEntityManager()->em();
$qr = $em->createQuery( "SELECT p FROM Demo\Models\Tag p" );
$tags = $qr->getResult();

/*
 * set up form helper...
 */

$form = Factory::getHtmlForms();
$form->setEntity( $post );
$post_form_name = $form->getFormName();
$post_cena_id = $form->getCenaId();

?>
<?php include( __DIR__ . '/menu/header.php' ); ?>
<form name="postForm" method="post" action="cena.php?id=<?= $id; ?>">
    
    <div class="post col-md-12">
        <h1>edit: <?= $form['title']; ?></h1>
        <span class="date">[<?= $form->get( 'createdAt' )->format( 'Y.m.d' ); ?>]</span>
        <dl>
            <dt>Title:</dt>
            <dd><input type="text" name="<?= $post_form_name ?>[prop][title]" class="form-control"
                       placeholder="title" value="<?= $form['title']; ?>"/></dd>
            <dt>Status:</dt>
            <dd>
                <label><input type="radio" name="<?= $post_form_name ?>[prop][status]"
                        <?= $form->isChecked('status', Post::STATUS_PREVIEW) ?>
                              placeholder="status" value="<?= Post::STATUS_PREVIEW ?>" >Preview</label>
                <label><input type="radio" name="<?= $post_form_name ?>[prop][status]"
                        <?= $form->isChecked('status', Post::STATUS_PUBLIC) ?>
                              placeholder="status" value="<?= Post::STATUS_PUBLIC?>" >Public</label>
                <label><input type="radio" name="<?= $post_form_name ?>[prop][status]"
                        <?= $form->isChecked('status', Post::STATUS_HIDE) ?>
                              placeholder="status" value="<?= Post::STATUS_HIDE ?>" >Hide this</label>
            </dd>
            <dt>Publish At:</dt>
            <dd><input type="datetime-local" name="<?= $post_form_name ?>[prop][publishAt]"
                       class="form-control" style="width: 250px"
                       placeholder="publish date" value="<?= $form['publishAt']->format('Y-m-d\TH:i:s') ?>" ></dd>
            <dt>Content:</dt>
            <dd><textarea type="text" name="<?= $post_form_name ?>[prop][content]" rows="10" class="form-control"
                          placeholder="content here..."><?= $form['content']; ?></textarea></dd>
            <dt>Tags:</dt>
            <dd>
                <?php
                foreach($tags as $t ) {
                    $form->setEntity( $t );
                    $checked = $postTag->exists( function($k,$e) use($t){ return $t==$e;} ) ? ' checked="checked"' : '';
                    ?>
                    <label>
                        <input type="checkbox" name="<?= $post_form_name ?>[link][tags][]"
                               value="<?= $form->getCenaId() ?>" <?= $checked ?> />
                        <?= $form['tag'] ?>
                    </label>
                <?php
                }
                $form->setEntity( $newTag );
                ?>
                <label>
                    <input type="checkbox" name="<?= $post_form_name ?>[link][tags][]" value="<?= $form->getCenaId() ?>" />
                    <input type="text" name="<?= $form->getFormName() ?>[prop][tag]" value=""
                           placeholder="new tag..." class="form-control" width="200px">
                </label>
            </dd>
        </dl>
        <button type="submit" class="btn btn-primary">submit post</button>
    </div>
    
    <?php if ( count( $comments ) > 0 ) { ?>
        <div class="comments col-md-8">
            <h2>comments...</h2>
            <?php
            /*
             * list all comments. 
             */
            foreach ( $comments as $comment ) {
                $form->setEntity( $comment );
                ?>
                <hr>
                <?php if ( $cm->getEntityManager()->isRetrieved( $comment ) ) { ?>
                    <div class="comment">
                        <input type="hidden" name="<?= $form->getFormName() ?>[link][post]" class="form-control"
                               value="<?= $post_cena_id ?>">
                        <textarea type="text" name="<?= $form->getFormName() ?>[prop][comment]" rows="4" class="form-control"
                                  placeholder="comment here..."><?= $form['comment']; ?></textarea>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <br/>
        <div class="post col-md-12">
            <button type="submit" class="btn btn-primary">submit post</button>
        </div>
    <?php } ?>
    
</form>
<?php include( __DIR__ . '/menu/footer.php' ); ?>
