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
		
		$searchField = array("����//","��ǰ����//","��ǰ��//","����/5/20","�ǸŰ�/10/5","���ǸŰ�/10/20","��������/10/20");
		$arrTbList = array(
							"�Ϸù�ȣ"=>array("������ȣ"),
							"��������ȣ"=>array("����","�ڵ��ȣ"),
							"��ǰ��"=>array("��ǰ��"),
							"����"=>array("����"),
							"�ǸŰ�"=>array("�ǸŰ�"),
							"���ǸŰ�"=>array("���ǸŰ�"),
							"����������"=>array("����������"),
							"Ư�̻���"=>array("Ư�̻���"),
							"��ǰ����"=>array("��ǰ����"),
							"��������"=>array("��������")
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
			$dbObj->addElementMOD("��ǰ��", $commonLink."id=#�Ϸù�ȣ#");

			$dbObj->addElementMOD("����", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("�ǸŰ�", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("���ǸŰ�", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("����������", $commonLink."id=#�Ϸù�ȣ#");

			$dbObj->addElementMOD("Ư�̻���", $commonLink."id=#�Ϸù�ȣ#");

			$dbObj->addElementMOD("��ǰ����", $commonLink."id=#�Ϸù�ȣ#");
			$dbObj->addElementMOD("��������", $commonLink."fieldvalue=#��������#");

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


		// �� ������ ���ڴ� ��ũ��Ʈ�� �ִ� ���� #VALUE#�� ���� ġȯ�Ҽ� ����
		$dbObj->AddElement($dbObj->TextElement("�Ϸù�ȣ"), "" , "");
		$dbObj->AddElement2("��������ȣ",
							$dbObj->TextElement("����"),
							$dbObj->TextElement("�ڵ��ȣ"),
							"", ""
							); 
		$dbObj->AddElement($dbObj->TextElement("����"),"","");
		$dbObj->AddElement($dbObj->TextElement("�ǸŰ�"), "", "");
		$dbObj->AddElement($dbObj->TextElement("���ǸŰ�"), "", "");
		$dbObj->AddElement($dbObj->TextElement("����������"), "", "");
		$dbObj->AddElement($dbObj->TextElement("Ư�̻���"), "", "");
		$dbObj->AddElement($dbObj->TextElement("��ǰ����"), "", "");
		$dbObj->AddElement($dbObj->TextElement("��������"), "", "");

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
		$dbObj->AddElement($dbObj->formElement("��ǰ��", $_GET["item_name"], ""), "");
		$dbObj->AddElement($dbObj->formElement("����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�ǸŰ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("���ǸŰ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("����������", ".", ""), "");
		$dbObj->AddElement($dbObj->formElement("Ư�̻���", ".", ""), "");
		$dbObj->AddElement($dbObj->formElement("��ǰ����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��������", date("Y-m-d"), ""), "");

		
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
		$dbObj->AddElement($dbObj->formElement("��ǰ��", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("�ǸŰ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("���ǸŰ�", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("����������", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("Ư�̻���", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��ǰ����", "", ""), "");
		$dbObj->AddElement($dbObj->formElement("��������", date("Y-m-d"), ""), "");

		
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
}

	?>