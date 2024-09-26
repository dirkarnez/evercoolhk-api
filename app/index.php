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

use MathPHP\NumericalAnalysis\Interpolation;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

if (strcmp($_SERVER['HTTP_HOST'], "evercoolhk.com") === 0) {
  $app->setBasePath('/api');
}

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


$app->get('/', function (Request $request, Response $response, array $args) {
    // $points = [[0, sqrt(0)], [2, sqrt(2)], [3, sqrt(3)], [4, sqrt(4)]];
    
    //$p = Interpolation\LagrangePolynomial::interpolate($points, 0);                // input as a set of points
    function me($x) {
        return 2 - $x;
    }

    $points = [[0, me(0)], [1, me(1)], [2, me(2)], [3, me(3)]];

    $p = Interpolation\LagrangePolynomial::interpolate($points, 0);
    
    $input = array(2, 2);

    $interpolate_end = 0;
    for ($x = 0; $x < INF; $x += 0.00001) {
        $y = $p($x);
        if ($y <= 0) {
            $interpolate_end = $x;
            break;
        }
    }

    $getDistance = function ($x) use ($input, $p) {
        return sqrt(pow($x - $input[0], 2) + pow($p($x) - $input[1], 2));
    };

    $minDistance = INF;
    $minDistance_x = NAN;
    for ($x = 0; $x < $interpolate_end; $x += 0.00001) {
        $distance = sqrt(pow($x - $input[0], 2) + pow($p($x) - $input[1], 2));
        if ($distance < $minDistance) {
            $minDistance = $distance;
            $minDistance_x = $x;
        }
    }

    $response->getBody()->write((string)json_encode([$minDistance, $minDistance_x, $p($minDistance_x) ], JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);




    // gx = diff fx
    // get x as t when gx = 0
    // hx = diff gx()
    // if (hx) > 0
    // calcualte d



    // $root = RootFinding\BisectionMethod::solve($fx, 0, INF, 0.00001); // Solve for x where f(x) = 0
    
    // sqrt()
    // $q = $p->differentiate();
    // $r = $q->roots();

});

$app->run();
?>
