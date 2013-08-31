<?php
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use App\Console\Command\Ping;
use App\Console\Helper\BootstrapHelper;
use App\Bootstrap\Base;

error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';

$bootstrap = new Base();
$bootstrap->register();
$bootstrap->boot();

$console = new Application('Sweet Sentinel', '0.1.0');
$console->setHelperSet(new HelperSet(array(
    new BootstrapHelper($bootstrap),
)));
$console->add(new Ping());
$console->run();



