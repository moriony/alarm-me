<?php
namespace Models;

use App\Model\AbstractModel;
use Models\Mailer\Exception\InvalidText;
use Symfony\Component\Validator\Constraints;

class Mailer extends AbstractModel
{
    protected static $ALARM_TITLE = "Внимание! С проектом %s случилась беда!";
    protected static $INVALID_TEXT_MESSAGE = "Сообщение не должно быть пустым, максимальная длина %d символов";

    protected $min = 1;
    protected $max = 150;

    /**
     * @param string $project
     * @param string $text
     * @throws Mailer\Exception\InvalidText
     */
    public function alarm($project, $text)
    {
        $projectModel = $this->model('Project');

        $constraints =  array(
            new Constraints\NotBlank,
            new Constraints\Length(array('min' => $this->min, 'max' => $this->max))
        );
        $errors = $this->validator()->validateValue($text, $constraints);
        if(count($errors)) {
            throw new InvalidText(sprintf(self::$INVALID_TEXT_MESSAGE, $this->max));
        }
        $emailList = $projectModel->getEmailList($project);
        $transport = new \Swift_Transport_SimpleMailInvoker();
        foreach($emailList as $email) {
            $transport->mail($email, sprintf(self::$ALARM_TITLE, $project), $text, sprintf('From: %s', $this->site('noreply_email')));
        }
    }
}