<?php

namespace App\Provider;

use App\Model\Repository;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Moriony\Instantiator\Factory;
use Moriony\Instantiator\ClassName\Decorator\NamespaceDecorator;
use Moriony\Instantiator\Constructor\Base as BaseConstructor;

class ModelsRepositoryProvider implements ServiceProviderInterface
{
    const MODELS_REPOSITORY = 'models_repository';
    const MODELS_FACTORY = 'models_repository.factory';
    const OPTIONS = 'models_repository.options';
    const OPTION_NAMESPACE = 'namespace';

    public function register(Application $app)
    {
        $app[self::MODELS_FACTORY] = $app->share(function() use($app) {
            $options = $app[ModelsRepositoryProvider::OPTIONS];
            $constructor = new BaseConstructor;
            $decorator = new NamespaceDecorator($options[ModelsRepositoryProvider::OPTION_NAMESPACE]);
            return new Factory($constructor, $decorator);
        });

        $app[self::MODELS_REPOSITORY] = $app->share(function() use($app) {
            $factory = $app[ModelsRepositoryProvider::MODELS_FACTORY];
            return new Repository($factory, $app);
        });
    }

    public function boot(Application $app)
    {}
}