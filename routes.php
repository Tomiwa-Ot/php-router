<?php

require_once __DIR__ . '/vendor/autoload.php';

use Grep\Library\Config;
use Grep\Library\Router;

foreach (glob(__DIR__ . '/Controller/*.php') as $file) {
    if ($file === __DIR__ . '/Controller/BaseController.php') continue;
    require_once $file;
}

error_reporting(E_ALL);

ini_set('display_errors', Config::getEnvProperties('display_error'));

/** @var object $route Router class instance */
$route = new Router();
