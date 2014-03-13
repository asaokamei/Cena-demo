<?php
namespace Demo\Resources;

use Cena\Cena\CenaManager;
use Demo\Factory;
use Demo\Models\Tag;
use Doctrine\ORM\EntityManager;
use Traversable;

class Tags implements \IteratorAggregate
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var CenaManager
     */
    protected $cm;

    /**
     * @var Tag[]
     */
    protected $tags;

    /**
     *
     */
    public function __construct()
    {
        $this->cm = Factory::getCenaManager();
        $this->em = Factory::getEntityManager();
        $this->tags = $this->getTags();
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        $qr = $this->em->createQuery( "SELECT p FROM Demo\\Models\\Tag p" );
        return $qr->getResult();
    }

    /**
     * @return Tag
     */
    public function getNewTag()
    {
        return $this->cm->newEntity('tag');
    }

    /**
     * Retrieve an external iterator
     * @return Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator( $this->tags );
    }
}