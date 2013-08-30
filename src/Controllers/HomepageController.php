<?php
namespace Controllers;

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
        $this->twig()->addGlobal('projects', $projectModel->getList());
        $this->twig()->addGlobal('error', $error);
        return $this->twig()->render('homepage/index.twig');
    }
}
