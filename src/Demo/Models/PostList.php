<?php
namespace Demo\Models;

/**
 * Class PostList
 *
 * @package Demo\Models
 *          
 * @Entity
 * @ReadOnly
 * @Table(name="post_view")
 *          
 */
class PostList extends PostDto
{
    /**
     * @var int
     * @Column(type="integer")
     */
    protected $count_comments;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $tags_list;

    /**
     * @return int
     */
    public function getCountComments()
    {
        return $this->count_comments;
    }

    /**
     * @return string
     */
    public function getTagsList()
    {
        return $this->tags_list;
    }
}