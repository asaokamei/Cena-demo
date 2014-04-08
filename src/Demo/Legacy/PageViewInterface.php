<?php
/**
 * Created by PhpStorm.
 * User: asao
 * Date: 2014/04/08
 * Time: 16:32
 */
namespace Demo\Legacy;

/**
 * Interface PageViewInterface
 *
 * @package Demo\Legacy
 */
interface PageViewInterface
{
    /**
     * @return bool
     */
    function isCritical();

    /**
     * @return bool
     */
    function isError();

    /**
     * @param bool $tag
     * @return null|string
     */
    function getMethod( $tag = true );

    /**
     * @param string $message
     */
    function critical( $message );

    /**
     * Offset to set
     */
    public function offsetSet( $offset, $value );

    /**
     * @param string $message
     */
    function message( $message );

    /**
     * Offset to retrieve
     */
    public function offsetGet( $offset );

    /**
     * @param $key
     * @return array|null
     */
    function collection( $key );

    /**
     * Whether a offset exists
     */
    public function offsetExists( $offset );

    /**
     * @return string
     */
    function alert();

    /**
     * @param $key
     * @param $value
     */
    function set( $key, $value );

    /**
     * @param string $message
     */
    function error( $message );

    /**
     * @param $key
     * @return null
     */
    function get( $key );

    /**
     * Offset to unset
     */
    public function offsetUnset( $offset );
}