<?php
namespace App\Console\Helper;

use Silex\Application;
use Symfony\Component\Console\Helper\Helper;
use App\Bootstrap\Abstracted as AbstractBootstrap;

class BootstrapHelper extends Helper
{
    /**
     * @var AbstractBootstrap
     */
    protected $bootstrap;

    public function __construct(AbstractBootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    public function getName()
    {
        return 'bootstrap';
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->bootstrap->app();
    }
}