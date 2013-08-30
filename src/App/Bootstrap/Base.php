<?php
namespace App\Bootstrap;

use App\Provider\ModelsRepositoryProvider;
use Igorw\Silex\ConfigServiceProvider;
use Moriony\Silex\Provider\A1SmsServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

class Base extends Abstracted
{
    public function register()
    {
        $app = $this->app();

        $env = getenv('APP_ENV') ?: getenv('APP_ENV');
        $suffix = $env ? '.' . $env : null;

        $app->register(new UrlGeneratorServiceProvider);
        $app->register(new SessionServiceProvider);
        $app->register(new TwigServiceProvider);
        $app->register(new SwiftmailerServiceProvider);
        $app->register(new ValidatorServiceProvider);
        $app->register(new A1SmsServiceProvider);
        $app->register(new ModelsRepositoryProvider);
        $app->register(new ConfigServiceProvider(__DIR__ . "/../../../config/application" . $suffix . ".yml"));
        $app->register(new ConfigServiceProvider(__DIR__ . "/../../../config/site.yml"));
    }
}