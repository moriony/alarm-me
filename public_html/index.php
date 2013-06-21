<?php
use App\Bootstrap\Web;

error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
$bootstrap = new Web();
$bootstrap->register();
$bootstrap->run();
