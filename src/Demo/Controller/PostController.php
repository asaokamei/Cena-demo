<?php
namespace Demo\Controller;

use Demo\Legacy\PageController;
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
    public static function factory()
    {
        /** @var self $self */
        $self = parent::factory();
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
        $this->posting->with( $_POST );
        if( $this->posting->onPostComment( $id ) ) {
            header( "Location: post.php?id={$id}" );
            exit;
        }
        $this->view->error( 'failed to post comment' );
        $this->setView();
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