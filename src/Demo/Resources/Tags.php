<?php
namespace Demo\Resources;

use Cena\Cena\CenaManager;
use Demo\Factory;
use Demo\Models\Tag;
use Doctrine\ORM\EntityManager;

class Tags
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
     *
     */
    public function __construct()
    {
        $this->cm = Factory::getCenaManager();
        $this->em = Factory::getEntityManager();
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
}