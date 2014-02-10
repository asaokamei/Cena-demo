<?php
namespace CenaDemo\Resource;

use Cena\Cena\CenaManager;
use Cena\Cena\Process;
use Demo\Resources\Posting;
use Doctrine\ORM\Tools\SchemaTool;

class Posting_BasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CenaManager
     */
    public $cm;

    /**
     * @var Process
     */
    public $process;

    /**
     * @var Posting
     */
    public $post;

    static function setUpBeforeClass()
    {
        require_once( __DIR__ . '/../../autoload.php' );
        $em = include( __DIR__ . '/../../em-doctrine2.php' );
        $tool = new SchemaTool( $em );
        $classes = array(
            $em->getClassMetadata( 'Demo\Models\Post' ),
            $em->getClassMetadata( 'Demo\Models\Comment' ),
        );
        $tool->dropSchema( $classes );
        $tool->createSchema( $classes );
    }

    function setUp()
    {
        $this->cm = include( __DIR__ . '/../../cm-doctrine2.php' );
        $this->cm->setClass( 'Demo\Models\Post' );
        $this->cm->setClass( 'Demo\Models\Comment' );
        $this->process = new Process( $this->cm );
        $this->post = $this->getNewPosting();
    }

    /**
     * @return Posting
     */
    function getNewPosting() {
        return new Posting( $this->cm, $this->process );
    }

    function test0()
    {
        $this->assertEquals( 'Cena\Cena\CenaManager', get_class( $this->cm ) );
        $this->assertEquals( 'Demo\Resources\Posting', get_class( $this->post ) );
    }

    /**
     * @test
     */
    function onNew_creates_new_Post_entity()
    {
        // make sure initial post is null.
        $this->assertEquals( null, $this->post->getPost() );
        // make a new post. 
        $this->post->onNew();
        $this->assertEquals( 'Demo\Models\Post', get_class( $this->post->getPost() ) );
    }

    /**
     * @test
     */
    function getNewComment_returns_a_comment_associated_with_the_post()
    {
        // make a new post, then a new comment. 
        $this->post->onNew();
        $comment = $this->post->getNewComment();
        $post    = $comment->getPost();
        $this->assertSame( $this->post->getPost(), $post );
    }

    /**
     * @test
     */
    function onPost_creates_new_post()
    {
        $md_content = 'content:'.md5(uniqid());
        $md_comment = 'comment:'.md5(uniqid());
        $input = array(
            'post.0.1' => array(
                'prop' => array(
                    'title' => 'title:'.md5(uniqid()),
                    'content' => $md_content,
                ),
            ),
            'comment.0.1' => array(
                'prop' => array(
                    'comment' => $md_comment,
                ),
                'link' => array(
                    'post' => 'post.0.1'
                ),
            ),
        );
        $this->post->with( $input );
        $this->post->onPost();
        
        $post_id = $this->post->getPost()->getPostId();
        $this->assertTrue( $post_id > 0 );
        
        // retrieve from database. 
        $this->cm->clear();
        $post = $this->getNewPosting();
        $post->onGet( $post_id );
        
        $this->assertEquals( $post_id, $post->getPost()->getPostId() );
        $comments = $post->getComments();
        $this->assertEquals( 1, count( $comments ) );
        $this->assertEquals( $md_content, $post->getPost()->getContent() );
        $this->assertEquals( $md_comment, $comments[0]->getComment() );
    }

    /**
     * this test does not work, but do not know why... 
     * maybe Doctrine2 requires to relate at owning side. 
     * 
     * @ test
     */
    function onPost_creates_new_post_with_post2comment_link()
    {
        $md_content = 'content:'.md5(uniqid());
        $md_comment = 'comment:'.md5(uniqid());
        $input = array(
            'post.0.1' => array(
                'prop' => array(
                    'title' => 'title:'.md5(uniqid()),
                    'content' => $md_content,
                ),
                'link' => array(
                    'comments' => [ 'comment.0.1' ],
                ),
            ),
            'comment.0.1' => array(
                'prop' => array(
                    'comment' => $md_comment,
                ),
            ),
        );
        $this->post->with( $input );
        $this->post->onPost();

        $post_id = $this->post->getPost()->getPostId();
        $this->assertTrue( $post_id > 0 );

        // retrieve from database. 
        $this->cm->clear();
        $post = $this->getNewPosting();
        $post->onGet( $post_id );

        $this->assertEquals( $post_id, $post->getPost()->getPostId() );
        $comments = $post->getComments();
        $this->assertEquals( 1, count( $comments ) );
        $this->assertEquals( $md_content, $post->getPost()->getContent() );
        $this->assertEquals( $md_comment, $comments[0]->getComment() );
    }

    /**
     * @test
     */
    function onPut_modifies_existing_data()
    {
        // let's save a new post and a comment. 
        $input = array(
            'post.0.1' => array( 'prop' => [
                    'title' => 'title:',
                    'content' => 'content',
            ], ),
            'comment.0.1' => array( 
                'prop' => [ 'comment' => 'comment', ],
                'link' => [ 'post'    => 'post.0.1' ],
            ),
            'comment.0.2' => array(
                'prop' => [ 'comment' => 'comment', ],
                'link' => [ 'post'    => 'post.0.1' ],
            ),
        );
        $this->post->with( $input );
        $this->post->onPost();

        $post_id = $this->post->getPost()->getPostId();
        $this->assertTrue( $post_id > 0 );
        
        // get the post from database. 
        $this->cm->clear();
        $post = $this->getNewPosting();
        $post->onGet( $post_id );

        $this->assertEquals( $post_id, $post->getPost()->getPostId() );
        $comments = $post->getComments();
        $this->assertEquals( 2, count( $comments ) );
        foreach( $comments as $c ) {
            $this->assertEquals( 'comment', $c->getComment() );
        }
        
        // update with the input
        $post_cena_id = 'post.1.' . $post_id;
        $com1_cena_id = 'comment.1.' . $comments[0]->getCommentId();
        $com2_cena_id = 'comment.1.' . $comments[1]->getCommentId();
        $md_content = 'content:'.md5(uniqid());
        $md_comment = 'comment:'.md5(uniqid());
        $input = array(
            $post_cena_id => array(
                'prop' => [ 'content' => $md_content ],
            ),
            $com1_cena_id => array(
                'prop' => [ 'comment' => $md_comment ],
            ),
            $com2_cena_id => array(
                'prop' => [ 'comment' => $md_comment ],
            ),
        );
        
        $post->with( $input );
        $post->onPut( $post_id );

        // get the post from database. 
        $this->cm->clear();
        $post = $this->getNewPosting();
        $post->onGet( $post_id );

        $this->assertEquals( $post_id, $post->getPost()->getPostId() );
        $comments = $post->getComments();
        $this->assertEquals( $md_content, $post->getPost()->getContent() );
        $this->assertEquals( 2, count( $comments ) );
        foreach( $comments as $c ) {
            $this->assertEquals( $md_comment, $c->getComment() );
        }
    }
}