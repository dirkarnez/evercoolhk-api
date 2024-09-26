<?php

use DI\ContainerBuilder;
use Slim\App;
use \Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/container.php');
$container = $containerBuilder->build();

$app = $container->get(App::class);
$app->setBasePath('/api');

$errorMiddleware = $app->addErrorMiddleware(true, true, true, null);
//$errorMiddleware->setDefaultErrorHandler(function () use ($app) {
//    return $app
//    ->getResponseFactory()
//    ->createResponse()
//    ->withHeader('Location',
//        (
//            isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])
//            ?
//            'https'
//            :
//            'http'
//        )
//        .
//        "://"
//        .
//        $_SERVER['HTTP_HOST']
//        .
//        "/not-found"
//    )
//    ->withStatus(302);
//});


$capsule = new Capsule;
$capsule->addConnection([
    'driver'  => 'mysql',
    'host'    => 'localhost',
    'database'=> 'evercoolhk_2020',
    'username'=> 'mysql2020',
    'password'=> "cahxieyooteej",
    'charset' => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
    'prefix'  => ''
]);

$capsule->bootEloquent();
$capsule->setAsGlobal();

(require __DIR__ . '/routes.php')($app);

(require __DIR__ . '/middleware.php')($app);

return $app;
