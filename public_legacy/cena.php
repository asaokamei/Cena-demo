<?php

/** @var CenaManager $cm */
use Cena\Cena\CenaManager;
use Demo\Factory as DemoFactory;

include( dirname(__DIR__) . '/autoload.php' );

$posting = DemoFactory::getPosting();

$id = isset( $_GET['id'] ) ? $_GET['id'] : '';
$posting->with( $_POST );
try {

    if( $id ) {                  // edit an existing posting. 
        $posting->onPut( $id );
    } else {                     // form for a new posting. 
        $posting->onPost();
        $id = $posting->getPost()->getPostId();
    }
    header( "Location: post.php?id={$id}" );
    exit;

} catch ( Exception $e ) {
    
    echo $e->getMessage();
}

