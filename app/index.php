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


// a_x=[1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 4.7,5, 5.5]
// a_y=[1610, 1625, 1623, 1605, 1557, 1480, 1385, 1230, 1150, 1000, 500]

// b_x=[4, 4.5, 4.7, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5]
// b_y=[1410, 1380, 1355, 1310, 1240, 1160, 1060, 950, 840, 720, 580, 430, 245]

// c_x=[2.5, 3, 3.5, 4, 4.5, 4.7, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12]
// c_y=[1635, 1630, 1625, 1620, 1600, 1588, 1570, 1530, 1480, 1430, 1370, 1300, 1230, 1140, 1060, 970, 870, 760, 630, 480, 355]

// d_x=[1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 4.7, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.5, 13, 13.5]
// d_y=[2080, 2075, 2070, 2065, 2060, 2050, 2040, 2035, 2033, 2030, 2010, 1970, 1930, 1880, 1820, 1760, 1690, 1610, 1530, 1440, 1350, 1260, 1150, 1040, 900, 680, 200]

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


    $minDistance = INF;
    $minDistance_x = NAN;
    for ($x = 0; $x < $interpolate_end; $x += 0.00001) {
        $distance = sqrt(pow($x - $input[0], 2) + pow($p($x) - $input[1], 2));
        if ($distance < $minDistance) {
            $minDistance = $distance;
            $minDistance_x = $x;
        }
    };

    $is_closer_to_origin = function ($in, $point_in_curve) {
        return sqrt(pow($point_in_curve[1] - 0, 2) + pow($point_in_curve[0] - 0, 2)) > sqrt(pow($in[1] - 0, 2) + pow($in[0] - 0, 2));
    };
    $t = $is_closer_to_origin($input, array($minDistance_x, $p($minDistance_x)));

    $response->getBody()->write((string)json_encode([$minDistance, $minDistance_x, $p($minDistance_x), $t], JSON_PRETTY_PRINT));
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
