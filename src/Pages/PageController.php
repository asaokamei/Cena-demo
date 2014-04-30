<?php
namespace WScore\Pages;

/**
 * A simple page-based controller.
 * should have done a lot more, such as...
 * - add CSRF token,
 * - use Request class.
 *
 * Class PageController
 * @package Demo\Legacy
 */
class PageController
{
    /**
     * @var PageView
     */
    protected $view;

    /**
     * @param PageView $view
     */
    public function __construct( $view )
    {
        $this->view = $view;
    }

    /**
     * @return static
     */
    public static function factory()
    {
        return new static(
            new PageView()
        );
    }

    /**
     * @param $string
     * @return null|string
     */
    protected function safeString( $string )
    {
        if( !mb_check_encoding( $string, 'UTF-8' ) ) {
            return null;
        }
        return $string;
    }

    /**
     * @param $code
     * @return null|string
     */
    protected function safeCode( $code )
    {
        $code = $this->safeString( $code );
        if( preg_match( '/^[-_0-9a-zA-Z]*$/', $code ) ) {
            return $code;
        }
        return null;
    }

    /**
     * @param string $url
     */
    protected function location( $url )
    {
        header( "Location: {$url}" );
        exit;
    }

    /**
     * @param null|string $name
     * @return string
     */
    public function getMethod( $name=null )
    {
        if( $name && isset( $_REQUEST[$name ] ) ) {
            $method = $this->safeCode( $_REQUEST[$name ] );
        }
        elseif( isset( $_REQUEST['_method' ] ) ) {
            $method = $this->safeCode( $_REQUEST['_method' ] );
        } else {
            $method = $_SERVER['REQUEST_METHOD'];
        }
        $method = strtolower( $method );
        return $this->safeCode( $method );
    }

    /**
     * @param $execMethod
     * @return array
     */
    protected function execMethod( $execMethod )
    {
        $ref  = new \ReflectionMethod( $this, $execMethod );
        $args = $ref->getParameters();
        $list = array();
        foreach( $args as $arg ) {
            $key  = $arg->getPosition();
            $name = $arg->getName();
            $val  = isset($_REQUEST[$name]) ? $_REQUEST[$name]: $arg->getDefaultValue();
            $list[$key] = $val;
        }
        $ref->setAccessible(true);
        $ref->invokeArgs( $this, $list );
    }

    /**
     * @param null|string $method
     * @return PageView
     */
    public function execute( $method=null )
    {
        $method = $this->getMethod( $method );
        $execMethod = 'on' . ucwords( $method );

        try {

            if( !method_exists( $this, $execMethod ) ) {
                throw new \RuntimeException( 'no method: ' . $method );
            }
            $this->execMethod( $execMethod );

        } catch( \Exception $e ) {
            $this->view->critical( $e->getMessage() );
        }
        return $this->view;
    }
}