<?php

require __DIR__ . '/routes.php';

/**
 *  Defines routes below
 */

$route->get('/src', function() {
    Index::get();
});

$route->get('/src/user', function() {
    User::get();
});

$route->post('/src/user', function() {
    User::post();
});

$route->get('/src/user/<int:id>/<int:name>/<string : aj>', function() {
    User::get();
});

$route->submit();