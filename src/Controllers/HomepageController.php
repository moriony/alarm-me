<?php
namespace Controllers;

use Models\Notifier;
use Models\Project;
use Silex\Application;
use Silex\ControllerCollection;
use App\Controller\AbstractController;

class HomepageController extends AbstractController
{
    /**
     * @var Notifier
     */
    protected $notifier;

    /**
     * @var Project
     */
    protected $project;

    protected function init(ControllerCollection $controllers)
    {
        $controllers->match('/', array($this, 'homepage'))
                    ->bind('homepage');

        $this->notifier = $this->model('Notifier');
        $this->project = $this->model('Project');
    }

    public function homepage()
    {
        $error = null;
        try {
            if($this->request()->isMethod('post')) {
                $message = $this->request()->get('message');
                $project = $this->request()->get('project');
                $this->notifier->alarm($project, $message);
                return $this->twig()->render('homepage/success.twig');
            }
        } catch(Notifier\Exception\Basic $e) {
            $error = $e->getMessage();
        }
        $this->twig()->addGlobal('projects', $this->project->getList());
        $this->twig()->addGlobal('error', $error);
        return $this->twig()->render('homepage/index.twig');
    }
}
