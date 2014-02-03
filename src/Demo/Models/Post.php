<?php
namespace Demo\Models;

use Michelf\MarkdownExtra;

/**
 * Class Post
 * @package Demo\Models
 *
 * @Entity
 * @Table(name="post")
 * @HasLifecycleCallbacks
 */
class Post extends PostDto
{
    /**
     * returns html from markdown text.
     *
     * @return string
     */
    public function getContentHtml()
    {
        return MarkdownExtra::defaultTransform( $this->content );
    }
}