<?
class STAT extends ComponentBlock_ComponentBlock implements impComponentBlock{

	// ÃÊ±âÈ­
	public function STAT($inputdata,$isString,$fieldName,$formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;
	}

	public function blockList(){
	
		return $this->inputdata;
	}

	public function blockSelect(){
		return stripslashes($this->inputdata);
	}

	public function blockUpdate($id, $fieldName, $script, $opt){
	
		return "<input ID=".$id." type='text' size='".trim($opt[1])."' maxlength='".trim($opt[2])."' name='".$fieldName."' value='".$this->inputdata ."' ".$script.">";
	}

	public function blockInsert($id, $fieldName, $script,$opt){
		return "<input ID=".$id." type='text' size='".trim($opt[1])."' maxlength='".trim($opt[2])."' name='".$fieldName."' value='".$this->inputdata ."' ".$script.">";
	}
	public function blockSearch($id, $fieldName, $script, $opt){
		return "<input ID=".$id." type='text' size='".trim($opt[1])."' maxlength='".trim($opt[2])."' name='".$fieldName."' value='".$this->inputdata ."' ".$script.">";
	}

	public function blockSearchCondition(){
		$strLike = "";
		if($_GET[$this->fieldName]){
			$arrFieldData = explode(",", $_GET[$this->fieldName]);
			$i=0;
			$strLike = "(";
			foreach($arrFieldData as $key=>$value){
				$strLike .= $this->fieldName." = '".$arrFieldData[$i++]."' or ";
			}
			$strLike = substr($strLike, 0, -4);
			$strLike .= ")";
			return $strLike;
		}else{
			return null;
		}
		
	}
}
?>