<?
//  컴포넌트 블럭 중 기타 만일 다른 컴포넌트에서 메소드를 찾지 못했을경우 이 클래스를 사용한다.
class ComponentBlock_ComponentBlock implements impComponentBlock{
	protected $inputdata;
	protected $fieldName;
	protected $formExtraValue;

	// 초기화
	public function ComponentBlock_ComponentBlock($inputdata,$isString,$fieldName,$formExtraValue){
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
		return "<input type='hidden' ID='".$id."' name='".$fieldName."' value='".$this->inputdata."'>";
	}

	public function blockInsert($id, $fieldName, $script, $opt){
		return "<input type='hidden' ID='".$id."' name='".$fieldName."' value='".$this->inputdata ."'>";
	}
	public function blockSearch($id, $fieldName, $script, $opt){
		return "<input type='hidden' ID='".$id."' name='".$fieldName."' value='".$this->inputdata ."'>";
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