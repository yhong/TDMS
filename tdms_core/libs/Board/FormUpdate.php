<?
class Board_FormUpdate extends Board_FormSelect implements impForm{ 

	private $arrUpdateField=array();
	private $Workfield;
	private $WorkFlag; // bool형태로서 true이면 있는 선택한 필드만 빼고 사용하며 false이면 선택한 필드만 사용함

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

	// arrInputValue는 형식이
	// array("필드명(alias)"=>array("값",타입(bool))이다.
	// 타입이 true이면 문자열 false이면 숫자형
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
		$this->setAddLikeis(GET_FIELD_NAME("일련번호")."=".$id);
		$this->setSelectQuery();
		$this->executeQuery();

		//print_r($this->input_query_result);
		$this->arrRow = $this->input_query_result->fetchRow(DB_FETCHMODE_ASSOC);
		
		$arr_temp_field = GET_CONFIG("arrTbField");
		$arrayRow = array();
		// 필드명 갱신
		foreach($this->arrRow as $k => $v){
			list($a, $b) = each($arr_temp_field);
			$arrayRow[$b[0]] = $v;
		}
		$this->arrRow = $arrayRow;

		$this->OutputData["rowdata"] = array();

		// 유형 번호를 자동으로 기입하기 위한 코드 삽입
		$i=0;
		$maxdata = array();
		foreach(GET_FIELD_TYPE("유형") as $key=>$value){
			$maxdata[$i] = $this->countMaxNum(GET_FIELD_NAME("코드번호"), GET_FIELD_NAME("유형")."='".$value."'");
			$i++;
		}

		echo "
			<script language='javascript'>
			// 휴형별번호에서 유형에 최대 ID값을 알아와 코드에 넣는다. 
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
				selindex = document.getElementById('".str_replace('.', '_', GET_FIELD_NAME("유형"))."').selectedIndex;

				if(selindex == 0){
					document.getElementById('".str_replace('.', '_', GET_FIELD_NAME("코드번호"))."').value='';
				}else{
					document.getElementById('".str_replace('.', '_', GET_FIELD_NAME("코드번호"))."').value=seldata[(selindex-1)]+1;
				}
			}
			</script>
			";
	}

	// 만들어진 화면 데이터를 리턴
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

			if($fieldName == GET_FIELD_NAME("유형")){
				$script .= " onChange=insertCode(); ";
			}

			if($formType){ // 폼 타입이 정의되어 있을 때만


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
					
					// 사용자 정의 컴포넌트가 있는지 체크
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
								echo "[error] 컴포넌트 블럭이 존재하지 않습니다.";
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
	 * 제목, 초기값, 제목옆에 넣어줄 추가코드, 필드옆에 들어갈 추가 코드, 추가할 스크립트소스
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



	// arrInputValue는 형식이
	// array("필드명(alias)"=>array("값",타입(0:숫자형 1:문자형),필트조건(선택형의 경우))이다.
	// 타입이 true이면 문자열 false이면 숫자형
	public function setUpdateQuery(){
		while(list($key, $value) = each($this->field)){
			if(is_array($this->Workfield)){
				if($this->WorkFlag == true){	// 해당 필드만 빼고
					if(in_array(trim($key), $this->Workfield)){continue;}
				}else{					// 해당 필드만
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