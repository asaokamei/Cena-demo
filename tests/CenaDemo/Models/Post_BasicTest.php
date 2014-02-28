<?php
namespace CenaDemo\Models;

use CenaDemo\Entity\Comment;
use CenaDemo\Entity\Post;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class Post_BasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    public $em;
    
    public $postClass = 'CenaDemo\Entity\Post';

    static function setUpBeforeClass()
    {
        require_once( __DIR__ . '/../../autoload.php' );
        $em = include( __DIR__ . '/../../em-doctrine2.php' );
        $tool = new SchemaTool( $em );
        $classes = array(
            $em->getClassMetadata( 'CenaDemo\Entity\Post' ),
            $em->getClassMetadata( 'CenaDemo\Entity\Comment' ),
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
        $post    = new \CenaDemo\Entity\Post();
        $content = 'content:'.md5(uniqid());
        $title   = 'title:'.md5(uniqid() );
        $post->setContent( $content );
        $post->setTitle( $title );
        return $post;
    }
    
    function makeNewComment( $post=null )
    {
        $comment = new \CenaDemo\Entity\Comment();
        $comment->setComment( 'comment:'.md5(uniqid()) );
        if( $post ) {
            $comment->setPost( $post );
        }
        return $comment;
    }

    function test0()
    {
        $post = new $this->postClass;
        $this->assertEquals( 'Doctrine\ORM\EntityManager', get_class( $this->em ) );
        $this->assertEquals( 'CenaDemo\Entity\Post', get_class( $post ) );
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
        $post2 = $this->em->find( 'CenaDemo\Entity\Post', $id );

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
        $com1 = $this->makeNewComment( $post );
        $com2 = $this->makeNewComment( $post );
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
        $post2 = $this->em->find( 'CenaDemo\Entity\Post', $id );

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