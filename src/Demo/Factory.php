<?php
namespace Demo;

use Cena\Cena\CenaManager;
use Cena\Cena\Utils\HtmlForms;
use Cena\Doctrine2\Factory as Dc2Factory;
use Cena\Cena\Factory as CenaFactory;
use Demo\Resources\CommentValidator;
use Demo\Resources\Posting;
use Demo\Resources\PostValidator;
use Doctrine\ORM\EntityManager;

class Factory
{
    /**
     * @var Posting
     */
    public static $posting;
    
    /**
     * @var CenaManager
     */
    public static $cm;

    /**
     * @var EntityManager
     */
    public static $em;

    /**
     * @return Posting
     */
    public static function getPosting()
    {
        if( !self::$posting ) {
            self::$posting = self::buildPosting();
        }
        return self::$posting;
    }

    /**
     * @return Posting
     */
    public static function buildPosting()
    {
        $posting = new Posting(
            self::getCenaManager(),
            CenaFactory::getProcess()
        );
        return $posting;               
    }

    /**
     * @return HtmlForms
     */
    public static function getHtmlForms()
    {
        return CenaFactory::getHtmlForms();
    }

    /**
     * @return CenaManager
     */
    public static function getCenaManager()
    {
        if( !self::$cm ) {
            self::$cm = self::buildCenaManager();
        }
        return self::$cm;
    }

    /**
     * create cm (CenaManager) for doctrine2 ema.
     * @return mixed
     */
    public static function buildCenaManager()
    {
        $em  = self::getEntityManager();
        $ema = Dc2Factory::getEmaDoctrine2( $em );
        $cm  = CenaFactory::getCenaManager( $ema );

        $cm->setClass( 'Demo\Models\Post' );
        $cm->setClass( 'Demo\Models\Comment' );
        $cm->setClass( 'Demo\Models\Tag' );

        $cm->setValidator( 'Demo\Models\Post',    new PostValidator() );
        $cm->setValidator( 'Demo\Models\Comment', new CommentValidator() );

        return $cm;
    }

    /**
     * @return EntityManager
     */
    public static function getEntityManager()
    {
        if( !self::$em ) {
            self::$em = self::buildEntityManager();
        }
        return self::$em;
    }

    /**
     * boot EntityManager for Doctrine2.
     * Create a simple "default" Doctrine ORM configuration for Annotations
     *
     * @return EntityManager
     */
    public static function buildEntityManager()
    {
        $paths = array(
            __DIR__ ."/Models"
        );
        $dbParams = include( dirname(dirname(__DIR__)) . '/config/dbParam.php' );

        return Dc2Factory::getEntityManager($dbParams, $paths);
    }
}