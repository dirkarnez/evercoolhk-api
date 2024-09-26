<?php

use \Slim\App;
use Slim\Routing\RouteCollectorProxy;
use MyApp\Middleware\UserAuthMiddleware;

return function (App $app) {
    $app->get("/init-db", \MyApp\Controller\SystemController::class);

    $app->post('/login', \MyApp\Controller\AuthController::class . ':login');
    $app->post('/logout', \MyApp\Controller\AuthController::class . ':logout');

    $app->group('/test', function (RouteCollectorProxy $group) {
        $group->get('', \MyApp\Controller\AdministratorController::class . ':test');
    })->add(UserAuthMiddleware::class);
};
