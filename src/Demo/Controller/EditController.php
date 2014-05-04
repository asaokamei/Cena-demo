<?php
namespace Demo\Controller;

use WScore\Pages\ControllerAbstract;
use Demo\Factory as DemoFactory;
use Demo\Resources\Posting;
use Demo\Resources\Tags;

class EditController extends ControllerAbstract
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
        $self = new self();
        $self->setPosting(
            DemoFactory::getPosting()
        );
        return $self;
    }

    /**
     * @param int|null $id
     * @return array
     */
    protected function onGet( $id=null )
    {
        ( $id ) ?
            $this->posting->onGet( $id ) :
            $this->posting->onNew();
        $this->pushToken();
        $this->setFlashMessage();
        return $this->setView( $id );
    }

    /**
     * @param int|null   $id
     * @param null|array $Cena
     * @return array
     */
    protected function onPost( $id=null, $Cena=null )
    {
        $this->posting->with( array('Cena'=>$Cena) );
        if( !$this->verifyToken() ) {
            $this->flashError( 'invalid token.' );
            $this->location( "post.php?id={$id}" );
        }
        elseif( ( $id ) ?
            $this->posting->onPut( $id ) :
            $this->posting->onPost() )
        {
            $id = $this->posting->getPost()->getPostId();
            $this->location( "post.php?id={$id}" );
        }
        $this->error( 'failed to process the blog post' );
        $this->pushToken();
        return $this->setView( $id );
    }


    /**
     * @param int|null $id
     * @return array
     */
    protected function setView( $id )
    {
        return array(
            'title'    => $id ? "Edit Post: #{$id}" : "New Post",
            'id'       => $this->posting->getPost()->getPostId(),
            'posting'  => $this->posting,
    
            /*
             * preparing tags.
             */
            'tags' => new Tags(),
    
            /*
             * set up form helper...
             */
            'form' => DemoFactory::getHtmlForms(),
        );
    }
}
