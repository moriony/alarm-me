<?php
namespace App\Model;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ModelServiceProvider implements ServiceProviderInterface
{
    const GET_MODEL = 'get_model';
    const MODELS_REGISTRY = 'models.registry';
    const MODELS_OPTIONS = 'models.options';
    const OPT_NAMESPACE = 'namespace';

    /**
     * @todo Refactor
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app[self::MODELS_OPTIONS] = array(
            ModelServiceProvider::OPT_NAMESPACE => '\Models',
        );

        $app[ModelServiceProvider::MODELS_REGISTRY] = array();

        $app[self::GET_MODEL] = $app->protect(function($name) use ($app) {
            $name = strtolower($name);
            $registry = $app[ModelServiceProvider::MODELS_REGISTRY];
            if(!isset($registry[$name])) {
                $options = $app[ModelServiceProvider::MODELS_OPTIONS];
                $namespace = $options[ModelServiceProvider::OPT_NAMESPACE];
                $class = sprintf('%s\\%s', $namespace, $name);
                $registry[$name] = new $class($app);
                $registry[$name]->init();
            }
            return $registry[$name];
        });
    }

    public function boot(Application $app)
    {}
}