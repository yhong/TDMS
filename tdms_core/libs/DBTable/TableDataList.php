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
	 * �� ���̺����� �˻��� ����� �̸��� �迭�� ��´�(���߿� ��ġ�ϴ��� ���θ� �˻���)
	 * 
	 * @param nothing
	 *
	 * @return $this->arrnamecheck : �̸��� ���� �迭
	*/
	public function getNameCheckValue(){
		return $this->arrnamecheck;
	} // end function


}
?>