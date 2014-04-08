<?php
namespace Demo\Controller;

use Demo\Legacy\PageController;
use Demo\Factory as DemoFactory;
use Demo\Resources\Posting;

class PostController extends PageController
{
    /**
     * @param int $id
     * @throws \InvalidArgumentException
     */
    protected function onGet( $id )
    {
        if( !isset( $id ) ) {
            throw new \InvalidArgumentException('please indicate post # to view. ');
        }
        $posting = DemoFactory::getPosting();
        $posting->onGet( $id );
        $posting->getNewComment();
        $this->setView( $posting );
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
        $posting = DemoFactory::getPosting();
        $posting->with( $_POST );
        if( $posting->onPostComment( $id ) ) {
            header( "Location: post.php?id={$id}" );
            exit;
        }
        $this->view->error( 'failed to post comment' );
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
        $this->view['tag_list'] = $posting->getTagList();
        $this->view['form']     = DemoFactory::getHtmlForms();
    }
}