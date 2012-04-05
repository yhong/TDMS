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

	public function search(){
		$dbObj = LOAD_MODULE("Board.FormList");
		
		$arrTbList = array(
								"�Ϸù�ȣ"		=>array("�Ϸù�ȣ"),
								"��������ȣ"	=>array("����Ʈ","�ڵ��ȣ"),
								"�ֹ���ǰ"		=>array("�ֹ���ǰ"),
								"���ͱ�"		=>array("���ͱ�"),
								"������"		=>array("������"),
								"�����ȣ"		=>array("�����ȣ"),
								"�ڵ���"		=>array("�ڵ���"),
								"�ּ�"			=>array("�ּ�"),
								"��������"		=>array("��������"),
								"���"			=>array("���"),
								"��ǰ��"		=>array("��ǰ��"),
								"����ó"		=>array("����ó"),
								"��ǰ�ڵ�"		=>array("��ǰ�ڵ�")
							);
		// �˻� ����
		$searchField = array("����ó/10/20","����Ʈ//","��ǰ��/10/20","����/10/20","��ǰ�ڵ�/10/20","��������/10/20");

		$dbObj->getStart($arrTbList, $searchField);

			// �˻� ���� ���� �� ��
			if($_GET["searchmode"] == "TRUE"){
				$dbObj->setSearchConditionbyGet();
				$dbObj->setQuery();
				
			}else{
				$dbObj->setOrderQuery($this->order, $this->how);
			}
			$dbObj->setOrderType($this->how); //���� ������ �ٲ�
			
		
			$Link=array("board_id"=>$this->board_id);
			$commonLink = "/".$this->element."/Pview?".$dbObj->setLink($Link)."&";


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
			if($_GET["searchmode"] != "TRUE"){
				$dbObj->setPageInfo($this->Page, $this->PageG, "/".$this->element."/Plist?".$dbObj->setLink($numLink));
			}
			// addElementMOD("�ʵ� alias", ��ũ)
			$dbObj->addElementMOD("�Ϸù�ȣ",	"");
			$dbObj->addElementMOD("��������ȣ",	"");
			$dbObj->addElementMOD("�ֹ���ǰ",	"");
			$dbObj->addElementMOD("���ͱ�",		"");
			$dbObj->addElementMOD("������",		"");
			$dbObj->addElementMOD("�����ȣ",	"");
			$dbObj->addElementMOD("�ڵ���",		"");
			$dbObj->addElementMOD("�ּ�",		"");
			$dbObj->addElementMOD("��������",	"");
			$dbObj->addElementMOD("���",		"");
			$dbObj->addElementMOD("��ǰ��",		"");
			$dbObj->addElementMOD("����ó",		"");
			$dbObj->addElementMOD("��ǰ�ڵ�",		"");


			$outputdata = $dbObj->getEnd();

		// �����ͱ� �հ� ���
		$sumprofit = number_format($dbObj->SumRecordRow(GET_FIELD_NAME("���ͱ�")));
		$totalprofit =  number_format($dbObj->SumRecordRow(GET_FIELD_NAME("����")));

		$cntRow =  number_format($dbObj->countConditionRecordRow(GET_FIELD_NAME("��ǰ����")."!='Y'"));
		$cntReturnRow =  number_format($dbObj->countConditionRecordRow(GET_FIELD_NAME("��ǰ����")."='Y'"));


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