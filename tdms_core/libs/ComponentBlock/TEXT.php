<?
class TEXT extends ComponentBlock_ComponentBlock implements impComponentBlock{

	// ÃÊ±âÈ­
	public function TEXT($inputdata,$isString,$fieldName,$formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;
	}

	
	public function blockList(){
		return nl2br($this->inputdata);
	}

	public function blockSelect(){
		return stripslashes(nl2br($this->inputdata));
	}

	public function blockUpdate($id, $fieldName, $script, $opt){
		return "<TEXTAREA ID='".$id."' NAME=\"".$fieldName."\" cols='".trim($opt[1])."' rows='".trim($opt[2])."' ".$script.">".$this->inputdata ."</TEXTAREA>\n";
	}
	
	public function blockInsert($id, $fieldName, $script, $opt){
		return "<TEXTAREA ID='".$id."' NAME=\"".$fieldName."\" cols='".trim($opt[1])."' rows='".trim($opt[2])."' ".$script.">".$this->inputdata ."</TEXTAREA>\n";
	}

	public function blockSearch($id, $fieldName, $script, $opt){
		return "<TEXTAREA ID='".$id."' NAME=\"".$fieldName."\" cols='".trim($opt[1])."' rows='".trim($opt[2])."' ".$script.">".$this->inputdata ."</TEXTAREA>\n";
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