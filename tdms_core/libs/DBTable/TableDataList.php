<?
class DBTable_TableDataList extends DBTable_TableForm{
	protected $title;
	
	protected $arrnamecheck = array();
	protected $user_id;

	function DBTable_TableDataList($title, $arr_tb_item, $user_id){
		$this->title = $title;
		$this->DBTable_TableForm($arr_tb_item);
		$this->user_id = $user_id;
		//$this->setItem($arr_tb_item);	
	}


	/**
	 *  
	 * getNameCheckValue()
	 * 각 테이블마다의 검색된 결과중 이름을 배열에 담는다(나중에 일치하는지 여부를 검사함)
	 * 
	 * @param nothing
	 *
	 * @return $this->arrnamecheck : 이름을 담은 배열
	*/
	public function getNameCheckValue(){
		return $this->arrnamecheck;
	} // end function


}
?>