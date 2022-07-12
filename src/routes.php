<?php

require __DIR__ . '/Library/Router.php';

foreach(glob(__DIR__ . '/Controller/*.php') as $file)
{
    if($file === __DIR__ . '/Controller/BaseController.php') continue;
    require $file;
}