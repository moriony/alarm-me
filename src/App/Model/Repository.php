<?php

namespace App\Model;

use Silex\Application;
use Moriony\Instantiator\Factory as InstantiatorFactory;

class Repository
{
    protected $storage = array();
    protected $factory;

    public function __construct(InstantiatorFactory $factory, Application $app)
    {
        $this->factory = $factory;
        $this->app = $app;
    }

    public function has($name)
    {
        $alias = $this->getFactory()->getDecorator()->decorate($name);
        return array_key_exists($alias, $this->storage);
    }

    protected function getFactory()
    {
        return $this->factory;
    }

    public function getModel($modelName)
    {
        $alias = $this->getFactory()->getDecorator()->decorate($modelName);
        if (!$this->has($modelName)) {
            $this->storage[$alias] = $this->getFactory()->create($modelName, array($this->app));
        }
        return $this->storage[$alias];
    }

    /**
     * @return \Models\Notifier
     */
    public function getNotifier()
    {
        return $this->getModel('Notifier');
    }
    /**
     * @return \Models\Project
     */
    public function getProject()
    {
        return $this->getModel('Project');
    }

    /**
     * @return \Models\Sounder
     */
    public function getSounder()
    {
        return $this->getModel('Sounder');
    }
}