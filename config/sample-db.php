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
                'title'   => 'About This Blog',
                'status'  => 1,
                'content' => "This is the first post sample text to demonstrate Cena technology. \n\n".
                    "##Markdown text\n".
                    "this text uses markdown style, and converted to html. \n\n".
                    "please edit this content. ",
            ],
            'link' => [ 'tags' => [ 'tag.0.1' ] ]
        ),
        'post.0.2'    => array(
            'prop' => [
                'title'   => 'Comments and Tags',
                'status'  => 1,
                'content' => "This post has two comments and two tags. \n\n".
                    "##Cena make it easy\n\n".
                    "Maybe add/modify/delete them to see how Cena works? ",
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

