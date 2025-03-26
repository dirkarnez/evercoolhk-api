<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\App;
use \Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Beste\Cache\InMemoryCache;



define ('K_TCPDF_EXTERNAL_CONFIG', true);
define ('K_PATH_IMAGES', __DIR__ . "/uploads/");

ini_set('memory_limit', '-1');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// $containerBuilder = new ContainerBuilder();
// $containerBuilder->addDefinitions(__DIR__ . '/container.php');
// $container = $containerBuilder->build();

// Create Container using PHP-DI
$container = new Container();
$container->set('cache', function () {
    return new InMemoryCache();
});

// Set container to create App with on AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

// $app = $container->get(App::class);
// $app->setBasePath('/api');


// if (str_contains($_SERVER['SERVER_NAME'], 'evercoolhk.com')) { 
//     $app->setBasePath('/api');
// }

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// This CORS middleware will append the response header
// Access-Control-Allow-Methods with all allowed methods
$app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app): ResponseInterface {
    if ($request->getMethod() === 'OPTIONS') {
        $response = $app->getResponseFactory()->createResponse();
    } else {
        $response = $handler->handle($request);
    }

    $response = $response
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->withHeader('Pragma', 'no-cache');

    if (ob_get_contents()) {
        ob_clean();
    }

    return $response;
});

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
    'host'    => $_ENV['DB_HOST'],
    'port'    => $_ENV['DB_PORT'],
    'database'=> $_ENV['DB_DATABASE'],
    'username'=> $_ENV['DB_USER_NAME'],
    'password'=> $_ENV['DB_PASSWORD'],
    'charset' => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
    'prefix'  => ''
]);

$capsule->bootEloquent();
$capsule->setAsGlobal();


// (require __DIR__ . '/routes.php')($app);

// (require __DIR__ . '/middleware.php')($app);

return $app;
