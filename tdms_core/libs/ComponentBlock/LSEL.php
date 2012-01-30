<?
class LSEL extends ComponentBlock_ComponentBlock implements impComponentBlock{

	// 초기화
	public function LSEL($inputdata,$isString,$fieldName,$formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;
	}

	public function blockList(){
	
		$result_form = "";

		if($this->inputdata == 'A'){
			$colortext="green";
		}else if($this->inputdata == 'B'){
			$colortext="blue";
		}else if($this->inputdata == 'C'){
			$colortext="red";
		}
		$result_form .= "<font style='color:".$colortext."'><b>";
		$result_form .= array_search( $this->inputdata, $this->formExtraValue);
		$result_form .= "</b></font>";

		return $result_form;
	}

	public function blockSelect(){
		return array_search( $this->inputdata, $this->formExtraValue);
	}

	public function blockUpdate($id, $fieldName, $script, $opt){
		if(is_Array($this->formExtraValue)){
			$result_form = "<select ID='".$id."' name='".$fieldName."' ".$script.">";
			$result_form .= "<option></option>";
			while(list($selkey, $selvalue) = each($this->formExtraValue)){
				if( $value == $selvalue ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option $sel value='".$selvalue."'>".$selkey."</option>";
			}
			$result_form .= "</select>";
		}else{
			$result_form .= "<script>alert('형식이 다릅니다!');</script>";
			exit;
		}

		return $result_form;
	}

	public function blockInsert($id, $fieldName, $script, $opt){
		if(is_Array($this->formExtraValue)){
			$result_form = "<select ID='".$id."' name='".$fieldName."' ".$script.">";
			$result_form .= "<option></option>";
			while(list($selkey, $selvalue) = each($this->formExtraValue)){
				if( $value == $selvalue ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option $sel value='".$selvalue."'>".$selkey."</option>";
			}
			$result_form .= "</select>";
		}else{
			$result_form .= "<script>alert('형식이 다릅니다!');</script>";
			exit;
		}

		return $result_form;
	}

	public function blockSearch($id, $fieldName, $script, $opt){
		return null;
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