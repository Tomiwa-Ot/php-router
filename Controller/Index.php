<?php

require_once __DIR__ . '/BaseController.php';

class Index extends BaseController
{
    public static function get()
    {
        // json('id', 200);
        // xml('id', 200);
        render('index.php', array('title' => 'Index Page'));
    }
}