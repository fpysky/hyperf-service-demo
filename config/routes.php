<?php

declare(strict_types=1);
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', function () {
    return ['message' => 'hello world!'];
});

Router::get('/favicon.ico', function () {
    return '';
});

Router::addServer('grpc', function () {
    Router::addGroup('/grpc.hi', function () {
        Router::post('/sayHello', 'App\Controller\HiController@sayHello');
    });
    Router::addGroup('/grpc.test', function () {
        Router::post('/tokenTest', \App\Controller\TestController::class . '@tokenTest');
    });
});
