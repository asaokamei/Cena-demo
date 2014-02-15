<?php

/** @var CenaManager $cm */
use Cena\Cena\CenaManager;

$cm = include( dirname( __DIR__ ) . '/config/bootCm.php' );
$process = new \Cena\Cena\Process( $cm );
$posting = new \Demo\Resources\Posting( $cm, $process );

$id = $_GET[ 'id' ];
$posting->onGet( $id );

$post = $posting->getPost();
$comments = $posting->getComments();
$newComment = $posting->getNewComment();

?>
<?php include( __DIR__ . '/menu/header.php' ); ?>
<style>
</style>
<div class="post">
    <h1><span class="date">[<?= $post->getCreatedAt()->format( 'Y.m.d' ); ?>]</span><?= $post->getTitle(); ?></h1>
    <div style="clear: both" ></div>
</div><div class="content">
    <span><?= $post->getContentHtml(); ?></span>
</div>
<div>
    <button class="editPost" onclick="location.href='edit.php?id=<?= $post->getPostId(); ?>'" >edit this post</button>
    <div style="clear: both" ></div>
</div>
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
                <span class="date">[<?= $comment->getCreatedAt()->format( 'Y.m.d' ); ?>]</span>
                <span class="comment"><?= $comment->getComment(); ?></span>
            </div>
        <?php }
        // for adding a new comment. 
        else { ?>
            <form name="addPost" method="post" action="cena.php?id=<?= $id; ?>" >
                <div class="comment">
                    <input type="hidden" name="<?= $cm->formBase( $newComment )?>[link][post]" value="<?= $cm->cenaId($post); ?>">
                    <textarea type="text" name="<?= $cm->formBase( $newComment )?>[prop][comment]" placeholder="comment here..."></textarea>
                </div>
                <button type="submit">add comment</button>
            </form>
        <?php } ?>
    <?php } ?>
</div>
<?php include( __DIR__ . '/menu/footer.php' ); ?>
