<?php

require_once __DIR__ . '/BaseController.php';

class User extends BaseController
{
    public static function get()
    {
        json(reqVar('id'), 200);
        // render('index.php', array('title' => 'Index Page'));
    }

    public static function post()
    {
        json('post user', 200);
    }
}