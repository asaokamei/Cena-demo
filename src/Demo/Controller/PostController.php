<?php
namespace Demo\Controller;

use WScore\Pages\PageController;
use Demo\Factory as DemoFactory;
use Demo\Resources\Posting;

class PostController extends PageController
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
     * @param int $id
     * @throws \InvalidArgumentException
     */
    protected function onGet( $id )
    {
        if( !isset( $id ) ) {
            throw new \InvalidArgumentException('please indicate post # to view. ');
        }
        $this->posting->onGet( $id );
        $this->posting->getNewComment();
        $this->pushToken();
        if( $message = $this->session->get('message') ) {
            if( $this->session->get('error') ) {
                $this->view->error($message);
            } else {
                $this->view->message($message);
            }
        }
        $this->setView();
    }

    /**
     * @param int $id
     * @throws \InvalidArgumentException
     */
    protected function onPost( $id )
    {
        if( !isset( $id ) ) {
            throw new \InvalidArgumentException('please indicate post # to view. ');
        }
        if( !$this->verifyToken() ) {
            $this->session->flash( 'message', 'invalid token.' );
            $this->session->flash( 'error',   'true' );
            header( "Location: post.php?id={$id}" );
            exit;
        }
        $this->posting->with( $_POST );
        if( $this->posting->onPostComment( $id ) ) {
            header( "Location: post.php?id={$id}" );
            exit;
        }
        $this->session->flash( 'message', 'failed to post comment.' );
        $this->session->flash( 'error',   'true' );
        header( "Location: post.php?id={$id}" );
        exit;
    }

    /**
     */
    protected function setView()
    {
        $this->view['id']       = $this->posting->getPost()->getPostId();
        $this->view['post']     = $this->posting->getPost();
        $this->view['comments'] = $this->posting->getComments();
        $this->view['tag_list'] = $this->posting->getTagList();
        $this->view['form']     = DemoFactory::getHtmlForms();
    }
}