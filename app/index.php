<?php

/*
(require __DIR__ . '/config/bootstrap.php')->run();*/


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Money\Money; // composer require moneyphp/money:^4.5.0
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

if (strcmp($_SERVER['HTTP_HOST'], "evercoolhk.com") === 0) {
  $app->setBasePath('/api');
}

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write((string)json_encode($_SERVER['HTTP_HOST'], JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});


$app->get('/names/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];

    $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    $numberFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 1);
    $numberFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 1);
    
    $moneyFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());
    
    $names = array("Peter", "Paul", "Mary", $moneyFormatter->format(Money::HKD(1000)->divide(6)), Money::HKD(1000)->divide(6)); // in cents
    array_push($names, $name);

    sort($names);

    $response->getBody()->write((string)json_encode($names, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->run();

?>
