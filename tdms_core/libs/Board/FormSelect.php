<?
class Board_FormSelect extends Board_Form implements impForm{
	protected $arrRow;
	protected $id;

	protected $joinType;
	protected $joinTarget;
	protected $joinCondition;
	protected $joinOrder;

	// ��� ����
	public $OutputData = "";

	function Board_FormSelect(){
		parent::Board_Form();

		$this->joinType			= GET_CONFIG("joinType");
		$this->joinTarget		= GET_CONFIG("joinTarget");
		$this->joinCondition	= GET_CONFIG("joinCondition");
		$this->joinOrder		= GET_CONFIG("joinOrder");

		$this->setTbName(GET_CONFIG("tbName"));

	}


	// arrInputValue�� ������
	// array("�ʵ��(alias)"=>array("��",Ÿ��(bool))�̴�.
	// Ÿ���� true�̸� ���ڿ� false�̸� ������
	public function setSelectQuery(){
	
		while(list(, $value) = each($this->field)){
			$fieldname = $value[0];
			$fieldList .= $fieldname.',';
		}
		$fieldList = substr($fieldList,0,-1);
		reset($this->field);

		if($this->joinOrder){
			$join_order = " order by ".$this->joinOrder;
		}else{
			$join_order = "";
		}

		if($this->joinTarget && $this->joinCondition){
			if($this->likeis != ""){
				$this->likeis .= " and ";
			}
			$this->likeis .= $this->joinCondition;
		}

		if($this->likeis){
			$this->query = "select ".$fieldList." from ".$this->tb_name." where ".$this->likeis;
		}else{
			$this->query = "select ".$fieldList." from ".$this->tb_name;
		}
	}

	// database.connectŬ������ �Լ��� �������̵� ��
	public function setTbName($tbname){
		if($this->joinTarget){
			parent::setTbName($tbname.", ".$this->joinTarget);
		}else{
			$this->tb_name = $tbname;
		}

	}

	public function getStart($id){
		$this->id = $id;
		$this->setAddLikeis(GET_FIELD_NAME("�Ϸù�ȣ")."=".$id);
		$this->setSelectQuery();
		$this->executeQuery();

		$this->arrRow = $this->input_query_result->fetchRow(DB_FETCHMODE_ASSOC);
		$arr_temp_field = GET_CONFIG("arrTbField");
		$arrayRow = array();
		// �ʵ�� ����
		foreach($this->arrRow as $k => $v){
			list($a, $b) = each($arr_temp_field);
			$arrayRow[$b[0]] = $v;
		}
		$this->arrRow = $arrayRow;
		$this->OutputData["rowdata"] = array();
	}

	
	// ������� ȭ�� �����͸� ����
	public function getEnd(){
		return $this->OutputData;
	}



	public function TextElement($name){
			$name = trim($name);
			$value = trim($value);

			$fieldName = $this->field[$name][0];
			$isString = $this->field[$name][1];
			$formType = $this->field[$name][2];
			$formExtraValue = $this->field[$name][3];
			$result_form = '';

			if(!$value and $this->arrRow[$fieldName]){
				$value = $this->arrRow[$fieldName];
			}

			if($script){
				$output  = str_replace("#VALUE#", $this->arrRow[$fieldName], $script);
				
				$result_form .= $output;
			}else{

				if($formType){ // �� Ÿ���� ���ǵǾ� ���� ����
					$opt=explode('/', $formType);
					$arrtmp = explode("/",$formType);
					$field_code = $arrtmp[0];
					
					if(file_exists(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
						require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

						if (class_exists($field_code)) {
							$component =& new $field_code($value,$isString,$fieldName,$formExtraValue);
							if(method_exists($component, "blockSelect")){
								$result_form .= $component->blockSelect();
							}else{
								require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
								$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
								$result_form .= $component->blockSelect();
							}
						}
					}else{ // ����� ���� ������Ʈ�� �ִ��� üũ
					
						if(file_exists(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
							require_once(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");
							
							if (class_exists($field_code)) {
								$component =& new $field_code($value,$isString,$fieldName,$formExtraValue);
								
								if(method_exists($component, "blockSelect")){
									$result_form .= $component->blockSelect();
								}else{
									require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
									$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
									$result_form .= $component->blockSelect();
								}
							}
						}else{
							if($this->plugin != null){
								if(file_exists(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
									require_once(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

									if (class_exists($field_code)) {
										$component =& new $field_code($row[$fieldName],$isString,$fieldName,$formExtraValue);
										
										if(method_exists($component, "blockSearch")){
											$result_form .= $component->blockSelect($name, $fieldName, $script, $opt);
										}else{
											require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
											$component =& new ETC($row[$fieldName],$isString,$fieldName,$formExtraValue);
											$result_form .= $component->blockSelect($name, $fieldName, $script, $opt);
										}
									}
								}
							}else{
								echo "[error] ������Ʈ ���� �������� �ʽ��ϴ�.";
								exit;
							}
						}
					}
				}else{
					print_r($row);
					$result_form .=  $this->arrRow[GET_FIELD_NAME("�Ϸù�ȣ")];
				}

			}

			$arrResult_form[0] = $result_form;
			$arrResult_form[1] = $name;
			$arrResult_form[3] = $fieldName;

			//print_r($arrResult_form);
			return $arrResult_form;
	}

	/* 
	 * ����, �ʱⰪ, ���񿷿� �־��� �߰��ڵ�, �ʵ忷�� �� �߰� �ڵ�, �߰��� ��ũ��Ʈ�ҽ�
	 */
	public function AddElement(&$arrResult_form, $titlescript, $valuescript){
		$arrdata = &$arrResult_form;

		$result_form = $arrdata[0];
		$name = $arrdata[1];
		$fieldname = $arrdata[3];
		
		if($titlescript){
				$name  = str_replace("#VALUE#", $name, $titlescript);
		}

		if($valuescript){
				$result_form  = str_replace("#VALUE#", $result_form, $valuescript);
		}

		$this->OutputData["rowdata"][$fieldname][title] = $name;
		$this->OutputData["rowdata"][$fieldname][value] = $result_form;
		 
	}

	public function AddElement2($totalname, &$arrResult_form1, &$arrResult_form2, $titlescript, $valuescript){
		$arrdata1 = &$arrResult_form1;
		$result_form1 = $arrdata1[0];

		$arrdata2 = &$arrResult_form2;
		$result_form2 = $arrdata2[0];


		if($titlescript){
			$totalname  = str_replace("#VALUE#", $totalname, $titlescript);
		}
		$this->OutputData["rowdata"][$totalname][title] = $totalname;

		if($valuescript){
			$result_form  = str_replace("#VALUE#", $result_form1."-".$result_form2, $valuescript);
			$this->OutputData["rowdata"][$totalname][value] = $result_form;
		}else{
			$this->OutputData["rowdata"][$totalname][value] = $result_form1."-".$result_form2;
		}
	}


}
?>