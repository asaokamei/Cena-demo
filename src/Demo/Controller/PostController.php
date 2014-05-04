<?php
namespace Demo\Controller;

use WScore\Pages\ControllerAbstract;
use Demo\Factory as DemoFactory;
use Demo\Resources\Posting;

class PostController extends ControllerAbstract
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
     * @param int $id
     * @return array
     * @throws \InvalidArgumentException
     */
    public function onGet( $id )
    {
        if( !isset( $id ) ) {
            throw new \InvalidArgumentException('please indicate post # to view. ');
        }
        $this->posting->onGet( $id );
        $this->posting->getNewComment();
        $this->pushToken();
        $this->setFlashMessage();
        return $this->setView();
    }

    /**
     * @param int   $id
     * @param array $Cena
     * @return array
     * @throws \InvalidArgumentException
     */
    public function onPost( $id, $Cena )
    {
        if( !isset( $id ) ) {
            throw new \InvalidArgumentException('please indicate post # to view. ');
        }
        $this->posting->with( array('Cena'=>$Cena) );
        if( !$this->verifyToken() ) {
            $this->flashError( 'invalid token.' );
            $this->view->location( "post.php?id={$id}" );
        }
        elseif( $this->posting->onPostComment( $id ) ) {
            $this->flashMessage( 'added a comment.' );
            $this->view->location( "post.php?id={$id}" );
        }
        $this->view->error( 'failed to post comment.' );
        $this->pushToken();
        return $this->setView();
    }

    /**
     */
    public function setView()
    {
        return array(
            'id'       => $this->posting->getPost()->getPostId(),
            'post'     => $this->posting->getPost(),
            'comments' => $this->posting->getComments(),
            'tag_list' => $this->posting->getTagList(),
            'form'     => DemoFactory::getHtmlForms(),
        );
    }
}