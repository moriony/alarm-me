<?php
namespace App\Model;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ModelServiceProvider implements ServiceProviderInterface
{
    const GET_MODEL = 'get_model';
    const MODELS_OPTIONS = 'models.options';
    const MODELS_FACTORY = 'models.factory';

    const OPT_NAMESPACE = 'namespace';

    protected $registry = array();

    /**
     * @todo Необходим рефакторинг построения имени класса модели
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app[self::MODELS_OPTIONS] = array(
            ModelServiceProvider::OPT_NAMESPACE => '\Models',
        );

        $registry = & $this->registry;
        $app[self::GET_MODEL] = $app->protect(function($name) use ($app, $registry) {
            if(!array_key_exists($name, $registry)) {
                $registry[$name] = $app[ModelServiceProvider::MODELS_FACTORY]($name);
            }
            return $registry[$name];
        });

        $app[self::MODELS_FACTORY] = $app->protect(function($name) use($app) {
            $options = $app[ModelServiceProvider::MODELS_OPTIONS];
            $namespace = $options[ModelServiceProvider::OPT_NAMESPACE];
            $class = sprintf('%s\\%s', $namespace, $name);
            $model = new $class($app);
            return $model;
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {}
}