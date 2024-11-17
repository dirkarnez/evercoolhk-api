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

ini_set('memory_limit', '-1');

require(__DIR__ . '/vendor/autoload.php');

$app = AppFactory::create();

if (str_contains($_SERVER['SERVER_NAME'], 'evercoolhk.com')) { 
    $app->setBasePath('/api');
}

$app->get('/names/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];

    $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    $numberFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 1);
    $numberFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 1);
    
    $moneyFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());
    
    $names = array("Peter", "Paul", "Mary", $_SERVER['SERVER_NAME'], $moneyFormatter->format(Money::HKD(1000)->divide(6)), Money::HKD(1000)->divide(6)); // in cents
    array_push($names, $name);

    sort($names);

    $response->getBody()->write((string)json_encode($names, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/pdf', function (Request $request, Response $response, array $args) {
    
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nicola Asuni');
    $pdf->SetTitle('TCPDF Example 006');
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // // set some language-dependent strings (optional)
    // if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    //     require_once(dirname(__FILE__).'/lang/eng.php');
    //     $pdf->setLanguageArray($l);
    // }

    // ---------------------------------------------------------
    // set default font subsetting mode
    
    $pdf->setFontSubsetting(true);
    $fontname = TCPDF_FONTS::addTTFfont(__DIR__ . '/assets/fonts/03_NotoSerifCJK-TTF-VF/Variable/TTF/NotoSerifCJKhk-VF.ttf', 'NotoSerifCJKhk', '', 10);
    
    // // // set font
    $pdf->SetFont($fontname, '', 10);

    // add a page
    $pdf->AddPage();

    // create some HTML content
    $subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';

    $html = '<h2>HTML TABLE:</h2>
    <table border="1" cellspacing="3" cellpadding="4">
        <tr>
            <th>#</th>
            <th align="right">中文 tę łódź jeża</th>
            <th align="left">LEFT align</th>
            <th>中文</th>
        </tr>
        <tr>
            <td>1</td>
            <td bgcolor="#cccccc" align="center" colspan="2">A1 ex<i>amp</i>le <a href="http://www.tcpdf.org">link</a> column span. One two tree four five six seven eight nine ten.<br />line after br<br /><small>small text</small> normal <sub>subscript</sub> normal <sup>superscript</sup> normal  bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla<ol><li>first<ol><li>sublist</li><li>sublist</li></ol></li><li>second</li></ol><small color="#FF0000" bgcolor="#FFFF00">small small small small small small small small small small small small small small small small small small small small</small></td>
            <td>4B</td>
        </tr>
        <tr>
            <td>'.$subtable.'</td>
            <td bgcolor="#0000FF" color="yellow" align="center">A2 € &euro; &#8364; &amp; è &egrave;<br/>A2 € &euro; &#8364; &amp; è &egrave;</td>
            <td bgcolor="#FFFF00" align="left"><font color="#FF0000">Red</font> Yellow BG</td>
            <td>4C</td>
        </tr>
        <tr>
            <td>1A</td>
            <td rowspan="2" colspan="2" bgcolor="#FFFFCC">2AA<br />2AB<br />2AC</td>
            <td bgcolor="#FF0000">4D</td>
        </tr>
        <tr>
            <td>1B</td>
            <td>4E</td>
        </tr>
        <tr>
            <td>1C</td>
            <td>2C</td>
            <td>3C</td>
            <td>4F</td>
        </tr>
    </table>';

    // output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------
    $response->getBody()->write($pdf->Output('123', 'I'));
    return $response->withHeader('Content-Type', 'application/pdf')->withStatus(200);
});

$app->get('/calculate', function (Request $request, Response $response, array $args) {
    // $points = [[0, sqrt(0)], [2, sqrt(2)], [3, sqrt(3)], [4, sqrt(4)]];
    $queryParams = $request->getQueryParams();

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

    $input = array($queryParams["x"], $queryParams["y"]);

    $filtered = array();

    foreach ($data as $datum) {
        $interpolate_start = $datum["points"][0][0];
        $interpolate_end = $datum["points"][count($datum["points"]) - 1][0];
        
        $p = Interpolation\LagrangePolynomial::interpolate($datum["points"]);

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
            array_push($filtered, array("name" => $datum["name"], "minDistance" => $minDistance));
        }
        

        // $response->getBody()->write((string)json_encode([$p->getCoefficients(), $minDistance, array($minDistance_x, $p($minDistance_x)), $t], JSON_PRETTY_PRINT));
        // return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    
    usort($filtered, function($a, $b) {
        if ($a["minDistance"] == $b["minDistance"]) {
            return 0;
        }
        return ($a["minDistance"] < $b["minDistance"]) ? -1 : 1;
    });
    
    $response->getBody()->write((string)json_encode(array("data" => $data, "best" => $filtered[0]["name"], "filtered" => $filtered), JSON_PRETTY_PRINT));
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

$app->get('/[{name:.*}]', function ($request, $response, array $args) {
    $response->getBody()->write("404");
    return $response->withStatus(404); 
});

$app->run();
?>
