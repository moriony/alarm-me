<?php
namespace App\Model;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ODM\MongoDB\DocumentManager;
use Moriony\Silex\Provider\ZmqSocketProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator;

abstract class AbstractModel
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @desc Override me
     */
    public function init() {}

    /**
     * @return Session
     */
    protected function session()
    {
        return $this->app['session'];
    }

    /**
     * @return CacheProvider
     */
    protected function cache()
    {
        return $this->app['cache'];
    }

    /**
     * @return DocumentManager
     */
    protected function odm()
    {
        return $this->app['doctrine.odm.mongodb.dm'];
    }

    /**
     * @param string $route
     * @param array $parameters
     * @return string
     */
    protected function path($route, $parameters = array())
    {
        return $this->app['url_generator']->generate($route, $parameters, false);
    }

    /**
     * @param string $route
     * @param array $parameters
     * @return string
     */
    protected function url($route, $parameters = array())
    {
        return $this->app['url_generator']->generate($route, $parameters, true);
    }


    /**
     * @return Validator
     */
    protected function validator()
    {
        return $this->app['validator'];
    }

    /**
     * @param string|null $name
     * @return ZmqSocketProvider|\ZMQSocket
     */
    protected function zmqSocket($name = null)
    {
        if(!is_null($name)) {
            return $this->app['zmq.socket'][$name];
        }
        return $this->app['zmq.socket'];
    }

    /**
     * @param string|null $option
     * @return mixed
     */
    protected function site($option = null)
    {
        if(is_null($option)) {
            return $this->app['site'];
        }
        return $this->app['site'][$option];
    }

    /**
     * @param string $name
     * @return AbstractModel
     */
    protected function model($name)
    {
        return $this->app['get_model']($name);
    }

    /**
     * @return \Swift_Mailer
     */
    protected function mailer()
    {
        return $this->app['mailer'];
    }
}