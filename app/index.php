<?php

$app = (require __DIR__ . '/config/bootstrap.php');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Money\Money; // composer require moneyphp/money:^4.5.0
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use MyApp\Models\AHUModel;

use MathPHP\NumericalAnalysis\Interpolation;
use Illuminate\Database\Eloquent\Collection;

use Symfony\Component\ExpressionLanguage\SyntaxError;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Parser;



function writeJSON(Response $response, int $statusCode, mixed $contentToEncode) {
    $response->getBody()->write((string)json_encode($contentToEncode, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
}

function writePDF(Response $response, int $statusCode, mixed $content) {
    $response->getBody()->write($content);
    return $response->withHeader('Content-Type', 'application/pdf')->withStatus($statusCode);
}

use \Illuminate\Database\Capsule\Manager as Capsule;

$app->get('/reset-db', function (Request $request, Response $response, array $args) {
    Capsule::schema()->dropIfExists('cache');

    Capsule::schema()->create('cache', function ($table) 
    {
        $table->primary(['key']);
        $table->string('key', 100);
        $table->string('value', 100);
        $table->timestamp('created_at');
        $table->timestamp('updated_at');
        
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    });

    return writeJSON($response, 200, array());
});

$app->get('/model', function (Request $request, Response $response, array $args) {
    $queryParams = $request->getQueryParams();
    $collection = [];

    if (isset($queryParams['actual_air_volume'])) {
        $actualAirVolume = (int)$queryParams['actual_air_volume'];
        // select * from ahu_models where `maximum_air_volume` > 14900 ORDER BY `maximum_air_volume` LIMIT 2;
        $collection = AHUModel::where('maximum_air_volume', '>', $actualAirVolume)
            ->orderBy("maximum_air_volume")
            ->limit(2)
            ->get();
    } else {
        $collection = AHUModel::all();
    }

    return writeJSON($response, 200, $collection);
    


    // AHUModel::where('maximum_air_volume', '>', $actualAirVolume)
    //     ->orderBy("maximum_air_volume")
    //     ->limit(2)
    //     ->get();

    //     if (isset($queryParams['random_count'])) {
    //         $randomCount = (int)$queryParams['random_count'];
    //         if ($areaId != null) {
    //             $collection = Project::where('area_id', '=', $areaId)->orderBy(Capsule::raw("RAND()"))->take($randomCount)->get();
    //         } else {
    //             $collection = Project::orderBy(Capsule::raw("RAND()"))->take($randomCount)->get();
    //         }



});

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

/*
input (json string): "{ \"formula\": "" }"
*/
$app->post('/expression', function (Request $request, Response $response, array $args) {
    $body = json_decode($request->getBody()->getContents());
    $formula = $body->{'formula'};

    // $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    // $numberFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 1);
    // $numberFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 1);
    
    // $moneyFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());
    
    // $names = array("Peter", "Paul", "Mary", $_SERVER['SERVER_NAME'], $moneyFormatter->format(Money::HKD(1000)->divide(6)), Money::HKD(1000)->divide(6)); // in cents
    // array_push($names, $name);

    // sort($names);


    $ans = json_encode((new ExpressionLanguage())->evaluate($formula, [
                'HEIGHT' => 1540,
                'BASE_HEIGHT' => 80,
                'WIDTH' => 1790,
                'LENGTH' => 3660
            ]
        )
    );

    $response->getBody()->write((string)json_encode($ans, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

class MyTCPDF extends TCPDF{
    public function Header(){
        $styleForTable_Th_Td='border: 1px solid black; border-collapse: collapse;';
        $html = '<img style="height: 59px;" src="'. __DIR__ . "/uploads/icon.jpg" . '">';
        $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
    }
}

$app->post('/email-login', function (Request $request, Response $response, array $args) {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    $data = $request->getParsedBody();
    $email = $data['email'];
  


    if (!str_ends_with(strtolower($email), strtolower("@evercoolhk.com"))) {
        $response->getBody()->write((string)json_encode("Only @evercoolhk.com email can access this system", JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    if (!$this->has('cache')) {
        $response->getBody()->write((string)json_encode('Access code could not be sent. System error', JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(503);
    }    
    
    $cache = $this->get('cache');

    $username = substr($email, 0, strpos($email, '@'));

    if (isset($data['access-code'])) {
        $accessCodeInput = $data['access-code'];
        $accessCodeCached = $cache->getItem($username)->get();

        if ($accessCodeCached === $accessCodeInput) {
            $response->getBody()->write((string)json_encode('Access code correct ' . $accessCodeInput . ' ' . $accessCodeCached . ' ' . $username, JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write((string)json_encode('Access code not correct '. $accessCodeInput . ' ' . $accessCodeCached . ' ' . $username, JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
 
    try {
        //Server settings
        # $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isMail(); // Set mailer to use SMTP
        $mail->Host = $_ENV['EMAIL_HOST']; // Specify SMTP server
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $_ENV['EMAIL_SENDER_ACCOUNT']; // SMTP username
        $mail->Password = $_ENV['EMAIL_SENDER_PASSWORD']; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->setFrom($mail->Username, 'Ever Cool HK');
        $mail->addAddress($mail->Username, $email); // Add a recipient
        // $mail->SMTPOptions = array(
        //     'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true
        //     )
        // );



        $item = $cache->getItem($username);
        $item->set(\MyApp\Utils\StringUtil::generateRandomString(10))->expiresAfter(new \DateInterval('PT5M'));;
        $cache->save($item);
        
        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'New sign-in to your Ever Cool HK account';
        $mail->Body = $cache->getItem($username)->get();

        $mail->send();
        $response->getBody()->write((string)json_encode('Access code has been sent ', JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write((string)json_encode('Access code could not be sent. Mailer Error: '. $mail->ErrorInfo, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(503);
    }
});

$app->get('/email-testing', function (Request $request, Response $response, array $args) {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions

    try {
        //Server settings
        # $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isMail(); // Set mailer to use SMTP
        $mail->Host = $_ENV['EMAIL_HOST']; // Specify SMTP server
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $_ENV['EMAIL_SENDER_ACCOUNT']; // SMTP username
        $mail->Password = $_ENV['EMAIL_SENDER_PASSWORD']; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        
        // $mail->SMTPOptions = array(
        //     'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true
        //     )
        // );
        
        //Recipients
        $mail->setFrom($mail->Username, 'Test Sender');
        $mail->addAddress($mail->Username, 'Test receipt'); // Add a recipient
        # $mail->addAddress(‘ellen@example.com’); // Name is optional
        # $mail->addReplyTo(‘info@example.com’, ‘Information’);
        # $mail->addCC(‘cc@example.com’);
        # $mail->addBCC(‘bcc@example.com’);
        
        
        
        //Attachments
        # $mail->addAttachment(‘/var/tmp/file.tar.gz’); // Add attachments
        # $mail->addAttachment(‘/tmp/image.jpg’, ‘new.jpg’); // Optional name
        
        
        
        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Here is the subject 587!!';
        $mail->Body = 'This is the HTML message body <b>in bold!</b>';
        
        $mail->send();
        $response->getBody()->write((string)json_encode('Message has been sent', JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write((string)json_encode('Message could not be sent. Mailer Error: '. $mail->ErrorInfo, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
});

$app->post('/pdf', function (Request $request, Response $response, array $args) {
    $body = json_decode($request->getBody()->getContents(), true);
    
    // create new PDF document
    $pdf = new MyTCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    // $pdf->SetCreator('PDF_CREATOR');
    // $pdf->SetAuthor('Nicola Asuni');
    // $pdf->SetTitle('TCPDF Example 006');
    // $pdf->SetSubject('TCPDF Tutorial');
    // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    


    // define ('PDF_HEADER_LOGO', __DIR__ . "/uploads/icon.png");
    // set default header data
    // $pdf->SetHeaderData( "icon.jpg", 59, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // // set default monospaced font
    // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(20, 30, 20);

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
    //$fontnameRegular = TCPDF_FONTS::addTTFfont(__DIR__ . '/assets/fonts/03_NotoSerifCJK-TTF-VF/Variable/TTF/NotoSerifCJKhk-VF.ttf', '', '');
    $fontnameRegular = TCPDF_FONTS::addTTFfont(__DIR__ . '/assets/fonts/NotoSerifCJKhk/NotoSerifCJKhk-Regular.ttf', '', '');
    $fontnameBold = TCPDF_FONTS::addTTFfont(__DIR__ . '/assets/fonts/NotoSerifCJKhk/NotoSerifCJKhk-Bold.ttf', '', '');
    
    // // // set font
    $pdf->SetFont($fontnameRegular, '', 7.80, '', false);
    $pdf->SetFont($fontnameBold, 'B', 7.80, '', false);

    // add a page
    $pdf->AddPage();

    // create some HTML content
    $subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';



    $styleForTable_Th_Td='border: 1px solid black; border-collapse: collapse;';
    $html = '<h1 style="font-weight: bold; font-family:' . $fontnameBold .  ';text-align: center;">报 价 单</h1><br>';
    $pdf->writeHTML($html, true, false, true, false, '');

    $txt = 'Lore';

    // Multicell test


    // $pdf->MultiCell(55, 5, '[JUSTIFY] '.$txt."\n", 1, 'J', 1, 2, '' ,'', true);
    // $pdf->MultiCell(55, 5, '[DEFAULT] '.$txt, 1, '', 0, 1, '', '', true);

    $header = function($name) use($styleForTable_Th_Td) {
        return '<th align="center" style="'. $styleForTable_Th_Td . '">'. $name .'</th>';
    };
    
    $html = '<table cellpadding="2" style="font-size: 7.8; font-weight: normal; font-family:' . $fontnameRegular .  '">
        <tbody>
            <tr>
                <td style="width: 70%">
                    <table cellpadding="2">
                        <tbody>
                            <tr>
                                <td style="width: 40px">TO：</td>
                                <td style="width: 100%">香港嘉毅设备有限公司</td>
                            </tr>
                            <tr>
                                <td style="width: 40px">Tel：</td>
                                <td style="width: 100%">00852-2356 8598</td>
                            </tr>
                            <tr>
                                <td style="width: 40px">Attn：</td>
                                <td style="width: 100%">Ivy</td>
                            </tr>
                            <tr>
                                <td style="width: 40px">Ref：</td>
                                <td style="width: 100%">The Proposed Shopping Mall Development(购物中心)</td>
                            </tr>
                            <tr>
                                <td colspan="3">供应"TECH FREE "牌产品报价如下：</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 30%">
                    <table cellpadding="2">
                        <tbody>
                            <tr>
                                <td style="width: 60px">Ref No：</td>
                                <td style="width: 100%">TQ364-06-24</td>
                            </tr>
                            <tr>
                                <td style="width: 60px">Date：</td>
                                <td style="width: 100%">2024/6/11</td>
                            </tr>
                            <tr>
                                <td style="width: 60px">Currency：</td>
                                <td style="width: 100%">HKD/RMB=0.92</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>
    <table cellpadding="2">
        <tbody>
            <tr>
                <td><h1 style="font-size: 7.8; font-weight: bold; font-family:' . $fontnameBold .  ';">一、组合式空气处理机组</h1></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="'. $styleForTable_Th_Td . ';text-align: center;" cellpadding="4">
        <thead>
            <tr style="font-size: 7.8; font-weight: normal; font-family:' . $fontnameRegular .  ';">'
                . $header("序号") 
                . $header("机组编号") 
                . $header("型号") 
                . $header("送风量<br>l/s") 
                . $header("新风量<br>l/s") 
                . $header("机外<br>余压<br>(Pa)")
                . $header("过滤器")
                . $header("冷水盘<br>管排数<br>(Rows)")
                . $header("制冷量<br>(KW)")
                . $header("送风机")
                . $header("EC风机<br>数量<br>（台）")
                . $header("参考尺寸<br>(MM)<br>H * W * L")
                . $header("机组单价<br>(HKD)")
                . $header("机组数量")
                . $header("小计<br>（HKD）") . '
            </tr>
        </thead>
        <tbody>' .
            implode("", 
                array_map(function(mixed $item, int $index) use ($styleForTable_Th_Td): string {
                    return '<tr>
                        <td style="'. $styleForTable_Th_Td . '">'. ($index + 1) . '</td>
                        <td style="'. $styleForTable_Th_Td . '">' . $item["ahu_number"] . '</td>
                        <td style="'. $styleForTable_Th_Td . '">' . $item["model"] . '</td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                        <td style="'. $styleForTable_Th_Td . '"></td>
                    </tr>';
                }, $body, array_keys($body))
            )
        . ' </tbody>
    </table>
    <br>
    <table cellpadding="2">
        <thead>
            <tr style="display: none; font-size: 7.8; font-weight: normal; font-family:' . $fontnameRegular .  ';">'
                . $header("序号") 
                . $header("机组编号") 
                . $header("型号") 
                . $header("送风量<br>l/s") 
                . $header("新风量<br>l/s") 
                . $header("机外<br>余压<br>(Pa)")
                . $header("过滤器")
                . $header("冷水盘<br>管排数<br>(Rows)")
                . $header("制冷量<br>(KW)")
                . $header("送风机")
                . $header("EC风机<br>数量<br>（台）")
                . $header("参考尺寸<br>(MM)<br>H * W * L")
                . $header("机组单价<br>(HKD)")
                . $header("机组数量")
                . $header("小计<br>（HKD）") . '
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="12"></td>
                <td style="text-align: center">
                    <span style="text-decoration: underline;font-size: 7.8; font-weight: bold; font-family:' . $fontnameBold .  ';text-align: center;">
                    合计
                    </span>
                </td>
                <td style="text-align: center">
                    <span style="text-decoration: underline;font-size: 7.8; font-weight: bold; font-family:' . $fontnameBold .  ';text-align: center;">
                    10
                    </span>
                </td>
                <td style="text-align: center">
                    <span style="text-decoration: underline;font-size: 7.8; font-weight: bold; font-family:' . $fontnameBold .  ';text-align: center;">
                    757,620
                    </span>
                </td>
            </tr>
            <tr style="font-size: 7.8; font-weight: normal; font-family:' . $fontnameRegular .  '">
                <td></td>
                <td colspan="14">
                    结 构:复合式铝合金结构（TB1).50MM PU双面板,(外板1.0MM铝板+内板0.8MM铝板)
                    <br>
                    功能段:进/混风段+G4&F7袋式中效过滤段+冷水盘管段 +EC风机段
                    <br>
                    <br>
                    <span>备注:</span><br>
                    1) 采用EBM EC风机，配EC风机接线盒。配EC风机防护网。风机段配门限位开关，视窗以及检修灯。<br>
                    2) 盘管采用铜管/亲水铝片，SS304不锈钢端板。<br>
                    3) 以上报价为净机组价钱，不包含控制。
                </td> 
            </tr>
            <tr><td><br></td></tr>
            <tr style="font-size: 7.8; font-weight: normal; font-family:' . $fontnameRegular .  '">
                <td colspan="15">
                    付款方式：按协议结算<br>
                    交货地点：送货至香港（不包卸货)<br>
                    交货周期：约6~8周<br>
                    备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：1.以上报价不含任何现场施工
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table cellpadding="2">
        <tbody>
            <tr>
                <td><br></td>
                <td><br></td>
                <td><br></td>
            </tr>
            <tr style="font-size: 7.8; font-weight: bold; font-family:' . $fontnameBold .  '">
                <td colspan="2">
                    <span>接受确认签署及盖章</span>   
                </td>
                <td>
                    <span>东莞骏科空调制造有限公司</span>   
                </td>
            </tr>
            <tr style="font-size: 7.8; font-weight: normal; font-family:' . $fontnameRegular .  '">
                <td colspan="2"><br><br><br><br>
                    <span style="text-decoration: underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>   
                </td>
                <td><br><br><br><br>
                    <span style="text-decoration: underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>   
                </td>
            </tr>
        </tbody>
    </table>
    
    ';
        

    // output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------
    /*
    $response->getBody()->write($pdf->Output('123', 'I'));
    return enableCORS($response->withHeader('Content-Type', 'application/pdf')->withStatus(200));
    */
    // 
    return 
        writePDF(
            $response, 
            200, 
            $pdf->Output('123', 'S')
        )
    ;
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
