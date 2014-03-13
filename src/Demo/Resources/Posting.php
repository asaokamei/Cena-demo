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
            throw new \RuntimeException( 'Post # not set' );
        }
        if( !$this->post ) {
            try {
                $this->post = $this->cm->getEntity( 'Post', $id );
            } catch( \Exception $e ) {
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
     * @return bool
     */
    public function onPut( $id )
    {
        $this->onGet( $id );
        $this->post->setTags( array() ); // set tags empty. 
        $this->process->setSource( $this->data )
            ->cleanNew( 'tag', 'tag' );
        if( $this->process->process() ) {
            $this->cm->save();
            return true;
        }
        return false;
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
            ->cleanNew( 'tag', 'tag' );
        if( $this->process->process() ) {
            $this->cm->save();
            return true;
        }
        return false;
    }

    /**
     * adds a comment to the post #id.
     * probably this resource class needs to be divided into
     * - for admins which has all the access rights,
     * - for users which can only add comments.
     *
     * @param $id     post id.
     * @return bool
     */
    public function onPostComment( $id )
    {
        $this->onGet( $id );
        $this->process
            ->setSource( $this->data )
            ->cleanExcept( 'comment' );
        if( $this->process->process() ) {
            $this->cm->save();
            return true;
        }
        $collection = $this->cm->getCollection();
        $comments   = $collection->findByModel( 'comment' );
        $this->post->getComments();
        $this->post->addComment( $comments[0] );
        return false;
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
     * @return array
     */
    public function getTagList()
    {
        $tags = $this->getTags();
        $tag_list = array();
        foreach( $tags as $t ) {
            $tag_list[] = $t->getTag();
        }
        return $tag_list;
    }

    /**
     * @return Comment
     */
    public function getNewComment()
    {
        $comment = $this->cm->newEntity( 'comment' );
        $comment->setPost( $this->post );
        $this->post->addComment( $comment );
        return $comment;
    }

    /**
     * @return Tag
     */
    public function getNewTag()
    {
        $tag = $this->cm->newEntity( 'tag' );
        $this->post->addTag( $tag );
        return $tag;
    }
    // +----------------------------------------------------------------------+
}
