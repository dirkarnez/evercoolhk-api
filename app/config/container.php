<?php

use MyApp\Factory\LoggerFactory;
use MyApp\Handler\DefaultErrorHandler;
use MyApp\Middleware\SessionMiddleware;
use MyApp\Middleware\CorsMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Selective\BasePath\BasePathMiddleware;
use Selective\Validation\Encoder\JsonEncoder;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Selective\Validation\Transformer\ErrorDetailsResultTransformer;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

return [
    'settings' => [
        
    ],
    
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        return AppFactory::create();
    },

    // For the responder
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },

    Session::class => function (ContainerInterface $container) {
//        [
//            "cookie_samesite" => "none",
//            "cookie_secure" => "1",
//            "cookie_samesite" => "Lax"
//        ]
        return new Session(new NativeSessionStorage());
    },

    SessionInterface::class => function (ContainerInterface $container) {
        return $container->get(Session::class);
    },

    SessionMiddleware::class => function (ContainerInterface $container) {
        return new SessionMiddleware($container->get(SessionInterface::class));
    },

    CorsMiddleware::class => function (ContainerInterface $container) {
        return new CorsMiddleware();
    }
];