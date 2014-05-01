<?php
namespace WScore\Pages;

abstract class ControllerAbstract
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
    //  Dependency Injection point.
    // +----------------------------------------------------------------------+
    /**
     * @param string $name
     * @param object $object
     */
    public function inject( $name, $object )
    {
        $this->$name = $object;
    }

    // +----------------------------------------------------------------------+
    //  C.S.R.F. tokens
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
}