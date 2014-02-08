<?php
namespace Demo\Resources;

use Demo\Models\Comment;
use Demo\Models\Post;
use Doctrine\ORM\EntityManager;

/**
 * Class Posting
 * a resource class for Post and associated Comments. 
 *
 * @package Demo\Resources
 */
class Posting
{
    /**
     * @var Post
     */
    protected $post = null;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var EntityManager
     */
    protected $em;

    // +----------------------------------------------------------------------+
    //  managing object.
    // +----------------------------------------------------------------------+
    /**
     * @param EntityManager $em
     */
    public function __construct( $em )
    {
        $this->em = $em;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function with( $data )
    {
        $this->data = $data;
        return $this;
    }

    // +----------------------------------------------------------------------+
    //  manipulating resource.
    // +----------------------------------------------------------------------+
    /**
     * @param int $id
     * @throws \RuntimeException
     * @return $this
     */
    public function onGet( $id )
    {
        if( !$id ) {
            return $this->onNew();
        }
        if( !$this->post ) {
            $this->post = $this->em->find( 'Demo\Models\Post', $id );
            if( !$this->post ) {
                throw new \RuntimeException( 'Cannot find Post #'.$id );
            }
        }
        return $this;
    }

    /**
     *
     * @return $this
     */
    public function onNew()
    {
        if( !$this->post ) {
            $this->post = new Post();
        }
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function onPut( $id )
    {
        $this->onGet( $id );
        return $this;
    }

    /**
     *
     * @return $this
     */
    public function onPost()
    {
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function onDel( $id )
    {
        $this->onGet( $id );
        $this->em->remove( $this->post );
        return $this;
    }

    // +----------------------------------------------------------------------+
    //  get entities from the resource.
    // +----------------------------------------------------------------------+
    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->post->getComments();
    }
    
    /**
     * @return Comment
     */
    public function getNewComment()
    {
        $comment = new Comment();
        $comment->setPost( $this->post );
        $this->post->setComments( $comment );
        return $comment;
    }
    // +----------------------------------------------------------------------+
}
