<?
class DATE extends ComponentBlock_ComponentBlock implements impComponentBlock{

	// ÃÊ±âÈ­
	public function DATE($inputdata,$isString,$fieldName,$formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;
	}

	
	public function blockList(){
		return substr($this->inputdata,0,10);
	}
	public function blockSelect(){
		return substr($this->inputdata,0,10);
	}
	public function blockUpdate($id, $fieldName, $script, $opt){
		return "<input ID='".$id."' type='text' size=".trim($opt[1])." maxlength='".trim($opt[2])."' name='".$fieldName."' value='".substr($this->inputdata,0,10)."' ".$script.">";
	}

	public function blockInsert($id, $fieldName, $script, $opt){
		return "<input ID='".$id."' type='text' size=".trim($opt[1])." maxlength='".trim($opt[2])."' name='".$fieldName."' value='".$this->inputdata ."' ".$script.">";
	}

	public function blockSearch($id, $fieldName, $script, $opt){
		return "<input ID='".$name."' type='text' size='".trim($opt[1])."' maxlength='".trim($opt[2])."' name='".$fieldName."1' value='".$this->inputdata ."' ".$script.">-<input ID='".$name."' type='text' size='".trim($opt[1])."' maxlength='".trim($opt[2])."' name='".$fieldName."2' value='".$this->inputdata ."' ".$script.">";
	}

	public function blockSearchCondition(){
		if($_GET[$this->fieldName."1"] || $_GET[$this->fieldName."2"]){
			return $this->fieldName." >= '".$_GET[$this->fieldName."1"]."' and ".$this->fieldName." <= '".$_GET[$this->fieldName."2"]."'";
		}else{
			return null;
		}
		
	}
}
?>