<?
//  컴포넌트 블럭 중 기타 만일 다른 컴포넌트에서 메소드를 찾지 못했을경우 이 클래스를 사용한다.
class OCMB extends ComponentBlock_ComponentBlock implements impComponentBlock{

	private $objDB;

	// 초기화
	public function OCMB($inputdata, $isString, $fieldName,$formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;

		$this->objDB = new Database_Manage(GET_CONFIG("dsn"));
		// 환경설정되어있는 대상 테이블
		if(!$formExtraValue["TBNAME"]){
			echo "[ERROR] OCMB 컴포넌트의 환경설정이 잘못되었습니다!";
			exit;
		}
		$this->objDB->setTbName($formExtraValue["TBNAME"]);
	}

	// 컴포넌트에 필요한 자바 스크립트 구문을 화면에 출력한다.
	private function writeScript(){
		// 설정을 통해 다른 필드의 필드명을 가져온다.
		$arrTbField = GET_CONFIG("arrTbField");
		$otherFormCfg = $arrTbField[$this->formExtraValue["VALUETO"]];
		echo "
		<script>
		function setChangeValueByOCMBFor".str_replace('.', '_', $this->fieldName)."(input){
			document.getElementById('".str_replace('.', '_', $otherFormCfg[0])."').value = input;
		}
		</script>
		";
	}
	
	public function blockList(){

		$tb_name = GET_CONFIG("tbName");
		$this->objDB->setTbName($tb_name.",".$this->formExtraValue["TBNAME"]);
		
		
		$this->objDB->setFieldByArray(array($this->formExtraValue["TITLE"]));
		$this->objDB->setEQUCondition(GET_FIELD_NAME("상품일련번호"), $this->inputdata , true);
		$this->objDB->likeis = $this->objDB->likeis." and ".GET_CONFIG("joinCondition");
		
		$data_val = $this->objDB->getRowValue();

		return $data_val;
	}

	public function blockSelect(){

		$tb_name = GET_CONFIG("tbName");
		$this->objDB->setTbName($tb_name.",".$this->formExtraValue["TBNAME"]);
		
		
		$this->objDB->setFieldByArray(array($this->formExtraValue["TITLE"]));
		$this->objDB->setEQUCondition(GET_FIELD_NAME("상품일련번호"), $this->inputdata , true);
		$this->objDB->likeis = $this->objDB->likeis." and ".GET_CONFIG("joinCondition");
		
		$data_val = $this->objDB->getRowValue();
		return $data_val;
	}

	public function blockUpdate($id, $fieldName, $script, $opt){
		if(is_Array($this->formExtraValue)){

			$this->writeScript();

			$result_form = "<select ID='".$id."' name=".$fieldName." ".$script." onChange=setChangeValueByOCMBFor".str_replace('.', '_', $this->fieldName)."(this.options[this.selectedIndex].id);>";
			$result_form .= "<option></option>";
			
			$this->objDB->setFieldByArray(array($this->formExtraValue["TITLE"], $this->formExtraValue["VALUE"], $this->formExtraValue["DATA"]));
			$arr_data_val = $this->objDB->getRowValues();

			// 조인쿼리를 위한 인덱스 바꾸기
			$renew = array();
			foreach($arr_data_val as $k=>$v){
				$renew[$k][$this->formExtraValue["TITLE"]]=array_shift($v);
				$renew[$k][$this->formExtraValue["VALUE"]]=array_shift($v);
				$renew[$k][$this->formExtraValue["DATA"]]=array_shift($v);
			}

			$arr_data_val = $renew;

			foreach($arr_data_val as $arr_value){
				if( $this->inputdata == $arr_value[$this->formExtraValue["VALUE"]] ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option id='".$arr_value[$this->formExtraValue["DATA"]]."' ".$sel." value='".$arr_value[$this->formExtraValue["VALUE"]]."'>".$arr_value[$this->formExtraValue["TITLE"]]."</option>";
			}

		}else{
			$result_form .= "<script>alert('형식이 다릅니다!');</script>";
			exit;
		}

		return $result_form;
	}

	public function blockInsert($id, $fieldName, $script, $opt){
		if(is_Array($this->formExtraValue)){

			$this->writeScript();

			$result_form = "<select ID='".$id."' name='".$fieldName."' ".$script." onChange='setChangeValueByOCMBFor".str_replace('.', '_', $this->fieldName)."(this.options[this.selectedIndex].id);'>";
			$result_form .= "<option></option>";
			
			$this->objDB->setFieldByArray(array($this->formExtraValue["TITLE"], $this->formExtraValue["VALUE"], $this->formExtraValue["DATA"], $this->formExtraValue["VALUE_TARGET"]));
			$arr_data_val = $this->objDB->getRowValues();

			// 조인쿼리를 위한 인덱스 바꾸기
			$renew = array();
			foreach($arr_data_val as $k=>$v){
				$renew[$k][$this->formExtraValue["TITLE"]]=array_shift($v);
				$renew[$k][$this->formExtraValue["VALUE"]]=array_shift($v);
				$renew[$k][$this->formExtraValue["DATA"]]=array_shift($v);
				$renew[$k][$this->formExtraValue["VALUE_TARGET"]]=array_shift($v);
			}

			$arr_data_val = $renew;

			foreach($arr_data_val as $arr_value){
				if( $this->inputdata == $arr_value[$this->formExtraValue["VALUE"]] ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option id='".($arr_value[$this->formExtraValue["DATA"]]-$arr_value[$this->formExtraValue["VALUE_TARGET"]])."' ".$sel." value='".$arr_value[$this->formExtraValue["VALUE"]]."'>".$arr_value[$this->formExtraValue["TITLE"]]."</option>";
			}

		}else{
			$result_form .= "<script>alert('형식이 다릅니다!');</script>";
			exit;
		}

		return $result_form;
	}
	public function blockSearch($id, $fieldName, $script, $opt){
		if(is_Array($this->formExtraValue)){

			$this->writeScript();

			$result_form = "<select ID='".$id."' name='".$fieldName."' ".$script." onChange='setChangeValueByOCMBFor".str_replace('.', '_', $this->fieldName)."(this.options[this.selectedIndex].id);'>";
			$result_form .= "<option></option>";
			
			$this->objDB->setFieldByArray(array($this->formExtraValue["TITLE"], $this->formExtraValue["VALUE"], $this->formExtraValue["DATA"], $this->formExtraValue["VALUE_TARGET"]));
			$arr_data_val = $this->objDB->getRowValues();

			// 조인쿼리를 위한 인덱스 바꾸기
			$renew = array();
			foreach($arr_data_val as $k=>$v){
				$renew[$k][$this->formExtraValue["TITLE"]]=array_shift($v);
				$renew[$k][$this->formExtraValue["VALUE"]]=array_shift($v);
				$renew[$k][$this->formExtraValue["DATA"]]=array_shift($v);
				$renew[$k][$this->formExtraValue["VALUE_TARGET"]]=array_shift($v);
			}

			$arr_data_val = $renew;

			foreach($arr_data_val as $arr_value){
				if( $this->inputdata == $arr_value[$this->formExtraValue["VALUE"]] ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option id='".($arr_value[$this->formExtraValue["DATA"]]-$arr_value[$this->formExtraValue["VALUE_TARGET"]])."' ".$sel." value='".$arr_value[$this->formExtraValue["VALUE"]]."'>".$arr_value[$this->formExtraValue["TITLE"]]."</option>";
			}

		}else{
			$result_form .= "<script>alert('형식이 다릅니다!');</script>";
			exit;
		}

		return $result_form;
	}

	public function blockSearchCondition(){
		if($_GET[$this->fieldName] ){
			return $this->fieldName." = '".$_GET[$this->fieldName]."'";
		}else{
			return null;
		}
		
	}
}
?>