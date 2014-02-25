<?php
namespace Demo\Models;

/**
 * Class Tag
 * @package Demo\Models
 *
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="tag")
 */
class Tag
{
    /**
     * @var int
     * @id @GeneratedValue
     * @Column(type="integer")
     */
    protected $tag_id;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $tag;

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
     *
     */
    public function __construct()
    {
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