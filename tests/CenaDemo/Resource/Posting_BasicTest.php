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
        $process = new Process();
        $this->post = new Posting( $this->cm, $process );
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
}