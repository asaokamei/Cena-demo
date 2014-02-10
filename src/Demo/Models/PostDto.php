<?php
namespace Demo\Models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PostDto
 * @package Demo\Models
 *
 */
class PostDto
{
    /**
     * @var int
     * @id @GeneratedValue
     * @Column(type="integer")
     */
    protected $post_id;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $status;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $title;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $content;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var
     * @OneToMany( targetEntity="Demo\Models\Comment", mappedBy="post" )
     * @JoinColumn( name="comment_id", referencedColumnName="comment_id" )
     */
    protected $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle( $title )
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent( $content )
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return CommentDto[] mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param CommentDto[]  $comments
     */
    public function setComments( $comments )
    {
        $this->comments = new ArrayCollection( $comments );
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus( $status )
    {
        $this->status = $status;
    }
}