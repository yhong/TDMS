<?
class Board_FormDelete extends Board_Form implements impForm{ 
	private $arrRow;
	private $id;

	public $OutputData;

	function Board_FormDelete(){
		parent::Board_Form();
	}

	public function getStart($id){
		$this->id = $id;
		$this->setAddLikeis("id=".$id);
		$this->OutputData["rowdata"] = array();
	}

	// 만들어진 화면 데이터를 리턴
	public function getEnd(){
		return $this->OutputData;
	}

	public function setDeleteQuery(){

		$arrTbTmp = explode(" ",$this->tb_name);
		$this->query = "delete from ".trim($arrTbTmp[0])." where ".$this->likeis;
	}

}
?>