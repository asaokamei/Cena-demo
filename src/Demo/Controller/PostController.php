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
        $this->setView();
    }

    /**
     * @param int $id
     * @param array $Cena
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
        }
        elseif( $this->posting->onPostComment( $id ) ) {
            $this->flashMessage( 'added a comment.' );
        } else {
            $this->flashError( 'failed to post comment.' );
        }
        $this->view->location( "post.php?id={$id}" );
    }

    /**
     */
    public function setView()
    {
        $this->view['id']       = $this->posting->getPost()->getPostId();
        $this->view['post']     = $this->posting->getPost();
        $this->view['comments'] = $this->posting->getComments();
        $this->view['tag_list'] = $this->posting->getTagList();
        $this->view['form']     = DemoFactory::getHtmlForms();
    }
}