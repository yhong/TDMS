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
			$this->Page = 1;  // ������ ��ȣ
		}else{
			$this->Page = $_GET["Page"];
		}
		if(!$_GET["PageG"]){ // ������ ����Ʈ ��ġ
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
			$this->order = GET_FIELD_NAME("�Ϸù�ȣ");
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


		$searchField = array("����//",
		
									"������/10/20",
											
									"������/10/20","�ڵ���/10/20","�������/10/20",
									"��ǰ�ڵ�/10/20","�Ǹž��̵�/10/20","�����ȣ/10/20",
											
								
									"������ȣ/10/20","��ǰ����/10/20","����������/10/20","��������/10/20","�ּ�/10/20","�ֹ���ǰ/10/20"
			
		);
		$arrTbList = array(
								"�Ϸù�ȣ"=>array("������ȣ"),
								"��������ȣ"=>array("����","�ڵ��ȣ"),
			
								"������"=>array("������"),
								"������"=>array("������"),
								"�ڵ���"=>array("�ڵ���"),
						//		"�����ȣ"=>array("�����ȣ"),
						//		"�������"=>array("�������"),
						//		"��ǰ�ڵ�"=>array("��ǰ�ڵ�"),
								"�Ǹž��̵�"=>array("�Ǹž��̵�"),
								"�ֹ���ǰ"=>array("�ֹ���ǰ"),
						//		"������ȣ"=>array("������ȣ"),
					//			"��ǰ����"=>array("��ǰ����"),
					//			"����������"=>array("����������"),
								"�ּ�"=>array("�ּ�"),
								"��������"=>array("��������")
					//			"�䱸����"=>array("�䱸����")
							);
		$dbObj->getStart($arrTbList, $searchField);

			// �˻� ���� ���� �� ��
			if($_GET["searchmode"] == 1){
				$likeis = $dbObj->setSearchConditionbyGet($_GET);
				$dbObj->setAddLikeis($likeis);
				$dbObj->setQuery();
			}else{

				//$dbObj->setDataLikeis($_POST["field"], $_POST["fieldvalue"]);
				$dbObj->setOrderQuery($this->order, $this->how);
			}
			$dbObj->setOrderType($this->how); //���� ������ �ٲ�
			
		
			$Link=array("board_id"=>$this->board_id);
			$commonLink = "/".$this->element."/Pview?".$dbObj->setLink($Link)."&";

			$totalnum =  $dbObj->countConditionRecordRow(null);
			

			// ���������� �Ѱ������ get��
			$numLink=array(
				"basedata"=>base64_encode($likeis),
				"order"=>$this->order,
				"how"=>$this->how2,
				"field"=>$_GET[field],
				"fieldvalue"=>$_GET[fieldvalue],
				"searchmode"=>$_GET[searchmode],
				"board_id"=>$this->board_id
			);

			// �� �޼ҵ带 �����ϸ� �������� �۵��Ѵ�.
			$dbObj->setPageInfo($this->Page, $this->PageG, "/".$this->element."/Plist?".$dbObj->setLink($numLink));

			// addElementMOD("�ʵ� alias", ��ũ)
			$dbObj->addElementMOD("�Ϸù�ȣ", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("��������ȣ",$commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("������", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("������", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("�ڵ���", $commonLink."id=#�Ϸù�ȣ#");
			//$dbObj->addElementMOD("�����ȣ", $commonLink."id=#�Ϸù�ȣ#");
			//$dbObj->addElementMOD("�������", $commonLink."id=#�Ϸù�ȣ#");
			//$dbObj->addElementMOD("��ǰ�ڵ�", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("�Ǹž��̵�", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("�ֹ���ǰ", $commonLink."id=#�Ϸù�ȣ#");
		//	$dbObj->addElementMOD("������ȣ", $commonLink."id=#�Ϸù�ȣ#");
		//	$dbObj->addElementMOD("��ǰ����", $commonLink."id=#�Ϸù�ȣ#");
		//	$dbObj->addElementMOD("����������", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("�ּ�", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("��������",  $commonLink."id=#�Ϸù�ȣ#");
		//	$dbObj->addElementMOD("�䱸����", $commonLink."id=#�Ϸù�ȣ#");

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


		// �� ������ ���ڴ� ��ũ��Ʈ�� �ִ� ���� #VALUE#�� ���� ġȯ�Ҽ� ����
		$dbObj->AddElement($dbObj->TextElement("�Ϸù�ȣ"), "" , "");
		$dbObj->AddElement2("��������ȣ",
							$dbObj->TextElement("����"),
							$dbObj->TextElement("�ڵ��ȣ"),
							"", ""
							); 
		$dbObj->AddElement($dbObj->TextElement("������"),"","");
		$dbObj->AddElement($dbObj->TextElement("�ּ�"), "", "");
		$dbObj->AddElement($dbObj->TextElement("��ȭ��ȣ"), "", "");
		$dbObj->AddElement($dbObj->TextElement("�ڵ���"), "", "");
		$dbObj->AddElement($dbObj->TextElement("�������"), "", "");
		$dbObj->AddElement($dbObj->TextElement("��ǰ�ڵ�"), "", "");
		$dbObj->AddElement($dbObj->TextElement("�䱸����"), "", "");
		$dbObj->AddElement($dbObj->TextElement("�Ǹž��̵�"), "", "");

		$dbObj->AddElement($dbObj->TextElement("�����ȣ"), "", "");

		$dbObj->AddElement($dbObj->TextElement("������"), "", "");
		$dbObj->AddElement($dbObj->TextElement("����"), "", "");
		$dbObj->AddElement($dbObj->TextElement("�̸���"), "", "");
		$dbObj->AddElement($dbObj->TextElement("�ֹ���ǰ"), "", "");
		$dbObj->AddElement($dbObj->TextElement("���ͱ�"), "", "");
		$dbObj->AddElement($dbObj->TextElement("������ȣ"), "", "");
		$dbObj->AddElement($dbObj->TextElement("��������"), "", "");
		$dbObj->AddElement($dbObj->TextElement("��ǰ����"), "", "");
		$dbObj->AddElement($dbObj->TextElement("����������"), "", "");
		$dbObj->AddElement($dbObj->TextElement("���"), "", "");


		$outputdata = $dbObj->getEnd();

		$this->display(array("outputData"=>$outputdata["rowdata"]));
	}


	/*
	* �Է� ������ �׼�ó��(html ���ø� �������� ������� �ʴ´� -> goLink �ݵ�� ���)
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
	* �Է� ������ ó��
	*/
	public function Pinsert(){
		$dbObj = LOAD_MODULE("Board.FormInsert");

		// id���� �ش�.. �ʿ�����Ƿ� null�� ��
		$dbObj->getStart(null);

		$Link=array("id"=>$_GET[id], "board_id"=>$_GET[board_id]);
		$dbObj->setLink($Link);


		$dbObj->AddElement2("��������ȣ", 
							$dbObj->formElement("����", "", ""),
							$dbObj->formElement("�ڵ��ȣ", "", ""),
							"");
		$dbObj->AddElement($dbObj->formElement("������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�ּ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��ȭ��ȣ", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�ڵ���", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��ǰ�ڵ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�䱸����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�Ǹž��̵�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�����ȣ", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�̸���", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�ֹ���ǰ", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("���ͱ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("������ȣ", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��������", date("Y-m-d"), ""), "");
		$dbObj->AddElement($dbObj->formElement("��ǰ����", "N", ""), "");
		$dbObj->AddElement($dbObj->formElement("����������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("���", "", ""), "");

		$outputdata = $dbObj->getEnd();

		$this->display(array("outputData"=>$outputdata["rowdata"],
							 "strLink"=>$dbObj->getLink()
		));
	}

	/*
	* �Է� ������ �׼�ó��(html ���ø� �������� ������� �ʴ´� -> goLink �ݵ�� ���)
	*/
	public function Pupdate_Action(){
		$dbObj = LOAD_MODULE("Board.FormUpdate");
		$dbObj->getStart($_GET[id]); // �ݵ�� �����ؾ� ��

		$dbObj->setWorkflag(true); // WorkField�� �ش����� �ʴ� �ʵ常
		$dbObj->setWorkField(array());
		$dbObj->setUpdateQuery();

		$dbObj->executeQuery();
		$this->goLink("/".$this->element."/Plist&board_id=".urlencode($_GET[board_id]));
		exit;
	}

	/*
	* �Է� ������ ó��
	*/
	public function Pupdate(){

		$dbObj = LOAD_MODULE("Board.FormUpdate");
		$dbObj->getStart($_GET[id]);

		$Link=array("id"=>$_GET[id], "board_id"=>$_GET[board_id]);
		$dbObj->setLink($Link);

		$dbObj->AddElement2("��������ȣ", 
							$dbObj->formElement("����", "", ""),
							$dbObj->formElement("�ڵ��ȣ", "", ""),
							"");
		$dbObj->AddElement($dbObj->formElement("������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�ּ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��ȭ��ȣ", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�ڵ���", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��ǰ�ڵ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�䱸����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�Ǹž��̵�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�����ȣ", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�̸���", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�ֹ���ǰ", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("���ͱ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("������ȣ", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��ǰ����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("����������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("���", "", ""), "");

		
		$outputdata = $dbObj->getEnd();

		$this->display(array("outputData"=>$outputdata["rowdata"],
							 "strLink"=>$dbObj->getLink()
		));
	}

	/*
	* �Է� ������ �׼�ó��(html ���ø� �������� ������� �ʴ´� -> goLink �ݵ�� ���)
	*/
	public function Pdelete_Action(){
		$dbObj = LOAD_MODULE("Board.FormDelete");
		$dbObj->getStart($_GET[id]);
		$dbObj->setDeleteQuery();
		$dbObj->executeQuery();
		$this->goLink("/".$this->element."/Plist&board_id=".urlencode($_GET[board_id]));
	}

	/*
	* �Է� ������ ó��
	*/
	public function Pdelete(){
		$dbObj = LOAD_MODULE("Board.FormDelete");
		$dbObj->getStart($_GET[id]);
		
		
		$Link=array("id"=>$_GET[id], "board_id"=>$_GET[board_id]);
		$dbObj->setLink($Link);


		$this->display(array("strLink"=>$dbObj->getLink()));
	}


   /*
	* ��� ������ ó��
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
	* �ϰ� �Է� ������ ó��
	*/
	public function import(){
		$dbObj = LOAD_MODULE("Board.Form");
		$dbconn = $dbObj->getConResource();

		LOAD_LIBRARY("Excel/reader");
		/*
		if(trim($_FILES["excel_file"]["type"]) != "application/vnd.ms-excel"){
			echo "<script>alert('���������� �����ϼž� �մϴ�.');history.back(-1);</script>";
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
						$arrPreOutput[$j][5] = "<font style='color:red;font-weight:bold'>��ǰ��Ͼȵ�</font>";
						$testFlag = false;
					}else{
						$arrPreOutput[$j][4] = $arrInfo[0];
						$arrPreOutput[$j][5] = "<font style='color:green;font-weight:bold'>��ǰ��ϵ�</font>";
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
							$arrOutput[$j][5] = "<font style='color:red;font-weight:bold'>��ǰ��Ͼȵ�</font>";
						}else{
							$arrOutput[$j][4] = $arrInfo[0];
							$res =& $dbconn->execute($sth, $data_row);
							
							if (PEAR::isError($res)) {
								//die($res->getMessage());
								$arrOutput[$j][5] = "<font style='color:red;font-weight:bold'>����</font>";

							}else{
								$arrOutput[$j][5] = "<font style='color:green;font-weight:bold'>�Ϸ�</font>";
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