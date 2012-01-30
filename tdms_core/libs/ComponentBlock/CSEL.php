<?
class CSEL extends ComponentBlock_ComponentBlock implements impComponentBlock{

	// 초기화
	public function CSEL($inputdata,$isString,$fieldName,$formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;
	}

	
	public function blockList(){
		$result_form = "";

		while(list($formkey, $formvalue) = each($this->formExtraValue)){
			if(trim($this->inputdata) == trim($formvalue[0])){
				$result_form .= $formkey;
				break;
			}
		}

		return $result_form;
	}

	public function blockSelect(){
		while(list($formkey, $formvalue) = each($this->formExtraValue)){
			if(trim($this->inputdata) == trim($formvalue[0])){
				$result_form = $formkey;
				break;
			}
		}
		return $result_form;
	}

	public function blockUpdate($id, $fieldName, $script, $opt){
		if(is_Array($this->formExtraValue)){
			$result_form = "<select ID='".$id."' name='".$fieldName."' ".$script.">";
			$result_form .= "<option></option>";
			while(list($selkey, $selvalue) = each($this->formExtraValue)){
				if( $this->inputdata == $selvalue[0] ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option $sel value='".$selvalue[0]."'>".$selkey."</option>";
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
				if( $this->inputdata == $selvalue[0] ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option ".$sel." value='".$selvalue[0]."'>".$selkey."</option>";
			}
			$result_form .= "</select>";
		}else{
			$result_form .= "<script>alert('형식이 다릅니다!');</script>";
			exit;
		}

		return $result_form;
	}

	public function blockSearch($id, $fieldName, $script, $opt){
		if(is_Array($formExtraValue)){
			$result_form = "<select ID='".$id."' name='".$fieldName."' ".$script.">";
			$result_form .= "<option></option>";
			while(list($selkey, $selvalue) = each($formExtraValue)){
				if( $this->inputdata == $selvalue[0] ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option ".$sel." value='".$selvalue[0]."'>".$selkey."</option>";
			}
			$result_form .= "</select>";
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