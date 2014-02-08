<?php
namespace CenaDemo\Models;

use Doctrine\ORM\EntityManager;

class Post_BasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    public $em;
    
    public $postClass = 'Demo\Models\Post';

    static function XsetUpBeforeClass()
    {
        require_once( __DIR__ . '/../../autoload.php' );
        $em = include( __DIR__ . '/../../em-doctrine2.php' );
        $tool = new \Doctrine\ORM\Tools\SchemaTool( $em );
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

    function test0()
    {
        $post = new $this->postClass;
        $this->assertEquals( 'Doctrine\ORM\EntityManager', get_class( $this->em ) );
        $this->assertEquals( 'Demo\Models\Post', get_class( $post ) );
    }
}