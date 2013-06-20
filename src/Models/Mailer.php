<?php
namespace Models;

use App\Model\AbstractModel;
use Models\Mailer\Exception\InvalidText;
use Models\Mailer\Exception\Undelivered;
use Symfony\Component\Validator\Constraints;

class Mailer extends AbstractModel
{
    protected static $ALARM_TITLE = "Alarm! Something went wrong!";

    protected static $INVALID_TEXT_MESSAGE = "Сообщение не должно быть пустым, максимальная длина %d символов";

    protected $min = 1;
    protected $max = 150;

    /**
     * @param string $text
     * @throws Mailer\Exception\Undelivered
     * @throws Mailer\Exception\InvalidText
     */
    public function alarm($text)
    {
        $constraints =  array(
            new Constraints\NotBlank,
            new Constraints\Length(array('min' => $this->min, 'max' => $this->max))
        );
        $errors = $this->validator()->validateValue($text, $constraints);
        if(count($errors)) {
            throw new InvalidText(sprintf(self::$INVALID_TEXT_MESSAGE, $this->max));
        }

        $transport = new \Swift_Transport_SimpleMailInvoker();
        foreach($this->site('email_list') as $email) {
            $transport->mail($email, self::$ALARM_TITLE, $text, sprintf('From: %s', $this->site('noreply_email')));
        }
    }
}