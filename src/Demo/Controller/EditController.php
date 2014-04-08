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
        $this->setView( $posting );
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
        $this->setView( $posting );
    }


    /**
     * @param Posting $posting
     */
    protected function setView( $posting )
    {
        $this->view['id']       = $posting->getPost()->getPostId();
        $this->view['post']     = $posting->getPost();
        $this->view['comments'] = $posting->getComments();

        /*
         * preparing tags.
         */
        $this->view['tags'] = new Tags();
        $this->view['postTag'] = $posting->getTags();

        /*
         * set up form helper...
         */
        $form = DemoFactory::getHtmlForms();
        $form->setEntity( $posting->getPost() );
        $this->view['form']           = $form;
        $this->view['post_form_name'] = $form->getFormName();
        $this->view['post_cena_id']   = $form->getCenaId();
    }
}
