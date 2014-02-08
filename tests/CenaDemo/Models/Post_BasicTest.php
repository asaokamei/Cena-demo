<?php
namespace CenaDemo\Models;

use Demo\Models\Comment;
use Demo\Models\Post;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class Post_BasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    public $em;
    
    public $postClass = 'Demo\Models\Post';

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
        require_once( __DIR__ . '/../../autoload.php' );
        $em = include( __DIR__ . '/../../em-doctrine2.php' );

        $this->em = $em;
    }

    /**
     * @return Post
     */
    function makeNewPost()
    {
        $post    = new \Demo\Models\Post();
        $content = 'content:'.md5(uniqid());
        $title   = 'title:'.md5(uniqid() );
        $post->setContent( $content );
        $post->setTitle( $title );
        return $post;
    }
    
    function makeNewComment()
    {
        $comment = new \Demo\Models\Comment();
        $comment->setComment( 'comment:'.md5(uniqid()) );
        return $comment;
    }

    function test0()
    {
        $post = new $this->postClass;
        $this->assertEquals( 'Doctrine\ORM\EntityManager', get_class( $this->em ) );
        $this->assertEquals( 'Demo\Models\Post', get_class( $post ) );
    }

    /**
     * @test
     */
    function post_entity_persistence()
    {
        $post = $this->makeNewPost();
        $this->em->persist( $post );
        $this->em->flush();
        
        $this->em->clear();
        $id = $post->getPostId();
        /** @var Post $post2 */
        $post2 = $this->em->find( 'Demo\Models\Post', $id );

        $this->assertNotEquals( $post, $post2 );
        $this->assertEquals( $post->getContent(), $post2->getContent() );
    }

    /**
     * @test
     */
    function comment_entity_persistence_with_association()
    {
        // create a post and associated 2 comments. 
        $post = $this->makeNewPost();
        $com1 = $this->makeNewComment();
        $com2 = $this->makeNewComment();
        $com1->setPost( $post );
        $com2->setPost( $post );
        // and save them. 
        $this->em->persist( $post );
        $this->em->persist( $com1 );
        $this->em->persist( $com2 );
        $this->em->flush();
        // prepare some useful array for testing. 
        /** @var Comment[] $comList */
        $comList = array(
            $com1->getCommentId() => $com1,
            $com2->getCommentId() => $com2,
        );

        // retrieve Post from database. 
        $this->em->clear();
        $id = $post->getPostId();
        /** @var Post $post2 */
        $post2 = $this->em->find( 'Demo\Models\Post', $id );

        // basic test
        $this->assertNotEquals( $post, $post2 );
        $this->assertEquals( $post->getContent(), $post2->getContent() );
        
        // test the comments are equal. 
        $comments = $post2->getComments();
        $this->assertEquals( 2, count( $comments ) );
        foreach( $comments as $comment ) {
            $this->assertEquals( $comList[$comment->getCommentId()]->getComment(), $comment->getComment() );
        }
    }
}