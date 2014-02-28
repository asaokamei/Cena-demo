<?php
namespace Demo\Models;

/**
 * Class Comment
 * @package Demo\Models
 *
 *
 * @Entity
 * @Table(name="test_comment")
 * @HasLifecycleCallbacks
 */
class Comment extends CommentDto
{
    const STATUS_NOT_YET  = '1';
    const STATUS_APPROVED = '2';
    const STATUS_REMOVE   = '9';

    /**
     *
     */
    public function __construct()
    {
        $this->status = self::STATUS_NOT_YET;
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

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * approve the comment.
     */
    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
    }

    /**
     * remove (i.e. hide) this comment.
     */
    public function remove()
    {
        $this->status = self::STATUS_REMOVE;
    }
}