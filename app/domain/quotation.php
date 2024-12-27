class Quotation {

}

class DomainObject
{
	private 序号; // A2
	private AHU编号; // B2
	private 功能段; // C2
	private 实际风量(M3/H); // D2
	private 最大风量(M3/H); // E2
	private 底座高度; // F2
	private 高; // G2
	private 宽; // H2
	private 长; // I2
	private 风机型号; // J2
	private 电机品牌; // K2
	private 电机/TECO IE3; // L2
	private EC风机数量; // M2
	private DIDW风机数量; // N2
	private 冷水盘管排数; // O2
	private 热水盘管排数; // P2
	private 电加热功率kw; // Q2
	private 型材; // R2
	private 面板; // S2
	private 槽钢底架; // T2
	private 盘管迎风面积; // U2
	private 过滤器数量; // V2
	private 水盘面积; // W2
	private 铝合金风阀面积; // X2
	private 型材定价60mm铝合金; // Z2
	private 面板定价50MM(PU)0.8MM+0.6MM GI; // AA2
	private 槽钢底架定价（条）; // AB2
	private 盘管定价铜管/亲水铝片0.41+0.13; // AC2
	private 盘管定价(其它情况); // AD2
	private 过滤器定价（G4+F7袋式）; // AE2
	private SS304水盘定价; // AF2
	private 电加热定价(180~200元/m2）; // AG2
	private 风阀定价1560元/m2; // AH2






	private profile; // AJ2 型材
	private ; // AK2 面板
	private ; // AL2 槽钢底架
	private ; // AM2 紧固件（结构*0.03~0.06）
	private ; // AN2 过滤器
	private ; // AO2 冷水盘管
	private ; // AP2 水盘（1）
	private ; // AQ2 热水盘管
	private ; // AR2 水盘（2）
	private ; // AS2 电加热
	private ; // AT2 EC风机价格
	private ; // AU2 DIDW/ACPLUG FAN 价格
	private ; // AV2 TECO电机单价
	private ; // AW2 EC风机接线盒
	private ; // AX2 DIDW风机/电机减振及传送
	private ; // AY2 风阀
	private ; // AZ2 镀锌止回阀
	private U型热管; // BA2
	private 转轮; // BB2
	private 加湿器; // BC2
	private 电加热; // BD2
	private UV灯; // BE2
	private 热回收系统; // BF2
	private 热泵功能; // BG2
	private 排风段结构; // BH2
	private 排风段风机; // BI2
	private 排风段过滤器; // BJ2
	private 一体式设备仓; // BK2
	private 一体式电控; // BL2
	private 一体式管路; // BM2
	private 室外机; // BN2
	private DDC控制; // BO2
	private 其他; // BP2

	// private 小计; // BQ2

	private 包装费2%; // BR2
	private 机组体积; // BS2
	private 港澳地区运费最低1500RMB/台; // BT2
	private 总价RMB; // BU2
	private 港币Currency：HKD/RMB=0.9; // BV2
	private 取整HKD; // BW2

	private function get_AHU编号() {
		// =报价表!B13
		return 报价表!B13
	};

	private function get_功能段() {
		// =报价表!C13
		return 报价表!C13
	};

	private function get_实际风量(M3/H)() {
		// =报价表!E13
		return 报价表!E13
	};

	private function get_最大风量(M3/H)() {
		// =IFERROR(VLOOKUP(C2,功能段数据!$A:$E,COLUMN()-3,FALSE()),"")
		return IFERROR(VLOOKUP(C2,功能段数据!$A:$E,COLUMN()-3,FALSE()),"")
	};

	private function get_底座高度() {
		// =IFERROR(VLOOKUP($C2,功能段数据!$A:$E,COLUMN()-3,FALSE()),"")
		return IFERROR(VLOOKUP($C2,功能段数据!$A:$E,COLUMN()-3,FALSE()),"")
	};

	private function get_高() {
		// =IFERROR(VLOOKUP($C2,功能段数据!$A:$E,COLUMN()-3,FALSE()),"")
		return IFERROR(VLOOKUP($C2,功能段数据!$A:$E,COLUMN()-3,FALSE()),"")
	};

	private function get_宽() {
		// =IFERROR(VLOOKUP($C2,功能段数据!$A:$E,COLUMN()-3,FALSE()),"")
		return IFERROR(VLOOKUP($C2, 功能段数据!$A:$E,COLUMN()-3,FALSE()),"")
	};

	private function get_长() {
		// =VLOOKUP(B2,机组功能段!$A:$AT,46,FALSE())
		return VLOOKUP(B2,机组功能段!$A:$AT,46,FALSE())
	};

	private function get_风机型号() {
		// =报价表!Q13
		return 报价表!Q13
	};

	private function get_电机品牌() {
		// =报价表!R13
		return 报价表!R13
	};

	private function get_电机/TECO IE3() {
		// =报价表!S13
		return 报价表!S13
	};

	private function get_EC风机数量() {
		// =IF(L2=0,报价表!T13,0)
		return IF(L2=0,报价表!T13,0)
	};

	private function get_DIDW风机数量() {
		// =IF(L2=0,"0",报价表!T13)
		return IF(L2=0,"0",报价表!T13)
	};

	private function get_冷水盘管排数() {
		// =报价表!M13
		return 报价表!M13
	};

	private function get_热水盘管排数() {
		// =报价表!O13
		return 报价表!O13
	};

	private function get_型材() {
        <!-- 
        分段
        2200
        4200
        6000 
        -->

		// =((G2-F2+H2)*4*(IF(I2>2200,2,1)+IF(I2>4200,1,0)+IF(I2>6000,1,0))+I2*4)*1.2/1000
		return ((G2-F2+H2)*4*(IF(I2>2200,2,1)+IF(I2>4200,1,0)+IF(I2>6000,1,0))+I2*4)*1.2/1000
	};

	private function get_面板() {
		// =IFERROR(((G2-F2)*H2*2+H2*I2*2+(G2-F2)*I2*2)/10^6,"")
		return IFERROR(((G2-F2)*H2*2+H2*I2*2+(G2-F2)*I2*2)/10^6,"")
	};

	private function get_槽钢底架() {
		// =((IF(I2>2200,2,1)+IF(I2>4200,1,FALSE())+IF(I2>6000,1,FALSE()))*H2+I2)*2.4/6000
		return ((IF(I2>2200,2,1)+IF(I2>4200,1,FALSE())+IF(I2>6000,1,FALSE()))*H2+I2)*2.4/6000
	};

	private function get_盘管迎风面积() {
		// =E2/(2.5*3600)
		return E2/(2.5*3600)
	};

	private function get_过滤器数量() {
		// =LEFT(RIGHT(C2,5),2)*LEFT(RIGHT(C2,3),2)/100
		return LEFT(RIGHT(C2,5),2)*LEFT(RIGHT(C2,3),2)/100
	};

	private function get_水盘面积() {
		// =IF(G2<2195,(H2-130)/1000,(H2-130)/1000*2)
		return IF(G2<2195,(H2-130)/1000,(H2-130)/1000*2)
	};

	private function get_铝合金风阀面积() {
		// =E2/3600/5
		return E2/3600/5
	};

	private function get_盘管定价铜管/亲水铝片0.41+0.13() {
		// =_xlfn.IFNA(VLOOKUP($C2,风机电机定价!$A$3:$H$42,8,FALSE()),"")
		return _xlfn.IFNA(VLOOKUP($C2,风机电机定价!$A$3:$H$42,8,FALSE()),"")
	};

	private function get_型材() {
		// =R2*Z2
		return R2*Z2
	};

	private function get_面板() {
		// =S2*AA2
		return S2*AA2
	};

	private function get_槽钢底架() {
		// =T2*AB2
		return T2*AB2
	};

	private function get_紧固件（结构*0.03~0.06）() {
		// =SUM(AJ2:AL2)*0.03
		return SUM(AJ2:AL2)*0.03
	};

	private function get_过滤器() {
		// =V2*AE2
		return V2*AE2
	};

	private function get_冷水盘管() {
		// =U2*O2*AC2
		return U2*O2*AC2
	};

	private function get_水盘（1）() {
		// =W2*AF2
		return W2*AF2
	};

	private function get_热水盘管() {
		// =U2*P2*AC2
		return U2*P2*AC2
	};

	private function get_水盘（2）() {
		// =W2*400
		return W2*400
	};

	private function get_电加热() {
		// =Q2*AG2
		return Q2*AG2
	};

	private function get_EC风机价格() {
		// =VLOOKUP(J2,风机电机定价!J:N,5,FALSE())*M2
		return VLOOKUP(J2,风机电机定价!J:N,5,FALSE())*M2
	};

	private function get_DIDW/ACPLUG FAN 价格 () {
		// =VLOOKUP(J2,风机电机定价!J:N,5,FALSE())*N2
		return VLOOKUP(J2,风机电机定价!J:N,5,FALSE())*N2
	};

	private function get_TECO电机单价() {
		// =IF(L2=0,0,VLOOKUP(K2&L2,风机电机定价!$Q:$U,5,FALSE()))*N2
		return IF(L2=0,0,VLOOKUP(K2&L2,风机电机定价!$Q:$U,5,FALSE()))*N2
	};

	private function get_EC风机接线盒() {
		// =_xlfn.IFNA(VLOOKUP($M2,风机电机定价!$W$19:$Y$24,3,FALSE()),"")
		return _xlfn.IFNA(VLOOKUP($M2,风机电机定价!$W$19:$Y$24,3,FALSE()),"")
	};

	private function get_DIDW风机/电机减振及传送() {
		// =_xlfn.IFNA(VLOOKUP(L2,风机电机定价!$V2:$Z24,5,FALSE()),"")
		return _xlfn.IFNA(VLOOKUP(L2,风机电机定价!$V2:$Z24,5,FALSE()),"")
	};

	private function get_风阀() {
		// =AH2*X2
		return AH2*X2
	};

	private function get_镀锌止回阀() {
		// =500*M2
		return 500*M2
	};

	private function get_小计() {
		// =SUM(AJ2:BO2)
		return SUM(AJ2:BO2)
	};

	private function get_包装费2%() {
		// =BQ2*0.02
		return BQ2*0.02
	};

	private function get_机组体积() {
		// =G2*H2*I2/1000000000
		return G2*H2*I2/1000000000
	};

	private function get_港澳地区运费最低1500RMB/台() {
		// =200*BS2
		return 200*BS2
	};

	private function get_总价RMB() {
		// =BQ2+BR2+BT2
		return BQ2+BR2+BT2
	};

	private function get_港币Currency：HKD/RMB=0.9() {
		// =BU2/0.9
		return BU2/0.9
	};

	private function get_取整HKD() {
		// =ROUND(BV2,-2)
		return ROUND(BV2,-2)
	};

	private function set_序号($序号In) {
		$this->序号 = $序号In;
	};

	private function set_电加热功率kw($电加热功率kwIn) {
		$this->电加热功率kw = $电加热功率kwIn;
	};

	private function set_型材定价60mm铝合金($型材定价60mm铝合金In) {
		$this->型材定价60mm铝合金 = $型材定价60mm铝合金In;
	};

	private function set_面板定价50MM(PU)0.8MM+0.6MM GI($面板定价50MM(PU)0.8MM+0.6MM GIIn) {
		$this->面板定价50MM(PU)0.8MM+0.6MM GI = $面板定价50MM(PU)0.8MM+0.6MM GIIn;
	};

	private function set_槽钢底架定价（条）($槽钢底架定价（条）In) {
		$this->槽钢底架定价（条） = $槽钢底架定价（条）In;
	};

	private function set_盘管定价(其它情况)($盘管定价(其它情况)In) {
		$this->盘管定价(其它情况) = $盘管定价(其它情况)In;
	};

	private function set_过滤器定价（G4+F7袋式）($过滤器定价（G4+F7袋式）In) {
		$this->过滤器定价（G4+F7袋式） = $过滤器定价（G4+F7袋式）In;
	};

	private function set_SS304水盘定价($SS304水盘定价In) {
		$this->SS304水盘定价 = $SS304水盘定价In;
	};

	private function set_电加热定价(180~200元/m2）($电加热定价(180~200元/m2）In) {
		$this->电加热定价(180~200元/m2） = $电加热定价(180~200元/m2）In;
	};

	private function set_风阀定价1560元/m2($风阀定价1560元/m2In) {
		$this->风阀定价1560元/m2 = $风阀定价1560元/m2In;
	};

	private function set_U型热管($U型热管In) {
		$this->U型热管 = $U型热管In;
	};

	private function set_转轮($转轮In) {
		$this->转轮 = $转轮In;
	};

	private function set_加湿器($加湿器In) {
		$this->加湿器 = $加湿器In;
	};

	private function set_电加热($电加热In) {
		$this->电加热 = $电加热In;
	};

	private function set_UV灯($UV灯In) {
		$this->UV灯 = $UV灯In;
	};

	private function set_热回收系统($热回收系统In) {
		$this->热回收系统 = $热回收系统In;
	};

	private function set_热泵功能($热泵功能In) {
		$this->热泵功能 = $热泵功能In;
	};

	private function set_排风段结构($排风段结构In) {
		$this->排风段结构 = $排风段结构In;
	};

	private function set_排风段风机($排风段风机In) {
		$this->排风段风机 = $排风段风机In;
	};

	private function set_排风段过滤器($排风段过滤器In) {
		$this->排风段过滤器 = $排风段过滤器In;
	};

	private function set_一体式设备仓($一体式设备仓In) {
		$this->一体式设备仓 = $一体式设备仓In;
	};

	private function set_一体式电控($一体式电控In) {
		$this->一体式电控 = $一体式电控In;
	};

	private function set_一体式管路($一体式管路In) {
		$this->一体式管路 = $一体式管路In;
	};

	private function set_室外机($室外机In) {
		$this->室外机 = $室外机In;
	};

	private function set_DDC控制($DDC控制In) {
		$this->DDC控制 = $DDC控制In;
	};

	private function set_其他($其他In) {
		$this->其他 = $其他In;
	};
}