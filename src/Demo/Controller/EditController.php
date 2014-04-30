<?php
namespace Demo\Controller;

use WScore\Pages\PageController;
use Demo\Factory as DemoFactory;
use Demo\Resources\Posting;
use Demo\Resources\Tags;

class EditController extends PageController
{
    /**
     * @var Posting
     */
    protected $posting;

    /**
     * @param Posting  $posting
     */
    public function setPosting( $posting )
    {
        $this->posting = $posting;
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        /** @var self $self */
        $self = parent::getInstance();
        $self->setPosting(
            DemoFactory::getPosting()
        );
        return $self;
    }

    /**
     * @param int|null $id
     */
    protected function onGet( $id=null )
    {
        ( $id ) ?
            $this->posting->onGet( $id ) :
            $this->posting->onNew();
        $this->pushToken();
        $this->setFlashMessage();
        $this->setView( $id );
    }

    /**
     * @param int|null $id
     */
    protected function onPost( $id=null )
    {
        $this->posting->with( $_POST );
        if( !$this->verifyToken() ) {
            $this->flashError( 'invalid token.' );
            $this->location( "post.php?id={$id}" );
        }
        elseif( $success = ( $id ) ?
            $this->posting->onPut( $id ) :
            $this->posting->onPost() )
        {
            $id = $this->posting->getPost()->getPostId();
            $this->location( "post.php?id={$id}" );
        }
        $this->view->error( 'failed to process the blog post' );
        $this->pushToken();
        $this->setView( $id );
    }


    /**
     * @param int|null $id
     */
    protected function setView( $id )
    {
        $this->view['title']    = $id ? "Edit Post: #{$id}" : "New Post";
        $this->view['id']       = $this->posting->getPost()->getPostId();
        $this->view['posting']  = $this->posting;

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
