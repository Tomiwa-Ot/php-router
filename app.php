<?php

require __DIR__ . '/routes.php';

/**
 *  Defines routes below
 */

$route->get('', function() {
    Index::get();
});

$route->submit();