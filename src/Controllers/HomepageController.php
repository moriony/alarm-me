<?php
namespace Controllers;

use Models\Mailer;
use Silex\Application;
use Silex\ControllerCollection;
use App\Controller\AbstractController;

class HomepageController extends AbstractController
{
    /**
     * @var Mailer
     */
    protected $mailer;

    protected function init(ControllerCollection $controllers)
    {
        $controllers->match('/', array($this, 'homepage'))
                    ->bind('homepage');

        $this->mailer = $this->model('mailer');
    }

    public function homepage()
    {
        $error = null;
        try {
            if($this->request()->isMethod('post')) {
                $message = $this->request()->get('message');
                $this->mailer->alarm($message);
                return $this->twig()->render('homepage/success.twig');
            }
        } catch(Mailer\Exception\Basic $e) {
            $error = $e->getMessage();
        }
        $this->twig()->addGlobal('error', $error);
        return $this->twig()->render('homepage/index.twig');
    }
}
