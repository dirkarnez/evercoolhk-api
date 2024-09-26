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
    
    $data = array(
        array( 
            'name' => 'a',
            'points' => [
                [1000, 1610], 
                [1500, 1625], 
                [2000, 1623], 
                [2500, 1605], 
                [3000, 1557], 
                [3500, 1480], 
                [4000, 1385], 
                [4500, 1230], 
                [4700, 1150],
                [5000, 1000], 
                [5500, 500]
            ]
            ),
        array( 
            'name' => 'b',
            'points' => [
                [4000, 1410], 
                [4500, 1380], 
                [4700, 1355], 
                [5000, 1310], 
                [5500, 1240], 
                [6000, 1160], 
                [6500, 1060], 
                [7000, 950], 
                [7500, 840], 
                [8000, 720], 
                [8500, 580], 
                [9000, 430], 
                [9500, 245]]
            ),
        array( 
            'name' => 'c',
            'points' => [
                [2500, 1635], 
                [3000, 1630], 
                [3500, 1625], 
                [4000, 1620], 
                [4500, 1600], 
                [4700, 1588], 
                [5000, 1570], 
                [5500, 1530], 
                [6000, 1480], 
                [6500, 1430], 
                [7000, 1370], 
                [7500, 1300], 
                [8000, 1230], 
                [8500, 1140], 
                [9000, 1060], 
                [9500, 970 ], 
                [10000, 870], 
                [10500, 760], 
                [11000, 630], 
                [11500, 480], 
                [12000, 355]
            ]
            ),
        array( 
            'name' => 'd',
            'points' => [
                [1000, 2080], 
                [1500, 2075], 
                [2000, 2070], 
                [2500, 2065], 
                [3000, 2060], 
                [3500, 2050], 
                [4000, 2040], 
                [4500, 2035], 
                [4700, 2033], 
                [5000, 2030], 
                [5500, 2010], 
                [6000, 1970], 
                [6500, 1930],
                [7000, 1880], 
                [7500, 1820], 
                [8000, 1760], 
                [8500, 1690], 
                [9000, 1610], 
                [9500, 1530], 
                [10000, 1440], 
                [10500, 1350], 
                [11000, 1260], 
                [11500, 1150], 
                [12000, 1040], 
                [12500, 900 ], 
                [13000, 680 ], 
                [13500, 200 ]
            ] 
        )
    );

    //$p = Interpolation\LagrangePolynomial::interpolate($points, 0);                // input as a set of points
    // function me($x) {
    //     return 2 - $x;
    // }

    // $points = [[0, me(0)], [1, me(1)], [2, me(2)], [3, me(3)]];

    // foreach ($data as &$datum) {
    //     $datum["t"] = 
    // }
    // foreach ($data as $name => $xy) {
    //     $data[$name] = array($data[$name])
    // }

    $input = array(5, 2000);

    $filtered = array();

    foreach ($data as $datum) {
        $p = Interpolation\LagrangePolynomial::interpolate($datum["points"]);

        $interpolate_start = $datum["points"][0][0];
        $interpolate_end = $datum["points"][count($datum["points"]) - 1][0];

        $minDistance = INF;
        $minDistance_x = NAN;

        for ($x = $interpolate_start; $x <= $interpolate_end; $x += 1) {
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

        if ($t) {
            array_push($filtered, $datum["name"]);
        }
        

        // $response->getBody()->write((string)json_encode([$p->getCoefficients(), $minDistance, array($minDistance_x, $p($minDistance_x)), $t], JSON_PRETTY_PRINT));
        // return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    

    
    $response->getBody()->write((string)json_encode(array("data" => $data, "best" => $filtered[0]), JSON_PRETTY_PRINT));
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
