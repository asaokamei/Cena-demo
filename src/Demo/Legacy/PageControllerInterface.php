<?php
namespace Demo\Legacy;

/**
 * Interface PageControllerInterface
 * @package Demo\Legacy
 */
interface PageControllerInterface
{
    /**
     * @param null|string $name
     * @return string
     */
    public function getMethod( $name = null );

    /**
     * @param null|string $method
     * @return PageView
     */
    public function execute( $method = null );
}