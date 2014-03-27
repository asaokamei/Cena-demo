<?php
namespace CenaDemo\Entity;

use Michelf\MarkdownExtra;

/**
 * Class Post
 * @package CenaDemo\Entity
 *
 * @Entity
 * @Table(name="test_post")
 */
class Post extends PostDto
{
    const STATUS_PREVIEW = '1';
    const STATUS_PUBLIC  = '5';
    const STATUS_HIDE    = '9';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->status = self::STATUS_PREVIEW;
    }

    /**
     * returns html from markdown text.
     *
     * @return string
     */
    public function getContentHtml()
    {
        return MarkdownExtra::defaultTransform( $this->content );
    }

    /**
     *
     */
    public function goPublic()
    {
        $this->status = self::STATUS_PUBLIC;
    }

    /**
     *
     */
    public function hide()
    {
        $this->status = self::STATUS_HIDE;
    }

    /**
     *
     */
    public function preview()
    {
        $this->status = self::STATUS_PREVIEW;
    }
}