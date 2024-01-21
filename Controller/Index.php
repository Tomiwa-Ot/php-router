<?php

namespace Grep\Controller;

use function Grep\Library\render;

class Index extends BaseController
{
    public static function get()
    {
        // json('id', 200);
        // xml('id', 200);
        render('index.php', array('title' => 'Index Page'));
    }
}