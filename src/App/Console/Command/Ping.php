<?php
namespace App\Console\Command;

use App\Model\Repository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class Ping extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('ping')
            ->setDefinition(array(
                new InputOption('notify', null, InputOption::VALUE_NONE, 'Notify developers if any project is down')))
            ->setDescription('Check servers status');
        ;
    }

    /**
     * @return Repository
     */
    protected function getModelsRepository()
    {
        $app = $this->getHelper('bootstrap')->getApplication();
        return $app['models_repository'];
    }

    /**
     * @return string
     */
    protected function getNotification()
    {
        $app = $this->getHelper('bootstrap')->getApplication();
        return $app['site']['notification'];
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $onFail = null;

        $projectModel = $this->getModelsRepository()->getProject();

        $result = array();
        foreach ($projectModel->getProjects() as $name => $project) {
            $ping = $projectModel->getPing($name);
            $loadTime = $projectModel->getLoadTime($name);
            $status = $projectModel->getStatus($name);
            $result[] = array(
                'host' => $project['host'],
                'ping' => $ping,
                'loadtime' => $loadTime,
                'status' => $status,
            );
            if ($input->getOption('notify') && !$status) {
                $this->getModelsRepository()->getNotifier()->alarm($name, sprintf($this->getNotification(), $name, date(DATE_ATOM)));
            }
        }
        $this->printAnswer($input, $output, $result);
    }

    protected function printAnswer(InputInterface $input, OutputInterface $output, array $projects)
    {
        if ($projects) {
            $output->writeln("host\tping\tloadtime\tstatus");
            $output->writeln("---------------------------------------------------------------");
            foreach($projects as $project) {
                $ping = self::preparePingSting($project['ping']);
                $loadTime = self::prepareLoadTimeSting($project['loadtime']);
                $status = self::prepareStatusString($project['status']);
                $output->writeln(sprintf("%s\t%s\t%s\t%s", $project['host'], $ping, $loadTime, $status));
            }
        } else {
            $output->writeln("Sites not found");
        }
    }

    protected static function preparePingSting($ping)
    {
        return $ping ?: '<fg=red>fail</fg=red>';
    }

    protected static function prepareLoadTimeSting($loadTime)
    {
        return $loadTime ?: '<fg=red>fail</fg=red>';
    }

    protected static function prepareStatusString($status)
    {
        return $status ? '<fg=white;bg=green> working </fg=white;bg=green>' : '<fg=white;bg=red> down </fg=white;bg=red>';
    }
}