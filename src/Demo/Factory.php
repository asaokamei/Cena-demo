<?php
namespace Demo;

use Cena\Cena\CenaManager;
use Cena\Doctrine2\Factory as Dc2Factory;
use Cena\Cena\Factory as CenaFactory;
use Doctrine\ORM\EntityManager;

class Factory
{
    /**
     * @var CenaManager
     */
    public static $cm;

    /**
     * @var EntityManager
     */
    public static $em;

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