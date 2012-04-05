<?

class config_plugin_statview extends Config {
	public function config_plugin_statview(){
		$this->dsn			= "mysqli://root@localhost/tdms";
		$this->tbName		= "TDMS_SELL_MANAGE A";
		$this->plugin		= "STATISTICS";

		$this->joinType		= "1"; // 1: 일대일 연결 2: 일대다 연결
		$this->joinTarget   = "TDMS_ITEM_MANAGE B"; // 조인할 대상 테이블
		$this->joinCondition= "A.itemid = B.id";
		$this->joinOrder	= "a.id desc";

		$this->content		= "판매 관리 테이블";
		$this->title		= "shop_item 관리";
		$this->tbColor		= array("tableColor" => "#8E1002",
								 "subTableColor" => "#E2E2E2",
								 "lineTableColor" => "#AFAFAF");
		$this->tableWidth	= "710";
		$this->arrTbField = array(
								"일련번호"	=>	array("A.ID", false, "", ""),
								"하위번호"	=>	array("A.SUBID",false,"STAT/5/15",""),
								"완료여부"	=>	array("A.DONE",true,"TSEL", array("완료"=>"Y","미완료"=>"N")),
								"사이트"		=>	array("A.CODE", true, "STAT/20/4", 
													array("옥션"=>"옥션",
														  "G마켓"=>"G마켓",
														  "더미라클"=>"더미라클",
														  "GS이스토어"=>"GS이스토어",
														  "인터파크"=>"인터파크", 
														  "온켓"=>"온켓",
														  "직거래"=>"직거래",
														  "11번가"=>"11번가"
														  )
													),
								"코드번호"	=>	array("A.CODENUMBER",true, "STAT/5/15", ""),
								"수령자"	=>	array("A.getname", true, "STAT/16/25", ""),
								"주소"		=>	array("A.address",true, "STAT/35/255", ""),
								"판매아이디"=>	array("A.userid", true, "STAT/16/25", ""),
								"전화번호"	=>	array("A.tel", true, "STAT/16/15", ""),
								"핸드폰"	=>	array("A.mobile", true, "STAT/16/15", ""),
								"배송형태"	=>	array("A.status",true, "TSEL", array( "선불"=>"선불", "착불"=>"착불" )),
								"요구사항"	=>	array("A.request",true, "TEXT/40/4",""),
								"송장번호"	=>	array("A.sn",true, "STAT/14/25", ""),
								"구매자"	=>	array("A.buyname", true, "STAT/16/25", ""),
								"성별"		=>	array("A.sex",true, "STAT/20/4", array( "남"=>"남", "여"=>"여", "모름"=>"" )),
								"이메일"	=>	array("A.email", true, "STAT/20/50", ""),
								
								"주문물품"	=>	array("A.itemid",true, "OCMB", 
														array( 
															"TBNAME"=>"TDMS_ITEM_MANAGE B", 
															"TITLE"=>"B.name", 
															"VALUE"=>"B.id",
															"VALUETO"=>"이익금"
														)
													),
								"이익금"	=>	array("A.profit",false, "STAT/10/25", ""),
								"낙찰번호"	=>	array("A.buynumber",true, "STAT/10/25", ""),
								"접수일자"	=>	array("A.takendate", true, "DATE/10/20", ""),
								"회신일자"	=>	array("A.conducteddate", true, "DATE/10/20", ""),
								"회신결과"	=>	array("A.conductingresult", true, "ELST", 
												array(
														"물품"=>"물품", 
														"반품"=>"반품",
														"기타"=>"기타")
												),
								"반품유무"		=>	array("A.isreturn", true, "TSEL", array("N"=>"N","Y"=>"Y")),
								"비고"		=>	array("A.note", true, "TEXT/40/4", ""),
								"상품일련번호"	=>	array("B.ID", true, "", ""),
								"상품명"	=>	array("B.name", true, "STAT/20/4", ""),
								"구매처"	=>	array("B.CODE", true, "STAT/20/4", ""),
								"상품코드"	=>	array("B.CODENUMBER", true, "STAT/20/4", ""),
								"원가"	=>	array("B.oprice", true, "STAT/20/4", "")
								
							);
				
		$this->arrTbItem = " ";
	}
}
?>