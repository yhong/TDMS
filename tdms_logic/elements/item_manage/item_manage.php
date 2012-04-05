<?
Class item_manage extends Element{
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

	function item_manage(){
		$this->layout = "subpage";

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

		if ($_GET[order] == ""){
			$this->order = "id";
			$this->how = "desc";
		}else{
			$this->order = $_GET[order];
		}	

		if (!$_GET["workYear"]){
			$this->workYear = date('Y');
		}else{
			$this->workYear = $_GET["workYear"];
		}

		$this->index = $_GET["index"];
		$this->board_id = $_GET["board_id"];

	}

	public function Plist(){
		$this->layout = "subpage";

		$dbObj = LOAD_MODULE("Board.FormList");
		
		$searchField = array("유형//","상품종류//","상품명//","원가/5/20","판매가/10/5","실판매가/10/20","접수일자/10/20");
		$arrTbList = array(
							"일련번호"=>array("하위번호"),
							"유형별번호"=>array("유형","코드번호"),
							"상품명"=>array("상품명"),
							"원가"=>array("원가"),
							"판매가"=>array("판매가"),
							"실판매가"=>array("실판매가"),
							"사이즈정보"=>array("사이즈정보"),
							"특이사항"=>array("특이사항"),
							"상품종류"=>array("상품종류"),
							"접수일자"=>array("접수일자")
						);
		$dbObj->getStart($arrTbList, $searchField);

			// 검색 중인 상태 일 때
			if($_GET["searchmode"] == 1){
				$likeis = $dbObj->setSearchConditionbyGet($_GET);
				$dbObj->setAddLikeis($likeis);
				$dbObj->setQuery();
			}else{

				//$dbObj->setDataLikeis($_POST["field"], $_POST["fieldvalue"]);
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
			$dbObj->setPageInfo($this->Page, $this->PageG, "/".$this->element."/Plist?".$dbObj->setLink($numLink));

			// addElementMOD("필드 alias", 링크)
			$dbObj->addElementMOD("일련번호", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("유형별번호",$commonLink."id=#일련번호#");
			$dbObj->addElementMOD("상품명", $commonLink."id=#일련번호#");

			$dbObj->addElementMOD("원가", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("판매가", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("실판매가", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("사이즈정보", $commonLink."id=#일련번호#");

			$dbObj->addElementMOD("특이사항", $commonLink."id=#일련번호#");

			$dbObj->addElementMOD("상품종류", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("접수일자", $commonLink."fieldvalue=#접수일자#");

			$outputdata = $dbObj->getEnd();


		$this->display(array("searchOutputData"=>$searchOutputData,
							 "listHeader"=>$outputdata["header"],
							 "outputData"=>$outputdata["rowdata"],
							 "outputSearchBoxData"=>$outputdata["searchbox"],
							 "outputFormData"=>$outputdata["rowformdata"],
							 "pagelist"=>$outputdata["page"]));
	}



	public function Pview(){

		$dbObj = LOAD_MODULE("Board.FormSelect");

		$dbObj->getStart($_GET[id]);


		// 맨 마지막 인자는 스크립트를 넣는 것임 #VALUE#로 값을 치환할수 있음
		$dbObj->AddElement($dbObj->TextElement("일련번호"), "" , "");
		$dbObj->AddElement2("유형별번호",
							$dbObj->TextElement("유형"),
							$dbObj->TextElement("코드번호"),
							"", ""
							); 
		$dbObj->AddElement($dbObj->TextElement("원가"),"","");
		$dbObj->AddElement($dbObj->TextElement("판매가"), "", "");
		$dbObj->AddElement($dbObj->TextElement("실판매가"), "", "");
		$dbObj->AddElement($dbObj->TextElement("사이즈정보"), "", "");
		$dbObj->AddElement($dbObj->TextElement("특이사항"), "", "");
		$dbObj->AddElement($dbObj->TextElement("상품종류"), "", "");
		$dbObj->AddElement($dbObj->TextElement("접수일자"), "", "");

		$outputdata = $dbObj->getEnd();

		$this->display(array("outputData"=>$outputdata["rowdata"]));
	}


	/*
	* 입력 페이지 액션처리(html 템플릿 페이지를 사용하지 않는다 -> goLink 반드시 사용)
	*/
	public function Pinsert_Action(){
		$dbObj = LOAD_MODULE("Board.FormInsert");
		$dbObj->getStart(null);
		$dbObj->setInsertQuery();
		$dbObj->executeQuery();
		$this->goLink("/".$this->element."/Plist&board_id=".urlencode($_GET[board_id]));
		exit;
	}

	/*
	* 입력 페이지 처리
	*/
	public function Pinsert(){
		$dbObj = LOAD_MODULE("Board.FormInsert");

		// id값을 준다.. 필요없으므로 null로 함
		$dbObj->getStart(null);


		$Link=array("id"=>$_GET[id], "board_id"=>$_GET[board_id]);
		$dbObj->setLink($Link);

		$dbObj->AddElement2("유형별번호", 
							$dbObj->formElement("유형", "", ""),
							$dbObj->formElement("코드번호", "", ""),
							"");
		$dbObj->AddElement($dbObj->formElement("상품명", $_GET["item_name"], ""), "");
		$dbObj->AddElement($dbObj->formElement("원가", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("판매가", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("실판매가", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("사이즈정보", ".", ""), "");
		$dbObj->AddElement($dbObj->formElement("특이사항", ".", ""), "");
		$dbObj->AddElement($dbObj->formElement("상품종류", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("접수일자", date("Y-m-d"), ""), "");

		
		$outputdata = $dbObj->getEnd();

		$this->display(array("outputData"=>$outputdata["rowdata"],
							 "strLink"=>$dbObj->getLink()
		));
	}

	/*
	* 입력 페이지 액션처리(html 템플릿 페이지를 사용하지 않는다 -> goLink 반드시 사용)
	*/
	public function Pupdate_Action(){
		$dbObj = LOAD_MODULE("Board.FormUpdate");
		$dbObj->getStart($_GET[id]); // 반드시 포함해야 함

		$dbObj->setWorkflag(true); // WorkField에 해당하지 않는 필드만
		$dbObj->setWorkField(array());
		$dbObj->setUpdateQuery();
		$dbObj->executeQuery();
		$this->goLink("/".$this->element."/Plist&board_id=".urlencode($_GET[board_id]));
		exit;
	}

	/*
	* 입력 페이지 처리
	*/
	public function Pupdate(){

		$dbObj = LOAD_MODULE("Board.FormUpdate");
		$dbObj->getStart($_GET[id]);

		$Link=array("id"=>$_GET[id], "board_id"=>$_GET[board_id]);
		$dbObj->setLink($Link);

		$dbObj->AddElement2("유형별번호", 
							$dbObj->formElement("유형", "", ""),
							$dbObj->formElement("코드번호", "", ""),
							"");
		$dbObj->AddElement($dbObj->formElement("상품명", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("원가", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("판매가", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("실판매가", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("사이즈정보", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("특이사항", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("상품종류", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("접수일자", date("Y-m-d"), ""), "");

		
		$outputdata = $dbObj->getEnd();

		$this->display(array("outputData"=>$outputdata["rowdata"],
							 "strLink"=>$dbObj->getLink()
		));
	}

	/*
	* 입력 페이지 액션처리(html 템플릿 페이지를 사용하지 않는다 -> goLink 반드시 사용)
	*/
	public function Pdelete_Action(){
		$dbObj = LOAD_MODULE("Board.FormDelete");
		$dbObj->getStart($_GET[id]);
		$dbObj->setDeleteQuery();
		$dbObj->executeQuery();
		$this->goLink("/".$this->element."/Plist&board_id=".urlencode($_GET[board_id]));
	}

	/*
	* 입력 페이지 처리
	*/
	public function Pdelete(){
		$dbObj = LOAD_MODULE("Board.FormDelete");
		$dbObj->getStart($_GET[id]);
		
		
		$Link=array("id"=>$_GET[id], "board_id"=>$_GET[board_id]);
		$dbObj->setLink($Link);


		$this->display(array("strLink"=>$dbObj->getLink()));
	}


   /*
	* 통계 페이지 처리
	*/
	public function Pstatistics(){
		$dbObj = LOAD_MODULE("Board.FormStatistics");
		$dbObj->getStart($_GET[id]);
		
		
		$Link=array("id"=>$_GET[id], "board_id"=>$_GET[board_id]);
		$dbObj->setLink($Link);


		$this->display(array("strLink"=>$dbObj->getLink()));
	}
}

	?>