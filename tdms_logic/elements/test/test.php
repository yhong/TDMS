<?
Class test extends Element{
	private $Page;
	private $PageG;
	
	private $how;
	private $how2;
	private $order;

	private $board_id;

	function test(){
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

		if($_GET[how] == "desc"){
			$this->how = "asc";
			$this->how2 = "desc";
		}else{
			$this->how = "desc";
			$this->how2 = "asc";
		}

		if (!$_GET[order]){
			$this->order = "id";
			$this->how = "desc";
		}else{
			$this->order = $_GET[order];

		}	
		$this->board_id = $_GET["board_id"];
	}

	public function Plist(){
		$this->layout = "subpage";

		$dbObj = LOAD_MODULE("Board.FormList");

		// 검색 항목을 추가할 수 있습니다.
		$searchField = array("유형//","코드번호/10/10");
		//////////////////////////////////////////////////
		$arrTbList = array(
							"일련번호"=>array("일련번호"),
							"유형별번호"=>array("유형","코드번호"),
							// 예) "필드명"=>array("필드명"),
						);
		//////////////////////////////////////////////////



		// 필드항목 설정
		////////////////////////////////////////////////////////
		foreach($this->params["config"]->arrTbField as $k=>$v){
			if($k == "일련번호" || $k == "유형" || $k == "코드번호"){continue;}
			$arrTbList[$k] = array($k);
		}
		////////////////////////////////////////////////////////
		// 이 부분을 삭제하고 arrTbList에 필요한 항목만 추가하세요


		$dbObj->getStart($arrTbList, $searchField);

			// 검색 중인 상태 일 때
			if($_GET["searchmode"] == 1){
				$likeis = $dbObj->setSearchConditionbyGet($_GET);
				$dbObj->setAddLikeis($likeis);
				$dbObj->setQuery();
			}else{

				$dbObj->setOrderQuery($this->order, $this->how);
			}

			$dbObj->setOrderType($this->how); //정렬 순서를 바꿈

		
			$Link=array("board_id"=>$this->board_id);
			$commonLink = "/".$this->element."/Pview?".$dbObj->setLink($Link)."&";


			// 페이지에서 넘겨줘야할 get값
			$numLink=array(
				"searchdata"=>base64_encode($likeis),
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

			////////////////////////////////////////////////////////
			foreach($this->params["config"]->arrTbField as $k=>$v){
				if($k == "일련번호" || $k == "유형" || $k == "코드번호"){continue;}
				$arrTbList[$k] = array($k);
				$dbObj->addElementMOD($k, $commonLink."id=#일련번호#");
			}
			////////////////////////////////////////////////////////
			// 이 부분을 삭제하고 arrTbList에 필요한 항목만 추가하세요

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
		////////////////////////////////////////////////////////
		foreach($this->params["config"]->arrTbField as $k=>$v){
			if($k == "일련번호" || $k == "유형" || $k == "코드번호"){continue;}
			$arrTbList[$k] = array($k);
			$dbObj->AddElement($dbObj->TextElement($k), "", "");
		}
		////////////////////////////////////////////////////////
		// 이 부분을 삭제하고 arrTbList에 필요한 항목만 추가하세요
		

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
		////////////////////////////////////////////////////////
		foreach($this->params["config"]->arrTbField as $k=>$v){
			if($k == "일련번호" || $k == "유형" || $k == "코드번호"){continue;}
			$arrTbList[$k] = array($k);
			$dbObj->AddElement($dbObj->formElement($k, "", ""), "");
		}
		////////////////////////////////////////////////////////
		// 이 부분을 삭제하고 arrTbList에 필요한 항목만 추가하세요

		
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
		////////////////////////////////////////////////////////
		foreach($this->params["config"]->arrTbField as $k=>$v){
			if($k == "일련번호" || $k == "유형" || $k == "코드번호"){continue;}
			$arrTbList[$k] = array($k);
			$dbObj->AddElement($dbObj->formElement($k, "", ""), "");
		}
		////////////////////////////////////////////////////////
		// 이 부분을 삭제하고 arrTbList에 필요한 항목만 추가하세요
		
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