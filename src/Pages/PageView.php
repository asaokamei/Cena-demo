<?php
namespace WScore\Pages;

/**
 * manages the state of a page (i.e. view).
 *
 * Class PageView
 */
class PageView implements \ArrayAccess
{
    const ERROR    = '400';
    const CRITICAL = '500';

    protected $error   = false;
    protected $message = '';
    protected $data    = array();

    // +----------------------------------------------------------------------+
    //  managing variables
    // +----------------------------------------------------------------------+
    /**
     * @param $key
     * @param $value
     */
    function set( $key, $value )
    {
        $this->data[ $key ] = $value;
    }

    /**
     * @param $key
     * @return null
     */
    function get( $key )
    {
        if( isset( $this->data[$key] ) ) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @return string
     */
    function getHidden( $key )
    {
        if( $value = $this->get( $key ) ) {
            return "<input type=\"hidden\" name=\"{$key}\" value=\"{$value}\" />";
        }
        return '';
    }

    /**
     * @param $key
     * @return array|null
     */
    function collection( $key )
    {
        $got = $this->get( $key );
        if( !is_array( $got ) ) {
            return array();
        }
        return $got;
    }

    /**
     * @param bool $tag
     * @return null|string
     */
    function getMethod( $tag=true )
    {
        if( !$method = $this->get( '_method' ) ) return null;
        if( $tag ) {
            $method = $this->getHidden( '_method' );
        }
        return $method;
    }

    // +----------------------------------------------------------------------+
    //  managing errors and messages
    // +----------------------------------------------------------------------+
    /**
     * @param string $message
     */
    function message( $message )
    {
        $this->message = $message;
    }

    /**
     * @param string $message
     */
    function error( $message )
    {
        $this->error = self::ERROR;
        $this->message = $message;
    }

    /**
     * @param string $message
     */
    function critical( $message )
    {
        $this->error = self::CRITICAL;
        $this->message = $message;
    }

    /**
     * @return bool
     */
    function isError()
    {
        return $this->error >= self::ERROR;
    }

    /**
     * @return bool
     */
    function isCritical()
    {
        return $this->error >= self::CRITICAL;
    }

    /**
     * @return string
     */
    function alert()
    {
        $html = '';
        if( !$this->message ) return $html;
        if( $this->isError() ) {
            $html .= "<strong>Error:</strong><br/>\n";
            $class = 'alert alert-danger';
        } else {
            $class = 'alert alert-success';
        }
        $html .= $this->message;
        $html  = "<div class=\"{$class}\">{$html}</div>";
        return $html;
    }
    // +----------------------------------------------------------------------+
    /**
     * Whether a offset exists
     */
    public function offsetExists( $offset )
    {
        return array_key_exists( $offset, $this->data );
    }

    /**
     * Offset to retrieve
     */
    public function offsetGet( $offset )
    {
        return array_key_exists( $offset, $this->data ) ? $this->data[$offset] : null;
    }

    /**
     * Offset to set
     */
    public function offsetSet( $offset, $value )
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     */
    public function offsetUnset( $offset )
    {
        if( $this->offsetExists($offset) ) {
            unset( $this->data[$offset] );
        }
    }
}