<?php
namespace Demo\Models;

/**
 * Class Comment
 * @package Demo\Models
 *
 *
 * @Entity
 * @Table(name="comment")
 * @HasLifecycleCallbacks
 */
class Comment extends CommentDto
{
    const STATUS_NOT_YET  = '1';
    const STATUS_APPROVED = '2';
    const STATUS_REMOVE   = '9';

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