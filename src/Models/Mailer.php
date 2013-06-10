<?php
namespace Models;

use App\Model\AbstractModel;
use Models\Mailer\Exception\InvalidText;
use Models\Mailer\Exception\Undelivered;
use Symfony\Component\Validator\Constraints;

class Mailer extends AbstractModel
{
    protected static $ALARM_TITLE = "Alarm! Something wrong!";

    protected static $INVALID_TEXT_MESSAGE = "Сообщение не должно быть пустым, максимальная длина %d символов";
    protected static $UNDELIVERED_MESSAGE = "Не удалось доставить сообщение";

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

        /**
         * @var \Swift_Message $message
         */
        $message = $this->mailer()->createMessage();
        $message->setSubject(self::$ALARM_TITLE);
        $message->setTo($this->site('email_list'));
        $message->setFrom($this->site('noreply_email'));
        $message->setBody($text);
        if(!$this->mailer()->send($message)) {
            throw new Undelivered(self::$UNDELIVERED_MESSAGE);
        }
    }
}