<?
Class plugin_statview extends main{
	private $Page;
	private $PageG;
	private $intStartNum;
	
	private $plist_link;

	private $how;
	private $how2;
	private $order;
	private $workYear;

	private $board_id;
	private $index;

	function plugin_statview(){
		$this->layout = "main";


		if(!$_GET["Page"]){
			$this->Page = 1;  // 페이지 번호
		}else{
			$this->Page = $_GET["Page"];
		}
		if(!$_GET["PageG"]){ // 페이지 리스트 위치
			$this->PageG = 1;
		}else{
			$this->PageG = $_GET["PageG"];
		}
		if(!$_GET["intStartNum"]){
			$this->intStartNum = 0;
		}else{
			$_GET["intStartNum"] = $this->intStartNum;
		}

		if($_GET[how] == "desc"){
			$this->how = "asc";
			$this->how2 = "desc";
		}else{
			$this->how = "desc";
			$this->how2 = "asc";
		}

		if (!$_GET[order]){
			$this->order = GET_FIELD_NAME("일련번호");
			$this->how = "desc";
		}else{
			$this->order = $_GET["order"];
			$this->how = $_GET["how"];
		}	

		if (!$_GET["workYear"]){
			$this->workYear = date('Y');
		}else{
			$this->workYear = $_GET["workYear"];
		}

		$this->index = $_GET["index"];
		$this->board_id = $_GET["board_id"];
		
	

	}

	public function search(){
		$dbObj = LOAD_MODULE("Board.FormList");
		
		$arrTbList = array(
								"일련번호"		=>array("일련번호"),
								"유형별번호"	=>array("사이트","코드번호"),
								"주문물품"		=>array("주문물품"),
								"이익금"		=>array("이익금"),
								"수령자"		=>array("수령자"),
								"송장번호"		=>array("송장번호"),
								"핸드폰"		=>array("핸드폰"),
								"주소"			=>array("주소"),
								"접수일자"		=>array("접수일자"),
								"비고"			=>array("비고"),
								"상품명"		=>array("상품명"),
								"구매처"		=>array("구매처"),
								"상품코드"		=>array("상품코드")
							);
		// 검색 설정
		$searchField = array("구매처/10/20","사이트//","상품명/10/20","성별/10/20","상품코드/10/20","접수일자/10/20");

		$dbObj->getStart($arrTbList, $searchField);

			// 검색 중인 상태 일 때
			if($_GET["searchmode"] == "TRUE"){
				$dbObj->setSearchConditionbyGet();
				$dbObj->setQuery();
				
			}else{
				$dbObj->setOrderQuery($this->order, $this->how);
			}
			$dbObj->setOrderType($this->how); //정렬 순서를 바꿈
			
		
			$Link=array("board_id"=>$this->board_id);
			$commonLink = "/".$this->element."/Pview?".$dbObj->setLink($Link)."&";


			// 페이지에서 넘겨줘야할 get값
			$numLink=array(
				"basedata"=>base64_encode($likeis),
				"order"=>$this->order,
				"how"=>$this->how2,
				"field"=>$_GET[field],
				"fieldvalue"=>$_GET[fieldvalue],
				"searchmode"=>$_GET[searchmode],
				"board_id"=>$this->board_id

			);


			// 이 메소드를 실행하면 페이져가 작동한다.
			if($_GET["searchmode"] != "TRUE"){
				$dbObj->setPageInfo($this->Page, $this->PageG, "/".$this->element."/Plist?".$dbObj->setLink($numLink));
			}
			// addElementMOD("필드 alias", 링크)
			$dbObj->addElementMOD("일련번호",	"");
			$dbObj->addElementMOD("유형별번호",	"");
			$dbObj->addElementMOD("주문물품",	"");
			$dbObj->addElementMOD("이익금",		"");
			$dbObj->addElementMOD("수령자",		"");
			$dbObj->addElementMOD("송장번호",	"");
			$dbObj->addElementMOD("핸드폰",		"");
			$dbObj->addElementMOD("주소",		"");
			$dbObj->addElementMOD("접수일자",	"");
			$dbObj->addElementMOD("비고",		"");
			$dbObj->addElementMOD("상품명",		"");
			$dbObj->addElementMOD("구매처",		"");
			$dbObj->addElementMOD("상품코드",		"");


			$outputdata = $dbObj->getEnd();

		// 순이익금 합계 계산
		$sumprofit = number_format($dbObj->SumRecordRow(GET_FIELD_NAME("이익금")));
		$totalprofit =  number_format($dbObj->SumRecordRow(GET_FIELD_NAME("원가")));

		$cntRow =  number_format($dbObj->countConditionRecordRow(GET_FIELD_NAME("반품유무")."!='Y'"));
		$cntReturnRow =  number_format($dbObj->countConditionRecordRow(GET_FIELD_NAME("반품유무")."='Y'"));


		$this->display(array("searchOutputData"		=>$searchOutputData,
							 "listHeader"			=>$outputdata["header"],
							 "outputData"			=>$outputdata["rowdata"],
							 "outputSearchBoxData"	=>$outputdata["searchbox"],
							 "outputFormData"		=>$outputdata["rowformdata"],
							 "pagelist"				=>$outputdata["page"],
							 "SEARCH_MODE"			=>$_GET["searchmode"],
							 "SUM_PROFIT"			=>$sumprofit,
							 "TOTAL_PROFIT"			=>$totalprofit,
							 "COUNT_ROW"			=>$cntRow,
							 "COUNT_RETURN_ROW"		=>$cntReturnRow,
							 "DB_OBJECT"=>$dbObj));
	}
}

	?>