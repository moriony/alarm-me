<?php
namespace App\Bootstrap;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

abstract class Abstracted
{
    /**
     * @var Application
     */
    protected $app;

    final public function __construct(array $values = array())
    {
        $this->app = new Application($values);
    }

    /**
     * @return Application
     */
    final public function app()
    {
        return $this->app;
    }

    /**
     * @desc Register application services here
     * @return mixed
     */
    abstract protected function register();

    /**
     * @desc Application runner
     */
    final public function run()
    {
        $this->app->run();
    }

    public function boot()
    {
        $this->app->boot();
    }
}