<?
//  ������Ʈ �� �� ��Ÿ ���� �ٸ� ������Ʈ���� �޼ҵ带 ã�� ��������� �� Ŭ������ ����Ѵ�.
class OCMB extends ComponentBlock_ComponentBlock implements impComponentBlock{

	private $objDB;

	// �ʱ�ȭ
	public function OCMB($inputdata, $isString, $fieldName,$formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;

		$this->objDB = new Database_Manage(GET_CONFIG("dsn"));
		// ȯ�漳���Ǿ��ִ� ��� ���̺�
		if(!$formExtraValue["TBNAME"]){
			echo "[ERROR] OCMB ������Ʈ�� ȯ�漳���� �߸��Ǿ����ϴ�!";
			exit;
		}
		$this->objDB->setTbName($formExtraValue["TBNAME"]);
	}

	// ������Ʈ�� �ʿ��� �ڹ� ��ũ��Ʈ ������ ȭ�鿡 ����Ѵ�.
	private function writeScript(){
		// ������ ���� �ٸ� �ʵ��� �ʵ���� �����´�.
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
		$this->objDB->setEQUCondition(GET_FIELD_NAME("��ǰ�Ϸù�ȣ"), $this->inputdata , true);
		$this->objDB->likeis = $this->objDB->likeis." and ".GET_CONFIG("joinCondition");
		
		$data_val = $this->objDB->getRowValue();

		return $data_val;
	}

	public function blockSelect(){

		$tb_name = GET_CONFIG("tbName");
		$this->objDB->setTbName($tb_name.",".$this->formExtraValue["TBNAME"]);
		
		
		$this->objDB->setFieldByArray(array($this->formExtraValue["TITLE"]));
		$this->objDB->setEQUCondition(GET_FIELD_NAME("��ǰ�Ϸù�ȣ"), $this->inputdata , true);
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

			// ���������� ���� �ε��� �ٲٱ�
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
			$result_form .= "<script>alert('������ �ٸ��ϴ�!');</script>";
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

			// ���������� ���� �ε��� �ٲٱ�
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
			$result_form .= "<script>alert('������ �ٸ��ϴ�!');</script>";
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

			// ���������� ���� �ε��� �ٲٱ�
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
			$result_form .= "<script>alert('������ �ٸ��ϴ�!');</script>";
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