<?php
namespace Demo\Controller;

use Demo\Legacy\PageController;
use Demo\Factory as DemoFactory;
use Demo\Resources\Posting;
use Demo\Resources\Tags;

class EditController extends PageController
{
    /**
     * @param int|null $id
     */
    protected function onGet( $id=null )
    {
        $posting = DemoFactory::getPosting();
        ( $id ) ?
            $posting->onGet( $id ) :
            $posting->onNew();
        $this->setView( $id, $posting );
    }

    /**
     * @param int|null $id
     */
    protected function onPost( $id=null )
    {
        $posting = DemoFactory::getPosting();
        $posting->with( $_POST );
        $success = ( $id ) ?
            $posting->onPut( $id ) :
            $posting->onPost();

        if( $success ) {
            $id = $posting->getPost()->getPostId();
            $this->location( "post.php?id={$id}" );
        }
        $this->view->error( 'failed to process the blog post' );
        $this->setView( $id, $posting );
    }


    /**
     * @param int|null $id
     * @param Posting $posting
     */
    protected function setView( $id, $posting )
    {
        $this->view['title']    = $id ? "Edit Post: #{$id}" : "New Post";
        $this->view['id']       = $posting->getPost()->getPostId();
        $this->view['posting']  = $posting;

        /*
         * preparing tags.
         */
        $this->view['tags'] = new Tags();

        /*
         * set up form helper...
         */
        $this->view['form'] = DemoFactory::getHtmlForms();
    }
}
