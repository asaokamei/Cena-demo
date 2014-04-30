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
     * @param PageRequest $req
     * @param PageView $view
     */
    public function __construct( $req, $view )
    {
        $this->request = $req;
        $this->view = $view;
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        return new static(
            new PageRequest(),
            new PageView()
        );
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
}