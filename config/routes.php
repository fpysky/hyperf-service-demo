<?php

declare(strict_types=1);
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', function () {
    return ['message' => 'hello world!'];
});

Router::get('/favicon.ico', function () {
    return '';
});
