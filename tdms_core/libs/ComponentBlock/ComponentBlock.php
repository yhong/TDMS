<?
//  ������Ʈ �� �� ��Ÿ ���� �ٸ� ������Ʈ���� �޼ҵ带 ã�� ��������� �� Ŭ������ ����Ѵ�.
class ComponentBlock_ComponentBlock implements impComponentBlock{
	protected $inputdata;
	protected $fieldName;
	protected $formExtraValue;

	// �ʱ�ȭ
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