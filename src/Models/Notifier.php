<?php
namespace Models;

use App\Model\AbstractModel;
use Models\Notifier\Exception\InvalidText;
use Symfony\Component\Validator\Constraints;

class Notifier extends AbstractModel
{
    protected static $ALARM_TITLE = "Внимание! С проектом %s случилась беда!";
    protected static $INVALID_TEXT_MESSAGE = "Сообщение не должно быть пустым, максимальная длина %d символов";

    protected $min = 1;
    protected $max = 150;

    /**
     * @param string $project
     * @param string $text
     * @throws InvalidText
     */
    public function alarm($project, $text)
    {
        /**
         * @var Project $projectModel
         */
        $projectModel = $this->getModelsRepository()->getProject();

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

        $phoneList = $projectModel->getPhoneList($project);
        $a1sms = $this->a1sms();
        foreach($phoneList as $phone) {
            $a1sms->send($phone, sprintf(self::$ALARM_TITLE, $project));
        }
    }
}