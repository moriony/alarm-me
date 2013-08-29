<?php
namespace App\Model;

use App\Provider\ModelsRepositoryProvider;
use Moriony\Service\A1Sms\Client as A1SmsClient;
use Moriony\Silex\Provider\A1SmsServiceProvider;
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
     * @return Validator
     */
    protected function validator()
    {
        return $this->app['validator'];
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
     * @return Repository
     */
    protected function getModelsRepository()
    {
        return $this->app[ModelsRepositoryProvider::MODELS_REPOSITORY];
    }

    /**
     * @return \Swift_Mailer
     */
    protected function mailer()
    {
        return $this->app['mailer'];
    }

    /**
     * @return A1SmsClient
     */
    protected function a1sms()
    {
        return $this->app[A1SmsServiceProvider::A1SMS];
    }
}