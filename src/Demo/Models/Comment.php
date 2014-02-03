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
}