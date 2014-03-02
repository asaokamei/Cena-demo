<?php
/*
 * create sample blog posts, comments, and tags with relations. 
 * 
 * run this script as a command, or include this fril from another script. 
 */
use Cena\Cena\Factory as CenaFactory;
use Demo\Factory as DemoFactory;

require_once( __DIR__ . '/../autoload.php' );

call_user_func( function() {
    
    $cm      = DemoFactory::getCenaManager();
    $process = CenaFactory::getProcess();

    $input = array(
        'post.0.1'    => array(
            'prop' => [
                'title'   => 'First Post',
                'content' => 'This is the first post. please edit this content. ',
            ],
            'link' => [ 'tags' => 'tag.0.1' ]
        ),
        'post.0.2'    => array(
            'prop' => [
                'title'   => 'Second Post',
                'content' => 'This is the second post. maybe try modify its tags? ',
            ],
            'link' => [ 'tags' => [ 'tag.0.2', 'tag.0.3' ] ],
        ),
        'comment.0.1' => array(
            'prop' => [ 'comment' => 'comment', ],
            'link' => [ 'post' => 'post.0.1' ],
        ),
        'comment.0.2' => array(
            'prop' => [ 'comment' => 'comment', ],
            'link' => [ 'post' => 'post.0.1' ],
        ),
        'comment.0.3' => array(
            'prop' => [ 'comment' => 'comment', ],
            'link' => [ 'post' => 'post.0.2' ],
        ),
        'tag.0.1'     => array(
            'prop' => [ 'tag' => 'cena', ],
        ),
        'tag.0.2'     => array(
            'prop' => [ 'tag' => 'php', ],
        ),
        'tag.0.3'     => array(
            'prop' => [ 'tag' => 'fun', ],
        ),
    );
    $process->setSource( $input );
    $process->process();
    $cm->save();
    
});

