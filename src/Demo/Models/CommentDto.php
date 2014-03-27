<?php
namespace Demo\Models;

/**
 * Class Comment
 * @package Demo\Models
 *
 * @MappedSuperClass
 * @HasLifecycleCallbacks
 */
class CommentDto
{
    /**
     * @var int
     * @id @GeneratedValue
     * @Column(type="integer")
     */
    protected $comment_id;

    /**
     * @var Post
     * @ManyToOne( targetEntity="Demo\Models\Post", inversedBy="comments" )
     * @JoinColumn( name="post_id", referencedColumnName="post_id", onDelete="CASCADE" )
     */
    protected $post;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $status;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $comment;

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
     * @return int
     */
    public function getCommentId()
    {
        return $this->comment_id;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param PostDto $post
     */
    public function setPost( $post )
    {
        $this->post = $post;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment( $comment )
    {
        $this->comment = $comment;
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

    /**
     * @PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = $this->createdAt;
    }

    /**
     * @PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime('now');
    }
}