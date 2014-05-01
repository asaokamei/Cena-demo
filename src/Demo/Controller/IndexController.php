<?php
namespace Demo\Controller;

use WScore\Pages\ControllerAbstract;
use Demo\Factory as DemoFactory;

class IndexController extends ControllerAbstract
{
    /**
     * get list of posts.
     */
    public function onGet()
    {
        $em = DemoFactory::getEntityManager();
        $query = $em->createQuery( 'SELECT p FROM Demo\Models\PostList p' );
        $this->view[ 'posts' ] = $query->getResult();
        $this->view[ 'form'  ]  = DemoFactory::getHtmlForms();
    }

    /**
     * create sample blog posts.
     */
    public function onSample()
    {
        include( dirname(dirname(dirname(__DIR__))).'/config/sample-db.php' );
        $this->view->location('index.php');
    }

    /**
     * set up database; drop and create tables.
     */
    public function onSetup()
    {
        include( dirname(dirname(dirname(__DIR__))).'/config/setup-db.php' );
        $this->view->location('index.php');
    }
}