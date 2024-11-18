<?php


namespace MyApp\Controller;

use Carbon\Carbon;
use MyApp\Models\Project;
use MyApp\Models\Area;
use MyApp\Models\User;
use MyApp\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\HttpFoundation\Session\Session;

final class SystemController
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @var Session
     */
    private $session;

    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param Session $session The session handler
     */
    public function __construct(Responder $responder, Session $session)
    {
        $this->responder = $responder;
        $this->session = $session;
    }

    /**
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            Capsule::enableQueryLog();

            Capsule::schema()->dropAllTables();
            
            Capsule::schema()->create('users', function ($table) 
            {
                $table->bigIncrements('id')->unsigned();
                $table->string('login_id', 100)->unique();
                $table->string('nickname', 100)->unique();
                $table->string('email', 200)->unique();
                $table->string('password', 100);
                $table->binary('profile_image');
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
                
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            Capsule::schema()->create('areas', function($table)
            {
                $table->bigIncrements('id')->unsigned();
                $table->string('name_chi', 100);
                $table->string('name_eng', 100);
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
    
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });
    
            Capsule::schema()->create('projects', function($table)
            {
                $table->bigIncrements('id')->unsigned();
                $table->string('title_chi', 100)->nullable();
                $table->string('title_eng', 100);
                $table->string('client_name_chi', 100)->nullable();
                $table->string('client_name_eng', 100);
                $table->date('year')->nullable();
                $table->string('image_src', 100)->unique();
    
                $table->bigInteger('area_id')->unsigned();
                $table->foreign('area_id')->references('id')->on('area');
    
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
    
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            Capsule::beginTransaction();

            $user = new User;
            $user->login_id = "admin";
            $user->nickname = "admin";
            $user->email = "info@evercoolhk.com";
            $user->password = "123456";
            $user->profile_image = Capsule::raw("FROM_BASE64('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')");//$uploadedFile->getStream()->__toString();
            $user->save();

            $areaHongKong = new Area;
            $areaHongKong->name_chi = "香港";
            $areaHongKong->name_eng = "Hong Kong";
            $areaHongKong->save();
            $areaHongKong->projects()->saveMany([
                new Project([
                    'title_chi' => '南豐 AIRSIDE 商廈',
                    'title_eng' => 'Nam Fung AIRSIDE',
                    'client_name_chi' => '南豐集團', 
                    'client_name_eng' => 'Nan Fung Group', 
                    'year' => Carbon::create(2020, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/Nan%20Fung%20AIRSIDE.jpg'
                ]),
                new Project([
                    'title_chi' => '機電工程署總部',
                    'title_eng' => 'EMSD Headquarter',
                    'client_name_chi' => '機電工程署', 
                    'client_name_eng' => 'EMSD', 
                    'year' => Carbon::create(2019, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/EMSD%20Headquarter.jpeg'
                ]),
                new Project([
                    'title_chi' => null,
                    'title_eng' => 'The Eastmark',
                    'client_name_chi' => '大鴻輝興業有限公司',
                    'client_name_eng' => 'Tai Hung Fai Enterprise Co. Ltd.',
                    'year' => Carbon::create(2019, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/The%20Eastmark.jpg'
                ]),
                new Project([
                    'title_chi' => '美麗華廣場',
                    'title_eng' => 'Mira Place',
                    'client_name_chi' => '美麗華集團',
                    'client_name_eng' => 'Miramar Group',
                    'year' => Carbon::create(2019, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/Mira%20Place.jpeg'
                ]),
                new Project([
                    'title_chi' => '領展商場',
                    'title_eng' => 'LINK SHOPPING MALL',
                    'client_name_chi' => '領展', 
                    'client_name_eng' => 'LINK REIT', 
                    'year' => Carbon::create(2019, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/LINK%20SHOPPING%20MALL.jpeg'
                ]),
                new Project([
                    'title_chi' => 'AEON STYLE康怡',
                    'title_eng' => 'AEON STYLE Kornhill',
                    'client_name_chi' => '永旺百貨',
                    'client_name_eng' => 'AEON',
                    'year' => Carbon::create(2016, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/KORNHILL%20Aeon.jpg'
                ]),
                new Project([
                    'title_chi' => '香港帝苑酒店',
                    'title_eng' => 'The Royal Garden',
                    'client_name_chi' => '香港帝苑酒店', 
                    'client_name_eng' => 'The Royal Garden', 
                    'year' => Carbon::create(2016, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/Royal%20Garden%20(Hotel).jpg'
                ]),
                new Project([
                    'title_chi' => '香港專業教育學院(青衣)',
                    'title_eng' => 'IVE (Tsing Yi)',
                    'client_name_chi' => '職業訓練局', 
                    'client_name_eng' => 'VTC', 
                    'year' => Carbon::create(2017, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/IVE%20-%20Tsing%20Yi.jpeg'
                ]),
                new Project([
                    'title_chi' => '富通中心',
                    'title_eng' => 'FTLife Tower',
                    'client_name_chi' => '港豐發展有限公司', 
                    'client_name_eng' => 'Hong Kong Pacific Investments Limited',
                    'year' => Carbon::create(2017, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/FTLife%20Tower.jpg'
                ]),
                new Project([
                    'title_chi' => '澳美製藥廠C座及E座',
                    'title_eng' => 'Bright Future Block C & E',
                    'client_name_chi' => '澳美製藥廠有限公司',
                    'client_name_eng' => 'Bright Future Pharmaceutical Lab. Ltd.',
                    'year' => Carbon::create(2018, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/Bright%20Future%20Block%20C%20%26%20E.jpg'
                ]),
                new Project([
                    'title_chi' => '漢莎天廚',
                    'title_eng' => 'LSG Sky Chefs',
                    'client_name_chi' => '漢莎集團',
                    'client_name_eng' => 'LSG Group',
                    'year' => Carbon::create(2018, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/LSG%20Sky%20Chefs.jpg'
                ]),
                new Project([
                    'title_chi' => '美心食品廠',
                    'title_eng' => 'Maxim’s Food Production Centre',
                    'client_name_chi' => '美心食品有限公司', 
                    'client_name_eng' => 'Maxim’s',
                    'year' => Carbon::create(2018, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/Maxim%E2%80%99s%20Food%20Production%20Centre.jpg'
                ]),
                new Project([
                    'title_chi' => '香港浸會大學附屬學校王錦輝中小學',
                    'title_eng' => 'Hong Kong Baptist University Affiliated School Wong Kam Fai Secondary and Primary School',
                    'client_name_chi' => '香港浸會大學附屬學校王錦輝中小學', 
                    'client_name_eng' => 'Hong Kong Baptist University Affiliated School Wong Kam Fai Secondary and Primary School', 
                    'year' => Carbon::create(2016, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/hkbuas.jpg'
                ])
            ]);
    
            $areaMacau = new Area;
            $areaMacau->name_chi = "澳門";
            $areaMacau->name_eng = "Macau";
            $areaMacau->save();
            $areaMacau->projects()->saveMany([
                new Project([
                    'title_chi' => '上葡京度假村',
                    'title_eng' => 'Grand Lisboa Palace',
                    'client_name_chi' => '澳門博彩控股有限公司', 
                    'client_name_eng' => 'SJM Holdings Limited',
                    'year' => Carbon::create(2018, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/GRAND%20LISBOA%20PALACE.jpeg'
                ]),
                new Project([
                    'title_chi' => '葡京人',
                    'title_eng' => 'Lisboeta',
                    'client_name_chi' => '澳門主題公園渡假村股份有限公司', 
                    'client_name_eng' => 'Macau Theme Park and Resort Limited', 
                    'year' => Carbon::create(2019, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/LISBOETA.jpg'
                ]),
                new Project([
                    'title_chi' => '澳門回力酒店',
                    'title_eng' => 'Jai Alai Hotel',
                    'client_name_chi' => '澳門博彩控股有限公司', 
                    'client_name_eng' => 'SJM Holdings Limited',
                    'year' => Carbon::create(2017, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/HOTEL%20JAI%20ALAI.png'
                ]),
                new Project([
                    'title_chi' => '新濠天地 (改建及加建)',
                    'title_eng' => 'CITY OF DREAM (Alteration & Addition work)',
                    'client_name_chi' => '新濠國際', 
                    'client_name_eng' => 'Melco International', 
                    'year' => Carbon::create(2016, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/CITY%20OF%20DREAM.jpeg'
                ]),
                new Project([
                    'title_chi' => '新濠天地 - 皇冠度假酒店',
                    'title_eng' => 'City of Dreams - Nuwa',
                    'client_name_chi' => '新濠國際', 
                    'client_name_eng' => 'Melco International', 
                    'year' => Carbon::create(2016, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/MELCO%20CROWN.jpg'
                ]),
                new Project([
                    'title_chi' => '摩卡娛樂場',
                    'title_eng' => 'Mocha Clubs',
                    'client_name_chi' => '新濠國際', 
                    'client_name_eng' => 'Melco International', 
                    'year' => Carbon::create(2017, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/MACAU%20MOCHA.jpg'
                ]),
                new Project([
                    'title_chi' => '澳門漁人碼頭 (改建及加建)',
                    'title_eng' => 'Macau Fisherman’s Wharf (Alteration & Addition work)',
                    'client_name_chi' => '澳門漁人碼頭', 
                    'client_name_eng' => 'Macau Fisherman’s Wharf',
                    'year' => Carbon::create(2017, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/Macau%20Fishermans%20Wharf.jpg'
                ]),
                new Project([
                    'title_chi' => '澳門威尼斯人',
                    'title_eng' => 'The Venetian Macao',
                    'client_name_chi' => '威尼斯人（澳門）股份有限公司', 
                    'client_name_eng' => 'Venetian Macau Ltd.', 
                    'year' => Carbon::create(2017, 12, 31),
                    'image_src' => 'https://evercoolhk.com/api/uploads/The%20Venetian%20Macau.jpg'
                ])
            ]);
    
            $areaShanghai = new Area;
            $areaShanghai->name_chi = "上海";
            $areaShanghai->name_eng = "Shanghai";
            $areaShanghai->save();
            $areaShanghai->projects()->saveMany([
                new Project([
                    'title_chi' => '齊網松江數據中心',
                    'title_eng' => 'Qnet Songjiang Data Center',
                    'client_name_chi' => '上海齊網網絡科技有限公司', 
                    'client_name_eng' => 'Shanghai Qnet Network Technology Co., Ltd.', 
                    'year' => null,
                    'image_src' => 'https://evercoolhk.com/api/uploads/Qnet%20Songjiang%20Data%20Center%20Shanghai.jpg'
                ]),
            ]);
    
            $areaBeijing = new Area;
            $areaBeijing->name_chi = "北京";
            $areaBeijing->name_eng = "Beijing";
            $areaBeijing->save();
            $areaBeijing->projects()->saveMany([
                new Project([
                    'title_chi' => '瑞銀數據中心',
                    'title_eng' => 'UBS Data Center',
                    'client_name_chi' => '瑞銀集團', 
                    'client_name_eng' => 'UBS Group AG', 
                    'year' => null, 
                    'image_src' => 'https://evercoolhk.com/api/uploads/UBS%20Data%20Center.jpg'
                ]),            
                new Project([
                    'title_chi' => '現代汽車空間',
                    'title_eng' => 'Hyundai Motorstudio',
                    'client_name_chi' => '現代汽車',
                    'client_name_eng' => 'Hyundai Motor Company',
                    'year' => null, 
                    'image_src' => 'https://evercoolhk.com/api/uploads/Hyundai%20Motorstudio.jpg'
                ])
            ]);
    
            $areaUlaanbaatar = new Area;
            $areaUlaanbaatar->name_chi = "烏蘭巴托";
            $areaUlaanbaatar->name_eng = "Ulaanbaatar";
            $areaUlaanbaatar->save();
            $areaUlaanbaatar->projects()->saveMany([
                new Project([
                    'title_chi' => '香格里拉酒店',
                    'title_eng' => 'Shangri-La Hotel',
                    'client_name_chi' => '香格里拉（亞洲）有限公司', 
                    'client_name_eng' => 'Shangri-La Asia Limited',
                    'year' => null, 
                    'image_src' => 'https://evercoolhk.com/api/uploads/Ulaanbaatar%20Shangri-La.jpg'
                ]),
            ]);
    
            $areaDongguan = new Area;
            $areaDongguan->name_chi = "東莞";
            $areaDongguan->name_eng = "Dongguan";
            $areaDongguan->save();
            $areaDongguan->projects()->saveMany([
                new Project([
                    'title_chi' => '東莞康華醫院',
                    'title_eng' => 'Dongguan Kanghua Hospital',
                    'client_name_chi' => '廣東康華醫療股份有限公司', 
                    'client_name_eng' => 'Guangdong Kanghua Healthcare Co., Ltd.',
                    'year' => null,
                    'image_src' => 'https://evercoolhk.com/api/uploads/Dongguan%20KangHua%20Hospital.jpg'
                ]),
            ]);
            
            $areaThailand = new Area;
            $areaThailand->name_chi = "泰國";
            $areaThailand->name_eng = "Thailand";
            $areaThailand->save();
            $areaThailand->projects()->saveMany([
                new Project([
                    'title_chi' => '程逸府醫院',
                    'title_eng' => 'Uttaradit Hospital',
                    'client_name_chi' => '程逸府醫院', 
                    'client_name_eng' => 'Uttaradit Hospital', 
                    'year' => null,
                    'image_src' => 'https://evercoolhk.com/api/uploads/Uttaradit%20Hospital.jpg'
                ])
            ]);
    
            $areaPuertoRico = new Area;
            $areaPuertoRico->name_chi = "波多黎各";
            $areaPuertoRico->name_eng = "Puerto Rico";
            $areaPuertoRico->save();
            $areaPuertoRico->projects()->saveMany([
                new Project([
                    'title_chi' => '美敦力 (維拉爾巴)',
                    'title_eng' => 'Medtronic (Villalba)',
                    'client_name_chi' => '美敦力', 
                    'client_name_eng' => 'Medtronic',
                    'year' => null,
                    'image_src' => 'https://evercoolhk.com/api/uploads/Medtronic%20Villalba.jpg'
                ]),            
                new Project([
                    'title_chi' => null,
                    'title_eng' => 'Hospital Metropolitano Dr Susoni',
                    'client_name_chi' => null,
                    'client_name_eng' => 'Hospital Metropolitano Dr Susoni',
                    'year' => null,
                    'image_src' => 'https://evercoolhk.com/api/uploads/Hospital%20Metropolitano%20Dr%20Susoni.jpg'
                ]),
            ]);
        
            $areaBangladesh = new Area;
            $areaBangladesh->name_chi = "孟加拉";
            $areaBangladesh->name_eng = "Bangladesh";
            $areaBangladesh->save();
            $areaBangladesh->projects()->saveMany([
                new Project([
                    'title_chi' => null,
                    'title_eng' => 'Incepta Production Facilities',
                    'client_name_chi' => null,
                    'client_name_eng' => 'Incepta Pharmaceuticals Ltd.',
                    'year' => null, 
                    'image_src' => 'https://evercoolhk.com/api/uploads/Incepta%20Pharma.jpg'
                ]),
                new Project([
                    'title_chi' => null,
                    'title_eng' => 'Opsonin Pharmaceuticals Ltd. Factory',
                    'client_name_chi' => null, 
                    'client_name_eng' => 'Opsonin Pharmaceuticals Ltd.',
                    'year' => null, 
                    'image_src' => 'https://evercoolhk.com/api/uploads/Opsonin%20Pharmaceuticals.jpg'
                ]),
                new Project([
                    'title_chi' => null,
                    'title_eng' => 'Healthcare Pharmaceuticals Ltd. Head office',
                    'client_name_eng' => 'Healthcare Pharmaceuticals Ltd.',
                    'year' => null, 
                    'image_src' => 'https://evercoolhk.com/api/uploads/Healthcare%20Pharmaceuticals%20Ltd%20-%20Bangladesh.jpg'
                ])
            ]);

            Capsule::commit();

            $this->session->invalidate();
            return $this->responder->ok($response);
        } catch (\Exception $e) {
            Capsule::rollBack();
            return $this->responder->internal_server_error($response);
        }
    }
}
