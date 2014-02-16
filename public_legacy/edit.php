<?php

/** @var CenaManager $cm */
use Cena\Cena\CenaManager;

$cm = include( dirname( __DIR__ ) . '/config/bootCm.php' );
$process = new \Cena\Cena\Process( $cm );
$posting = new \Demo\Resources\Posting( $cm, $process );

$id = isset( $_GET['id'] ) ? $_GET['id'] : '';
if( $id ) {                  // edit an existing posting. 
    $posting->onGet( $id );
} else {                     // form for a new posting. 
    $posting->onNew();    
}

$post = $posting->getPost();
$comments = $posting->getComments();

?>
<?php include( __DIR__ . '/menu/header.php' ); ?>
<form name="postForm" method="post" action="cena.php?id=<?= $id; ?>" >
    <div class="post">
        <h1>edit: <?= $post->getTitle(); ?></h1>
        <dl>
            <dt>Title:</dt>
            <dd><input type="text" name="<?= $cm->formBase( $post ) ?>[prop][title]"
                       placeholder="title" value="<?= $post->getTitle(); ?>" /></dd>
            <dt>Content:</dt>
            <dd><textarea type="text" name="<?= $cm->formBase( $post ) ?>[prop][content]" rows="10"
                          placeholder="content here..."><?= $post->getContent(); ?></textarea></dd>
        </dl>
        <button type="submit">submit post</button>
    </div>
    <?php if( count( $comments ) > 0 ) { ?>
    <div class="comments">
        <h2>comments...</h2>
        <?php
        /*
         * list all comments. 
         */
        foreach ( $comments as $comment ) {
            ?>
            <hr>
            <?php if ( $cm->getEntityManager()->isRetrieved( $comment ) ) { ?>
                <div class="comment">
                    <input type="hidden" name="<?= $cm->formBase( $comment ) ?>[link][post]"
                           value="<?= $cm->cenaId( $post ); ?>">
                    <textarea type="text" name="<?= $cm->formBase( $comment ) ?>[prop][comment]" rows="4"
                              placeholder="comment here..."><?= $comment->getComment(); ?></textarea>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <button type="submit">submit post</button>
    <?php } ?>
</form>
<?php include( __DIR__ . '/menu/footer.php' ); ?>
