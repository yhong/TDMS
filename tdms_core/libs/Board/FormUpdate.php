<?
class Board_FormUpdate extends Board_FormSelect implements impForm{ 

	private $arrUpdateField=array();
	private $Workfield;
	private $WorkFlag; // bool���·μ� true�̸� �ִ� ������ �ʵ常 ���� ����ϸ� false�̸� ������ �ʵ常 �����

	public $OutputData;

	protected $joinType;
	protected $joinTarget;
	protected $joinCondition;
	protected $joinOrder;

	function Board_FormUpdate(){
		$this->Board_FormSelect();
		$WorkFlag = true;

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
	public function getStart($id){
		$this->id = $id;
		$this->setAddLikeis(GET_FIELD_NAME("�Ϸù�ȣ")."=".$id);
		$this->setSelectQuery();
		$this->executeQuery();

		//print_r($this->input_query_result);
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

		// ���� ��ȣ�� �ڵ����� �����ϱ� ���� �ڵ� ����
		$i=0;
		$maxdata = array();
		foreach(GET_FIELD_TYPE("����") as $key=>$value){
			$maxdata[$i] = $this->countMaxNum(GET_FIELD_NAME("�ڵ��ȣ"), GET_FIELD_NAME("����")."='".$value."'");
			$i++;
		}

		echo "
			<script language='javascript'>
			// ��������ȣ���� ������ �ִ� ID���� �˾ƿ� �ڵ忡 �ִ´�. 
			function insertCode(){
				var selindex;
				var seldata;
				";
					$arrctn = count($maxdata);	
					echo "seldata = new Array(".$arrctn.");\n";

					for($i=0; $i<$arrctn; $i++){
						echo "seldata[".$i."]=".$maxdata[$i].";\n";
					}
					
		echo "
				selindex = document.getElementById('".str_replace('.', '_', GET_FIELD_NAME("����"))."').selectedIndex;

				if(selindex == 0){
					document.getElementById('".str_replace('.', '_', GET_FIELD_NAME("�ڵ��ȣ"))."').value='';
				}else{
					document.getElementById('".str_replace('.', '_', GET_FIELD_NAME("�ڵ��ȣ"))."').value=seldata[(selindex-1)]+1;
				}
			}
			</script>
			";
	}

	// ������� ȭ�� �����͸� ����
	public function getEnd(){
		return $this->OutputData;
	}



	public function setWorkField($arrInputNameValue){
		$this->Workfield = $arrInputNameValue;
	}

	public function setWorkflag($invalue){
		$this->WorkFlag = $invalue;
	}


	public function formElement($name, $value, $script){
			$id = str_replace('.', '_', $this->field[$name][0]);
			$value = trim($value);

			$fieldName = $this->field[$name][0];
			$isString = $this->field[$name][1];
			$formType = $this->field[$name][2];
			$formExtraValue = $this->field[$name][3];
			$result_form = '';

			if(!$value and $this->arrRow[$fieldName]){
				$value = $this->arrRow[$fieldName];
			}

			if($fieldName == GET_FIELD_NAME("����")){
				$script .= " onChange=insertCode(); ";
			}

			if($formType){ // �� Ÿ���� ���ǵǾ� ���� ����


				$opt=explode('/', $formType);
				$arrtmp = explode("/",$formType);
				$field_code = $arrtmp[0];

				if(file_exists(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
					require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

					if (class_exists($field_code)) {
						$component =& new $field_code($value,$isString,$fieldName,$formExtraValue);
						if(method_exists($component, "blockUpdate")){
							$result_form .= $component->blockUpdate($id, $fieldName, $script, $opt);
						}else{
							require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
							$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
							$result_form .= $component->blockUpdate($id, $fieldName, $script, $opt);
						}
					}
				}else{
					
					// ����� ���� ������Ʈ�� �ִ��� üũ
					if(file_exists(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
						require_once(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");
						
						if (class_exists($field_code)) {
							
							$component =& new $field_code($value,$isString,$fieldName,$formExtraValue);
							if(method_exists($component, "blockUpdate")){
							
								$result_form .= $component->blockUpdate($id, $fieldName, $script, $opt);
								
							}else{
								require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
								$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
								$result_form .= $component->blockUpdate($id, $fieldName, $script, $opt);
							}
							
						}
					}else{
						if($this->plugin != null){
								if(file_exists(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
									require_once(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

									if (class_exists($field_code)) {
										$component =& new $field_code($row[$fieldName],$isString,$fieldName,$formExtraValue);
										
										if(method_exists($component, "blockUpdate")){
											$result_form .= $component->blockUpdate($id, $fieldName, $script, $opt);
										}else{
											require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
											$component =& new ETC($row[$fieldName],$isString,$fieldName,$formExtraValue);
											$result_form .= $component->blockUpdate($id, $fieldName, $script, $opt);
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
				$result_form .= $row[$fieldName];
			}

			$arrResult_form[0] = $result_form;
			$arrResult_form[1] = $name;
			$arrResult_form[2] = $value;
			$arrResult_form[3] = $fieldName;
			return $arrResult_form;
	}

	
	/* 
	 * ����, �ʱⰪ, ���񿷿� �־��� �߰��ڵ�, �ʵ忷�� �� �߰� �ڵ�, �߰��� ��ũ��Ʈ�ҽ�
	 */
	public function AddElement(&$arrResult_form, $titlescript){
		$arrdata = &$arrResult_form;
		$result_form = $arrdata[0];
		$name = $arrdata[1];
		$fieldname = $arrdata[3];
		
		if($titlescript){
				$name  = str_replace("#VALUE#", $name, $titlescript);
		}

		$this->OutputData["rowdata"][$fieldname][title] = $name;
		$this->OutputData["rowdata"][$fieldname][value] = $result_form;
		 
	}

	public function AddElement2($totalname, &$arrResult_form1, &$arrResult_form2, $titlescript){
		$arrdata1 = &$arrResult_form1;
		$result_form1 = $arrdata1[0];

		$arrdata2 = &$arrResult_form2;
		$result_form2 = $arrdata2[0];

		if($titlescript){
			$totalname  = str_replace("#VALUE#", $totalname, $titlescript);
		}
		
		$this->OutputData["rowdata"][$totalname][title] = $totalname;
		$this->OutputData["rowdata"][$totalname][value] = $result_form1."-".$result_form2;
	}



	// arrInputValue�� ������
	// array("�ʵ��(alias)"=>array("��",Ÿ��(0:������ 1:������),��Ʈ����(�������� ���))�̴�.
	// Ÿ���� true�̸� ���ڿ� false�̸� ������
	public function setUpdateQuery(){
		while(list($key, $value) = each($this->field)){
			if(is_array($this->Workfield)){
				if($this->WorkFlag == true){	// �ش� �ʵ常 ����
					if(in_array(trim($key), $this->Workfield)){continue;}
				}else{					// �ش� �ʵ常
					if(!in_array(trim($key), $this->Workfield)){continue;}
				}
			}

			$fieldname=str_replace('.', '_', $value[0]);
			if($value[2] == ""){
				continue;
			}

			$fieldList .= $fieldname.',';

			if($value[1] == true){
				$inValue = "'".addslashes($_POST[$fieldname])."'";
			}else if($value[1] == false){
				$inValue = $_POST[$fieldname];
			}
			$updateList .= $value[0]."=".$inValue.",";
		}
			$updateList = substr($updateList,0,-1);

		$this->query = "update ".$this->tb_name." set ".$updateList." where ".$this->likeis;

	}



}
?>