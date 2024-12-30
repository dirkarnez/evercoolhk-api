<?php

use DI\ContainerBuilder;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\App;
use \Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . '/../vendor/autoload.php';

define ('K_TCPDF_EXTERNAL_CONFIG', true);
define ('K_PATH_IMAGES', __DIR__ . "/uploads/");

ini_set('memory_limit', '-1');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// $containerBuilder = new ContainerBuilder();
// $containerBuilder->addDefinitions(__DIR__ . '/container.php');
// $container = $containerBuilder->build();

// Create Container using PHP-DI
$container = new Container();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

// $app = $container->get(App::class);
// $app->setBasePath('/api');


if (str_contains($_SERVER['SERVER_NAME'], 'evercoolhk.com')) { 
    $app->setBasePath('/api');
}


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
    'host'    => $_ENV['EMAIL_SENDER_ACCOUNT'];,
    'database'=> 'evercoolhk_2020',
    'username'=> 'mysql2020',
    'password'=> "cahxieyooteej",
    'charset' => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
    'prefix'  => ''
]);

$capsule->bootEloquent();
$capsule->setAsGlobal();

// (require __DIR__ . '/routes.php')($app);

// (require __DIR__ . '/middleware.php')($app);

return $app;
