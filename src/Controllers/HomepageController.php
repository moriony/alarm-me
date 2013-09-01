<?php
namespace Controllers;

use App\Model\ModelException;
use Models\Notifier;
use Models\Project;
use Silex\Application;
use Silex\ControllerCollection;
use App\Controller\AbstractController;

class HomepageController extends AbstractController
{
    protected function init(ControllerCollection $controllers)
    {
        $controllers->match('/', array($this, 'homepage'))
                    ->bind('homepage');

        $controllers->match('/ping/', array($this, 'ping'))
                    ->bind('ping');

        $controllers->match('/loadtime/', array($this, 'loadtime'))
                    ->bind('loadtime');

        $controllers->match('/status/', array($this, 'status'))
                    ->bind('status');
    }

    public function homepage()
    {
        $error = null;
        try {
            if($this->request()->isMethod('post')) {
                $message = $this->request()->get('message');
                $project = $this->request()->get('project');
                $this->getModelsRepository()
                     ->getNotifier()
                     ->alarm($project, $message);
                return $this->twig()->render('homepage/success.twig');
            }
        } catch(Notifier\Exception\Basic $e) {
            $error = $e->getMessage();
        }
        $projectModel = $this->getModelsRepository()->getProject();
        $this->twig()->addGlobal('projects', $projectModel->getProjects());
        $this->twig()->addGlobal('error', $error);
        return $this->twig()->render('homepage/index.twig');
    }

    public function ping()
    {
        try {
            $project = $this->request()->get('project');
            return $this->json(array(
                'value' => $this->getModelsRepository()->getProject()->getPing($project)
            ));
        } catch (ModelException $e) {
            return $this->json(array(
                'message' => $e->getMessage()
            ), $e->getCode());
        }
    }

    public function loadtime()
    {
        try {
            $project = $this->request()->get('project');
            return $this->json(array(
                'value' => $this->getModelsRepository()->getProject()->getLoadTime($project)
            ));
        } catch (ModelException $e) {
            return $this->json(array(
                'message' => $e->getMessage()
            ), $e->getCode());
        }
    }

    public function status()
    {
        try {
            $project = $this->request()->get('project');
            return $this->json(array(
                'value' => $this->getModelsRepository()->getProject()->getStatus($project)
            ));
        } catch (ModelException $e) {
            return $this->json(array(
                'message' => $e->getMessage()
            ), $e->getCode());
        }
    }
}
