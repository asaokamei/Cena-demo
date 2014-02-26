<?php
namespace Demo\Resources;

use Cena\Cena\Process;
use Cena\Cena\CenaManager;
use Demo\Models\Comment;
use Demo\Models\Post;
use Demo\Models\Tag;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var CenaManager
     */
    protected $cm;

    /**
     * @var Process
     */
    protected $process;
    // +----------------------------------------------------------------------+
    //  managing object.
    // +----------------------------------------------------------------------+
    /**
     * @param CenaManager   $cm
     * @param Process       $process
     */
    public function __construct( $cm, $process )
    {
        $this->cm = $cm;
        $this->process = $process;
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
     * get an existing Post data. 
     * 
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
            $this->post = $this->cm->getEntity( 'Post', $id );
            if( !$this->post ) {
                throw new \RuntimeException( 'Cannot find Post #'.$id );
            }
        }
        return $this;
    }

    /**
     * create a new Post. 
     * 
     * @return $this
     */
    public function onNew()
    {
        if( !$this->post ) {
            $this->post = $this->cm->newEntity( 'Post' );
        }
        return $this;
    }

    /**
     * modify an existing post and save to db. 
     * 
     * @param int $id
     * @return $this
     */
    public function onPut( $id )
    {
        $this->onGet( $id );
        $this->process->setSource( $this->data )
            ->cleanNew( 'tag', 'tag' )
            ->cleanNew( 'comment', 'comment' );
        $this->process->process();
        $this->cm->save();
        return $this;
    }

    /**
     * create a new post and save to db. 
     * 
     * @return $this
     */
    public function onPost()
    {
        $this->onNew();
        $this->process->setSource( $this->data )
            ->cleanNew( 'tag', 'tag' )
            ->cleanNew( 'comment', 'comment' );
        $this->process->process();
        $this->cm->save();
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function onDel( $id )
    {
        $this->onGet( $id );
        $this->cm->delEntity( $this->post );
        $this->cm->save();
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
     * @return Comment[]|ArrayCollection
     */
    public function getComments()
    {
        return $this->post->getComments();
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->post->getTags();
    }

    /**
     * @return Comment
     */
    public function getNewComment()
    {
        $comment = new Comment();
        $comment->setPost( $this->post );
        $this->post->addComment( $comment );
        return $comment;
    }

    /**
     * @return Tag
     */
    public function getNewTag()
    {
        $tag = new Tag();
        $this->post->addTag( $tag );
        return $tag;
    }
    // +----------------------------------------------------------------------+
}
