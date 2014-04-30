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
     * @var PageRequest
     */
    protected $request;

    /**
     * @var PageSession
     */
    protected $session;

    // +----------------------------------------------------------------------+
    //  construction of controller
    // +----------------------------------------------------------------------+
    /**
     * @param PageRequest $req
     * @param PageView    $view
     * @param null|PageSession  $session
     */
    public function __construct( $req, $view, $session=null )
    {
        $this->request = $req;
        $this->view = $view;
        $this->session = $session;
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        return new static(
            new PageRequest(),
            new PageView(),
            PageSession::getInstance()
        );
    }

    // +----------------------------------------------------------------------+
    //  other helpers.
    // +----------------------------------------------------------------------+
    /**
     * @param string $url
     */
    protected function location( $url )
    {
        header( "Location: {$url}" );
        exit;
    }

    // +----------------------------------------------------------------------+
    //  csrf tokens
    // +----------------------------------------------------------------------+
    /**
     * 
     */
    protected function pushToken()
    {
        $token = $this->session->pushToken();
        $this->view->set( PageSession::TOKEN_ID, $token );
    }

    /**
     * @return bool
     */
    protected function verifyToken()
    {
        $token = $this->request->getCode( PageSession::TOKEN_ID );
        if( !$this->session->verifyToken( $token ) ) {
            return false;
        }
        return true;
    }

    // +----------------------------------------------------------------------+
    //  flash messages
    // +----------------------------------------------------------------------+
    /**
     * @param $message
     */
    protected function flashMessage( $message )
    {
        $this->session->flash( 'flash-message', $message );
        $this->session->flash( 'flash-error',   false );
    }

    /**
     * @param $message
     */
    protected function flashError( $message )
    {
        $this->session->flash( 'flash-message', $message );
        $this->session->flash( 'flash-error',   true );
    }

    /**
     * 
     */
    protected function setFlashMessage()
    {
        if( $message = $this->session->get('flash-message') ) {
            if( $this->session->get('flash-error') ) {
                $this->view->error($message);
            } else {
                $this->view->message($message);
            }
        }
    }

    // +----------------------------------------------------------------------+
    //  execution of controllers. 
    // +----------------------------------------------------------------------+
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
            $opt  = $arg->isOptional() ? $arg->getDefaultValue() : null;
            $val  = $this->request->get( $name, $opt );
            $list[$key] = $val;
        }
        $ref->setAccessible(true);
        $ref->invokeArgs( $this, $list );
    }

    /**
     * @param null|string $method
     * @return PageView
     */
    public function execute( $method='_method' )
    {
        $method = $this->request->getMethod( $method );
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
    // +----------------------------------------------------------------------+
}