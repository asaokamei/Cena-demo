<?php
namespace Demo\Controller;

use WScore\Pages\PageController;
use Demo\Factory as DemoFactory;

class IndexController extends PageController
{
    /**
     * get list of posts.
     */
    protected function onGet()
    {
        $em = DemoFactory::getEntityManager();
        $query = $em->createQuery( 'SELECT p FROM Demo\Models\PostList p' );
        $this->view[ 'posts' ] = $query->getResult();
        $this->view[ 'form'  ]  = DemoFactory::getHtmlForms();
    }

    /**
     * create sample blog posts.
     */
    protected function onSample()
    {
        include( dirname(dirname(dirname(__DIR__))).'/config/sample-db.php' );
        $this->location('index.php');
    }

    /**
     * set up database; drop and create tables.
     */
    protected function onSetup()
    {
        include( dirname(dirname(dirname(__DIR__))).'/config/setup-db.php' );
        $this->location('index.php');
    }
}