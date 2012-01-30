<?
class TSEL extends ComponentBlock_ComponentBlock implements impComponentBlock{

	// 초기화
	public function TSEL($inputdata,$isString,$fieldName,$formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;
	}

	
	public function blockList(){
		return array_search($this->inputdata, $this->formExtraValue);
	}

	public function blockSelect(){
		return array_search( $this->inputdata, $this->formExtraValue);
	}

	public function blockUpdate($id, $fieldName, $script, $opt){
		if(is_Array($this->formExtraValue)){
			$result_form = "<select ID='".$id."' name='".$fieldName."' ".$script.">";
			$result_form .= "<option></option>";
			while(list($selkey, $selvalue) = each($this->formExtraValue)){
				if( $this->inputdata  == $selvalue ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option ".$sel." value='".$selvalue."'>".$selkey."</option>";
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
				if( $this->inputdata  == $selvalue ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option ".$sel." value='".$selvalue."'>".$selkey."</option>";
			}
			$result_form .= "</select>";
		}else{
			$result_form .= "<script>alert('형식이 다릅니다!');</script>";
			exit;
		}
		return $result_form;
	}

	public function blockSearch($id, $fieldName, $script, $opt){
		if(is_Array($this->formExtraValue)){
			$result_form = "<select ID='".$id."' name='".$fieldName."' ".$script.">";
			$result_form .= "<option></option>";
			while(list($selkey, $selvalue) = each($this->formExtraValue)){
				if( $this->inputdata  == $selvalue ){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$result_form .= "<option ".$sel." value='".$selvalue."'>".$selkey."</option>";
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