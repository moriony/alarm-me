<?php
use App\Bootstrap\Web;
require_once __DIR__ . '/../vendor/autoload.php';

$bootstrap = new Web();
$bootstrap->register();
$bootstrap->run();
