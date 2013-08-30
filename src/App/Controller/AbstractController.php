<?php
namespace App\Controller;

use App\Model\Repository;
use App\Provider\ModelsRepositoryProvider;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param ControllerCollection $controllers
     * @return void
     */
    abstract protected function init(ControllerCollection $controllers);

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $this->app = $app;
        $controllerCollection = $this->app['controllers_factory'];
        $this->init($controllerCollection);
        return $controllerCollection;
    }

    /**
     * @return Request
     */
    protected function request()
    {
        return $this->app['request'];
    }

    /**
     * @return \Twig_Environment
     */
    protected function twig()
    {
        return $this->app['twig'];
    }

    /**
     * @return Session
     */
    protected function session()
    {
        return $this->app['session'];
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
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     */
    protected function redirect($url, $status = 302)
    {
        return $this->app->redirect($url, $status);
    }

    /**
     * @param string $text
     * @param int $flags
     * @param string $charset
     * @param bool $doubleEncode
     * @return string
     */
    protected function escape($text, $flags = ENT_COMPAT, $charset = null, $doubleEncode = true)
    {
        return $this->app->escape($text, $flags, $charset, $doubleEncode);
    }

    /**
     * @return Repository
     */
    protected function getModelsRepository()
    {
        return $this->app[ModelsRepositoryProvider::MODELS_REPOSITORY];
    }

    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    protected function response($data = array(), $status = 200, $headers = array())
    {
        return $this->app->json($data , $status, $headers);
    }
}
