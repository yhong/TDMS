<?
class Board_FormInsert extends Board_Form implements impForm{
	// ��� ����
	public $OutputData = "";

	function Board_FormInsert(){
		parent::Board_Form();
	}

	// �ʱ�ȭ �Լ�
	public function getStart($id){
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

	public function formElement($name, $value, $script){
			$name = trim($name);
			$value = trim($value);
			$id = str_replace('.', '_', $this->field[$name][0]);

			$fieldName = $this->field[$name][0];
			$isString = $this->field[$name][1];
			$formType = $this->field[$name][2];
			$formExtraValue = $this->field[$name][3];
			$result_form = '';

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
						if(method_exists($component, "blockInsert")){
							$result_form .= $component->blockInsert($id, $fieldName, $script, $opt);
						}else{
							require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
							$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
							$result_form .= $component->blockInsert($id, $fieldName, $script, $opt);
						}
					}
				}else{ // ����� ���� ������Ʈ�� �ִ��� üũ
				
					if(file_exists(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
						require_once(LOGIC_ROOT.D_S.COMPONENT_BLOCK.D_S.$field_code.".php");
						
						if (class_exists($field_code)) {
							$component =& new $field_code($value,$isString,$fieldName,$formExtraValue);
							
							if(method_exists($component, "blockInsert")){
								$result_form .= $component->blockInsert($id, $fieldName, $script, $opt);
							}else{
								require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
								$component =& new ETC($value,$isString,$fieldName,$formExtraValue);
								$result_form .= $component->blockInsert($id, $fieldName, $script, $opt);
							}
						}
					}else{
						if($this->plugin != null){
								if(file_exists(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php")){
									require_once(LOGIC_ROOT.D_S.PLUGIN.D_S.strtolower($this->plugin).D_S.COMPONENT_BLOCK.D_S.$field_code.".php");

									if (class_exists($field_code)) {
										$component =& new $field_code($row[$fieldName],$isString,$fieldName,$formExtraValue);
										
										if(method_exists($component, "blockInsert")){
											$result_form .= $component->blockInsert($id, $fieldName, $script, $opt);
										}else{
											require_once(CORE_LIB_ROOT.D_S.COMPONENT_BLOCK.D_S."ETC.php");
											$component =& new ETC($row[$fieldName],$isString,$fieldName,$formExtraValue);
											$result_form .= $component->blockInsert($id, $fieldName, $script, $opt);
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
	// array("�ʵ��(alias)"=>array("��",Ÿ��(0:������ 1:������))�̴�.
	// Ÿ���� true�̸� ���ڿ� false�̸� ������
	public function setInsertQuery(){

		while(list($key, $value) = each($this->field)){
			if($value[0] == false){
				continue;
			}
			$fieldname = str_replace('.', '_', $value[0]);

			switch($fieldname){
				case str_replace('.', '_', GET_FIELD_NAME("�Ϸù�ȣ")):
					$id_val = $this->db_conn->getOne("select max(".GET_FIELD_NAME("�Ϸù�ȣ").") from ".$this->tb_name);

					if($id_val == 0){
						$id_val = 1;
					}else{
						$id_val++;
					}
					$_POST[str_replace('.', '_', GET_FIELD_NAME("�Ϸù�ȣ"))] = $id_val;
				break;
			}

			$arrTbTmp = explode(" ",$this->tb_name);

			// �ٸ� ���̺��� �ʵ尪�� �Է¿� �������� �ʴ´�.
			if(stristr($fieldname, trim($arrTbTmp[1]).'_') === FALSE && (count($arrTbTmp) > 1)) {
				continue;
			}
				
			$fieldList .= str_replace('_', '.', $fieldname).',';
			if($value[1] == true){

				$inValue = "'".addslashes($_POST[$fieldname])."'";
			}else if($value[1] == false){
				$inValue = $_POST[$fieldname];
			}
			$valueList .= $inValue.',';
		}
		$fieldList = substr($fieldList,0,-1);
		$valueList = substr($valueList,0,-1);

		

		$fieldList = str_replace(trim($arrTbTmp[1]).'.', '', $fieldList);

		$this->query = "insert into ".trim($arrTbTmp[0])."(".$fieldList.") values(".$valueList.")";

	}
}
?>