<?php

require_once __DIR__ . '/BaseController.php';

class Index extends BaseController
{
    public static function get()
    {
        render('index.php', array('title' => 'Index Page'));
    }
}