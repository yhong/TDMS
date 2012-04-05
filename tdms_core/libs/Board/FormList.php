<?
class Board_FormList extends DBTable_PageTableForm{

	protected $limit_query_result;
	protected $indexNumber;
	protected $arrTitle = array();
	protected $row_context;
	protected $arrTbList;
	protected $arrTbField;
	protected $plugin;
	protected $Element = array();
	protected $targetValue;
	protected $orderType;
	protected $Page;
	protected $PageG;
	protected $PageLink = null;
	protected $joinType;
	protected $joinTarget;
	protected $joinCondition;
	protected $joinOrder;
	protected $Rownum;

	public $OutputData = "";

	

	function Board_FormList(){

		if($dsn = GET_CONFIG("dsn")){
			$this->Database_Manage($dsn);
		}

		$this->joinType			= GET_CONFIG("joinType");
		$this->joinTarget		= GET_CONFIG("joinTarget");
		$this->joinCondition	= GET_CONFIG("joinCondition");
		$this->joinOrder		= GET_CONFIG("joinOrder");


		if($arr_tb_item = GET_CONFIG("arrTbItem")){
			$this->DBTable_TableForm($arr_tb_item);	
		}

		if($tb_name = GET_CONFIG("tbName")){
			$this->setTbName($tb_name);
		}

		$this->plugin = GET_CONFIG("plugin");

		if($arr_tb_field = GET_CONFIG("arrTbField")){
			$this->setField($arr_tb_field); 
			$this->arrTbField = $arr_tb_field;
		}
		
			
		
		$this->row_context = "";
		$this->targetValue = "board";


		if($arrdata = GET_CONFIG("tbColor")){
			$this->tablecolor	   = $arrdata["tableColor"];
			$this->line_tablecolor = $arrdata["lineTableColor"];
			$this->sub_tablecolor  = $arrdata["subTableColor"];
		}

			
		$this->m_table_width = "400";
		$this->setOrderType("desc");
		$this->Rownum = 0;
	}



	// database.connectŬ������ �Լ��� �������̵� ��
	public function setTbName($tbname){
		if($this->joinTarget){
			parent::setTbName($tbname.", ".$this->joinTarget);
		}else{
			$this->tb_name = $tbname;
		}

	}


	

	// �����ϱ� ���� �ʱ�ȭ �ϴ� �Լ�
	public function getStart($arr_tb_list, $searchbox){
		$this->arrfldname = $searchbox;
		$this->arrTbList = $arr_tb_list;
		$this->OutputData["searchbox"] = array();

		$this->OutputData["rowdata"] = array();
		

	}

	
	private function makeHeader(){
		/*
		 * ���̺��� ����� �����Ѵ�.
		 * orderby : �����ϰ��� �ϴ� ��� �ʵ��
		 * orderType : ���Ĺ��
		 * key : ȭ�鿡 ǥ���� ��Ī
		 */
		$this->OutputData["header"] = array();
		$recordIndex = 0;
		while(list($key,) = each($this->Element)){

			$this->OutputData["header"][$recordIndex]["fieldname"] = $this->arr_field[$key][0];
			$this->OutputData["header"][$recordIndex]["ordertype"] = $this->orderType;
			$this->OutputData["header"][$recordIndex]["key"] = $key;

			$recordIndex++;
		} // END FOR

	}

	public function getPagePerRow(){
			return $this->Rownum;
	}

	private function makeRow(){

		if($this->PageLink != null){
			$totalnum = $this->getTotalPageNum(); //��ü ������ ����

			// ����¡
			$pager = new Pager($totalnum, $this->Page, $this->PageG); //������ Ŭ����

			$pager->setPagePerRow(20);
			$pager->setlimitPagenum(20);
			$pager->PageCal();

			$from = $pager->getStartNum();
			$count = $pager->getPagePerRow();
			// �����͸� �����´�.
		
			
			$PagerList = $pager->viewPageNumber($this->PageLink);

			$this->OutputData["page"] = $PagerList;

			// ���� ����
			$this->exe_query($from, $count);
		
		}else{
			
			$this->executeQuery();
		}

		/*
		 * row ����
		 */
		$this->OutputData["rowdata"] = array();
		$this->OutputData["rowformdata"] = array();


		$recordIndex = 0;
		$arrayRow = array();

		while($this->arrRow = $this->input_query_result->fetchRow(DB_FETCHMODE_ORDERED)){
			
			$fieldIndex = 0;
			while(list($key, $arrValue) = each($this->arrTbList)){
			
				foreach($this->arrRow as $k => $v){
					list($a, $b) = each($this->arrTbField);
					$arrayRow[$b[0]] = $v;
				}
				$this->OutputData["rowformdata"][$recordIndex][$this->arrTbField[$key][0]] = $this->AddLinkElement($arrayRow, $arrValue);
				
				$this->OutputData["rowdata"][$recordIndex][$this->arrTbField[$key][0]] = $this->AddTextElement($arrayRow, $arrValue);
				$fieldIndex++;
				reset($this->arrTbField);
			}
			reset($arrayRow);
			reset($this->arrTbList);
			$recordIndex++;
		}

		$this->Rownum = $recordIndex;
	}



	// �˻� �ڽ� ����
	public function makeSearchBox(){
		if(!$this->arrfldname){
			echo "[ERROR] getStart() �޼ҵ带 ���� �������ּ���";
			exit;
		}

		$countname = count($this->arrfldname);
		for($i=0; $i<$countname; $i++){
				$option = explode('/', $this->arrfldname[$i]);
				$fieldName = $this->arrTbField[$option[0]][0];

				$data = $this->searchFormElement($option[0], $option[1], $option[2]);

				$this->OutputData["searchbox"][$fieldName]["title"] = $data[1];
				$this->OutputData["searchbox"][$fieldName]["value"] = $data[0];
		}
		
	}


	// ������� ȭ�� �����͸� ����
	public function getEnd(){
		$this->makeHeader();
		$this->makeRow();
		$this->makeSearchBox();

		return $this->OutputData;
	}



	public function setTarget($input){
		$this->targetValue = $input;
	}

	public function setOrderType($orderType){
		$this->orderType = $orderType;
	}

	public function setPageInfo($page, $page_g, $pagelink){
		$this->Page = $page;
	    $this->PageG = $page_g;
		$this->PageLink = $pagelink;
	}

	/**
	 *  
	 * setDataLikeis()
	 * ���� ���� ����
	 * private
	 * 
	 * @param $field : ������ ���Ǵ� �ʵ� ��(�ʵ��� alias�̹Ƿ� field������ �ٲ��־�� �Ѵ�)
	 * @param $fieldvalue : �ش� �ʵ��� ��
	 *
	 * @return nothing
	*/
	public function setDataLikeis($field, $fieldvalue){
		if($field && $fieldvalue){
			$this->setAddLikeis($this->arr_field[$field][0]."='".$fieldvalue."'");
		}
	}

	
	// dbtable.PageTableFormŬ������ �Լ��� �������̵� ��
	/**
	 *  
	 * setQuery()
	 * ���� ���� ����
	 * private
	 * 
	 * @param $tb_name : ������ ���Ǵ� ���̺� ��
	 *
	 * @return nothing
	*/
	public function setQuery(){

		if($this->joinOrder){
			$join_order = " order by ".$this->joinOrder;
		}else{
			$join_order = "";
		}
			
		if($this->joinTarget && $this->joinCondition){
/*
			if($this->likeis != ""){
				$this->likeis .= " and ";
			}
*/
			$this->likeis .= $this->joinCondition;
		}

		// and ���� ó��
		$cutand = substr(trim($this->likeis),-3);
		if($cutand == "and"){
			$this->likeis = substr(trim($this->likeis),0,-3);
		}

		if($this->likeis){
			$this->query = "select ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis." ".$join_order;
		}else{
			$this->query = "select ".$this->field_kind." from ".$this->tb_name." ".$join_order;
		}
		
		//echo $this->query;
	}	// END FUNCTION


	/**
	 *  
	 * setOrderQuery()
	 * ���� ���� ����
	 * public
	 * 
	 * @param $order	: ���Ŀ� �ʿ��� �ʵ��
	 * @param $orderType		: ���� ����(desc, asc)
	 *
	 * @return nothing
	*/
	public function setOrderQuery($order, $orderType){
			if($order && $orderType && $order != "id"){
				$fieldname = $this->searchFieldName($order);
				/*
				$orderTypename = ($orderType=="desc") ? "��������" : "�ø�����";
				$this->OutputData["row"] .= "<font style='color=blue;font-size=9pt'><b>".$fieldname."</b></font>�� �ʵ带 <font style='color=brown;font-size=9pt'><b>".$orderTypename."</b></font>���� ����";
				$this->OutputData["row"] .= " (<a href=index.php?index=".$this->targetValue."&".$this->targetValue."_index=Plist>�˻��ʱ�ȭ</a>)";
			*/
			}

			if($this->joinTarget && $this->joinCondition){
				
				$this->likeis .= $this->joinCondition;
			}

			if($this->likeis){
				$this->query = "select ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis." order by ".$order." ".$orderType;
			}else{
				$this->query = "select ".$this->field_kind." from ".$this->tb_name." order by ".$order." ".$orderType;
			}
			
	}	// END FUNCTION


	
	/**
	 *  
	 * countConditionRecordRow()
	 * ������ ���� ������ ��� �� ������ ������ (database.connect���� �������̵� ��)
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $total_num : ������ ���ڵ��
	*/
	public function countConditionRecordRow($likeis){

		if($this->joinTarget && $this->joinCondition){
				//if($this->likeis != ""){
				//	$this->likeis .= " and ";
				//}
				$this->likeis .= $this->joinCondition;
		}

		if($likeis){
			if($this->likeis != ""){
					$this->likeis .= " and ";
			}
			$this->likeis .= $likeis;

			$cntsql = "select count(*) from ".$this->tb_name." where ".$this->likeis;
		}else{
			if($this->joinTarget && $this->joinCondition){
					$cntsql = "select count(*) from ".$this->tb_name." where ".$this->joinCondition;
			} else{
				$cntsql = "select count(*) from ".$this->tb_name;
			}
		}
		$total_num = $this->db_conn->getOne($cntsql);
		return $total_num;
	}


	public function setSearchConditionbyGet(){
		foreach($this->arrTbField as $key=>$option_value){
			
			//if($_GET[str_replace('.', '_', $option_value[0])]){
				
				// get���� .�� _�� �ٲ�� ����
				$likeis = $this->searchConditionElement(str_replace('.', '_', $option_value[0]),$option_value[1],$option_value[3], $option_value[2]);
				if($likeis){
					$this->setAddLikeis(str_replace('_', '.', $likeis));
				}

			//}
		}
	}

	private function searchConditionElement($fieldName, $isString, $formExtraValue, $formType){

			$opt=explode('/', $formType);
			if($formType){ // �� Ÿ���� ���ǵǾ� ���� ����
				$field_code = $opt[0];
				
					if(file_exists(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
					require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

					if (class_exists($field_code)) {
						$component =& new $field_code(null,$isString,$fieldName,$formExtraValue);
						if(method_exists($component, "blockSearchCondition")){
							$result_condition = $component->blockSearchCondition(null, $fieldName, $script, $opt);
						}else{
							require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
							$component =& new ETC(null,$isString,$fieldName,$formExtraValue);
							$result_condition = $component->blockSearchCondition($name, $fieldName, $script, $opt);
						}
					}
				}else{
					
					// ����� ���� ������Ʈ�� �ִ��� üũ
					if(file_exists(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
						require_once(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");
						
						if (class_exists($field_code)) {
							
							$component =& new $field_code(null,$isString,$fieldName,$formExtraValue);
							if(method_exists($component, "blockSearchCondition")){
							
								$result_condition = $component->blockSearchCondition(null, $fieldName, $script, $opt);
								
							}else{
								require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
								$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
								$result_condition = $component->blockSearchCondition(null, $fieldName, $script, $opt);
							}
							
						}
					}else{
						if($this->plugin != null){
								if(file_exists(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
									require_once(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

									if (class_exists($field_code)) {
										$component =& new $field_code(null,$isString,$fieldName,$formExtraValue);
										
										if(method_exists($component, "blockSearchCondition")){
											$result_condition = $component->blockSearchCondition(null, $fieldName, $script, $opt);
										}else{
											require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
											$component =& new ETC(null,$isString,$fieldName,$formExtraValue);
											$result_condition = $component->blockSearchCondition(null, $fieldName, $script, $opt);
										}
									}
								}
						}else{
							echo "[error] ".$field_code." ������Ʈ ���� �������� �ʽ��ϴ�.";
							exit;
						}
					}
				}
			}
			return $result_condition;

	}



	public function addElementMOD($name, $link){
		// link : ��ũ��, getVariable : ��ũ�ڿ� �ٿ��� �ʵ尪(ID��� �Է��ϸ� &(?)ID=row[ID] ���� ����
		$this->Element[$name] = array("link"=>$link);
	}



	private function AddLinkElement($row, $arrValue){
		$fieldcnt = count($arrValue);
		//$data = "<td bgcolor=white align=center height=30>";
		//print_r($this->Element[$arrValue[0]][0]);
		if($fieldcnt == 1){
			$elementdata = $this->TextElement($row, $arrValue[0], $this->Element[$arrValue[0]]["link"]);
			$data .= $elementdata;
		}else if($fieldcnt > 1){
			for($i=0; $i<$fieldcnt; $i++){
				$elementdata = $this->TextElement($row, $arrValue[$i], $this->Element[$arrValue[$i]]["link"]);
				$data .= $elementdata;
				$data .= '-';
			}
			$data = substr($data, 0, -1);
		}
		//$data .= "</td>";
		return $data;
	}

	private function AddTextElement($row, $arrValue){
		$fieldcnt = count($arrValue);
		if($fieldcnt == 1){
			$elementdata = $this->TextElement($row, $arrValue[0], null);
			$data .= $elementdata;
		}else if($fieldcnt > 1){
			for($i=0; $i<$fieldcnt; $i++){
				$elementdata = $this->TextElement($row, $arrValue[$i], null);
				$data .= $elementdata;
				$data .= '-';
			}
			$data = substr($data, 0, -1);
		}
		return $data;
	}

	protected function TextElement($row, $name, $textlink){
			$name = trim($name);

			$fieldName		= $this->arr_field[$name][0];
			$isString		= $this->arr_field[$name][1];
			$formType		= $this->arr_field[$name][2];
			$formExtraValue = $this->arr_field[$name][3];
			$result_form	= '';

			if($textlink){
				$pattern = "|#(.*)#|U";
				preg_match_all($pattern, $textlink, $match);

				for($i=0;$i<count($match[0]);$i++){
					$textlink = str_ireplace($match[0][$i], $row[($this->arr_field[($match[1][$i])][0])], $textlink);
				}
				$result_form .= "<a href=".$textlink.">";
			}

			if($formType){ // �� Ÿ���� ���ǵǾ� ���� ����
				$arrtmp = explode("/",$formType);
				$field_code = $arrtmp[0];
					
				if(file_exists(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
						require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

						if (class_exists($field_code)) {
							$component =& new $field_code($row[$fieldName],$isString,$fieldName,$formExtraValue);
							if(method_exists($component, "blockList")){
								$result_form .= $component->blockList();
							}else{
								require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
								$component =& new ETC($row[$fieldName],$isString,$fieldName,$formExtraValue);
								$result_form .= $component->blockList();
							}
						}
				}else{ // ����� ���� ������Ʈ�� �ִ��� üũ
					
						if(file_exists(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
							require_once(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");
							
							if (class_exists($field_code)) {
								$component =& new $field_code($row[$fieldName],$isString,$fieldName,$formExtraValue);
								
								if(method_exists($component, "blockList")){
									$result_form .= $component->blockList();
								}else{
									require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
									$component =& new ETC($row[$fieldName],$isString,$fieldName,$formExtraValue);
									$result_form .= $component->blockList();
								}
							}
						}else{
						
							if($this->plugin != null){
								if(file_exists(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
									require_once(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

									if (class_exists($field_code)) {
										$component =& new $field_code($row[$fieldName],$isString,$fieldName,$formExtraValue);
										
										if(method_exists($component, "blockList")){
											$result_form .= $component->blockList();
										}else{
											require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
											$component =& new ETC($row[$fieldName],$isString,$fieldName,$formExtraValue);
											$result_form .= $component->blockList();
										}
									}
								}
							}else{
								echo "[error] ".$field_code." ������Ʈ ���� �������� �ʽ��ϴ�.";
								exit;
							}
						}
					}
			}else{
				$result_form .= $row[GET_FIELD_NAME("�Ϸù�ȣ")];
			}
			if($textlink){
				$result_form .= "</a>";
			}

			return $result_form;
	}

	

	// �Ʒ��� �˻��� ���� �޼ҵ�
	public function checkValue(){
		$like = "";
		$cnt = count($this->arrfldname);
		for($i=0; $i<$cnt; $i++){
			$pos = strpos($this->arrfldname[$i], '/');
			$name = substr($this->arrfldname[$i], 0, $pos);
			
			$fieldname = $this->field[$name][0];
			if(!$_POST[$fieldname]){
				continue;
			}
			$like .= $fieldname;

			if($this->field[$name][1] == true){
				if((substr($this->field[$name][2], 1, 3) == "SEL") || (substr($this->field[$name][2], 0, 4) == "DATE")){
					$like .= " ='".$_POST[$fieldname]."' and ";
				}else{
					$like .= " like '%".$_POST[$fieldname]."%' and ";
				}

			}else{
				$like .= " = ".$_POST[$fieldname]." and ";
			}
			
		}
		return substr($like, 0, -4);
	}

	
	public function searchFormElement($name, $opt1, $opt2){

			$name = trim($name);
			$value = trim($value);

			$fieldName = $this->arrTbField[$name][0];
			$isString = $this->arrTbField[$name][1];
			$formType = $this->arrTbField[$name][2];
			$formExtraValue = $this->arrTbField[$name][3];
			$result_form = '';

			if($opt1 && $opt2){
					$opt[1] = $opt1;
					$opt[2] = $opt2;
			}else{
				$opt=explode('/', $formType);
			}

			if($formType){ // �� Ÿ���� ���ǵǾ� ���� ����
				$temp = explode('/', $formType);
				$field_code = $temp[0];
				
					if(file_exists(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
					require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

					if (class_exists($field_code)) {
						$component =& new $field_code($value,$isString,$fieldName,$formExtraValue);
						if(method_exists($component, "blockSearch")){
							$result_form .= $component->blockSearch($name, $fieldName, $script, $opt);
						}else{
							require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
							$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
							$result_form .= $component->blockSearch($name, $fieldName, $script, $opt);
						}
					}
				}else{
					
					// ����� ���� ������Ʈ�� �ִ��� üũ
					if(file_exists(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
						require_once(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");
						
						if (class_exists($field_code)) {
							
							$component =& new $field_code($value,$isString,$fieldName,$formExtraValue);
							if(method_exists($component, "blockSearch")){
							
								$result_form .= $component->blockSearch($name, $fieldName, $script, $opt);
								
							}else{
								require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
								$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
								$result_form .= $component->blockSearch($name, $fieldName, $script, $opt);
							}
							
						}
					}else{
						if($this->plugin != null){
								if(file_exists(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
									require_once(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

									if (class_exists($field_code)) {
										$component =& new $field_code($row[$fieldName],$isString,$fieldName,$formExtraValue);
										
										if(method_exists($component, "blockSearch")){
											$result_form .= $component->blockSearch($name, $fieldName, $script, $opt);
										}else{
											require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
											$component =& new ETC($row[$fieldName],$isString,$fieldName,$formExtraValue);
											$result_form .= $component->blockSearch($name, $fieldName, $script, $opt);
										}
									}
								}
						}else{
							echo "[error] ".$field_code."������Ʈ ���� �������� �ʽ��ϴ�.";
							exit;
						}
					}
				}
			}else{
				$result_form = "<input type=hidden ID=".$name." name=".$fieldName." value='".$value."'>";
			}

			$arrResult_form[0] = $result_form;
			$arrResult_form[1] = $name;
			$arrResult_form[2] = $value;

			return $arrResult_form;
	}
}
?>
