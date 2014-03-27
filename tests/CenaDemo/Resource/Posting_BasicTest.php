<?php
namespace CenaDemo\Resource;

use Cena\Cena\CenaManager;
use Cena\Cena\Process;
use Demo\Resources\Posting;
use Demo\Resources\CommentValidator;
use Doctrine\ORM\EntityManager;
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
        $this->cm->setValidator( 'Demo\Models\Comment', new CommentValidator() );
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

    /**
     * @test
     */
    function onDel_removes_post_and_comment_from_db()
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

        // get the post from database. 
        $this->cm->clear();
        $post = $this->getNewPosting();
        $post->onGet( $post_id );
        
        $comments = $post->getComments();
        $com_id1 = $comments[0]->getCommentId();
        $com_id2 = $comments[1]->getCommentId();
        
        // delete
        $this->cm->clear();
        $post = $this->getNewPosting();
        $post->onDel( $post_id );
        
        // make sure any of the post and comments are in db.
        /** @var EntityManager $em */
        $em = $this->cm->getEntityManager()->em();
        $this->assertEquals( null, $em->find( 'Demo\Models\Post', $post_id ) );
        $this->assertEquals( null, $em->find( 'Demo\Models\Comment', $com_id1 ) );
        $this->assertEquals( null, $em->find( 'Demo\Models\Comment', $com_id2 ) );
    }

    /**
     * @test
     */
    function formName_for_new_objects_returns_cena_id()
    {
        // get a new post data 
        $this->post->onNew();
        $post = $this->post->getPost();
        $this->assertEquals( 'Cena[post][0][1][prop][content]', $this->cm->formName( $post, 'content' ) );
        
        $comment = $this->post->getNewComment();
        $this->assertEquals( 'Cena[comment][0][2][prop][comment]', $this->cm->formName( $comment, 'comment' ) );
    }

    /**
     * @test
     */
    function formName_for_entities_from_database() 
    {
        // clear and save
        $md_content = 'content:'.md5(uniqid());
        $md_comment = 'comment:'.md5(uniqid());
        $input = array(
            'post.0.1' => array( 'prop' => [
                    'title' => 'title:'.md5(uniqid()),
                    'content' => $md_content,
            ],),
            'comment.0.1' => array(
                'prop' => [  'comment' => $md_comment, ],
                'link' => [  'post' => 'post.0.1' ],
            ),
        );
        $this->post->with( $input );
        $this->post->onPost();
        $post_id = $this->post->getPost()->getPostId();

        // 
        $this->cm->clear();
        $posting = $this->getNewPosting();
        $posting->onGet( $post_id );
        
        $post = $posting->getPost();
        $comments = $posting->getComments();
        $comment  = $comments[0];
        $this->assertEquals( "Cena[post][1][{$post->getPostId()}][prop][content]", $this->cm->formName( $post, 'content' ) );
        $this->assertEquals( "Cena[comment][1][{$comment->getCommentId()}][prop][comment]", $this->cm->formName( $comment, 'comment' ) );
    }

    /**
     * @test
     */
    function convert_post_to_cena_id_input()
    {
        $md_content = 'content:'.md5(uniqid());
        $md_comment = 'comment:'.md5(uniqid());
        $post_info  = array('prop' => [
            'title' => $md_content,
            'content' => $md_content,
        ]);
        $comment_info = array(
            'prop' => [  'comment' => $md_comment, ],
            'link' => [  'post' => 'post.0.1' ],
        );
        $post = array( 'Cena' => array(
                'post' => [
                    '0' => [
                        '1' => $post_info
                    ]
                ],
                'comment' => [
                    '0' => [
                        '1' => $comment_info,
                    ]
                ]
        ) );
        $input = array(
            'post.0.1' => array( 'prop' => [
                'title' => $md_content,
                'content' => $md_content,
            ],),
            'comment.0.1' => array(
                'prop' => [  'comment' => $md_comment, ],
                'link' => [  'post' => 'post.0.1' ],
            ),
        );
        $method = new \ReflectionMethod( 'Cena\Cena\Process', 'prepareSource' );
        $method->setAccessible(true);
        $cena =$method->invoke( $this->process, $post );
        $this->assertEquals( $input, $cena );
    }
}