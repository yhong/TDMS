<?
Class sell_manage extends Element{
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

	function sell_manage(){
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

	public function Plist(){
		$this->layout = "subpage";

		$dbObj = LOAD_MODULE("Board.FormList");


		$searchField = array("유형//",
		
									"수령자/10/20",
											
									"구매자/10/20","핸드폰/10/20","배송형태/10/20",
									"상품코드/10/20","판매아이디/10/20","송장번호/10/20",
											
								
									"낙찰번호/10/20","반품유무/10/20","사이즈정보/10/20","접수일자/10/20","주소/10/20","주문물품/10/20"
			
		);
		$arrTbList = array(
								"일련번호"=>array("하위번호"),
								"유형별번호"=>array("유형","코드번호"),
			
								"수령자"=>array("수령자"),
								"구매자"=>array("구매자"),
								"핸드폰"=>array("핸드폰"),
						//		"송장번호"=>array("송장번호"),
						//		"배송형태"=>array("배송형태"),
						//		"상품코드"=>array("상품코드"),
								"판매아이디"=>array("판매아이디"),
								"주문물품"=>array("주문물품"),
						//		"낙찰번호"=>array("낙찰번호"),
					//			"반품유무"=>array("반품유무"),
					//			"사이즈정보"=>array("사이즈정보"),
								"주소"=>array("주소"),
								"접수일자"=>array("접수일자")
					//			"요구사항"=>array("요구사항")
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

			$totalnum =  $dbObj->countConditionRecordRow(null);
			

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
			$dbObj->addElementMOD("수령자", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("구매자", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("핸드폰", $commonLink."id=#일련번호#");
			//$dbObj->addElementMOD("송장번호", $commonLink."id=#일련번호#");
			//$dbObj->addElementMOD("배송형태", $commonLink."id=#일련번호#");
			//$dbObj->addElementMOD("상품코드", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("판매아이디", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("주문물품", $commonLink."id=#일련번호#");
		//	$dbObj->addElementMOD("낙찰번호", $commonLink."id=#일련번호#");
		//	$dbObj->addElementMOD("반품유무", $commonLink."id=#일련번호#");
		//	$dbObj->addElementMOD("사이즈정보", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("주소", $commonLink."id=#일련번호#");
			$dbObj->addElementMOD("접수일자",  $commonLink."id=#일련번호#");
		//	$dbObj->addElementMOD("요구사항", $commonLink."id=#일련번호#");

			$outputdata = $dbObj->getEnd();

		$rownumperpage =  $dbObj->getPagePerRow();

		$this->display(array("searchOutputData"=>$searchOutputData,
							 "listHeader"=>$outputdata["header"],
							 "outputData"=>$outputdata["rowdata"],
							 "outputSearchBoxData"=>$outputdata["searchbox"],
							 "outputFormData"=>$outputdata["rowformdata"],
							 "pagelist"=>$outputdata["page"],
							"total_data_row"=>$totalnum,
							"RowPerPage"=>$rownumperpage,
							 "DB_OBJECT"=>$dbObj));
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
		$dbObj->AddElement($dbObj->TextElement("수령자"),"","");
		$dbObj->AddElement($dbObj->TextElement("주소"), "", "");
		$dbObj->AddElement($dbObj->TextElement("전화번호"), "", "");
		$dbObj->AddElement($dbObj->TextElement("핸드폰"), "", "");
		$dbObj->AddElement($dbObj->TextElement("배송형태"), "", "");
		$dbObj->AddElement($dbObj->TextElement("상품코드"), "", "");
		$dbObj->AddElement($dbObj->TextElement("요구사항"), "", "");
		$dbObj->AddElement($dbObj->TextElement("판매아이디"), "", "");

		$dbObj->AddElement($dbObj->TextElement("송장번호"), "", "");

		$dbObj->AddElement($dbObj->TextElement("구매자"), "", "");
		$dbObj->AddElement($dbObj->TextElement("성별"), "", "");
		$dbObj->AddElement($dbObj->TextElement("이메일"), "", "");
		$dbObj->AddElement($dbObj->TextElement("주문물품"), "", "");
		$dbObj->AddElement($dbObj->TextElement("이익금"), "", "");
		$dbObj->AddElement($dbObj->TextElement("낙찰번호"), "", "");
		$dbObj->AddElement($dbObj->TextElement("접수일자"), "", "");
		$dbObj->AddElement($dbObj->TextElement("반품유무"), "", "");
		$dbObj->AddElement($dbObj->TextElement("사이즈정보"), "", "");
		$dbObj->AddElement($dbObj->TextElement("비고"), "", "");


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
		$dbObj->AddElement($dbObj->formElement("수령자", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("주소", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("전화번호", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("핸드폰", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("배송형태", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("상품코드", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("요구사항", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("판매아이디", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("송장번호", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("구매자", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("성별", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("이메일", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("주문물품", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("이익금", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("낙찰번호", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("접수일자", date("Y-m-d"), ""), "");
		$dbObj->AddElement($dbObj->formElement("반품유무", "N", ""), "");
		$dbObj->AddElement($dbObj->formElement("사이즈정보", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("비고", "", ""), "");

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
		$dbObj->AddElement($dbObj->formElement("수령자", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("주소", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("전화번호", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("핸드폰", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("배송형태", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("상품코드", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("요구사항", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("판매아이디", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("송장번호", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("구매자", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("성별", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("이메일", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("주문물품", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("이익금", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("낙찰번호", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("접수일자", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("반품유무", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("사이즈정보", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("비고", "", ""), "");

		
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



	public function import_form(){

	}


	/*
	* 일괄 입력 페이지 처리
	*/
	public function import(){
		$dbObj = LOAD_MODULE("Board.Form");
		$dbconn = $dbObj->getConResource();

		LOAD_LIBRARY("Excel/reader");
		/*
		if(trim($_FILES["excel_file"]["type"]) != "application/vnd.ms-excel"){
			echo "<script>alert('엑셀파일을 선택하셔야 합니다.');history.back(-1);</script>";
		}*/


		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP949');
		$data->read($_FILES["excel_file"]["tmp_name"]);
		error_reporting(E_ALL ^ E_NOTICE);

		$excel_insert_query = "INSERT INTO tdms_sell_manage (";
		$excel_insert_query .= "getname,address,tel,mobile,siteid,status,itemid,sizeinfo,request,buyname,sn,itemcode,buynumber,CODE,takendate,isreturn,CODENUMBER,profit";
		$excel_insert_query .= ") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		
		$sth = $dbconn->prepare($excel_insert_query);
		if (PEAR::isError($sth)) {
			die($sth->getMessage());
		}
		
		if($_POST["register_date"]){
			$register_date = $_POST["register_date"];
		}else{
			$register_date = date("Y-m-d");
		}


		$testFlag = true;

		for ($j = 1,$i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
				$arrTRD = $data->sheets[0]['cells'][$i];
				
				$arrdata = explode(',', $arrTRD[9]);
				
				foreach($arrdata as $value){
					$arrInfo = explode('/', $value);

					$codenumber = $dbconn->getOne("select max(CODENUMBER) from tdms_sell_manage where CODE='".trim($arrTRD[17])."'");
					$itemid = $dbconn->getOne("select ID from tdms_item_manage where name='".trim($arrInfo[0])."'");

					$data_row = array($arrTRD[1], "[".$arrTRD[2]."] ".$arrTRD[3], $arrTRD[4], $arrTRD[5], $arrTRD[7], $arrTRD[8],$itemid,$arrInfo[1], $arrTRD[10], $arrTRD[11], $arrTRD[12],$arrTRD[13], $arrTRD[15], trim($arrTRD[17]),$register_date,"N",($codenumber+1));
					
					$arrPreOutput[$j][0] = $j++;
					$arrPreOutput[$j][1] = $arrTRD[1]." / ".$arrTRD[11];
					$arrPreOutput[$j][2] = "[".$arrTRD[2]."] ".$arrTRD[3];
					$arrPreOutput[$j][3] = $arrTRD[4]." / ".$arrTRD[5];
					

					if(!$itemid){
						$arrPreOutput[$j][4] = "<a href='/item_manage/Pinsert?item_name=".$arrInfo[0]."' target='_blank'>".$arrInfo[0]."</a>";
						$arrPreOutput[$j][5] = "<font style='color:red;font-weight:bold'>상품등록안됨</font>";
						$testFlag = false;
					}else{
						$arrPreOutput[$j][4] = $arrInfo[0];
						$arrPreOutput[$j][5] = "<font style='color:green;font-weight:bold'>상품등록됨</font>";
					}
				}
			}


		if($testFlag == true){
				for ($j = 1,$i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
					$arrTRD = $data->sheets[0]['cells'][$i];
					
					$arrdata = explode(',', $arrTRD[9]);
					
					foreach($arrdata as $value){
						$arrInfo = explode('/', $value);

						$codenumber = $dbconn->getOne("select max(CODENUMBER) from tdms_sell_manage where CODE='".trim($arrTRD[17])."'");
						


						$array_item_data =&$dbconn->getRow("select ID,oprice,rprice from tdms_item_manage where name='".trim($arrInfo[0])."'",array(), DB_FETCHMODE_ASSOC);


						
						$itemid = $array_item_data[ID];
						$profit = $array_item_data[rprice] - $array_item_data[oprice];



						$data_row = array($arrTRD[1], "[".$arrTRD[2]."] ".$arrTRD[3], $arrTRD[4], $arrTRD[5], $arrTRD[7], $arrTRD[8],$itemid,$arrInfo[1], $arrTRD[10], $arrTRD[11], $arrTRD[12],$arrTRD[13], $arrTRD[15], trim($arrTRD[17]),$register_date,"N",($codenumber+1), $profit);
						
						$arrOutput[$j][0] = $j++;
						$arrOutput[$j][1] = $arrTRD[1]." / ".$arrTRD[11];
						$arrOutput[$j][2] = "[".$arrTRD[2]."] ".$arrTRD[3];
						$arrOutput[$j][3] = $arrTRD[4]." / ".$arrTRD[5];
						

						if(!$itemid){
							$arrOutput[$j][4] = "<a href='/item_manage/Pinsert?item_name=".$arrInfo[0]."' target='_blank'>".$arrInfo[0]."</a>";
							$arrOutput[$j][5] = "<font style='color:red;font-weight:bold'>상품등록안됨</font>";
						}else{
							$arrOutput[$j][4] = $arrInfo[0];
							$res =& $dbconn->execute($sth, $data_row);
							
							if (PEAR::isError($res)) {
								//die($res->getMessage());
								$arrOutput[$j][5] = "<font style='color:red;font-weight:bold'>에러</font>";

							}else{
								$arrOutput[$j][5] = "<font style='color:green;font-weight:bold'>완료</font>";
							}
						}
					}
				}


		} else{// end if
			$arrOutput = $arrPreOutput;
		}
		$this->display(array("arrOutput"=>$arrOutput));
	}
}

	?>