<?php
namespace Models;

use App\Model\AbstractModel;
use Models\Project\Exception\EmailListNotFound;
use Models\Project\Exception\PhoneListNotFound;
use Models\Project\Exception\ProjectNotFound;
use Symfony\Component\Validator\Constraints;

class Project extends AbstractModel
{
    protected static $PROJECT_NOT_FOUND_MESSAGE = "Проект %s не найден";
    protected static $EMAIL_LIST_NOT_FOUND_MESSAGE = "Список рассылки для проекта %s не найден";
    protected static $PHONE_LIST_NOT_FOUND_MESSAGE = "Список телефонных номеров для проекта %s не найден";

    /**
     * @param string $projectName
     * @return array
     * @throws Project\Exception\ProjectNotFound
     */
    public function getProject($projectName)
    {
        $projects = $this->site('projects');
        if(!array_key_exists($projectName, $projects)) {
            throw new ProjectNotFound(sprintf(self::$PROJECT_NOT_FOUND_MESSAGE, $projectName));
        }
        return $projects[$projectName];
    }

    /**
     * @param string $projectName
     * @return mixed
     * @throws Project\Exception\EmailListNotFound
     * @throws Project\Exception\ProjectNotFound
     */
    public function getEmailList($projectName)
    {
        $project = $this->getProject($projectName);
        if(!array_key_exists('email_list', $project)) {
            throw new EmailListNotFound(sprintf(self::$EMAIL_LIST_NOT_FOUND_MESSAGE, $projectName));
        }
        return $project['email_list'];
    }

    /**
     * @param string $projectName
     * @return mixed
     * @throws Project\Exception\PhoneListNotFound
     * @throws Project\Exception\ProjectNotFound
     */
    public function getPhoneList($projectName)
    {
        $project = $this->getProject($projectName);
        if(!array_key_exists('phone_list', $project)) {
            throw new PhoneListNotFound(sprintf(self::$PHONE_LIST_NOT_FOUND_MESSAGE, $projectName));
        }
        return $project['phone_list'];
    }

    /**
     * @return array
     */
    public function getProjects()
    {
        return $this->site('projects');
    }

    /**
     * @return array
     */
    public function getList()
    {
        $projects = $this->site('projects');
        return array_keys($projects);
    }

    public function getPing($projectName)
    {
        $project = $this->getProject($projectName);
        $ping = $this->getModelsRepository()->getSounder()
                     ->ping($project['host'], $project['port']);
        $result = false;
        if ($ping) {
            $result = sprintf('%d ms', $ping);
        }
        return  $result;
    }

    public function getLoadTime($projectName)
    {
        $project = $this->getProject($projectName);
        $loadTime = $this->getModelsRepository()->getSounder()
                         ->getLoadTime($project['load_url']);
        $result = false;
        if ($loadTime) {
            $result = sprintf('%d ms', $loadTime);
        }
        return  $result;
    }

    /**
     * @param string $projectName
     * @return bool
     */
    public function getStatus($projectName)
    {
        $project = $this->getProject($projectName);
        return $this->getModelsRepository()->getSounder()
                    ->isTextOnPage($project['positive_text'], $project['load_url']);
    }
}