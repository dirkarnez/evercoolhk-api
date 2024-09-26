<?php

use MyApp\Middleware\SessionMiddleware;
use MyApp\Middleware\CorsMiddleware;
use Slim\App;

return function (App $app) {
    /**
     * Add the Slim body parsing middleware to the app middleware stack
     */
    $app->addBodyParsingMiddleware();
    $app->add(SessionMiddleware::class);
    $app->add(CorsMiddleware::class);

    /**
     * To control middleware order
     */
    $app->addRoutingMiddleware();
};